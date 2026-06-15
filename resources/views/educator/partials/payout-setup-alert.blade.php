@if (! auth()->user()->canReceivePayouts())
    <div class="alert alert-warning mb-3" role="alert">
        <div class="d-flex align-items-start gap-3 flex-wrap">
            <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
            <div class="flex-grow-1">
                <strong>Payout setup incomplete</strong>
                <p class="mb-2 small">
                    You can add courses and content now, but you will need to connect Stripe and add your bank details
                    (IBAN) before you can receive earnings.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('stripe.connect') }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-link-45deg me-1"></i> Set up payouts
                    </a>
                    <a href="{{ route('educator.settings') }}#tab-connections" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-gear me-1"></i> Settings
                    </a>
                    @if (isset($openPayoutRequest) && $openPayoutRequest)
                        <span class="badge text-bg-info align-self-center">
                            Assistance request {{ $openPayoutRequest->status === 'in_progress' ? 'in progress' : 'pending' }}
                        </span>
                    @else
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#payoutAssistModal">
                            <i class="bi bi-headset me-1"></i> Ask admin for help
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (! isset($openPayoutRequest) || ! $openPayoutRequest)
        <div class="modal fade" id="payoutAssistModal" tabindex="-1" aria-labelledby="payoutAssistModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('educator.payout-requests.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="assist">
                        <div class="modal-header">
                            <h5 class="modal-title" id="payoutAssistModalLabel">
                                <i class="bi bi-headset me-2"></i>Request payout assistance
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small">
                                Tell us what you need help with. An admin can guide you through Stripe Connect and payout setup.
                            </p>
                            <label class="form-label" for="payoutAssistMessage">Message (optional)</label>
                            <textarea class="form-control" id="payoutAssistMessage" name="message" rows="4"
                                maxlength="2000"
                                placeholder="e.g. I need help adding my IBAN / bank account for payouts.">{{ old('message') }}</textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i> Send request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endif
