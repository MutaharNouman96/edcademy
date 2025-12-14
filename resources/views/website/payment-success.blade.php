<x-guest-layout>
    @push('styles')
        <style>
            .receipt-container {
                max-width: 800px;
                margin: 40px auto;
                background: #fff;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, .1);
            }

            .receipt-header {
                border-bottom: 2px solid #eee;
                margin-bottom: 20px;
                padding-bottom: 10px;
            }

            .receipt-title {
                font-size: 26px;
                font-weight: bold;
                color: #28a745;
            }

            .table th {
                background: #f8f9fa;
            }

            @media print {

                #printBtn,
                .navbar,
                footer {
                    display: none !important;
                }

                body {
                    background: #fff;
                }

                .receipt-container {
                    box-shadow: none;
                    margin: 0;
                }
            }
        </style>
    @endpush
    <div class="container">
        <div class="receipt-container">

            {{-- HEADER --}}
            <div class="receipt-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="receipt-title">Payment Successful</div>
                    <div class="text-muted">Thank you! Your payment has been completed.</div>
                </div>

                <button id="printBtn" onclick="window.print();" class="btn btn-outline-secondary">
                    <i class="bi bi-printer"></i> Print Receipt
                </button>
            </div>

            {{-- ORDER DETAILS --}}
            <div class="mb-4">
                <h5 class="fw-bold">Order Information</h5>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th style="width: 180px;">Order ID:</th>
                        <td>#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge bg-success">Paid</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Payment Method:</th>
                        <td class="text-uppercase">{{ $order->payment_method }}</td>
                    </tr>
                    <tr>
                        <th>Transaction ID:</th>
                        <td>{{ $order->transaction_id }}</td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ $order->created_at->format('d M Y - h:i A') }}</td>
                    </tr>
                </table>
            </div>

            {{-- BILLING INFO --}}
            <div class="mb-4">
                <h5 class="fw-bold">Billing Details</h5>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th style="width: 180px;">Customer Name:</th>
                        <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $order->user->email }}</td>
                    </tr>
                </table>
            </div>

            {{-- ITEMS --}}
            <div class="mb-4">
                <h5 class="fw-bold">Purchased Items</h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Model</th>
                            <th>Qty</th>
                            <th style="width:140px;">Price</th>
                            <th style="width:140px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp

                        @foreach ($order->items as $item)
                            @php
                                $grandTotal += $item->total;
                                $modelClass = 'App\\Models\\' . $item->model;
                                $product = $modelClass::find($item->item_id);
                            @endphp
                            <tr>
                                <td>
                                    {{ $product ? $product->title ?? ($product->name ?? 'Product #' . $item->item_id) : 'Item #' . $item->item_id }}
                                </td>
                                <td>{{ $item->model }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Grand Total:</th>
                            <th>${{ number_format($grandTotal, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- FOOTER --}}
            <div class="mt-4 text-center text-muted small">
                This payment receipt was automatically generated.
                <br>For support, contact: <strong>support@edcademy.com</strong>
            </div>

        </div>
    </div>

</x-guest-layout>
