<x-educator-layout>
    <!-- Header -->
    <div class=" py-2">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <a href="/educator" class="text-decoration-none brand">
                        <i class="bi bi-mortarboard-fill me-2"></i>Ed‑Cademy
                    </a>
                    <span class="d-none d-md-inline text-muted">Educator Settings</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button id="btnSaveAll" class="btn btn-sm btn-primary">
                        <i class="bi bi-save2 me-1"></i> Save All
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- Main -->

    @php
        $openPayoutRequest = ($user->isEducator() && ! $user->canReceivePayouts())
            ? \App\Models\EducatorPayoutRequest::query()
                ->where('educator_id', $user->id)
                ->open()
                ->latest()
                ->first()
            : null;
    @endphp

    @if ($user->isEducator() && ! $user->canReceivePayouts())
        <div class="alert alert-warning d-flex align-items-center justify-content-between flex-wrap gap-2" id="stripeSetupAlert">
            <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Payout setup recommended:</strong> Connect Stripe and add your IBAN / bank details to receive earnings from your courses.
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('stripe.connect') }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-link-45deg me-1"></i> Set up payouts
                </a>
                @if ($openPayoutRequest)
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
    @endif

    @if ($user->isEducator() && ! $user->canReceivePayouts() && ! $openPayoutRequest)
        <div class="modal fade" id="payoutAssistModal" tabindex="-1" aria-labelledby="payoutAssistModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('educator.payout-requests.store') }}">
                        @csrf
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

    <div class="card shadow-sm">
        <div class="section-header">
            <h2 class="section-title"><i class="bi bi-gear"></i> Profile & Account Settings</h2>
        </div>
        <div class="p-3">
            <!-- Tabs -->
            <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#tab-profile"
                        type="button" role="tab" aria-controls="tab-profile" aria-selected="true"><i
                            class="bi bi-person me-1"></i>
                        Profile</button>
                </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="verification-tab" data-bs-toggle="tab"
                        data-bs-target="#tab-verification" type="button" role="tab"
                        aria-controls="tab-verification" aria-selected="false"><i class="bi bi-patch-check me-1"></i>
                      Intro & Verification Documents </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#tab-security"
                        type="button" role="tab" aria-controls="tab-security" aria-selected="false"><i
                            class="bi bi-shield-lock me-1"></i> Security</button>
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#tab-payments"
                        type="button" role="tab" aria-controls="tab-payments" aria-selected="false"><i
                            class="bi bi-bank me-1"></i>
                        Payments</button>
                </li> --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="availability-tab" data-bs-toggle="tab"
                        data-bs-target="#tab-availability" type="button" role="tab"
                        aria-controls="tab-availability" aria-selected="false"><i class="bi bi-calendar2-week me-1"></i>
                        Availability</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="notifications-tab" data-bs-toggle="tab"
                        data-bs-target="#tab-notifications" type="button" role="tab"
                        aria-controls="tab-notifications" aria-selected="false"><i class="bi bi-bell me-1"></i>
                        Notifications</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#tab-privacy"
                        type="button" role="tab" aria-controls="tab-privacy" aria-selected="false"><i
                            class="bi bi-eye-slash me-1"></i> Privacy</button>
                </li>
              
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="connections-tab" data-bs-toggle="tab"
                        data-bs-target="#tab-connections" type="button" role="tab"
                        aria-controls="tab-connections" aria-selected="false"><i class="bi bi-plug me-1"></i>
                        Connections</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab"
                        data-bs-target="#tab-preferences" type="button" role="tab"
                        aria-controls="tab-preferences" aria-selected="false"><i class="bi bi-sliders me-1"></i>
                        Preferences</button>
                </li>
            </ul>

            <div class="tab-content pt-3">
                <!-- Profile -->
                <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                    <form id="formProfile" class="row g-3">
                        <div class="col-12 d-flex align-items-center gap-3">
                            <img id="avatarPreview" class="avatar" src="{{ url('public/'.$user->profile_picture)}}"
                                alt="Avatar">
                            <div>
                                <div class="btn-group">
                                    <label class="btn btn-sm btn-outline-primary mb-0">
                                        <i class="bi bi-upload me-1"></i> Change Photo
                                        <input id="avatarInput" type="file" accept="image/*" hidden>
                                    </label>
                                    <button type="button" id="btnRemoveAvatar"
                                        class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle me-1"></i>
                                        Remove</button>
                                </div>
                                <div class="help mt-1">Max 2MB JPG/PNG. Square works best.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label req">First name</label>
                            <input name="first_name" class="form-control" placeholder="Your name"
                                value="{{ $user->first_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label req">last name</label>
                            <input name="last_name" class="form-control" placeholder="Your name"
                                value="{{ $user->last_name }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label req">User name</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input name="handle" class="form-control" placeholder="username"
                                    value="{{ $user->username }}" required>
                            </div>
                            <div class="form-text">Used in your public profile URL.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="4" placeholder="Introduce yourself to students…">{{ $user->bio ?? '' }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Subjects</label>
                            <select id="subjectsSelect" name="subjects[]" class="form-select select2" multiple
                                data-placeholder="Select one or more subjects">
                                @php
                                    $subjectOptions = ['Mathematics', 'Science', 'English', 'Computer Science', 'Languages', 'Other'];
                                    $savedSubjects = array_filter(array_map('trim', explode(',', $user->educatorProfile->primary_subject ?? '')));
                                @endphp
                                @foreach ($subjectOptions as $subject)
                                    <option value="{{ $subject }}" {{ in_array($subject, $savedSubjects, true) ? 'selected' : '' }}>
                                        {{ $subject }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Select all subjects you teach.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hourly rate (USD)</label>
                            <div class="input-group"><span class="input-group-text">$</span><input name="rate"
                                    type="number" step="1" min="0" class="form-control"
                                    value="{{ $user->educatorProfile->hourly_rate ?? '' }}"
                                    placeholder="25"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Teaching Levels</label>
                            <select id="teachingLevelsSelect" name="teaching_levels[]" class="form-select select2"
                                multiple data-placeholder="Select one or more levels">
                                @php
                                    $levels = ['Elementary', 'Middle School', 'High School', 'College', 'Professional'];
                                    $savedLevels = json_decode($user->educatorProfile->teaching_levels ?? '[]', true) ?: [];
                                @endphp
                                @foreach($levels as $level)
                                    <option value="{{ $level }}" {{ in_array($level, $savedLevels, true) ? 'selected' : '' }}>{{ $level }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Search and select all levels you teach.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Certifications</label>
                            <textarea name="certifications" class="form-control" rows="3" placeholder="List your teaching certifications, degrees, or qualifications…">{{ $user->educatorProfile->certifications ?? '' }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Preferred Teaching Style</label>
                            <select name="preferred_teaching_style" class="form-select">
                                <option value="">-- Select --</option>
                                @php
                                    $styles = ['Interactive / Discussion-based', 'Lecture / Presentation', 'Hands-on / Practical', 'Assessment-driven'];
                                    $savedStyle = $user->educatorProfile?->decodedTeachingStyle() ?? '';
                                @endphp
                                @foreach($styles as $style)
                                    <option value="{{ $style }}" {{ $savedStyle === $style ? 'selected' : '' }}>{{ $style }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i> Save
                                Profile</button>
                        </div>
                    </form>
                </div>

                <!-- Security -->
                <div class="tab-pane fade" id="tab-security" role="tabpanel">
                    <form id="formPassword" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label req">Current password</label>
                            <input name="current" type="password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label req">New password</label>
                            <input name="password" id="newPass" type="password" class="form-control"
                                minlength="8" required>
                            <div class="form-text">Min 8 chars, include number & symbol.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label req">Confirm</label>
                            <input name="confirm" id="newPass2" type="password" class="form-control" required>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-shield-lock me-1"></i>
                                Update Password</button>
                        </div>
                    </form>

                    <hr class="my-4" />

                    <form id="form2FA" class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <div class="form-check form-switch switch">
                                <input class="form-check-input" type="checkbox" id="twoFA">
                                <label class="form-check-label" for="twoFA">Enable 2‑factor
                                    authentication</label>
                            </div>
                            <div class="help">Use authenticator app or SMS.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Method</label>
                            <select id="twoFAMethod" class="form-select" disabled>
                                <option value="app">Authenticator App</option>
                                <option value="sms">SMS</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone (for SMS)</label>
                            <input id="twoFAPhone" class="form-control" placeholder="+971…" disabled>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button id="btn2FASave" class="btn btn-outline-primary" type="button" disabled><i
                                    class="bi bi-check2 me-1"></i> Save 2FA</button>
                        </div>

                        <div id="appHint" class="col-12 help d-none"><i class="bi bi-qr-code me-1"></i>
                            Scan QR in your app → enter code to verify.</div>
                    </form>

                    <hr class="my-4" />

                    <div class="danger-zone">
                        <h6 class="text-danger mb-2"><i class="bi bi-exclamation-triangle me-1"></i>
                            Danger Zone</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <button id="btnLogoutAll" class="btn btn-sm btn-outline-danger"><i
                                    class="bi bi-box-arrow-right me-1"></i> Log out of all devices</button>
                            <button id="btnDeleteAcc" class="btn btn-sm btn-outline-danger"><i
                                    class="bi bi-trash me-1"></i> Delete account</button>
                        </div>
                    </div>
                </div>

                <!-- Payments -->
                {{-- <div class="tab-pane fade" id="tab-payments" role="tabpanel">
                    <form id="formPayout" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Default currency</label>
                            <select name="currency" class="form-select">
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="$">$</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Payout schedule</label>
                            <select name="schedule" class="form-select">
                                <option value="manual">Manual</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Min payout threshold</label>
                            <div class="input-group"><span class="input-group-text">$</span><input name="threshold"
                                    type="number" step="1" min="0" class="form-control"
                                    placeholder="50"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Payment methods</label>
                            <div id="payMethods" class="d-flex flex-wrap gap-2">
                                <!-- filled dynamically -->
                            </div>
                            <button id="btnAddMethod" type="button" class="btn btn-sm btn-outline-primary mt-2"><i
                                    class="bi bi-plus-lg me-1"></i>Add method</button>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Billing name</label>
                            <input name="billing_name" class="form-control" placeholder="Your legal name / business">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Billing address</label>
                            <input name="billing_address" class="form-control"
                                placeholder="Street, City, ZIP, Country">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tax/VAT number</label>
                            <input name="tax_id" class="form-control" placeholder="(optional)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Invoice email</label>
                            <input name="invoice_email" type="email" class="form-control"
                                placeholder="billing@you.com">
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i> Save
                                Payments</button>
                        </div>
                    </form>
                </div> --}}

                <!-- Availability -->
                <div class="tab-pane fade" id="tab-availability" role="tabpanel">
                    <form id="formAvail" class="row g-3">
                        <!-- Day availability schedule (main) -->
                        <div class="col-12">
                            <h6 class="mb-2"><i class="bi bi-calendar2-range me-1"></i> When can students book you?</h6>
                            <p class="text-muted small mb-3">Turn each day on or off and set your available time range. Only these slots will be shown on your profile.</p>
                            <div class="schedule-grid border rounded p-3 bg-light">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width:100px">Day</th>
                                                <th style="width:100px">Available</th>
                                                <th>From</th>
                                                <th>To</th>
                                            </tr>
                                        </thead>
                                        <tbody id="weekBody">
                                            @php
                                                $dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                                $scheduleForView = isset($sessionSchedules) ? $sessionSchedules->groupBy('day_of_week') : collect();
                                            @endphp
                                            @foreach($dayNames as $idx => $dayName)
                                                @php
                                                    $dayNum = $idx + 1;
                                                    $slot = $scheduleForView->get($dayNum)?->first();
                                                    $active = $slot ? true : ($idx < 5);
                                                    $start = $slot ? substr($slot->start_time, 0, 5) : '09:00';
                                                    $end = $slot ? substr($slot->end_time, 0, 5) : '17:00';
                                                @endphp
                                                <tr>
                                                    <td><strong>{{ $dayName }}</strong></td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input day-active" type="checkbox" data-day="{{ $idx }}" {{ $active ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td><input class="form-control form-control-sm start" type="time" value="{{ $start }}" {{ $active ? '' : 'disabled' }}></td>
                                                    <td><input class="form-control form-control-sm end" type="time" value="{{ $end }}" {{ $active ? '' : 'disabled' }}></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Max sessions per day</label>
                            <input name="max_per_day" type="number" class="form-control" min="1" max="20"
                                value="{{ $maxSessionsPerDay ?? 6 }}">
                            <div class="form-text">Maximum number of bookable sessions per day.</div>
                        </div>

                        <div class="col-12">
                            <hr class="my-3">
                            <h6 class="mb-2 text-muted">Other options</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Timezone</label>
                            <input id="tzInput" name="timezone" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Default session length</label>
                            <select name="default_length" class="form-select">
                                <option value="30">30 min</option>
                                <option value="45">45 min</option>
                                <option value="60" selected>60 min</option>
                                <option value="90">90 min</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Buffer between sessions</label>
                            <select name="buffer" class="form-select">
                                <option value="0">No buffer</option>
                                <option value="10">10 min</option>
                                <option value="15">15 min</option>
                                <option value="30">30 min</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch switch">
                                <input class="form-check-input" type="checkbox" id="instantBooking">
                                <label class="form-check-label" for="instantBooking">Enable instant
                                    booking</label>
                            </div>
                            <div class="help">Students can book available slots without your approval.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lead time (min)</label>
                            <select name="lead" class="form-select">
                                <option value="2">2 hours</option>
                                <option value="6">6 hours</option>
                                <option value="12">12 hours</option>
                                <option value="24" selected>24 hours</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch switch">
                                <input class="form-check-input" type="checkbox" id="vacationMode">
                                <label class="form-check-label" for="vacationMode">Vacation mode</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vacation start</label>
                            <input id="vacStart" type="date" class="form-control" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vacation end</label>
                            <input id="vacEnd" type="date" class="form-control" disabled>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i> Save
                                Availability</button>
                        </div>
                    </form>
                </div>

                <!-- Notifications -->
                <div class="tab-pane fade" id="tab-notifications" role="tabpanel">
                    <form id="formNotify" class="row g-3">
                        <div class="col-12">
                            <h6>Delivery channels</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-check form-switch"><input class="form-check-input"
                                            type="checkbox" name="email" checked> <label
                                            class="form-check-label">Email</label></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch"><input class="form-check-input"
                                            type="checkbox" name="sms"> <label
                                            class="form-check-label">SMS</label></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch"><input class="form-check-input"
                                            type="checkbox" name="push" checked> <label
                                            class="form-check-label">Push</label></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch"><input class="form-check-input"
                                            type="checkbox" name="whatsapp"> <label
                                            class="form-check-label">WhatsApp</label></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <h6 class="mt-2">Events</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"
                                            name="evt_new_booking" checked> <label class="form-check-label">New
                                            booking</label></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"
                                            name="evt_cancel" checked> <label
                                            class="form-check-label">Cancellation</label></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"
                                            name="evt_messages" checked> <label class="form-check-label">New
                                            message</label></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"
                                            name="evt_review" checked> <label class="form-check-label">New
                                            review</label></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"
                                            name="evt_payout" checked> <label class="form-check-label">Payout
                                            events</label></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check"><input class="form-check-input" type="checkbox"
                                            name="evt_low_balance"> <label class="form-check-label">Low
                                            balance/escrow</label></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Digest frequency</label>
                            <select name="digest" class="form-select">
                                <option value="off">Off</option>
                                <option value="daily">Daily</option>
                                <option value="weekly" selected>Weekly</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i> Save
                                Notifications</button>
                        </div>
                    </form>
                </div>

                <!-- Privacy -->
                <div class="tab-pane fade" id="tab-privacy" role="tabpanel">
                    <form id="formPrivacy" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Profile visibility</label>
                            <select name="visibility" class="form-select">
                                <option value="public">Public</option>
                                <option value="students">Students only</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="allow_messages" checked>
                                <label class="form-check-label">Allow students to message</label>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="allow_direct_book" checked>
                                <label class="form-check-label">Allow direct booking</label>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_location">
                                <label class="form-check-label">Show city on profile</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i> Save
                                Privacy</button>
                        </div>
                    </form>
                </div>

                <!-- Verification -->
                <div class="tab-pane fade" id="tab-verification" role="tabpanel">
                    <form id="formVerify" class="row g-3">
                        @csrf
                        @php
                            $v = $verification ?? null;
                            $ep = $user->educatorProfile;
                            $biz = old('business_type', $v->business_type ?? 'individual');

                            $verificationDocs = collect([
                                ['label' => 'CV', 'path' => $ep?->cv_path, 'icon' => 'bi-file-earmark-person'],
                                ['label' => 'Government ID', 'path' => $ep?->govt_id_path, 'icon' => 'bi-person-badge'],
                                ['label' => 'Teaching Credential', 'path' => $ep?->degree_proof_path, 'icon' => 'bi-mortarboard'],
                                ['label' => 'Intro Video', 'path' => $ep?->intro_video_path, 'icon' => 'bi-camera-video'],
                            ])->filter(fn ($doc) => !empty($doc['path']))->map(function ($doc) {
                                $doc['url'] = \App\Models\EducatorProfile::resolveFileUrl($doc['path']);
                                $doc['kind'] = \App\Models\EducatorProfile::fileKind($doc['path']);
                                $doc['name'] = basename(parse_url($doc['path'], PHP_URL_PATH) ?: $doc['path']);
                                return $doc;
                            });
                        @endphp

                        @if ($verificationDocs->isNotEmpty() || (isset($additionalDocuments) && $additionalDocuments->isNotEmpty()))
                            <div class="col-12">
                                <h6 class="mb-2"><i class="bi bi-folder2-open me-1"></i> Uploaded documents</h6>
                                <p class="text-muted small mb-3">Preview your verification files below. Click Preview to open in a modal.</p>
                                <div class="row g-3" id="verificationDocCards">
                                    @foreach ($verificationDocs as $doc)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="doc-preview-card h-100">
                                                <div class="doc-preview-card__icon">
                                                    <i class="bi {{ $doc['icon'] }}"></i>
                                                </div>
                                                <div class="doc-preview-card__body">
                                                    <div class="doc-preview-card__label">{{ $doc['label'] }}</div>
                                                    <div class="doc-preview-card__name" title="{{ $doc['name'] }}">{{ $doc['name'] }}</div>
                                                    <div class="doc-preview-card__actions">
                                                        <button type="button"
                                                            class="btn btn-sm btn-primary btn-doc-preview"
                                                            data-url="{{ $doc['url'] }}"
                                                            data-title="{{ $doc['label'] }}"
                                                            data-kind="{{ $doc['kind'] }}">
                                                            <i class="bi bi-eye me-1"></i> Preview
                                                        </button>
                                                        <a href="{{ $doc['url'] }}" target="_blank" rel="noopener"
                                                            class="btn btn-sm btn-outline-secondary">
                                                            <i class="bi bi-box-arrow-up-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if (isset($additionalDocuments))
                                        @foreach ($additionalDocuments as $doc)
                                            @php
                                                $docKind = \App\Models\EducatorProfile::fileKind($doc->document_path);
                                                $docIcon = match ($docKind) {
                                                    'pdf' => 'bi-file-earmark-pdf',
                                                    'image' => 'bi-file-earmark-image',
                                                    'video' => 'bi-camera-video',
                                                    default => 'bi-file-earmark',
                                                };
                                            @endphp
                                            <div class="col-md-6 col-lg-4" data-additional-doc-id="{{ $doc->id }}">
                                                <div class="doc-preview-card h-100">
                                                    <div class="doc-preview-card__icon">
                                                        <i class="bi {{ $docIcon }}"></i>
                                                    </div>
                                                    <div class="doc-preview-card__body">
                                                        <div class="doc-preview-card__label">Additional Document</div>
                                                        <div class="doc-preview-card__name" title="{{ $doc->document_name }}">{{ $doc->document_name }}</div>
                                                        <div class="doc-preview-card__actions">
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary btn-doc-preview"
                                                                data-url="{{ $doc->document_url }}"
                                                                data-title="{{ $doc->document_name }}"
                                                                data-kind="{{ $docKind }}">
                                                                <i class="bi bi-eye me-1"></i> Preview
                                                            </button>
                                                            <a href="{{ $doc->document_url }}" target="_blank" rel="noopener"
                                                                class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-box-arrow-up-right"></i>
                                                            </a>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-remove-additional-doc"
                                                                data-url="{{ route('educator.verification.document.destroy', $doc) }}">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-12"><hr class="my-1"></div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label">Government ID</label>
                            <div id="govIdDropzone" class="settings-dropzone dropzone" data-type="gov_id"></div>
                            <input type="hidden" name="gov_id_path" id="gov_id_path" value="" />
                            <small class="text-muted">JPG, PNG, or PDF — max 5MB. Uploaded directly to secure storage.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teaching credential (optional)</label>
                            <div id="credentialDropzone" class="settings-dropzone dropzone" data-type="degree_proof"></div>
                            <input type="hidden" name="degree_proof_path" id="degree_proof_path" value="" />
                            <small class="text-muted">JPG, PNG, or PDF — max 5MB.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Intro Video (optional)</label>
                            <div id="introVideoDropzone" class="settings-dropzone dropzone" data-type="intro_video"></div>
                            <input type="hidden" name="intro_video_path" id="intro_video_path" value="" />
                            <small class="text-muted">MP4 or MOV — max 50MB.</small>
                        </div>

                        <div class="col-12">
                            <h6 class="mb-2"><i class="bi bi-paperclip me-1"></i> Additional documents</h6>
                            <p class="text-muted small mb-2">Upload extra proof (certificates, references, etc.). PDF or images, up to 10 files, each max 5MB.</p>
                            <div id="additionalDocsDropzone" class="settings-dropzone dropzone" data-type="additional_document"></div>
                            <div id="additionalDocsNewContainer"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Business type</label>
                            <select name="business_type" class="form-select">
                                <option value="individual" {{ $biz === 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="company" {{ $biz === 'company' ? 'selected' : '' }}>Company</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Agreement</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tos" value="1" id="verifyTos"
                                    {{ old('tos', $v?->tos) ? 'checked' : '' }} required>
                                <label class="form-check-label" for="verifyTos">I agree to the Educator Terms of
                                    Service</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i> Submit
                                Verification</button>
                        </div>
                    </form>
                </div>

                <!-- Connections -->
                <div class="tab-pane fade" id="tab-connections" role="tabpanel">
                    <div class="row g-3">
                        {{-- <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-white h-100">
                                <h6><i class="bi bi-calendar4-week me-1"></i> Google Calendar</h6>
                                <p class="help mb-2">Sync your availability and accept bookings
                                    automatically.</p>
                                <div id="googleConn" class="mb-2"><span class="badge text-bg-warning">Not
                                        connected</span></div>
                                <button id="btnConnectGoogle" class="btn btn-sm btn-outline-primary"><i
                                        class="bi bi-link-45deg me-1"></i> Connect</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-white h-100">
                                <h6><i class="bi bi-camera-video me-1"></i> Zoom</h6>
                                <p class="help mb-2">Auto‑create meeting links for sessions.</p>
                                <div id="zoomConn" class="mb-2"><span class="badge text-bg-warning">Not
                                        connected</span></div>
                                <button id="btnConnectZoom" class="btn btn-sm btn-outline-primary"><i
                                        class="bi bi-link-45deg me-1"></i> Connect</button>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-white h-100">
                                <h6><i class="bi bi-credit-card-2-front me-1"></i> Stripe Connect &amp; Payouts</h6>
                                <p class="help mb-2">Add your IBAN / bank details through Stripe to receive your earnings when students purchase your content.</p>
                                <div id="stripeConn" class="mb-2">
                                    @if($user->canReceivePayouts())
                                        <span class="badge text-bg-success">Connected &amp; payouts enabled</span>
                                    @elseif($user->stripe_connect_id)
                                        <span class="badge text-bg-warning">Setup incomplete</span>
                                    @else
                                        <span class="badge text-bg-warning">Not connected</span>
                                    @endif
                                </div>
                                <a href="{{ route('stripe.connect') }}" class="btn btn-sm {{ $user->canReceivePayouts() ? 'btn-outline-secondary' : 'btn-outline-primary' }}">
                                    <i class="bi bi-link-45deg me-1"></i>
                                    {{ $user->canReceivePayouts() ? 'Manage payout details' : 'Connect & add IBAN' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="tab-pane fade" id="tab-preferences" role="tabpanel">
                    <form id="formPrefs" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Language</label>
                            <select name="lang" class="form-select">
                                <option value="en">English</option>
                                <option value="de">Deutsch</option>
                                <option value="ar">العربية</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Theme</label>
                            <select name="theme" class="form-select">
                                <option value="system">System</option>
                                <option value="light">Light</option>
                                <option value="dark">Dark</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Time format</label>
                            <select name="timefmt" class="form-select">
                                <option value="24">24‑hour</option>
                                <option value="12">12‑hour</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i> Save
                                Preferences</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Modals -->
    <div class="modal fade" id="methodModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-bank me-2"></i>Add Payment Method</h5>
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
                        <input id="pmLabel" class="form-control" placeholder="e.g., USD Bank (IBAN)">
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

    <!-- Document preview modal -->
    <div class="modal fade" id="docPreviewModal" tabindex="-1" aria-labelledby="docPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="docPreviewModalLabel">Document Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="docPreviewBody"></div>
                <div class="modal-footer py-2">
                    <a href="#" id="docPreviewOpenLink" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Open in new tab
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" />
        <style>
            .card {
                border-radius: 16px;
            }

            .section-header {
                background: var(--light-cyan);
                border-radius: 16px 16px 0 0;
                padding: 1.25rem;
                border-bottom: 1px solid rgba(0, 0, 0, .06);
            }

            .section-title {
                color: var(--dark-cyan);
                font-weight: 700;
                margin: 0;
                display: flex;
                gap: .5rem;
                align-items: center;
            }

            .nav-tabs .nav-link {
                border: 0;
                border-bottom: 3px solid transparent;
                color: #495057;
                font-weight: 600;
            }

            .nav-tabs .nav-link.active {
                border-color: var(--primary-cyan);
                color: var(--dark-cyan);
            }

            .avatar {
                width: 84px;
                height: 84px;
                border-radius: 999px;
                object-fit: cover;
                border: 2px solid #fff;
                box-shadow: 0 0 0 4px rgba(0, 0, 0, .06);
            }

            .tz-pill {
                background: #fff;
                border: 1px dashed rgba(0, 0, 0, .15);
                padding: .25rem .6rem;
                border-radius: 999px;
                font-size: .8rem;
                color: #6b7a8c;
            }

            .help {
                color: #6b7a8c;
                font-size: .86rem;
            }

            .switch .form-check-input {
                cursor: pointer
            }

            .req::after {
                content: "*";
                color: #dc3545;
                margin-left: .25rem;
            }

            .danger-zone {
                border: 1px dashed rgba(220, 53, 69, .35);
                border-radius: 16px;
                padding: 1rem;
                background: #fff5f5;
            }

            .schedule-grid {
                border: 1px solid rgba(0, 0, 0, .08);
                border-radius: 12px;
                overflow: hidden;
                background: #fff
            }

            .schedule-grid thead th {
                background: #fafafa;
                font-weight: 600;
                color: #6b7a8c
            }

            .chip {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                background: #fff;
                border: 1px solid rgba(0, 0, 0, .12);
                padding: .25rem .5rem;
                border-radius: 999px;
                font-size: .85rem;
                margin: .15rem
            }

            .chip .x {
                cursor: pointer
            }

            .method-badge {
                background: #fff;
                border: 1px solid rgba(0, 0, 0, .08);
                border-radius: 999px;
                padding: .25rem .6rem;
                font-size: .8rem;
            }

            .doc-preview-card {
                display: flex;
                gap: 0.85rem;
                align-items: flex-start;
                border: 1px solid rgba(0, 0, 0, .08);
                border-radius: 14px;
                padding: 1rem;
                background: #fff;
                box-shadow: 0 2px 8px rgba(0, 74, 87, 0.06);
                transition: box-shadow .2s, border-color .2s;
            }

            .doc-preview-card:hover {
                border-color: rgba(0, 131, 143, .35);
                box-shadow: 0 6px 16px rgba(0, 74, 87, 0.1);
            }

            .doc-preview-card__icon {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                background: var(--light-cyan, #e0f7fa);
                color: var(--dark-cyan, #006b7d);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                flex-shrink: 0;
            }

            .doc-preview-card__body {
                min-width: 0;
                flex: 1;
            }

            .doc-preview-card__label {
                font-weight: 600;
                color: var(--dark-cyan, #006b7d);
                font-size: .9rem;
            }

            .doc-preview-card__name {
                color: #6b7a8c;
                font-size: .82rem;
                margin-top: .15rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .doc-preview-card__actions {
                display: flex;
                flex-wrap: wrap;
                gap: .35rem;
                margin-top: .65rem;
            }

            #docPreviewModal .modal-body {
                background: #0f172a;
                min-height: 280px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #docPreviewModal .modal-body img,
            #docPreviewModal .modal-body iframe {
                max-width: 100%;
                max-height: 75vh;
            }

            #docPreviewModal video-player {
                width: 100%;
                max-height: 75vh;
            }

            .settings-dropzone.dropzone {
                border: 2px dashed #cbd5e1;
                border-radius: 10px;
                background: #f8fafc;
                min-height: 110px;
                padding: 14px;
            }

            .settings-dropzone.dropzone:hover,
            .settings-dropzone.dropzone.dz-drag-hover {
                border-color: var(--primary-cyan, #0891b2);
                background: #f0f9ff;
            }

            .settings-dropzone .dz-message {
                margin: 1.2em 0;
                color: #64748b;
                font-weight: 500;
                font-size: .9rem;
            }
        </style>
    @endpush
    @push('scripts')
        <!-- Bootstrap JS -->

        <script>
            // ===== Utilities =====
            function showToast(message, type = 'info') {
                if (typeof showEducatorToast === 'function') {
                    showEducatorToast(message, type);
                    return;
                }
                Swal.fire({
                    icon: type === 'danger' ? 'error' : type,
                    title: message,
                    timer: type === 'success' ? 2000 : undefined,
                    showConfirmButton: type !== 'success',
                });
            }

            function serializeForm(form) {
                const data = {};
                new FormData(form).forEach((v, k) => {
                    if (data[k] !== undefined) {
                        if (!Array.isArray(data[k])) data[k] = [data[k]];
                        data[k].push(v);
                    } else data[k] = v;
                });
                // switches not included when unchecked → add explicitly
                form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                    if (!form.contains) return;
                    if (!cb.checked) {
                        if (data[cb.name] === undefined) data[cb.name] = false;
                    } else {
                        if (cb.name) data[cb.name] = true;
                    }
                });
                return data;
            }

            // ===== State =====
            let payMethods = [];

            document.addEventListener('DOMContentLoaded', () => {
                const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                document.getElementById('tzInput').value = tz;

                initWeekGrid();
                // wireSecurity();
                // wirePayments();
                // wireAvailability();
                // wireForms();
            });

            // ===== Document preview modal =====
            let docPreviewModalInstance = null;

            function openDocPreview(title, url, kind) {
                const modalEl = document.getElementById('docPreviewModal');
                const body = document.getElementById('docPreviewBody');
                const titleEl = document.getElementById('docPreviewModalLabel');
                const openLink = document.getElementById('docPreviewOpenLink');

                if (!modalEl || !body) return;

                body.innerHTML = '';
                titleEl.textContent = title || 'Document Preview';
                openLink.href = url;

                if (kind === 'video') {
                    body.style.background = '#0f172a';
                    body.innerHTML = `
                        <video-player style="width:100%;max-height:75vh;">
                            <video src="${url}" playsinline controls style="width:100%;"></video>
                        </video-player>`;
                } else if (kind === 'image') {
                    body.style.background = '#f8fafc';
                    body.innerHTML = `<img src="${url}" alt="${title}" class="img-fluid">`;
                } else if (kind === 'pdf') {
                    body.style.background = '#fff';
                    body.innerHTML = `<iframe src="${url}" title="${title}" style="width:100%;height:75vh;border:0;"></iframe>`;
                } else {
                    body.style.background = '#f8fafc';
                    body.innerHTML = `<p class="text-muted p-4 mb-0">Preview not available for this file type.</p>`;
                }

                docPreviewModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                docPreviewModalInstance.show();
            }

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-doc-preview');
                if (!btn) return;
                e.preventDefault();
                openDocPreview(btn.dataset.title, btn.dataset.url, btn.dataset.kind);
            });

            document.getElementById('docPreviewModal')?.addEventListener('hidden.bs.modal', function() {
                document.getElementById('docPreviewBody').innerHTML = '';
            });

            // ===== Availability: weekly grid (session schedule: day + time) =====
            function initWeekGrid() {
                const body = document.getElementById('weekBody');
                if (!body) return;
                body.addEventListener('change', function(e) {
                    if (e.target.classList.contains('day-active')) {
                        const row = e.target.closest('tr');
                        if (row) row.querySelectorAll('input[type="time"]').forEach(inp => inp.disabled = !e.target.checked);
                    }
                });
            }

            // ===== Security wiring =====
            function wireSecurity() {
                const twoFA = document.getElementById('twoFA');
                const method = document.getElementById('twoFAMethod');
                const phone = document.getElementById('twoFAPhone');
                const appHint = document.getElementById('appHint');
                const saveBtn = document.getElementById('btn2FASave');

                twoFA.addEventListener('change', () => {
                    const on = twoFA.checked;
                    method.disabled = !on;
                    phone.disabled = !on || method.value !== 'sms';
                    saveBtn.disabled = !on;
                    appHint.classList.toggle('d-none', !(on && method.value === 'app'));
                });
                method.addEventListener('change', () => {
                    phone.disabled = !twoFA.checked || method.value !== 'sms';
                    appHint.classList.toggle('d-none', !(twoFA.checked && method.value === 'app'));
                });

                document.getElementById('formPassword').addEventListener('submit', async e => {
                    e.preventDefault();
                    const p1 = document.getElementById('newPass').value;
                    const p2 = document.getElementById('newPass2').value;
                    if (p1 !== p2) {
                        showToast('Passwords do not match.', 'danger');
                        return;
                    }
                    try {
                        const res = await fetch('/api/educator/security/password', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(serializeForm(e.target))
                        });
                        if (!res.ok) throw 0;
                        showToast('Password updated.', 'success');
                        e.target.reset();
                    } catch {
                        showToast('Failed to update password (demo).', 'danger');
                    }
                });

                document.getElementById('btn2FASave').addEventListener('click', async () => {
                    try {
                        const payload = {
                            enabled: twoFA.checked,
                            method: method.value,
                            phone: phone.value
                        };
                        const res = await fetch('/api/educator/security/2fa', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });
                        if (!res.ok) throw 0;
                        showToast('2FA settings saved.', 'success');
                    } catch {
                        showToast('Failed to save 2FA (demo).', 'danger');
                    }
                });

                document.getElementById('btnLogoutAll').addEventListener('click', async () => {
                    try {
                        await fetch('/api/educator/security/logout-all', {
                            method: 'POST'
                        });
                        showToast('Logged out of all devices.', 'success');
                    } catch {
                        showToast('Failed (demo).', 'danger');
                    }
                });
                document.getElementById('btnDeleteAcc').addEventListener('click', async () => {
                    if (!confirm('Delete your account? This cannot be undone.')) return;
                    try {
                        await fetch('/api/educator/security/delete', {
                            method: 'DELETE'
                        });
                        showToast('Account deletion requested.', 'success');
                    } catch {
                        showToast('Failed (demo).', 'danger');
                    }
                });
            }

            // ===== Payments wiring =====
            function wirePayments() {
                const wrap = document.getElementById('payMethods');

                function render() {
                    wrap.innerHTML = '';
                    payMethods.forEach(m => {
                        const el = document.createElement('div');
                        el.className = 'method-badge d-flex align-items-center gap-2';
                        el.innerHTML =
                            `<i class='bi bi-${iconFor(m.type)}'></i>${labelFor(m.type)} — <strong>${m.label}</strong> ${m.default?'<span class="badge text-bg-success ms-1">Default</span>':''}
            <button class='btn btn-sm btn-outline-secondary ms-2' onclick='setDefaultMethod("${m.id}")'><i class='bi bi-check2-circle'></i></button>
            <button class='btn btn-sm btn-outline-secondary' onclick='editMethod("${m.id}")'><i class='bi bi-pencil'></i></button>
            <button class='btn btn-sm btn-outline-danger' onclick='removeMethod("${m.id}")'><i class='bi bi-trash'></i></button>`;
                        wrap.appendChild(el);
                    });
                }
                window.renderPayMethods = render;

                // Demo initial
                payMethods = [{
                        id: 'm1',
                        type: 'bank',
                        label: 'Personal USD Bank',
                        default: true
                    },
                    {
                        id: 'm2',
                        type: 'paypal',
                        label: 'PayPal educator@example.com',
                        default: false
                    }
                ];
                render();

                document.getElementById('btnAddMethod').addEventListener('click', () => new bootstrap.Modal('#methodModal')
                    .show());
                document.getElementById('saveMethodBtn').addEventListener('click', () => {
                    const type = document.getElementById('pmType').value;
                    const label = document.getElementById('pmLabel').value.trim();
                    const details = document.getElementById('pmDetails').value.trim();
                    const isDefault = document.getElementById('pmDefault').checked;
                    const id = 'm' + (payMethods.length + 1);
                    payMethods.push({
                        id,
                        type,
                        label,
                        details,
                        default: isDefault
                    });
                    if (isDefault) {
                        payMethods.forEach(m => m.default = (m.id === id));
                    }
                    render();
                    bootstrap.Modal.getInstance(document.getElementById('methodModal')).hide();
                    showToast('Payment method added.', 'success');
                });

                window.setDefaultMethod = (id) => {
                    payMethods.forEach(m => m.default = (m.id === id));
                    render();
                    showToast('Default method updated.', 'success');
                };
                window.editMethod = (id) => {
                    alert('Open edit modal for ' + id + ' (wire to API).');
                };
                window.removeMethod = (id) => {
                    payMethods = payMethods.filter(m => m.id !== id);
                    render();
                    showToast('Payment method removed.', 'success');
                };
            }

            function labelFor(type) {
                return {
                    bank: 'Bank Transfer',
                    paypal: 'PayPal',
                    wise: 'Wise',
                    stripe: 'Stripe Connect'
                } [type] || type;
            }

            function iconFor(type) {
                return {
                    bank: 'bank',
                    paypal: 'paypal',
                    wise: 'currency-exchange',
                    stripe: 'credit-card-2-front'
                } [type] || 'credit-card';
            }

            // ===== Availability wiring =====
            function wireAvailability() {
                const vac = document.getElementById('vacationMode');
                const start = document.getElementById('vacStart');
                const end = document.getElementById('vacEnd');
                vac.addEventListener('change', () => {
                    start.disabled = end.disabled = !vac.checked;
                });
            }

            // ===== Forms submit wiring =====
            function wireForms() {
                document.getElementById('formProfile').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    try {
                        const res = await fetch('/api/educator/profile', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(serializeForm(e.target))
                        });
                        if (!res.ok) throw 0;
                        showToast('Profile saved.', 'success');
                    } catch {
                        showToast('Failed to save (demo).', 'danger');
                    }
                });
                document.getElementById('formPayout').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    try {
                        const res = await fetch('/api/educator/payments', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(serializeForm(e.target))
                        });
                        if (!res.ok) throw 0;
                        showToast('Payment settings saved.', 'success');
                    } catch {
                        showToast('Failed to save (demo).', 'danger');
                    }
                });
                document.getElementById('formAvail').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const grid = [...document.querySelectorAll('#weekBody tr')].map(tr => ({
                        active: tr.querySelector('.day-active').checked,
                        start: tr.querySelector('.start').value,
                        end: tr.querySelector('.end').value,
                    }));
                    const maxPerDay = parseInt(e.target.querySelector('input[name="max_per_day"]').value, 10) || 6;
                    const payload = { grid, max_per_day: maxPerDay };
                    try {
                        const res = await fetch('{{ route("educator.availability.update") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(payload)
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) throw new Error(data.message || 'Failed to save');
                        showToast(data.message || 'Availability saved.', 'success');
                    } catch (err) {
                        showToast(err.message || 'Failed to save availability.', 'danger');
                    }
                });
                document.getElementById('formNotify').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    try {
                        const res = await fetch('/api/educator/notifications', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(serializeForm(e.target))
                        });
                        if (!res.ok) throw 0;
                        showToast('Notification settings saved.', 'success');
                    } catch {
                        showToast('Failed to save (demo).', 'danger');
                    }
                });
                document.getElementById('formPrivacy').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    try {
                        const res = await fetch('/api/educator/privacy', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(serializeForm(e.target))
                        });
                        if (!res.ok) throw 0;
                        showToast('Privacy settings saved.', 'success');
                    } catch {
                        showToast('Failed to save (demo).', 'danger');
                    }
                });
                document.getElementById('formVerify').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    // For file upload you'd use multipart/form-data; here we demo only
                    showToast('Verification submitted (demo).', 'success');
                });
                document.getElementById('formPrefs').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    try {
                        const res = await fetch('/api/educator/preferences', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(serializeForm(e.target))
                        });
                        if (!res.ok) throw 0;
                        showToast('Preferences saved.', 'success');
                    } catch {
                        showToast('Failed to save (demo).', 'danger');
                    }
                });

                document.getElementById('btnSaveAll').addEventListener('click', () => {
                    document.getElementById('formProfile').requestSubmit();
                    document.getElementById('formPayout').requestSubmit();
                    document.getElementById('formAvail').requestSubmit();
                    document.getElementById('formNotify').requestSubmit();
                    document.getElementById('formPrivacy').requestSubmit();
                    document.getElementById('formPrefs').requestSubmit();
                });

                // Avatar preview
                const avatarInput = document.getElementById('avatarInput');
                const avatarPreview = document.getElementById('avatarPreview');
                avatarInput.addEventListener('change', () => {
                    const f = avatarInput.files?.[0];
                    if (!f) return;
                    const url = URL.createObjectURL(f);
                    avatarPreview.src = url;
                });
                document.getElementById('btnRemoveAvatar').addEventListener('click', () => {
                    avatarPreview.src = 'https://i.pravatar.cc/120?u=placeholder';
                });

                // Connections buttons (demo)
                document.getElementById('btnConnectGoogle').addEventListener('click', () => {
                    document.getElementById('googleConn').innerHTML =
                        '<span class="badge text-bg-success">Connected</span>';
                    showToast('Google connected (demo).', 'success');
                });
                document.getElementById('btnConnectZoom').addEventListener('click', () => {
                    document.getElementById('zoomConn').innerHTML =
                        '<span class="badge text-bg-success">Connected</span>';
                    showToast('Zoom connected (demo).', 'success');
                });

                // When opening settings, optionally focus the Connections tab via hash.
                if (window.location.hash === '#tab-connections') {
                    const connTabBtn = document.getElementById('connections-tab');
                    if (connTabBtn && window.bootstrap) {
                        new bootstrap.Tab(connTabBtn).show();
                    }
                }
            }
        </script>


        <script>
            $("#formProfile").on("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                // attach avatar manually
                if ($("#avatarInput")[0].files.length > 0) {
                    formData.append('avatar', $("#avatarInput")[0].files[0]);
                }

                $.ajax({
                    url: "{{ route('educator.profile.update') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    beforeSend: function() {
                        Swal.fire({
                            title: 'Saving...',
                            text: 'Updating your profile',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },

                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },

                    error: function(xhr) {
                        let msg = 'Something went wrong';

                        if (xhr.status === 422) {
                            msg = Object.values(xhr.responseJSON.errors)[0][0];
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });

            $("#formPassword").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "/educator/settings/security",
                    method: "POST",
                    data: $(this).serialize(),

                    success: function(res) {
                        showEducatorToast("Password updated!");
                    },
                    error: function(err) {
                        showEducatorToast("Incorrect current password or invalid data.", 'error');
                    }
                });
            });

            $("#btn2FASave").on("click", function() {

                let data = {
                    enabled: $("#twoFA").is(":checked"),
                    method: $("#twoFAMethod").val(),
                    phone: $("#twoFAPhone").val(),
                    _token: $('meta[name="csrf-token"]').attr("content")
                };

                $.ajax({
                    url: "/educator/settings/security/2fa",
                    method: "POST",
                    data,

                    success: function(res) {
                        showEducatorToast("2FA saved.");
                    },
                    error: function(err) {
                        showEducatorToast("Error saving 2FA.", 'error');
                    }
                });
            });
            $("#btn2FASave").on("click", function() {

                let data = {
                    enabled: $("#twoFA").is(":checked"),
                    method: $("#twoFAMethod").val(),
                    phone: $("#twoFAPhone").val(),
                    _token: $('meta[name="csrf-token"]').attr("content")
                };

                $.ajax({
                    url: "/educator/settings/security/2fa",
                    method: "POST",
                    data,

                    success: function(res) {
                        showEducatorToast("2FA saved.");
                    },
                    error: function(err) {
                        showEducatorToast("Error saving 2FA.", 'error');
                    }
                });
            });
            $("#saveMethodBtn").on("click", function() {

                let data = {
                    type: $("#pmType").val(),
                    label: $("#pmLabel").val(),
                    details: $("#pmDetails").val(),
                    is_default: $("#pmDefault").is(":checked"),
                    _token: $('meta[name="csrf-token"]').attr("content")
                };

                $.ajax({
                    url: "/educator/settings/payment-methods",
                    method: "POST",
                    data,

                    success: function(res) {
                        $("#methodModal").modal("hide");
                        showEducatorToast("Payment method added.");
                    },
                    error: function(err) {
                        showEducatorToast("Error adding payment method.", 'error');
                    }
                });
            });
            // formAvail is handled in wireForms() with fetch (sends grid + max_per_day to educator.availability.update)
            $("#formNotify").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "/educator/settings/notifications",
                    method: "POST",
                    data: $(this).serialize(),

                    success: function(res) {
                        showEducatorToast("Notification settings updated.");
                    },
                    error: function(err) {
                        showEducatorToast("Failed to update notification settings.", 'error');
                    }
                });
            });
            $("#formPrivacy").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "/educator/settings/privacy",
                    method: "POST",
                    data: $(this).serialize(),

                    success: function(res) {
                        showEducatorToast("Privacy updated.");
                    },
                    error: function(err) {
                        showEducatorToast("Error updating privacy.", 'error');
                    }
                });
            });
            $("#formVerify").on("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('educator.verification.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: res.message || 'Verification submitted.',
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(function() {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        var msg = "Error submitting verification.";
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            var first = Object.values(xhr.responseJSON.errors)[0];
                            msg = Array.isArray(first) ? first[0] : first;
                        }
                        showEducatorToast(msg, 'error');
                    }
                });
            });

            $(document).on("click", ".btn-remove-additional-doc", function() {
                if (!confirm("Remove this document?")) return;
                var url = $(this).data("url");
                var $card = $(this).closest('[data-additional-doc-id]');
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        _method: "DELETE"
                    },
                    success: function(res) {
                        showEducatorToast(res.message || "Document removed.");
                        $card.remove();
                    },
                    error: function() {
                        showEducatorToast("Could not remove document.", 'error');
                    }
                });
            });
            $("#formPrefs").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "/educator/settings/preferences",
                    method: "POST",
                    data: $(this).serialize(),

                    success: function(res) {
                        showEducatorToast("Preferences saved.");
                    },
                    error: function(err) {
                        showEducatorToast("Error saving preferences.", 'error');
                    }
                });
            });
            $("#btnDeleteAcc").on("click", function() {

                if (!confirm("Are you sure? This cannot be undone.")) return;

                $.ajax({
                    url: "/educator/settings/security/delete-account",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content")
                    },

                    success: function(res) {
                        showEducatorToast("Account deleted.");
                        window.location.href = "/";
                    },
                    error: function(err) {
                        showEducatorToast("Could not delete account.", 'error');
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {

                const defaultAvatar = "https://i.pravatar.cc/120?img=10";

                // Preview selected image
                $("#avatarInput").on("change", function() {
                    const file = this.files[0];

                    if (!file) return;

                    // Validate image type
                    if (!file.type.startsWith("image/")) {
                        Swal.fire({
                            icon: "error",
                            title: "Invalid file",
                            text: "Please select an image file"
                        });
                        this.value = "";
                        return;
                    }

                    // Validate size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: "error",
                            title: "File too large",
                            text: "Image must be less than 2MB"
                        });
                        this.value = "";
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $("#avatarPreview").attr("src", e.target.result);
                    };
                    reader.readAsDataURL(file);
                });

                // Remove avatar preview
                $("#btnRemoveAvatar").on("click", function() {

                    Swal.fire({
                        title: "Remove photo?",
                        text: "Your current avatar will be removed",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, remove",
                        cancelButtonText: "Cancel"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#avatarPreview").attr("src", defaultAvatar);
                            $("#avatarInput").val("");

                            // Optional: mark avatar for removal on backend
                            $("<input>")
                                .attr({
                                    type: "hidden",
                                    name: "remove_avatar",
                                    value: 1
                                })
                                .appendTo("#formProfile");
                        }
                    });
                });

            });
        </script>

        <script type="module" src="https://cdn.jsdelivr.net/npm/@videojs/html/cdn/video-minimal.js"></script>
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <script>
            Dropzone.autoDiscover = false;

            (function() {
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                const uploadUrl = "{{ route('educator.verification.upload') }}";
                const deleteUrl = "{{ route('educator.verification.upload.delete') }}";

                const acceptedByType = {
                    gov_id: 'image/jpeg,image/png,image/gif,image/webp,application/pdf',
                    degree_proof: 'image/jpeg,image/png,image/gif,image/webp,application/pdf',
                    additional_document: 'image/jpeg,image/png,image/gif,image/webp,application/pdf',
                    intro_video: 'video/mp4,video/quicktime',
                };

                function deleteUploadedFile(path) {
                    if (!path) return;
                    $.ajax({
                        url: deleteUrl,
                        method: 'POST',
                        data: JSON.stringify({ path }),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-HTTP-Method-Override': 'DELETE',
                        },
                    });
                }

                function initVerifySingle(elId, type, hiddenId, maxMb) {
                    const el = document.getElementById(elId);
                    if (!el) return;
                    const hidden = document.getElementById(hiddenId);

                    new Dropzone(el, {
                        url: uploadUrl,
                        method: 'post',
                        paramName: 'file',
                        maxFiles: 1,
                        maxFilesize: maxMb,
                        acceptedFiles: acceptedByType[type],
                        addRemoveLinks: true,
                        dictDefaultMessage: 'Drop file here or click to upload',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        params: { type },
                        init: function() {
                            const dz = this;
                            dz.on('addedfile', function() {
                                if (dz.files.length > 1) dz.removeFile(dz.files[0]);
                            });
                            dz.on('success', function(file, response) {
                                file._s3Path = response.path;
                                if (hidden) hidden.value = response.path;
                            });
                            dz.on('removedfile', function(file) {
                                deleteUploadedFile(file._s3Path);
                                if (hidden) hidden.value = '';
                            });
                            dz.on('error', function(file, message) {
                                const msg = typeof message === 'string' ? message : (message.message || 'Upload failed');
                                if (file.previewElement) {
                                    file.previewElement.querySelectorAll('[data-dz-errormessage]')
                                        .forEach(n => n.textContent = msg);
                                }
                            });
                        },
                    });
                }

                let additionalDocIdx = 0;

                function appendAdditionalDocFields(container, response) {
                    const idx = additionalDocIdx++;
                    const wrapper = document.createElement('div');
                    wrapper.dataset.s3Path = response.path;

                    const fields = {
                        path: response.path,
                        name: response.original_name,
                        type: response.mime_type || '',
                        size: response.size || '',
                    };

                    Object.entries(fields).forEach(([key, value]) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `additional_documents_new[${idx}][${key}]`;
                        input.value = value;
                        wrapper.appendChild(input);
                    });

                    container.appendChild(wrapper);
                    return wrapper;
                }

                function initVerifyMultiple(elId, type, containerId, maxFiles, maxMb) {
                    const el = document.getElementById(elId);
                    if (!el) return;
                    const container = document.getElementById(containerId);

                    new Dropzone(el, {
                        url: uploadUrl,
                        method: 'post',
                        paramName: 'file',
                        maxFiles: maxFiles,
                        maxFilesize: maxMb,
                        acceptedFiles: acceptedByType[type],
                        addRemoveLinks: true,
                        dictDefaultMessage: 'Drop files here or click to upload (up to ' + maxFiles + ')',
                        dictMaxFilesExceeded: 'Maximum ' + maxFiles + ' files allowed.',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        params: { type },
                        init: function() {
                            const dz = this;
                            dz.on('success', function(file, response) {
                                file._s3Path = response.path;
                                file._fieldWrapper = appendAdditionalDocFields(container, response);
                            });
                            dz.on('removedfile', function(file) {
                                deleteUploadedFile(file._s3Path);
                                file._fieldWrapper?.remove();
                            });
                            dz.on('error', function(file, message) {
                                const msg = typeof message === 'string' ? message : (message.message || 'Upload failed');
                                if (file.previewElement) {
                                    file.previewElement.querySelectorAll('[data-dz-errormessage]')
                                        .forEach(n => n.textContent = msg);
                                }
                            });
                        },
                    });
                }

                $(document).ready(function() {
                    initVerifySingle('govIdDropzone', 'gov_id', 'gov_id_path', 5);
                    initVerifySingle('credentialDropzone', 'degree_proof', 'degree_proof_path', 5);
                    initVerifySingle('introVideoDropzone', 'intro_video', 'intro_video_path', 50);
                    initVerifyMultiple('additionalDocsDropzone', 'additional_document', 'additionalDocsNewContainer', 10, 5);
                });
            })();
        </script>
    @endpush
</x-educator-layout>
