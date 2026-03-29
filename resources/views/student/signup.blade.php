<x-guest-layout>
    <style>
        body {
            background: #f6f8f9;
        }
        .glass-landing--signup-hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
        .glass-landing--signup-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .glass-landing--signup-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            margin: auto;
            position: relative;
            z-index: 1;
        }
        .glass-landing--signup-logo {
            color: #0891b2;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .glass-landing--signup-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }
        .glass-landing--form-label {
            font-weight: 600;
            color: #374151;
        }
        .glass-landing--form-input,
        .glass-landing--form-select,
        .glass-landing--form-textarea {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .glass-landing--form-input:focus,
        .glass-landing--form-select:focus,
        .glass-landing--form-textarea:focus {
            border-color: #0891b2;
            box-shadow: 0 0 0 2px #e0f7fa;
        }
        .glass-landing--form-input.is-invalid,
        .glass-landing--form-select.is-invalid,
        .glass-landing--form-textarea.is-invalid {
            border-color: #ef4444;
            background: #fff1f2;
        }
        .glass-landing--form-group {
            margin-bottom: 1.5rem;
        }
        .glass-landing--progress {
            background: #e0f7fa;
            border-radius: 4px;
            height: 6px;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .glass-landing--progress-bar {
            background: #0891b2;
            height: 100%;
            transition: width 0.3s;
        }
        .glass-landing--step {
            display: none;
        }
        .glass-landing--step.active {
            display: block;
        }
        @media (max-width: 600px) {
            .glass-landing--signup-card {
                padding: 20px;
            }
            .glass-landing--signup-title {
                font-size: 1.3rem;
            }
        }
    </style>

    <div class="container py-5">
        <div class="glass-landing--signup-card mt-4 mb-4">
            <div class="mb-4">
                <div class="glass-landing--signup-logo text-center mb-3">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div class="text-center">
                    <div class="glass-landing--signup-title">Student Signup</div>
                </div>
            </div>
            <div>
                <!-- Progress -->
                <div class="glass-landing--progress" role="progressbar">
                    <div class="glass-landing--progress-bar" id="progressBar" style="width: 33%;"></div>
                </div>
                <small class="text-muted d-block mb-4 text-center" id="stepLabel">Step 1 of 3: Account Information</small>

                <form id="signupForm" method="POST" action="{{ route('student.signup.store') }}">
                    @csrf

                    <!-- Step 1 -->
                    <div class="glass-landing--step active">
                        <h5 class="fw-semibold mb-3">Account Information</h5>
                        <div class="glass-landing--form-group form-floating mb-3">
                            <input
                                type="text"
                                name="first_name"
                                class="glass-landing--form-input form-control"
                                id="studentFirstName"
                                placeholder="First Name"
                                required
                            />
                            <label for="studentFirstName" class="glass-landing--form-label">First Name *</label>
                            @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="glass-landing--form-group form-floating mb-3">
                            <input
                                type="text"
                                name="last_name"
                                class="glass-landing--form-input form-control"
                                id="studentLastName"
                                placeholder="Last Name"
                                required
                            />
                            <label for="studentLastName" class="glass-landing--form-label">Last Name *</label>
                            @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="glass-landing--form-group form-floating mb-3">
                            <input
                                type="email"

                                name="email"
                                class="glass-landing--form-input form-control"
                                id="studentEmail"
                                placeholder="Email"
                                required
                            />
                            <label for="studentEmail" class="glass-landing--form-label">Email *</label>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="glass-landing--form-group form-floating mb-3">
                            <input
                                type="password"
                                name="password"
                                class="glass-landing--form-input form-control"
                                id="studentPassword"
                                placeholder="Password"
                                required
                            />
                            <label for="studentPassword" class="glass-landing--form-label">Password *</label>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="glass-landing--form-group form-floating mb-3">
                            <input
                                type="password"
                                name="password_confirmation"
                                class="glass-landing--form-input form-control"
                                id="studentPasswordConfirmation"
                                placeholder="Confirm Password"
                                required
                            />
                            <label for="studentPasswordConfirmation" class="glass-landing--form-label">Confirm Password *</label>
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="glass-landing--step">
                        <h5 class="fw-semibold mb-3">Signup Type</h5>
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="signupType"
                                id="forMyself"
                                value="self"
                            />
                            <label class="form-check-label" for="forMyself">
                                I’m signing up for myself
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="signupType"
                                id="forKid"
                                value="kid"
                            />
                            <label class="form-check-label" for="forKid">
                                I’m signing up my kid
                            </label>
                        </div>
                        @error('signupType')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <!-- Guardian Details -->
                        <div id="guardianDetails" class="mt-3" style="display: none">
                            <h6 class="fw-semibold">Guardian Information</h6>
                            <div class="glass-landing--form-group form-floating mb-3">
                                <input
                                    type="text"
                                    class="glass-landing--form-input form-control"
                                    id="guardianName"
                                    name="guardian_name"
                                    placeholder="Guardian Name"
                                    required
                                />
                                <label for="guardianName" class="glass-landing--form-label">Guardian Name *</label>
                                @error('guardian_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="glass-landing--form-group form-floating mb-3">
                                <input
                                    type="text"
                                    class="glass-landing--form-input form-control"
                                    id="guardianRelation"
                                    name="guardian_relation"
                                    placeholder="Relation"
                                    required
                                />
                                <label for="guardianRelation" class="glass-landing--form-label">Relation *</label>
                                @error('guardian_relation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="glass-landing--form-group form-floating mb-3">
                                <input
                                    type="tel"
                                    name="guardian_contact"
                                    class="glass-landing--form-input form-control"
                                    id="guardianContact"
                                    placeholder="Contact"
                                    required
                                />
                                <label for="guardianContact" class="glass-landing--form-label">Contact Number *</label>
                                @error('guardian_contact')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="glass-landing--step">
                        <h5 class="fw-semibold mb-3">Review & Submit</h5>
                        <p class="text-muted">
                            Please confirm your details before completing signup.
                        </p>
                        <ul class="list-group mb-3">
                            <li class="list-group-item">
                                <strong>Name:</strong> <span id="reviewName"></span>
                            </li>
                            <li class="list-group-item">
                                <strong>Email:</strong> <span id="reviewEmail"></span>
                            </li>
                            <li class="list-group-item">
                                <strong>Signup Type:</strong> <span id="reviewType"></span>
                            </li>
                            <li class="list-group-item guardian-review d-none">
                                <strong>Guardian:</strong> <span id="reviewGuardian"></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            id="prevBtn"
                            disabled
                        >
                            <i class="bi bi-arrow-left me-1"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="nextBtn">
                            Next <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const steps = document.querySelectorAll('.glass-landing--step');
        const progressBar = document.getElementById('progressBar');
        const stepLabel = document.getElementById('stepLabel');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const signupTypeInputs = document.querySelectorAll('input[name="signupType"]');
        const guardianDetails = document.getElementById('guardianDetails');
        let currentStep = 0;

        const stepTitles = [
            'Step 1 of 3: Account Information',
            'Step 2 of 3: Signup Type',
            'Step 3 of 3: Review & Submit'
        ];

        function showStep(step) {
            steps.forEach((s, i) => s.classList.toggle('active', i === step));
            progressBar.style.width = ((step + 1) / steps.length) * 100 + '%';
            stepLabel.textContent = stepTitles[step];
            prevBtn.disabled = step === 0;
            nextBtn.innerHTML =
                step === steps.length - 1
                    ? '<i class="bi bi-check-circle me-1"></i> Submit'
                    : 'Next <i class="bi bi-arrow-right ms-1"></i>';
        }

        signupTypeInputs.forEach(input => {
            input.addEventListener('change', () => {
                guardianDetails.style.display = document.getElementById('forKid').checked ? 'block' : 'none';
                // Reset validation for guardian fields when switching back to 'self'
                if (!document.getElementById('forKid').checked) {
                    document.getElementById('guardianName').removeAttribute('required');
                    document.getElementById('guardianRelation').removeAttribute('required');
                    document.getElementById('guardianContact').removeAttribute('required');
                } else {
                    document.getElementById('guardianName').setAttribute('required', 'required');
                    document.getElementById('guardianRelation').setAttribute('required', 'required');
                    document.getElementById('guardianContact').setAttribute('required', 'required');
                }
            });
        });

        nextBtn.addEventListener('click', () => {
            const currentStepElement = steps[currentStep];
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            let allFieldsValid = true;

            requiredFields.forEach(field => {
                if (!field.checkValidity()) {
                    allFieldsValid = false;
                    field.reportValidity();
                }
            });

            if (!allFieldsValid) {
                return;
            }

            if (currentStep < steps.length - 1) {
                currentStep++;
                if (currentStep === steps.length - 1) {
                    document.getElementById('reviewName').textContent =
                        document.getElementById('studentFirstName').value + ' ' +
                        document.getElementById('studentLastName').value;
                    document.getElementById('reviewEmail').textContent =
                        document.getElementById('studentEmail').value;
                    const type = document.querySelector('input[name="signupType"]:checked').value;
                    document.getElementById('reviewType').textContent =
                        type === 'self' ? 'For Myself' : 'For My Kid';
                    if (type === 'kid') {
                        document.querySelector('.guardian-review').classList.remove('d-none');
                        document.getElementById('reviewGuardian').textContent =
                            document.getElementById('guardianName').value +
                            ' (' +
                            document.getElementById('guardianRelation').value +
                            ', ' +
                            document.getElementById('guardianContact').value +
                            ')';
                    }
                }
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

        showStep(currentStep);
    </script>
</x-guest-layout>
