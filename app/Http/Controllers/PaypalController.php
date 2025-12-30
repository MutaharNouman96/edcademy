<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use App\Models\Course;
use App\Models\EducatorPayment;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\UserPurchasedItem;
use App\Services\EmailService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaypalController extends Controller
{
    protected $paypal_base_url;
    //
    public function __construct()
    {
        $this->paypal_base_url = (env('PAYPAL_MODE') == "live" ? "https://api-m.paypal.com" : "https://api-m.sandbox.paypal.com");
    }


    public function create(Request $request)
    {
        // 1. --- CRITICAL: THE TRY-CATCH BLOCK FOR JSON RESPONSE ---
        try {
            $validated = Validator::make($request->all(), [
                'order_id' => 'required',
                'description' => 'nullable|string',

            ]);

            if ($validated->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validated->errors(),
                ]);
            }

            // 2. --- CRITICAL: PAYPAL ORDER CREATION ---
            // Get the access token
            $accessToken = $this->getAccessToken();
            $cartOrder = Order::where('id', $request->order_id)->first();

            if (!$cartOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ]);
            }
            //get the order amount
            $amount = $cartOrder->total;

            // Create the order on PayPal's server
            $order = $this->requestPayPalOrder($accessToken, $amount);

            //update the order transaction id and other details
            $cartOrder->transaction_id = $order['id'];

            $cartOrder->payment_method = 'paypal';
            $cartOrder->note = $request->description;

            $cartOrder->save();


            // 4. SUCCESS: Return the Order ID and other data as JSON
            return response()->json([
                'success' => true,
                'id' => $order['id']
            ], 201);
        } catch (\Throwable $e) {
            // 5. FAILURE: Log the error and return a JSON error response
            // This prevents Laravel's default behavior of returning an HTML error page.
            Log::error('PayPal Order Creation Failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Could not process payment request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper to get the PayPal Access Token.
     * This is the most common point of failure.
     * @return string
     */
    protected function getAccessToken()
    {
        $response = Http::asForm()
            ->withBasicAuth(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'))
            ->post($this->paypal_base_url . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        // Check if the request failed (e.g., 401 Unauthorized due to bad credentials)
        if ($response->failed()) {
            throw new \Exception('Failed to retrieve PayPal access token.', $response->status());
        }

        return $response->json()['access_token'];
    }

    /**
     * Helper to create the order with PayPal.
     * @param string $accessToken
     * @param string $amount
     * @return array
     */
    protected function requestPayPalOrder($accessToken, $amount)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ])->post($this->paypal_base_url . '/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($amount, 2, '.', ''),
                    ]
                ]
            ],
            // Add application context details here for return/cancel URLs if needed
        ]);

        if ($response->failed()) {
            // PayPal's API returns a JSON error body for bad requests (400, 422, etc.)
            $errorDetails = $response->json();
            $errorMessage = $errorDetails['details'][0]['description'] ?? ($errorDetails['message'] ?? 'Unknown API error');
            throw new \Exception("PayPal Order API failed: {$errorMessage}", $response->status());
        }

        return $response->json();
    }



    public function capture(Request $request)
    {
        $request->validate([
            'orderID' => 'required|string',
        ]);

        try {
            //  Get PayPal Access Token
            $accessToken = $this->getAccessToken();

            //  Capture Payment
            $response = Http::withToken($accessToken)
                ->post(config('services.paypal.base_url') . "/v2/checkout/orders/{$request->orderID}/capture");

            if (! $response->successful()) {
                Log::error('PayPal Capture Failed', $response->json());

                return response()->json([
                    'success' => false,
                    'message' => 'Payment capture failed',
                ], 422);
            }

            $paypalData = $response->json();

            //  Payment Status
            $status = $paypalData['status'] ?? 'FAILED';

            //  Save Order
            $order = Order::where('transaction_id', $request->orderID)->first();

            if (! $order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], 404);
            }

            $order->update([
                'payment_method'  => 'paypal',
                'status'          => $status === 'COMPLETED' ? 'paid' : 'failed',
                'transaction_id'  => $request->orderID,
                'payment_details' => json_encode($paypalData),
                'is_active'       => $status === 'COMPLETED',
            ]);

            $paymentId = $order->id . (Str::uuid());
            foreach ($order->items as $item) {
                $commission = money(
                    $item->total * setting('platform_commission', 0) / 100
                );
                $itemEducatorId = $item->model == "App\Models\Lesson" ? Lesson::find($item->item_id)->with('course')->first()->course->user_id : Course::find($item->item_id)->user_id;
                UserPurchasedItem::firstOrCreate([
                    'user_id' => auth()->id(),
                    'purchasable_id' => $item->id,
                    'purchasable_type' => $item->model,
                    'active' => true,
                ]);
                EducatorPayment::firstOrCreate([
                    'educator_id' => $itemEducatorId,
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'gross_amount' => $item->total,
                    'currency' => setting('currency', 'USD'),
                    'platform_commission' => $commission,
                    'net_amount' => $item->total - $commission,
                    'payment_id' => $paymentId,
                    'status' => 'completed',
                ]);
            }
            EmailService::send(
                $order->user->email,
                new OrderInvoiceMail($order),
                'emails'
            );

            return response()->json([
                'success' => true,
                'status'  => $status,
                'order'   => $order,
            ]);
        } catch (\Throwable $e) {

            Log::error('PayPal Capture Exception', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while capturing payment',
            ], 500);
        }
    }
}
