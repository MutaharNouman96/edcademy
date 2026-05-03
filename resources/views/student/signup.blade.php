<x-guest-layout>
    <style>
        .glass-landing--signup-hero {
            position: relative;
            min-height: 100vh;
            padding: 120px 0 60px;
            background:
                radial-gradient(1200px 600px at 10% -10%, rgba(8, 145, 178, 0.18), transparent 60%),
                radial-gradient(900px 500px at 110% 10%, rgba(245, 158, 11, 0.12), transparent 60%),
                linear-gradient(135deg, #f0fdff 0%, #e0f2fe 50%, #ecfeff 100%);
            overflow: hidden;
        }

        .glass-landing--signup-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(2px 2px at 20% 30%, rgba(8, 145, 178, 0.25) 50%, transparent 51%),
                radial-gradient(2px 2px at 80% 60%, rgba(8, 145, 178, 0.18) 50%, transparent 51%),
                radial-gradient(1.5px 1.5px at 50% 80%, rgba(245, 158, 11, 0.2) 50%, transparent 51%);
            background-size: 220px 220px, 280px 280px, 200px 200px;
            opacity: 0.6;
            pointer-events: none;
        }

        .glass-landing--signup-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px;
            padding: 48px;
            box-shadow:
                0 20px 60px rgba(8, 145, 178, 0.12),
                0 4px 20px rgba(0, 0, 0, 0.04);
            max-width: 760px;
            margin: auto;
            position: relative;
            z-index: 1;
        }

        .glass-landing--signup-logo {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            color: #fff;
            font-size: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 10px 25px rgba(8, 145, 178, 0.35);
        }

        .glass-landing--signup-title {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .glass-landing--signup-subtitle {
            color: #64748b;
            font-size: 0.975rem;
        }

        /* Stepper */
        .glass-landing--stepper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin: 32px 0 28px;
        }

        .glass-landing--stepper-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .glass-landing--stepper-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            left: calc(50% + 22px);
            right: calc(-50% + 22px);
            height: 3px;
            background: #e2e8f0;
            border-radius: 2px;
            z-index: 0;
            transition: background 0.3s ease;
        }

        .glass-landing--stepper-item.completed:not(:last-child)::after {
            background: linear-gradient(90deg, #0891b2, #06b6d4);
        }

        .glass-landing--stepper-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #e2e8f0;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .glass-landing--stepper-item.active .glass-landing--stepper-circle {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 6px 16px rgba(8, 145, 178, 0.35);
            transform: scale(1.05);
        }

        .glass-landing--stepper-item.completed .glass-landing--stepper-circle {
            background: #0891b2;
            border-color: #0891b2;
            color: #fff;
        }

        .glass-landing--stepper-label {
            margin-top: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-align: center;
        }

        .glass-landing--stepper-item.active .glass-landing--stepper-label,
        .glass-landing--stepper-item.completed .glass-landing--stepper-label {
            color: #0891b2;
        }

        /* Form */
        .glass-landing--step-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .glass-landing--step-desc {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 22px;
        }

        .glass-landing--form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.875rem;
            margin-bottom: 6px;
            display: block;
        }

        .glass-landing--input-group {
            position: relative;
        }

        .glass-landing--input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            font-size: 1.05rem;
            transition: color 0.2s;
        }

        .glass-landing--form-input,
        .glass-landing--form-select,
        .glass-landing--form-textarea {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px 14px 46px;
            font-size: 0.975rem;
            color: #0f172a;
            background: #fff;
            transition: all 0.2s ease;
        }

        .glass-landing--form-input::placeholder {
            color: #94a3b8;
        }

        .glass-landing--form-input:focus,
        .glass-landing--form-select:focus,
        .glass-landing--form-textarea:focus {
            outline: none;
            border-color: #0891b2;
            box-shadow: 0 0 0 4px rgba(8, 145, 178, 0.12);
        }

        .glass-landing--form-input:focus + .glass-landing--input-icon,
        .glass-landing--input-group:focus-within .glass-landing--input-icon {
            color: #0891b2;
        }

        .glass-landing--form-input.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .glass-landing--password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: 0;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .glass-landing--password-toggle:hover {
            color: #0891b2;
        }

        .glass-landing--field-error {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .glass-landing--alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        /* Radio cards */
        .glass-landing--radio-card {
            display: block;
            cursor: pointer;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 18px 18px 56px;
            position: relative;
            transition: all 0.2s ease;
            background: #fff;
        }

        .glass-landing--radio-card:hover {
            border-color: #67e8f9;
            background: #f0fdff;
        }

        .glass-landing--radio-card input[type="radio"] {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            accent-color: #0891b2;
        }

        .glass-landing--radio-card.selected {
            border-color: #0891b2;
            background: linear-gradient(135deg, rgba(8, 145, 178, 0.06), rgba(6, 182, 212, 0.06));
            box-shadow: 0 6px 20px rgba(8, 145, 178, 0.12);
        }

        .glass-landing--radio-card-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .glass-landing--radio-card-desc {
            color: #64748b;
            font-size: 0.85rem;
        }

        /* Review */
        .glass-landing--review-list {
            list-style: none;
            padding: 0;
            margin: 0;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .glass-landing--review-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }

        .glass-landing--review-list li:last-child {
            border-bottom: 0;
        }

        .glass-landing--review-list li strong {
            color: #475569;
            font-weight: 600;
        }

        .glass-landing--review-list li span {
            color: #0f172a;
            font-weight: 600;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }

        /* Buttons */
        .glass-landing--btn-next,
        .glass-landing--btn-prev {
            border-radius: 12px;
            padding: 12px 26px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .glass-landing--btn-next {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            border: 0;
            color: #fff;
            box-shadow: 0 8px 18px rgba(8, 145, 178, 0.3);
        }

        .glass-landing--btn-next:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px rgba(8, 145, 178, 0.4);
            color: #fff;
        }

        .glass-landing--btn-prev {
            background: #fff;
            border: 2px solid #e2e8f0;
            color: #475569;
        }

        .glass-landing--btn-prev:hover:not(:disabled) {
            border-color: #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
        }

        .glass-landing--btn-prev:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .glass-landing--login-link {
            margin-top: 24px;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
        }

        .glass-landing--login-link a {
            color: #0891b2;
            font-weight: 700;
            text-decoration: none;
        }

        .glass-landing--login-link a:hover {
            text-decoration: underline;
        }

        /* Step transitions */
        .glass-landing--step {
            display: none;
            animation: glassFadeIn 0.35s ease;
        }

        .glass-landing--step.active {
            display: block;
        }

        @keyframes glassFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            .glass-landing--signup-card {
                padding: 28px 22px;
                border-radius: 18px;
            }
            .glass-landing--signup-title {
                font-size: 1.5rem;
            }
            .glass-landing--stepper-label {
                font-size: 0.7rem;
            }
            .glass-landing--form-input {
                padding: 12px 14px 12px 42px;
                font-size: 0.95rem;
            }
        }
    </style>

    @php
        $hasStep2Error = $errors->has('signupType')
            || $errors->has('guardian_name')
            || $errors->has('guardian_relation')
            || $errors->has('guardian_contact');
        $hasStep1Error = $errors->has('first_name')
            || $errors->has('last_name')
            || $errors->has('email')
            || $errors->has('password');
        $initialStep = $hasStep2Error ? 1 : ($hasStep1Error ? 0 : 0);
        $oldSignupType = old('signupType');
    @endphp

    <section class="glass-landing--signup-hero">
        <div class="container">
            <div class="glass-landing--signup-card">
                <div class="text-center">
                    <div class="glass-landing--signup-logo">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <h1 class="glass-landing--signup-title">Create your student account</h1>
                    <p class="glass-landing--signup-subtitle">Join Ed-Cademy in just a few quick steps.</p>
                </div>

                <!-- Stepper -->
                <div class="glass-landing--stepper" id="stepper">
                    <div class="glass-landing--stepper-item" data-step="0">
                        <div class="glass-landing--stepper-circle">1</div>
                        <div class="glass-landing--stepper-label">Account</div>
                    </div>
                    <div class="glass-landing--stepper-item" data-step="1">
                        <div class="glass-landing--stepper-circle">2</div>
                        <div class="glass-landing--stepper-label">Signup Type</div>
                    </div>
                    <div class="glass-landing--stepper-item" data-step="2">
                        <div class="glass-landing--stepper-circle">3</div>
                        <div class="glass-landing--stepper-label">Review</div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="glass-landing--alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Please review the highlighted fields and try again.
                    </div>
                @endif

                <form id="signupForm" method="POST" action="{{ route('student.signup.store') }}" novalidate>
                    @csrf

                    <!-- Step 1: Account Info -->
                    <div class="glass-landing--step {{ $initialStep === 0 ? 'active' : '' }}">
                        <div class="glass-landing--step-title">Account information</div>
                        <p class="glass-landing--step-desc">Tell us a bit about yourself to get started.</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="studentFirstName" class="glass-landing--form-label">First name</label>
                                <div class="glass-landing--input-group">
                                    <i class="bi bi-person glass-landing--input-icon"></i>
                                    <input
                                        type="text"
                                        name="first_name"
                                        id="studentFirstName"
                                        class="glass-landing--form-input @error('first_name') is-invalid @enderror"
                                        placeholder="Jane"
                                        value="{{ old('first_name') }}"
                                        autocomplete="given-name"
                                        required
                                    />
                                </div>
                                @error('first_name')
                                    <div class="glass-landing--field-error"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="studentLastName" class="glass-landing--form-label">Last name</label>
                                <div class="glass-landing--input-group">
                                    <i class="bi bi-person glass-landing--input-icon"></i>
                                    <input
                                        type="text"
                                        name="last_name"
                                        id="studentLastName"
                                        class="glass-landing--form-input @error('last_name') is-invalid @enderror"
                                        placeholder="Doe"
                                        value="{{ old('last_name') }}"
                                        autocomplete="family-name"
                                        required
                                    />
                                </div>
                                @error('last_name')
                                    <div class="glass-landing--field-error"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="studentEmail" class="glass-landing--form-label">Email address</label>
                                <div class="glass-landing--input-group">
                                    <i class="bi bi-envelope glass-landing--input-icon"></i>
                                    <input
                                        type="email"
                                        name="email"
                                        id="studentEmail"
                                        class="glass-landing--form-input @error('email') is-invalid @enderror"
                                        placeholder="you@example.com"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        required
                                    />
                                </div>
                                @error('email')
                                    <div class="glass-landing--field-error"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="studentPassword" class="glass-landing--form-label">Password</label>
                                <div class="glass-landing--input-group">
                                    <i class="bi bi-lock glass-landing--input-icon"></i>
                                    <input
                                        type="password"
                                        name="password"
                                        id="studentPassword"
                                        class="glass-landing--form-input @error('password') is-invalid @enderror"
                                        placeholder="At least 8 characters"
                                        autocomplete="new-password"
                                        required
                                    />
                                    <button type="button" class="glass-landing--password-toggle" data-target="studentPassword" aria-label="Show password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="glass-landing--field-error"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="studentPasswordConfirmation" class="glass-landing--form-label">Confirm password</label>
                                <div class="glass-landing--input-group">
                                    <i class="bi bi-shield-lock glass-landing--input-icon"></i>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="studentPasswordConfirmation"
                                        class="glass-landing--form-input"
                                        placeholder="Repeat password"
                                        autocomplete="new-password"
                                        required
                                    />
                                    <button type="button" class="glass-landing--password-toggle" data-target="studentPasswordConfirmation" aria-label="Show password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Signup Type -->
                    <div class="glass-landing--step {{ $initialStep === 1 ? 'active' : '' }}">
                        <div class="glass-landing--step-title">Who is this account for?</div>
                        <p class="glass-landing--step-desc">Choose the option that best describes you.</p>

                        <div class="d-flex flex-column gap-3">
                            <label class="glass-landing--radio-card {{ $oldSignupType === 'self' ? 'selected' : '' }}" data-radio="self">
                                <input
                                    type="radio"
                                    name="signupType"
                                    id="forMyself"
                                    value="self"
                                    {{ $oldSignupType === 'self' ? 'checked' : '' }}
                                />
                                <div class="glass-landing--radio-card-title">
                                    <i class="bi bi-person-circle me-1 text-primary"></i> I’m signing up for myself
                                </div>
                                <div class="glass-landing--radio-card-desc">I am the learner using this account.</div>
                            </label>

                            <label class="glass-landing--radio-card {{ $oldSignupType === 'kid' ? 'selected' : '' }}" data-radio="kid">
                                <input
                                    type="radio"
                                    name="signupType"
                                    id="forKid"
                                    value="kid"
                                    {{ $oldSignupType === 'kid' ? 'checked' : '' }}
                                />
                                <div class="glass-landing--radio-card-title">
                                    <i class="bi bi-people-fill me-1 text-primary"></i> I’m signing up my kid
                                </div>
                                <div class="glass-landing--radio-card-desc">I’m a parent or guardian managing this account.</div>
                            </label>
                        </div>
                        @error('signupType')
                            <div class="glass-landing--field-error mt-2"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                        @enderror

                        <!-- Guardian Details -->
                        <div id="guardianDetails" class="mt-4" style="display: {{ $oldSignupType === 'kid' ? 'block' : 'none' }};">
                            <div class="glass-landing--step-title" style="font-size: 1.05rem;">Guardian information</div>
                            <p class="glass-landing--step-desc">We use this to verify the account.</p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="guardianName" class="glass-landing--form-label">Guardian name</label>
                                    <div class="glass-landing--input-group">
                                        <i class="bi bi-person-badge glass-landing--input-icon"></i>
                                        <input
                                            type="text"
                                            class="glass-landing--form-input @error('guardian_name') is-invalid @enderror"
                                            id="guardianName"
                                            name="guardian_name"
                                            placeholder="Full name"
                                            value="{{ old('guardian_name') }}"
                                        />
                                    </div>
                                    @error('guardian_name')
                                        <div class="glass-landing--field-error"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="guardianRelation" class="glass-landing--form-label">Relation</label>
                                    <div class="glass-landing--input-group">
                                        <i class="bi bi-people glass-landing--input-icon"></i>
                                        <input
                                            type="text"
                                            class="glass-landing--form-input @error('guardian_relation') is-invalid @enderror"
                                            id="guardianRelation"
                                            name="guardian_relation"
                                            placeholder="e.g. Mother, Father"
                                            value="{{ old('guardian_relation') }}"
                                        />
                                    </div>
                                    @error('guardian_relation')
                                        <div class="glass-landing--field-error"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="guardianContact" class="glass-landing--form-label">Contact number</label>
                                    <div class="glass-landing--input-group">
                                        <i class="bi bi-telephone glass-landing--input-icon"></i>
                                        <input
                                            type="tel"
                                            name="guardian_contact"
                                            class="glass-landing--form-input @error('guardian_contact') is-invalid @enderror"
                                            id="guardianContact"
                                            placeholder="+1 234 567 8900"
                                            value="{{ old('guardian_contact') }}"
                                        />
                                    </div>
                                    @error('guardian_contact')
                                        <div class="glass-landing--field-error"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Review -->
                    <div class="glass-landing--step {{ $initialStep === 2 ? 'active' : '' }}">
                        <div class="glass-landing--step-title">Review &amp; submit</div>
                        <p class="glass-landing--step-desc">Please confirm your details before completing signup.</p>

                        <ul class="glass-landing--review-list">
                            <li>
                                <strong>Name</strong>
                                <span id="reviewName">—</span>
                            </li>
                            <li>
                                <strong>Email</strong>
                                <span id="reviewEmail">—</span>
                            </li>
                            <li>
                                <strong>Signup type</strong>
                                <span id="reviewType">—</span>
                            </li>
                            <li class="guardian-review d-none">
                                <strong>Guardian</strong>
                                <span id="reviewGuardian">—</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="button" class="glass-landing--btn-prev" id="prevBtn" disabled>
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="glass-landing--btn-next" id="nextBtn">
                            Next <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>

                <div class="glass-landing--login-link">
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const steps = document.querySelectorAll('.glass-landing--step');
            const stepperItems = document.querySelectorAll('.glass-landing--stepper-item');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const signupTypeInputs = document.querySelectorAll('input[name="signupType"]');
            const guardianDetails = document.getElementById('guardianDetails');
            const radioCards = document.querySelectorAll('.glass-landing--radio-card');
            const totalSteps = steps.length;
            let currentStep = {{ $initialStep }};

            function updateGuardianRequired() {
                const isKid = document.getElementById('forKid').checked;
                guardianDetails.style.display = isKid ? 'block' : 'none';
                ['guardianName', 'guardianRelation', 'guardianContact'].forEach((id) => {
                    const el = document.getElementById(id);
                    if (!el) return;
                    if (isKid) {
                        el.setAttribute('required', 'required');
                    } else {
                        el.removeAttribute('required');
                    }
                });
            }

            function paintStepper(step) {
                stepperItems.forEach((item, i) => {
                    item.classList.toggle('active', i === step);
                    item.classList.toggle('completed', i < step);
                });
            }

            function showStep(step) {
                steps.forEach((s, i) => s.classList.toggle('active', i === step));
                paintStepper(step);
                prevBtn.disabled = step === 0;
                nextBtn.innerHTML = step === totalSteps - 1
                    ? '<i class="bi bi-check2-circle"></i> Complete signup'
                    : 'Next <i class="bi bi-arrow-right"></i>';

                if (step === totalSteps - 1) {
                    fillReview();
                }
            }

            function fillReview() {
                const first = document.getElementById('studentFirstName').value.trim();
                const last = document.getElementById('studentLastName').value.trim();
                const email = document.getElementById('studentEmail').value.trim();
                const checked = document.querySelector('input[name="signupType"]:checked');
                const type = checked ? checked.value : null;

                document.getElementById('reviewName').textContent = (first + ' ' + last).trim() || '—';
                document.getElementById('reviewEmail').textContent = email || '—';
                document.getElementById('reviewType').textContent = type === 'self'
                    ? 'For myself'
                    : (type === 'kid' ? 'For my kid' : '—');

                const guardianRow = document.querySelector('.guardian-review');
                if (type === 'kid') {
                    guardianRow.classList.remove('d-none');
                    document.getElementById('reviewGuardian').textContent =
                        document.getElementById('guardianName').value + ' (' +
                        document.getElementById('guardianRelation').value + ', ' +
                        document.getElementById('guardianContact').value + ')';
                } else {
                    guardianRow.classList.add('d-none');
                }
            }

            function validateCurrentStep() {
                const currentEl = steps[currentStep];
                const requiredFields = currentEl.querySelectorAll('[required]');
                let valid = true;
                requiredFields.forEach((field) => {
                    if (!field.checkValidity()) {
                        valid = false;
                        field.classList.add('is-invalid');
                        if (valid === false) field.reportValidity();
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (currentStep === 0) {
                    const pwd = document.getElementById('studentPassword');
                    const confirm = document.getElementById('studentPasswordConfirmation');
                    if (pwd.value && confirm.value && pwd.value !== confirm.value) {
                        confirm.setCustomValidity('Passwords do not match.');
                        confirm.reportValidity();
                        valid = false;
                    } else {
                        confirm.setCustomValidity('');
                    }
                }

                if (currentStep === 1) {
                    const checked = document.querySelector('input[name="signupType"]:checked');
                    if (!checked) {
                        valid = false;
                        alert('Please choose a signup type.');
                    }
                }

                return valid;
            }

            signupTypeInputs.forEach((input) => {
                input.addEventListener('change', () => {
                    radioCards.forEach((card) => {
                        const wantValue = card.getAttribute('data-radio');
                        const isSelected = document.querySelector('input[name="signupType"]:checked')?.value === wantValue;
                        card.classList.toggle('selected', isSelected);
                    });
                    updateGuardianRequired();
                });
            });

            nextBtn.addEventListener('click', () => {
                if (!validateCurrentStep()) return;

                if (currentStep < totalSteps - 1) {
                    currentStep++;
                    showStep(currentStep);
                } else {
                    document.getElementById('signupForm').submit();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            document.querySelectorAll('.glass-landing--password-toggle').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const target = document.getElementById(btn.getAttribute('data-target'));
                    if (!target) return;
                    const icon = btn.querySelector('i');
                    if (target.type === 'password') {
                        target.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        target.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });

            updateGuardianRequired();
            showStep(currentStep);
        })();
    </script>
</x-guest-layout>
