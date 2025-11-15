<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ed-Cademy — Student Signup</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    />
    <style>
      :root {
        --primary-cyan: #006b7d;
        --dark-cyan: #004a57;
        --light-cyan: #e0f7fa;
        --bg-soft: #f6f8f9;
      }
      body {
        background: var(--bg-soft);
      }
      .form-section {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.06);
        margin-bottom: 2rem;
      }
      .section-header {
        background: var(--light-cyan);
        border-radius: 16px 16px 0 0;
        padding: 1.25rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
      }
      .section-title {
        color: var(--dark-cyan);
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }
      .btn-primary {
        background: var(--primary-cyan);
        border-color: var(--primary-cyan);
      }
      .btn-primary:hover {
        background: var(--dark-cyan);
        border-color: var(--dark-cyan);
      }
      .progress-bar {
        background-color: var(--primary-cyan);
      }
      .step {
        display: none;
      }
      .step.active {
        display: block;
      }
    </style>
  </head>
  <body>
    <div class="container py-5">
      <div class="col-lg-7 mx-auto">
        <div class="form-section">
          <div class="section-header">
            <h2 class="section-title">
              <i class="bi bi-person-plus"></i> Student Signup
            </h2>
          </div>
          <div class="p-4">
            <!-- Progress -->
            <div class="mb-4">
              <div class="progress" style="height: 6px">
                <div
                  class="progress-bar"
                  id="progressBar"
                  style="width: 33%"
                ></div>
              </div>
              <small class="text-muted" id="stepLabel"
                >Step 1 of 3: Account Information</small
              >
            </div>

            <form id="signupForm" method="POST" action="{{ route('student.signup.store') }}">
              @csrf
              <!-- Step 1 -->
              <div class="step active">
                <h5 class="fw-semibold mb-3">Account Information</h5>
                <div class="form-floating mb-3">
                  <input
                    type="text"
                    name="first_name"
                    class="form-control"
                    id="studentFirstName"
                    placeholder="First Name"
                    required
                  />
                  <label for="studentFirstName">First Name *</label>
                  @error('first_name')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-floating mb-3">
                  <input
                    type="text"
                    name="last_name"
                    class="form-control"
                    id="studentLastName"
                    placeholder="Last Name"
                    required
                  />
                  <label for="studentLastName">Last Name *</label>
                  @error('last_name')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-floating mb-3">
                  <input
                    type="email"
                    name="email"
                    class="form-control"
                    id="studentEmail"
                    placeholder="Email"
                    required
                  />
                  <label for="studentEmail">Email *</label>
                  @error('email')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-floating mb-3">
                  <input
                    type="password"
                    name="password"
                    class="form-control"
                    id="studentPassword"
                    placeholder="Password"
                    required
                  />
                  <label for="studentPassword">Password *</label>
                  @error('password')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-floating mb-3">
                  <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    id="studentPasswordConfirmation"
                    placeholder="Confirm Password"
                    required
                  />
                  <label for="studentPasswordConfirmation">Confirm Password *</label>
                  @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Step 2 -->
              <div class="step">
                <h5 class="fw-semibold mb-3">Signup Type</h5>
                <div class="form-check mb-2">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="signupType"
                    id="forMyself"
                    value="self"

                  />
                  <label class="form-check-label" for="forMyself"
                    >I’m signing up for myself</label
                  >
                </div>
                <div class="form-check mb-2">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="signupType"
                    id="forKid"
                    value="kid"
                  />
                  <label class="form-check-label" for="forKid"
                    >I’m signing up my kid</label
                  >
                </div>
                @error('signupType')
                  <div class="text-danger">{{ $message }}</div>
                @enderror

                <!-- Guardian Details -->
                <div id="guardianDetails" class="mt-3" style="display: none">
                  <h6 class="fw-semibold">Guardian Information</h6>
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="guardianName"
                      name="guardian_name"
                      placeholder="Guardian Name"
                      required
                    />
                    <label for="guardianName">Guardian Name *</label>
                    @error('guardian_name')
                      <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="guardianRelation"
                      name="guardian_relation"
                      placeholder="Relation"
                      required
                    />
                    <label for="guardianRelation">Relation *</label>
                    @error('guardian_relation')
                      <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-floating mb-3">
                    <input
                      type="tel"
                      name="guardian_contact"
                      class="form-control"
                      id="guardianContact"
                      placeholder="Contact"
                      required
                    />
                    <label for="guardianContact">Contact Number *</label>
                    @error('guardian_contact')
                      <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <!-- Step 3 -->
              <div class="step">
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
    </div>

    <script>
      const steps = document.querySelectorAll('.step')
      const progressBar = document.getElementById('progressBar')
      const stepLabel = document.getElementById('stepLabel')
      const nextBtn = document.getElementById('nextBtn')
      const prevBtn = document.getElementById('prevBtn')
      const signupTypeInputs = document.querySelectorAll(
        'input[name="signupType"]'
      )
      const guardianDetails = document.getElementById('guardianDetails')
      let currentStep = 0

      const stepTitles = [
        'Step 1 of 3: Account Information',
        'Step 2 of 3: Signup Type',
        'Step 3 of 3: Review & Submit'
      ]

      function showStep(step) {
        steps.forEach((s, i) => s.classList.toggle('active', i === step))
        progressBar.style.width = ((step + 1) / steps.length) * 100 + '%'
        stepLabel.textContent = stepTitles[step]
        prevBtn.disabled = step === 0
        nextBtn.innerHTML =
          step === steps.length - 1
            ? '<i class="bi bi-check-circle me-1"></i> Submit'
            : 'Next <i class="bi bi-arrow-right ms-1"></i>'
      }

      signupTypeInputs.forEach(input => {
        input.addEventListener('change', () => {
          guardianDetails.style.display = document.getElementById('forKid')
            .checked
            ? 'block'
            : 'none'

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
        })
      })

      nextBtn.addEventListener('click', () => {
        const currentStepElement = steps[currentStep];
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        let allFieldsValid = true;

        requiredFields.forEach(field => {
          if (!field.checkValidity()) {
            allFieldsValid = false;
            field.reportValidity(); // Show native browser validation message
          }
        });

        if (!allFieldsValid) {
          return; // Stop if any required field is not valid
        }

        if (currentStep < steps.length - 1) {
          currentStep++;
          if (currentStep === steps.length - 1) {
            document.getElementById('reviewName').textContent =
              document.getElementById('studentFirstName').value + ' ' + document.getElementById('studentLastName').value;
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
        //   alert('Signup submitted successfully!');
          document.getElementById('signupForm').submit();
        }
      });

      prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
          currentStep--
          showStep(currentStep)
        }
      })

      showStep(currentStep)
    </script>
  </body>
</html>
