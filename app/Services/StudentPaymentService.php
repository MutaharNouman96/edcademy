<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserPurchasedItem;
use Carbon\Carbon;

/**
 * Builds a unified payment history for the student dashboard, merging checkout
 * orders, session bookings and legacy purchase records without duplicates.
 */
class StudentPaymentService
{
    /**
     * @return array{payments: array<int, array>, stats: array{total_spent: float, count: int}}
     */
    public function getPaymentHistory(User $user): array
    {
        $payments = collect();

        $paidOrders = Order::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', (string) $user->id)
                    ->orWhere('user_id', $user->id);
            })
            ->whereIn('status', ['paid', 'completed'])
            ->with(['items', 'user'])
            ->latest()
            ->get();

        $coveredKeys = collect();

        foreach ($paidOrders as $order) {
            foreach ($order->items as $item) {
                $coveredKeys->push($this->itemKey($item->model, (int) $item->item_id));
            }

            $invoice = $this->buildOrderInvoice($order);
            $summary = $this->summarizeLineItems($invoice['line_items']);

            $payments->push([
                'key'           => 'order-' . $order->id,
                'date'          => $order->created_at,
                'item_title'    => $summary['title'],
                'type'          => 'Order',
                'amount'        => (float) $invoice['total'],
                'status'        => ucfirst($order->status),
                'payment_method'=> $this->formatPaymentMethod($order->payment_method),
                'invoice'       => $invoice,
            ]);
        }

        $paidBookings = Booking::query()
            ->where('student_id', $user->id)
            ->where('payment_status', Booking::PAYMENT_PAID)
            ->with(['student', 'educator'])
            ->latest('paid_at')
            ->get();

        foreach ($paidBookings as $booking) {
            $invoice = $this->buildBookingInvoice($booking);

            $payments->push([
                'key'           => 'booking-' . $booking->id,
                'date'          => $booking->paid_at ?? $booking->created_at,
                'item_title'    => $invoice['line_items'][0]['title'] ?? 'Session booking',
                'type'          => 'Session',
                'amount'        => (float) $invoice['total'],
                'status'        => 'Paid',
                'payment_method'=> $this->formatPaymentMethod($booking->payment_method),
                'invoice'       => $invoice,
            ]);
        }

        // Legacy course_purchases not already covered by a paid order line item.
        $legacyCoursePurchases = CoursePurchase::query()
            ->where('student_id', $user->id)
            ->where('is_active', true)
            ->with(['course', 'student'])
            ->latest()
            ->get();

        foreach ($legacyCoursePurchases as $purchase) {
            $key = $this->itemKey(Course::class, (int) $purchase->course_id);
            if ($coveredKeys->contains($key)) {
                continue;
            }

            $invoice = $this->buildCoursePurchaseInvoice($purchase);
            if (! $invoice) {
                continue;
            }

            $payments->push([
                'key'           => 'course-purchase-' . $purchase->id,
                'date'          => $purchase->created_at,
                'item_title'    => $invoice['line_items'][0]['title'] ?? 'Course',
                'type'          => 'Course',
                'amount'        => (float) $invoice['total'],
                'status'        => ucfirst($purchase->payment_status ?: 'paid'),
                'payment_method'=> $this->formatPaymentMethod($purchase->payment_method),
                'invoice'       => $invoice,
            ]);
        }

        // Stand-alone user_purchased_items (e.g. admin grants) not tied to an order.
        $purchasedItems = UserPurchasedItem::query()
            ->where('user_id', $user->id)
            ->where('active', true)
            ->with('purchasable')
            ->latest()
            ->get();

        foreach ($purchasedItems as $item) {
            $purchasable = $item->purchasable;
            if (! $purchasable) {
                continue;
            }

            $type = $item->purchasable_type;
            $key  = $this->itemKey($type, (int) $item->purchasable_id);
            if ($coveredKeys->contains($key)) {
                continue;
            }

            $invoice = $this->buildPurchasedItemInvoice($item, $user);
            if (! $invoice) {
                continue;
            }

            $payments->push([
                'key'           => 'item-' . $item->id,
                'date'          => $item->created_at,
                'item_title'    => $invoice['line_items'][0]['title'] ?? 'Purchase',
                'type'          => class_basename($type),
                'amount'        => (float) $invoice['total'],
                'status'        => 'Paid',
                'payment_method'=> null,
                'invoice'       => $invoice,
            ]);

            // Avoid duplicate legacy rows for the same purchasable.
            $coveredKeys->push($key);
        }

        // Legacy purchases table rows (seed / old flow).
        $legacyPurchases = Purchase::query()
            ->where('student_id', $user->id)
            ->where('is_active', 1)
            ->whereIn('payment_status', ['paid', 'completed', 'approved'])
            ->with(['course', 'student'])
            ->latest()
            ->get();

        foreach ($legacyPurchases as $purchase) {
            $key = $this->itemKey(Course::class, (int) $purchase->course_id);
            if ($coveredKeys->contains($key)) {
                continue;
            }

            $invoice = $this->buildLegacyPurchaseInvoice($purchase);
            if (! $invoice) {
                continue;
            }

            $payments->push([
                'key'           => 'purchase-' . $purchase->id,
                'date'          => $purchase->created_at,
                'item_title'    => $invoice['line_items'][0]['title'] ?? 'Course',
                'type'          => 'Course',
                'amount'        => (float) $invoice['total'],
                'status'        => ucfirst($purchase->payment_status ?: 'paid'),
                'payment_method'=> $this->formatPaymentMethod($purchase->payment_method),
                'invoice'       => $invoice,
            ]);
        }

        $payments = $payments->sortByDesc(fn ($p) => Carbon::parse($p['date'])->timestamp)->values();

        return [
            'payments' => $payments->all(),
            'stats'    => [
                'total_spent' => round($payments->sum('amount'), 2),
                'count'       => $payments->count(),
            ],
        ];
    }

    public function buildOrderInvoice(Order $order): array
    {
        $order->loadMissing(['items', 'user']);
        $lineItems = [];

        foreach ($order->items as $item) {
            $product = class_exists($item->model) ? $item->model::find($item->item_id) : null;
            $type    = str_replace('App\\Models\\', '', $item->model);
            $title   = $product?->title ?? ('Item #' . $item->item_id);
            $subtitle = null;

            if ($product instanceof Lesson && $product->course) {
                $subtitle = 'Course: ' . $product->course->title;
            }

            $lineItems[] = [
                'title'      => $title,
                'subtitle'   => $subtitle,
                'type'       => $type,
                'quantity'   => (int) ($item->quantity ?: 1),
                'unit_price' => (float) $item->price,
                'total'      => (float) $item->total,
            ];
        }

        $subtotal = collect($lineItems)->sum('total');

        $tax = 0.0;
        foreach ($order->items as $item) {
            $base = (float) $item->price * (int) ($item->quantity ?: 1);
            $tax += $base * ((float) ($item->tax ?? 0) / 100);
        }

        return $this->invoicePayload(
            number: 'ORD-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT),
            issuedAt: $order->created_at,
            customer: $order->user,
            paymentMethod: $order->payment_method,
            transactionId: $order->transaction_id,
            status: ucfirst($order->status),
            lineItems: $lineItems,
            subtotal: $subtotal,
            tax: round($tax, 2),
            total: round($subtotal + $tax, 2),
        );
    }

    public function buildBookingInvoice(Booking $booking): array
    {
        $booking->loadMissing(['student', 'educator']);
        $educatorName = optional($booking->educator)->full_name ?? 'Educator';
        $amount       = (float) ($booking->amount ?? 0);

        $lineItems = [[
            'title'      => '1-on-1 Session with ' . $educatorName,
            'subtitle'   => sprintf(
                '%s · %s at %s · %s hr',
                ucfirst($booking->subject ?? 'General'),
                $booking->date?->format('M j, Y') ?? '',
                substr((string) $booking->time, 0, 5),
                rtrim(rtrim((string) $booking->duration, '0'), '.')
            ),
            'type'       => 'Session',
            'quantity'   => 1,
            'unit_price' => $amount,
            'total'      => $amount,
        ]];

        return $this->invoicePayload(
            number: 'BKG-' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT),
            issuedAt: $booking->paid_at ?? $booking->created_at,
            customer: $booking->student,
            paymentMethod: $booking->payment_method,
            transactionId: $booking->payment_intent_id,
            status: 'Paid',
            lineItems: $lineItems,
            subtotal: $amount,
            tax: 0,
            total: $amount,
            currency: strtoupper($booking->currency ?? 'USD'),
        );
    }

    private function buildCoursePurchaseInvoice(CoursePurchase $purchase): ?array
    {
        if (! $purchase->course) {
            return null;
        }

        $price = (float) ($purchase->course->price ?? 0);

        return $this->singleItemInvoice(
            number: 'CPR-' . str_pad((string) $purchase->id, 6, '0', STR_PAD_LEFT),
            issuedAt: $purchase->created_at,
            customer: $purchase->student,
            paymentMethod: $purchase->payment_method,
            transactionId: $purchase->payment_id,
            status: ucfirst($purchase->payment_status ?: 'paid'),
            title: $purchase->course->title,
            type: 'Course',
            amount: $price,
        );
    }

    private function buildPurchasedItemInvoice(UserPurchasedItem $item, User $user): ?array
    {
        $purchasable = $item->purchasable;
        if (! $purchasable) {
            return null;
        }

        $type  = class_basename($item->purchasable_type);
        $title = $item->displayTitle();
        $price = $item->displayPrice();
        $subtitle = null;

        if ($purchasable instanceof Lesson) {
            $purchasable->loadMissing('course');
            if ($purchasable->course) {
                $subtitle = 'Course: ' . $purchasable->course->title;
            }
        }

        $lineItems = [[
            'title'      => $title,
            'subtitle'   => $subtitle,
            'type'       => $type,
            'quantity'   => 1,
            'unit_price' => $price,
            'total'      => $price,
        ]];

        return $this->invoicePayload(
            number: 'ITM-' . str_pad((string) $item->id, 6, '0', STR_PAD_LEFT),
            issuedAt: $item->created_at,
            customer: $user,
            paymentMethod: null,
            transactionId: null,
            status: 'Paid',
            lineItems: $lineItems,
            subtotal: $price,
            tax: 0,
            total: $price,
        );
    }

    private function buildLegacyPurchaseInvoice(Purchase $purchase): ?array
    {
        if (! $purchase->course) {
            return null;
        }

        $price = (float) ($purchase->course->price ?? 0);

        return $this->singleItemInvoice(
            number: 'PUR-' . str_pad((string) $purchase->id, 6, '0', STR_PAD_LEFT),
            issuedAt: $purchase->created_at,
            customer: $purchase->student,
            paymentMethod: $purchase->payment_method,
            transactionId: $purchase->payment_id,
            status: ucfirst($purchase->payment_status ?: 'paid'),
            title: $purchase->course->title,
            type: 'Course',
            amount: $price,
        );
    }

    private function singleItemInvoice(
        string $number,
        $issuedAt,
        ?User $customer,
        ?string $paymentMethod,
        ?string $transactionId,
        string $status,
        string $title,
        string $type,
        float $amount,
    ): array {
        return $this->invoicePayload(
            number: $number,
            issuedAt: $issuedAt,
            customer: $customer,
            paymentMethod: $paymentMethod,
            transactionId: $transactionId,
            status: $status,
            lineItems: [[
                'title'      => $title,
                'subtitle'   => null,
                'type'       => $type,
                'quantity'   => 1,
                'unit_price' => $amount,
                'total'      => $amount,
            ]],
            subtotal: $amount,
            tax: 0,
            total: $amount,
        );
    }

    private function invoicePayload(
        string $number,
        $issuedAt,
        ?User $customer,
        ?string $paymentMethod,
        ?string $transactionId,
        string $status,
        array $lineItems,
        float $subtotal,
        float $tax,
        float $total,
        string $currency = 'USD',
    ): array {
        return [
            'number'          => $number,
            'issued_at'       => Carbon::parse($issuedAt),
            'status'          => $status,
            'payment_method'  => $this->formatPaymentMethod($paymentMethod),
            'transaction_id'  => $transactionId,
            'currency'        => $currency,
            'customer'        => [
                'name'  => $customer?->full_name ?? $customer?->name ?? 'Student',
                'email' => $customer?->email,
            ],
            'line_items'      => $lineItems,
            'subtotal'        => round($subtotal, 2),
            'tax'             => round($tax, 2),
            'total'           => round($total, 2),
        ];
    }

    private function summarizeLineItems(array $lineItems): array
    {
        if (count($lineItems) === 0) {
            return ['title' => 'Purchase', 'count' => 0];
        }

        $first = $lineItems[0]['title'];
        $extra = count($lineItems) - 1;

        return [
            'title' => $extra > 0 ? $first . ' +' . $extra . ' more' : $first,
            'count' => count($lineItems),
        ];
    }

    private function itemKey(string $model, int $id): string
    {
        return $model . ':' . $id;
    }

    private function formatPaymentMethod(?string $method): ?string
    {
        if (! $method) {
            return null;
        }

        if (str_starts_with($method, 'stripe:')) {
            return 'Stripe (' . ucfirst(str_replace('stripe:', '', $method)) . ')';
        }

        return ucfirst(str_replace(['_', '-'], ' ', $method));
    }
}
