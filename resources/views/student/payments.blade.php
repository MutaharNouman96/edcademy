<x-student-layout>
    {{-- All content for this page goes here, without a <x> tag or column definitions --}}

    <h2 class="h4 mb-4">Payments & Billing</h2>

    <!-- KPI cards -->
    <div class="row g-3 mb-4 d-none">

        <div class="col-sm-6 col-lg-3">
            <div class="card kpi-card p-3">
                <div class="d-flex justify-content-between">
                    <span class="kpi-icon"><i class="bi bi-box-seam"></i></span>
                    <span class="small text-muted">Subscription</span>
                </div>
                <h3 class="mt-2">Premium</h3>
                <small class="text-muted">Active Plan</small>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card kpi-card p-3">
                <div class="d-flex justify-content-between">
                    <span class="kpi-icon"><i class="bi bi-calendar-event"></i></span>
                    <span class="small text-muted">Next</span>
                </div>
                <h3 class="mt-2">Oct 15</h3>
                <small class="text-muted">Renewal Date</small>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card kpi-card p-3">
                <div class="d-flex justify-content-between">
                    <span class="kpi-icon"><i class="bi bi-credit-card"></i></span>
                    <span class="small text-muted">Method</span>
                </div>
                <h3 class="mt-2">Visa •••• 4242</h3>
                <small class="text-muted">Default Payment</small>
            </div>
        </div>
    </div>

    <!-- Payments history -->
    <div class="card p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Payment History</h6>
            <a href="#" class="small">Download Invoices</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Purchased Item</th>
                        <th>Type</th>
                        <th>Amount</th>

                    </tr>
                </thead>
                <tbody id="paymentTable">
                    @foreach ($paymentData as $payment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($payment['date'])->format('M d, Y') }}</td>
                            <td>{{ $payment['item_title'] }}</td>
                            <td>{{ $payment['type'] }}</td>
                            <td>@if ($payment['amount']) $ @endif {{ $payment['amount'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment settings -->
    <div class="card p-3 d-none">
        <h6 class="mb-3">Payment Settings</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6><i class="bi bi-credit-card me-2"></i>Manage Cards</h6>
                    <p class="small text-muted">Update or add a new credit/debit card.</p>
                    <button class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i> Add Card</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6><i class="bi bi-paypal me-2"></i> PayPal</h6>
                    <p class="small text-muted">Link your PayPal account for fast checkout.</p>
                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-link-45deg me-1"></i> Connect
                        PayPal</button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="border rounded p-3">
                    <h6><i class="bi bi-file-earmark-text me-2"></i>Billing Info</h6>
                    <p class="small text-muted">Edit your billing address and tax details.</p>
                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i> Edit Billing
                        Info</button>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>
