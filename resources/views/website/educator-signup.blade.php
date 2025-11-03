<x-guest-layout>


    <div class="glass-landing--signup-hero">
        <div class="container">
            <div class="glass-landing--signup-card mt-5">
                <div class="text-center mb-4">
                    <div class="glass-landing--signup-logo">Ed-Cademy</div>
                    <h1 class="glass-landing--signup-title mb-2">
                        Become a Tutor
                    </h1>
                    <p class="text-muted">
                        Share your knowledge and inspire students worldwide
                    </p>
                </div>

                <!-- Step Indicator -->
                <div class="glass-landing--step-indicator">
                    <div class="glass-landing--step active" data-step="1">
                        <div class="glass-landing--step-circle">1</div>
                        <div class="glass-landing--step-label">
                            Personal Info
                        </div>
                    </div>
                    <div class="glass-landing--step" data-step="2">
                        <div class="glass-landing--step-circle">2</div>
                        <div class="glass-landing--step-label">
                            Expertise
                        </div>
                    </div>
                    <div class="glass-landing--step" data-step="3">
                        <div class="glass-landing--step-circle">3</div>
                        <div class="glass-landing--step-label">
                            Verification
                        </div>
                    </div>
                </div>

                <div class="glass-landing--info-box">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Join 10,000+ tutors making a difference. Complete your
                    profile to start teaching and earning.
                </div>

                <form id="tutorForm" method="POST" action="{{ route('educator.signup.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Step 1 -->
                    <div class="step" id="step1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="glass-landing--form-label">First Name *</label>
                                <input type="text" name="first_name"
                                    class="form-control glass-landing--form-input @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name') }}" required />
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="glass-landing--form-label">Last Name *</label>
                                <input type="text" name="last_name"
                                    class="form-control glass-landing--form-input @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}" required />
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="glass-landing--form-label">Email *</label>
                                <input type="email" name="email"
                                    class="form-control glass-landing--form-input @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="glass-landing--form-label">Password *</label>
                                <input type="password" name="password"
                                    class="form-control glass-landing--form-input @error('password') is-invalid @enderror"
                                    required />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="glass-landing--form-label">Confirm Password *</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control glass-landing--form-input @error('password_confirmation') is-invalid @enderror"
                                    required />
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="button" class="btn glass-landing--submit-btn mt-4 next-btn">
                            Next <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <!-- Step 2 -->
                    <div class="step hidden-step" id="step2">
                        <div class="mb-3">
                            <label class="glass-landing--form-label">Primary Subject *</label>
                            <select name="primary_subject"
                                class="form-select glass-landing--form-select @error('primary_subject') is-invalid @enderror"
                                required>
                                <option value="">Select Subject</option>
                                @foreach (['Mathematics', 'Science', 'English', 'Computer Science', 'Languages', 'Other'] as $subject)
                                    <option value="{{ $subject }}"
                                        {{ old('primary_subject') == $subject ? 'selected' : '' }}>
                                        {{ $subject }}
                                    </option>
                                @endforeach
                            </select>
                            @error('primary_subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="glass-landing--form-label">Teaching Levels *</label>
                            <select name="teaching_levels[]" multiple
                                class="form-select glass-landing--form-select @error('teaching_levels') is-invalid @enderror"
                                required>
                                @foreach (['Elementary', 'Middle School', 'High School', 'College', 'Professional'] as $level)
                                    <option value="{{ $level }}"
                                        {{ in_array($level, old('teaching_levels', [])) ? 'selected' : '' }}>
                                        {{ $level }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teaching_levels')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple</small>
                        </div>

                        <div class="mb-3">
                            <label class="glass-landing--form-label">Hourly Rate (USD) *</label>
                            <input name="hourly_rate" type="number"
                                class="form-control glass-landing--form-input @error('hourly_rate') is-invalid @enderror"
                                min="5" placeholder="e.g. 25" value="{{ old('hourly_rate') }}" required />
                            @error('hourly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="glass-landing--form-label">Certifications / Degrees</label>
                            <textarea name="certifications"
                                class="form-control glass-landing--form-textarea @error('certifications') is-invalid @enderror"
                                placeholder="List your academic or teaching certifications">{{ old('certifications') }}</textarea>
                            @error('certifications')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="glass-landing--form-label">Preferred Teaching Style</label>
                            <select name="preferred_teaching_style"
                                class="form-select glass-landing--form-select @error('preferred_teaching_style') is-invalid @enderror">
                                <option value="">Select</option>
                                @foreach (['Interactive / Discussion-based', 'Lecture / Presentation', 'Hands-on / Practical', 'Assessment-driven'] as $style)
                                    <option value="{{ $style }}"
                                        {{ old('preferred_teaching_style') == $style ? 'selected' : '' }}>
                                        {{ $style }}
                                    </option>
                                @endforeach
                            </select>
                            @error('preferred_teaching_style')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary back-btn">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </button>
                            <button type="button" class="btn glass-landing--submit-btn next-btn">
                                Next <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="step hidden-step" id="step3">
                        <div class="mb-3">
                            <label class="glass-landing--form-label">Upload Government ID *</label>
                            <input name="govt_id" type="file"
                                class="form-control glass-landing--form-input @error('govt_id') is-invalid @enderror"
                                accept="image/*,application/pdf" required />
                            @error('govt_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="glass-landing--form-label">Upload Degree / Certification Proof *</label>
                            <input name="degree_proof" type="file"
                                class="form-control glass-landing--form-input @error('degree_proof') is-invalid @enderror"
                                accept="image/*,application/pdf" required />
                            @error('degree_proof')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="glass-landing--form-label">Intro Video (Optional)</label>
                            <input name="intro_video" type="file"
                                class="form-control glass-landing--form-input @error('intro_video') is-invalid @enderror"
                                accept="video/*" />
                            <small class="text-muted">Introduce yourself and your teaching style (max 2 min)</small>
                            @error('intro_video')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input name="consent" type="checkbox"
                                class="form-check-input @error('consent') is-invalid @enderror" id="consentCheck"
                                {{ old('consent') ? 'checked' : '' }} required />
                            <label for="consentCheck" class="form-check-label">
                                I consent to Ed-Cademy verifying my documents and contacting me for onboarding.
                            </label>
                            @error('consent')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary back-btn">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </button>
                            <button type="submit" class="btn glass-landing--submit-btn">
                                Submit Application <i class="fas fa-check ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>


                <div class="glass-landing--divider">
                    <span class="glass-landing--divider-text">Or sign up with</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="glass-landing--social-btn">
                            <i class="fab fa-google me-2 text-danger"></i>Sign up with Google
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="glass-landing--social-btn">
                            <i class="fab fa-facebook me-2 text-primary"></i>Sign up with Facebook
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                overflow-x: hidden;
            }

            .glass-landing--signup-hero {
                background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=2071&q=80') no-repeat center/cover;
                min-height: 100vh;
                position: relative;
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
                box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
            }

            .glass-landing--submit-btn {
                background: #0891b2;
                border: none;
                padding: 15px 40px;
                font-size: 1.2rem;
                font-weight: 600;
                border-radius: 10px;
                width: 100%;
                color: white;
                transition: all 0.3s;
                margin-top: 20px;
            }

            .glass-landing--submit-btn:hover {
                background: #0e7490;
                transform: translateY(-2px);
            }

            .glass-landing--step-indicator {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
            }

            .glass-landing--step {
                text-align: center;
                flex: 1;
                position: relative;
            }

            .glass-landing--step::after {
                content: '';
                position: absolute;
                top: 20px;
                left: 50%;
                width: 100%;
                height: 2px;
                background: #e5e7eb;
                z-index: -1;
            }

            .glass-landing--step:last-child::after {
                display: none;
            }

            .glass-landing--step-circle {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #e5e7eb;
                color: #9ca3af;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .glass-landing--step.active .glass-landing--step-circle {
                background: #0891b2;
                color: white;
            }

            .glass-landing--step-label {
                font-size: 0.85rem;
                color: #6b7280;
                font-weight: 600;
            }

            .glass-landing--step.active .glass-landing--step-label {
                color: #0891b2;
            }

            .hidden-step {
                display: none;
            }

            .glass-landing--info-box {
                background: #f0f9ff;
                border-left: 4px solid #0891b2;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 30px;
            }

            .glass-landing--divider {
                text-align: center;
                margin: 30px 0;
                position: relative;
            }

            .glass-landing--divider::before {
                content: '';
                position: absolute;
                left: 0;
                right: 0;
                top: 50%;
                height: 1px;
                background: #e5e7eb;
            }

            .glass-landing--divider-text {
                background: white;
                padding: 0 20px;
                color: #9ca3af;
                position: relative;
            }

            .glass-landing--social-btn {
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                padding: 12px;
                width: 100%;
                font-weight: 600;
                transition: all 0.3s;
            }

            .glass-landing--social-btn:hover {
                border-color: #0891b2;
                color: #0891b2;
                transform: translateY(-2px);
            }
        </style>
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const steps = document.querySelectorAll('.step')
            const indicators = document.querySelectorAll('.glass-landing--step')
            let currentStep = 0

            function showStep(index) {
                steps.forEach((step, i) =>
                    step.classList.toggle('hidden-step', i !== index)
                )
                indicators.forEach((ind, i) =>
                    ind.classList.toggle('active', i <= index)
                )
                currentStep = index
            }

            document.querySelectorAll('.next-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep < steps.length - 1)
                        showStep(currentStep + 1)
                })
            })
            document.querySelectorAll('.back-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep > 0) showStep(currentStep - 1)
                })
            })
        </script>
    @endpush
</x-guest-layout>
