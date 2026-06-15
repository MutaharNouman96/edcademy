<x-student-layout>
    @push('styles')
        <style>
            :root {
                --pay-primary: #6f42c1;
                --pay-primary-50: #f3e8ff;
            }
            .pay-header {
                background: linear-gradient(135deg, #7c3aed 0%, #6f42c1 55%, #4b2a87 100%);
                border-radius: 18px;
                color: #fff;
                padding: 1.5rem 1.75rem;
            }
            .pay-kpi {
                border: 0;
                border-radius: 16px;
                box-shadow: 0 4px 16px rgba(0,0,0,.05);
                height: 100%;
            }
            .pay-kpi-body { padding: 1.1rem 1.25rem; }
            .pay-kpi-value { font-size: 1.6rem; font-weight: 800; line-height: 1; }
            .pay-kpi-label { font-size: .82rem; color: #6b7280; margin-top: .25rem; }
            .pay-table-card {
                border: 0;
                border-radius: 18px;
                box-shadow: 0 4px 16px rgba(0,0,0,.05);
            }
            .pay-table thead th {
                font-size: .78rem;
                text-transform: uppercase;
                letter-spacing: .04em;
                color: #6b7280;
                background: #f9fafb;
                border-bottom: 1px solid #eef0f4;
            }
            .pay-amount { font-weight: 700; color: var(--pay-primary); }
            .pay-empty {
                text-align: center;
                padding: 3rem 1rem;
                color: #6b7280;
            }
            #invoiceModal .modal-content { border: 0; border-radius: 18px; overflow: hidden; }
            #invoiceModal .modal-header {
                background: var(--pay-primary-50);
                border-bottom: 1px solid #e9d5ff;
            }
            @media print {
                body * { visibility: hidden; }
                #invoiceModal, #invoiceModal * { visibility: visible; }
                #invoiceModal { position: absolute; left: 0; top: 0; width: 100%; }
                .modal-dialog { max-width: 100%; margin: 0; }
            }
        </style>
    @endpush

    <div class="pay-header mb-4">
        <h2 class="h4 mb-1 fw-bold">Payments & Billing</h2>
        <p class="mb-0 opacity-75 small">Review your purchase history and download invoices.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <div class="card pay-kpi">
                <div class="pay-kpi-body">
                    <div class="pay-kpi-value">${{ number_format($stats['total_spent'], 2) }}</div>
                    <div class="pay-kpi-label">Total spent</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card pay-kpi">
                <div class="pay-kpi-body">
                    <div class="pay-kpi-value">{{ $stats['count'] }}</div>
                    <div class="pay-kpi-label">Transactions</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card pay-table-card p-3 p-md-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 fw-bold">Payment History</h6>
        </div>

        @if (count($payments) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle pay-table mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Purchased Item</th>
                            <th>Type</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th class="text-end">Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($payment['date'])->format('M d, Y') }}</td>
                                <td>{{ $payment['item_title'] }}</td>
                                <td><span class="badge text-bg-light border">{{ $payment['type'] }}</span></td>
                                <td class="small text-muted">{{ $payment['payment_method'] ?? '—' }}</td>
                                <td class="pay-amount">${{ number_format((float) $payment['amount'], 2) }}</td>
                                <td class="text-end">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary rounded-pill btn-view-invoice"
                                        data-invoice-target="invoice-src-{{ $payment['key'] }}">
                                        <i class="bi bi-receipt me-1"></i> View
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Hidden invoice sources rendered with the shared component --}}
            @foreach ($payments as $payment)
                <div id="invoice-src-{{ $payment['key'] }}" class="d-none">
                    <x-student-invoice :invoice="$payment['invoice']" />
                </div>
            @endforeach
        @else
            <div class="pay-empty">
                <i class="bi bi-receipt fs-1 d-block mb-2 text-muted"></i>
                <div class="fw-semibold mb-1">No payments yet</div>
                <p class="small mb-3">Your paid orders and session bookings will appear here.</p>
                <a href="{{ route('web.courses') }}" class="btn btn-primary btn-sm rounded-pill">
                    <i class="bi bi-compass me-1"></i> Browse Courses
                </a>
            </div>
        @endif
    </div>

    {{-- Invoice modal --}}
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="invoiceModalLabel">
                        <i class="bi bi-receipt me-2" style="color:#6f42c1;"></i>Payment Invoice
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="invoiceModalBody"></div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary rounded-pill" id="btnPrintInvoice">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const modalEl = document.getElementById('invoiceModal');
                const modalBody = document.getElementById('invoiceModalBody');
                if (!modalEl || !modalBody) return;

                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

                document.querySelectorAll('.btn-view-invoice').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const src = document.getElementById(btn.dataset.invoiceTarget);
                        if (!src) return;
                        modalBody.innerHTML = src.innerHTML;
                        modal.show();
                    });
                });

                document.getElementById('btnPrintInvoice')?.addEventListener('click', function () {
                    window.print();
                });
            })();
        </script>
    @endpush
</x-student-layout>
