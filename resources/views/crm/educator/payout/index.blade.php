<x-educator-layout>

    <!-- KPIs -->
    <section>
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="kpi">
                    <div class="label">In Escrow</div>
                    <div class="value" id="kpiEscrow">—</div>
                    <div class="hint" id="kpiEscrowHint">Pending release</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="kpi">
                    <div class="label">Available to withdraw</div>
                    <div class="value" id="kpiAvailable">—</div>
                    <div class="hint">After fees</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="kpi">
                    <div class="label">Paid this month</div>
                    <div class="value" id="kpiPaidMonth">—</div>
                    <div class="hint" id="kpiPaidMonthCount">— payouts</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="kpi">
                    <div class="label">Lifetime paid</div>
                    <div class="value" id="kpiLifetime">—</div>
                    <div class="hint">Since joining</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Releases & Payment Method -->
    <section class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h4 class="section-title"><i class="bi bi-calendar2-week"></i> Upcoming Payment Releases</h4>
                    <div class="d-flex gap-2 align-items-center">
                        <input id="upcomingFrom" type="date" class="form-control form-control-sm" />
                        <input id="upcomingTo" type="date" class="form-control form-control-sm" />
                        <button id="upcomingApply" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-funnel me-1"></i>Apply</button>
                    </div>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="upcomingTable" class="table table-striped align-middle w-100">
                            <thead>
                                <tr>
                                    <th>Release Date</th>
                                    <th>Source</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h4 class="section-title"><i class="bi bi-bank"></i> Bank Accounts</h4>
                    <button id="btnAddMethod" class="btn btn-sm btn-outline-primary"><i
                            class="bi bi-plus-lg me-1"></i>Add</button>
                </div>
                <div class="p-3">
                    <ul id="methodList" class="list-group list-group-flush">
                        <!-- dynamically filled -->
                    </ul>
                    <div class="mt-3 small text-muted">Manage your default bank accounts for receiving payouts.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Payout History -->
    <section class="mt-4">
        <div class="card shadow-sm">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h4 class="section-title"><i class="bi bi-clock-history"></i> Payout History</h4>
                <div class="d-flex flex-wrap gap-2">
                    <select id="historyStatus" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="processing">Processing</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="hold">On Hold</option>
                    </select>
                    <input id="historySearch" class="form-control form-control-sm" placeholder="Search ref / note…" />
                    <button id="historyReset" class="btn btn-sm btn-outline-secondary"><i
                            class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                </div>
            </div>
            <div class="p-3">
                <div class="table-responsive">
                    <table id="historyTable" class="table table-striped align-middle w-100">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Fees</th>
                                <th>Net</th>
                                <th>Status</th>
                                <th>Note</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


    <!-- Withdraw Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-cash-coin me-2"></i>Withdraw Funds</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input id="wdAmount" type="number" min="0" step="0.01"
                                    class="form-control" placeholder="0.00" />
                            </div>
                            <div class="form-text">Max available: <span id="wdMax">—</span></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Method</label>
                            <select id="wdMethod" class="form-select">
                                <!-- filled dynamically from methods -->
                            </select>
                            <div class="form-text">Default method preselected.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Note (optional)</label>
                            <textarea id="wdNote" class="form-control" rows="2" placeholder="Add a note for your records…"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="confirmWithdrawBtn" class="btn btn-primary"><i class="bi bi-send me-1"></i> Request
                        Withdrawal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add / Edit Method Modal -->
    <div class="modal fade" id="methodModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-bank me-2"></i>Payment Method</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select id="pmType" class="form-select">
                            <option value="bank">Bank Transfer</option>
                            <option value="paypal">PayPal</option>
                            <option value="wise">Wise</option>
                            <option value="stripe">Stripe Connect</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Label</label>
                        <input id="pmLabel" class="form-control" placeholder="e.g., Personal USD Bank" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Details</label>
                        <textarea id="pmDetails" class="form-control" rows="3" placeholder="IBAN ****, SWIFT **** (stored securely)"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="pmDefault">
                        <label class="form-check-label" for="pmDefault">Set as default</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="saveMethodBtn" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="bankModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="bankForm" class="modal-content">
                @csrf
                <input type="hidden" id="bankId">

                <div class="modal-header">
                    <h5 class="modal-title">Bank Account</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Bank Name</label>
                        <input class="form-control" id="bankName" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Account Name</label>
                        <input class="form-control" id="accountName" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">IBAN</label>
                        <input class="form-control" id="iban" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
        <script>
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            /* -------------------------
               KPIs
            --------------------------*/
            function loadKPIs() {
                fetch('{{ url('/') }}/educator-panel/payouts/kpis')
                    .then(r => r.json())
                    .then(d => {
                        kpiEscrow.innerText = `$${d.escrow}`;
                        kpiAvailable.innerText = `$${d.available}`;
                        kpiPaidMonth.innerText = `$${d.paid_month}`;
                        kpiPaidMonthCount.innerText = `${d.paid_count} payouts`;
                        kpiLifetime.innerText = `$${d.lifetime}`;
                    });
            }

            /* -------------------------
               Upcoming Payments
            --------------------------*/
            function loadUpcoming() {
                const from = upcomingFrom.value;
                const to = upcomingTo.value;

                fetch(`{{ url('/') }}/educator-panel/payouts/upcoming?from=${from}&to=${to}`)
                    .then(r => r.json())
                    .then(rows => {
                        const tbody = document.querySelector('#upcomingTable tbody');
                        tbody.innerHTML = '';

                        if (!rows.length) {
                            tbody.innerHTML =
                                `<tr><td colspan="5" class="text-center text-muted">No upcoming releases</td></tr>`;
                            return;
                        }

                        rows.forEach(r => {
                            tbody.innerHTML += `
                    <tr>
                        <td>${r.release_date}</td>
                        <td>${r.source}</td>
                        <td>$${r.amount}</td>
                        <td><span class="badge bg-warning">${r.status}</span></td>
                        <td class="text-end">—</td>
                    </tr>`;
                        });
                    });
            }

            /* -------------------------
               Payout History
            --------------------------*/
            function loadHistory() {
                const status = historyStatus.value;
                const search = historySearch.value;

                fetch(`{{ url('/') }}/educator-panel/payouts/history?status=${status}&search=${search}`)
                    .then(r => r.json())
                    .then(rows => {
                        const tbody = document.querySelector('#historyTable tbody');
                        tbody.innerHTML = '';

                        if (!rows.length) {
                            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">No payouts</td></tr>`;
                            return;
                        }

                        rows.forEach(r => {
                            tbody.innerHTML += `
                    <tr>
                        <td>${r.date}</td>
                        <td>${r.ref}</td>
                        <td>${r.method}</td>
                        <td>$${r.amount}</td>
                        <td>${r.fees}</td>
                        <td>$${r.net}</td>
                        <td><span class="badge bg-success">${r.status}</span></td>
                        <td>${r.note ?? ''}</td>
                        <td class="text-end">—</td>
                    </tr>`;
                        });
                    });
            }

            /* -------------------------
               Banks
            --------------------------*/
            const bankModal = new bootstrap.Modal(document.getElementById('bankModal'));

            function loadBanks() {
                fetch('{{ url('/') }}/educator-panel/payouts/banks')
                    .then(r => r.json())
                    .then(banks => {
                        const list = document.getElementById('methodList');
                        list.innerHTML = '';

                        if (!banks.length) {
                            list.innerHTML = `<li class="list-group-item text-muted text-center">No bank accounts</li>`;
                            return;
                        }

                        banks.forEach(b => {
                            let bankApprovalStatus = b.approval_status == 1 ? 'Approved' : 'Pending';
                            let bankApprovalClass = bankApprovalStatus == 'Approved' ? 'bg-success' : 'bg-warning';
                            list.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Bank: ${b.bank_name}</strong><br>
                            <strong>Account Holder: ${b.account_name}</strong><br>
                            <small>IBAN: ${b.iban}</small> <span class='ms-2  badge  ${bankApprovalClass}'>${bankApprovalStatus}</span>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="editBank(${JSON.stringify(b).replace(/"/g, '&quot;')})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteBank(${b.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </li>`;
                        });
                    });
            }

            function editBank(b) {
                bankId.value = b.id;
                bankName.value = b.bank_name;
                accountName.value = b.account_name;
                iban.value = b.iban;
                bankModal.show();
            }

            function deleteBank(id) {
                if (!confirm('Delete this bank account?')) return;

                fetch(`{{ url('/') }}/educator-panel/payouts/banks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    }
                }).then(() => loadBanks());
            }

            /* -------------------------
               Save Bank
            --------------------------*/
            document.getElementById('bankForm').addEventListener('submit', e => {
                e.preventDefault();

                fetch('{{ url('/') }}/educator-panel/payouts/banks/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        id: bankId.value,
                        bank_name: bankName.value,
                        account_name: accountName.value,
                        iban: iban.value
                    })
                }).then(() => {
                    bankModal.hide();
                    loadBanks();
                });
            });

            /* -------------------------
               Events
            --------------------------*/
            upcomingApply.onclick = loadUpcoming;
            historyStatus.onchange = loadHistory;
            historySearch.onkeyup = loadHistory;
            historyReset.onclick = () => {
                historyStatus.value = '';
                historySearch.value = '';
                loadHistory();
            };

            btnAddMethod.onclick = () => {
                bankId.value = '';
                bankForm.reset();
                bankModal.show();
            };

            /* -------------------------
               Init
            --------------------------*/
            loadKPIs();
            loadUpcoming();
            loadHistory();
            loadBanks();
        </script>
    @endpush
</x-educator-layout>
