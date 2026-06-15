<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaypalController extends Controller
{
    protected $paypal_base_url;

    public function __construct()
    {
        $this->paypal_base_url = (env('PAYPAL_MODE') == 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com');
    }

    public function create(Request $request)
    {
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

            $accessToken = $this->getAccessToken();
            $cartOrder = Order::where('id', $request->order_id)->first();

            if (! $cartOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ]);
            }

            $amount = $cartOrder->total;
            $order = $this->requestPayPalOrder($accessToken, $amount);

            $cartOrder->transaction_id = $order['id'];
            $cartOrder->payment_method = 'paypal';
            $cartOrder->note = $request->description;
            $cartOrder->save();

            return response()->json([
                'success' => true,
                'id' => $order['id'],
            ], 201);
        } catch (\Throwable $e) {
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

    protected function getAccessToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'))
            ->post($this->paypal_base_url . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new \Exception('Failed to retrieve PayPal access token.', $response->status());
        }

        return $response->json()['access_token'];
    }

    protected function requestPayPalOrder(string $accessToken, $amount): array
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
                    ],
                ],
            ],
        ]);

        if ($response->failed()) {
            $errorDetails = $response->json();
            $errorMessage = $errorDetails['details'][0]['description'] ?? ($errorDetails['message'] ?? 'Unknown API error');
            throw new \Exception("PayPal Order API failed: {$errorMessage}", $response->status());
        }

        return $response->json();
    }

    public function capture(Request $request, CheckoutService $checkoutService)
    {
        $request->validate([
            'orderID' => 'required|string',
        ]);

        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)
                ->post($this->paypal_base_url . "/v2/checkout/orders/{$request->orderID}/capture");

            if (! $response->successful()) {
                Log::error('PayPal Capture Failed', $response->json());

                return response()->json([
                    'success' => false,
                    'message' => 'Payment capture failed',
                ], 422);
            }

            $paypalData = $response->json();
            $status = $paypalData['status'] ?? 'FAILED';

            $order = Order::where('transaction_id', $request->orderID)->first();

            if (! $order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], 404);
            }

            if ($status !== 'COMPLETED') {
                $order->update([
                    'payment_method' => 'paypal',
                    'status' => 'failed',
                    'transaction_id' => $request->orderID,
                    'payment_details' => json_encode($paypalData),
                    'is_active' => false,
                ]);

                return response()->json([
                    'success' => false,
                    'status' => $status,
                    'message' => 'Payment was not completed',
                ], 422);
            }

            $checkoutService->completePaidOrder(
                $order,
                $request->orderID,
                'paypal',
                $paypalData,
                'stripe',
            );

            return response()->json([
                'success' => true,
                'status' => $status,
                'order' => $order->fresh(),
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
