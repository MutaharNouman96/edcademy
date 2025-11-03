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
                    <h2 class="section-title"><i class="bi bi-calendar2-week"></i> Upcoming Payment Releases</h2>
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
                    <h2 class="section-title"><i class="bi bi-bank"></i> Payment Methods</h2>
                    <button id="btnAddMethod" class="btn btn-sm btn-outline-primary"><i
                            class="bi bi-plus-lg me-1"></i>Add</button>
                </div>
                <div class="p-3">
                    <ul id="methodList" class="list-group list-group-flush">
                        <!-- dynamically filled -->
                    </ul>
                    <div class="mt-3 small text-muted">Manage your default method and verification.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Payout History -->
    <section class="mt-4">
        <div class="card shadow-sm">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h2 class="section-title"><i class="bi bi-clock-history"></i> Payout History</h2>
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
</x-educator-layout>
