@props(['invoice'])

@php
    $issuedAt = $invoice['issued_at'] ?? now();
    if (is_string($issuedAt)) {
        $issuedAt = \Carbon\Carbon::parse($issuedAt);
    }
@endphp

<div {{ $attributes->merge(['class' => 'student-invoice']) }}>
    <div class="si-header d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4 pb-3 border-bottom">
        <div>
            <div class="si-brand fw-bold" style="color:#6f42c1;font-size:1.1rem;">Ed-Cademy</div>
            <div class="text-muted small">Payment Invoice</div>
        </div>
        <div class="text-md-end">
            <div class="fw-bold fs-5">{{ $invoice['number'] ?? 'N/A' }}</div>
            <div class="small text-muted">{{ $issuedAt->format('M d, Y · g:i A') }}</div>
            @if (! empty($invoice['status']))
                <span class="badge rounded-pill mt-1" style="background:#d1fae5;color:#065f46;">{{ $invoice['status'] }}</span>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="small text-uppercase text-muted fw-semibold mb-1">Billed To</div>
            <div class="fw-semibold">{{ $invoice['customer']['name'] ?? 'Student' }}</div>
            @if (! empty($invoice['customer']['email']))
                <div class="small text-muted">{{ $invoice['customer']['email'] }}</div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="small text-uppercase text-muted fw-semibold mb-1">Payment Details</div>
            @if (! empty($invoice['payment_method']))
                <div class="small"><span class="text-muted">Method:</span> {{ $invoice['payment_method'] }}</div>
            @endif
            @if (! empty($invoice['transaction_id']))
                <div class="small"><span class="text-muted">Transaction:</span> <code class="small">{{ $invoice['transaction_id'] }}</code></div>
            @endif
        </div>
    </div>

    <div class="table-responsive mb-3">
        <table class="table table-sm align-middle mb-0">
            <thead style="background:#f3e8ff;">
                <tr>
                    <th>Item</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Unit</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice['line_items'] ?? [] as $row)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $row['title'] }}</div>
                            @if (! empty($row['subtitle']))
                                <div class="small text-muted">{{ $row['subtitle'] }}</div>
                            @endif
                        </td>
                        <td class="text-center"><span class="badge text-bg-light border">{{ $row['type'] ?? 'Item' }}</span></td>
                        <td class="text-center">{{ $row['quantity'] ?? 1 }}</td>
                        <td class="text-end">${{ number_format((float) ($row['unit_price'] ?? 0), 2) }}</td>
                        <td class="text-end fw-semibold">${{ number_format((float) ($row['total'] ?? 0), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end">
        <div class="col-md-5">
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted">Subtotal</td>
                    <td class="text-end">${{ number_format((float) ($invoice['subtotal'] ?? 0), 2) }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Tax</td>
                    <td class="text-end">${{ number_format((float) ($invoice['tax'] ?? 0), 2) }}</td>
                </tr>
                <tr class="border-top">
                    <td class="fw-bold" style="color:#6f42c1;">Grand Total</td>
                    <td class="text-end fw-bold fs-5" style="color:#6f42c1;">
                        ${{ number_format((float) ($invoice['total'] ?? 0), 2) }}
                        <span class="small fw-normal text-muted">{{ $invoice['currency'] ?? 'USD' }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="mt-4 pt-3 border-top small text-muted text-center">
        Thank you for your purchase. For billing questions, contact support@ed-cademy.com
    </div>
</div>
