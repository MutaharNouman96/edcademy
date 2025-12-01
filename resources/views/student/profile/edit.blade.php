<x-student-layout>
    <div class="py-12 d-none">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if (session('status') === 'profile-updated')
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ __('Your profile has been updated.') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="post" action="{{ route('student.profile.update') }}" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <label for="first_name" class="form-label">First Name</label>
                            <input id="first_name" name="first_name" type="text" class="form-control"
                                value="{{ old('first_name', $user->first_name) }}" required autofocus
                                autocomplete="first_name" />
                            @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="form-label">Last Name</label>
                            <input id="last_name" name="last_name" type="text" class="form-control"
                                value="{{ old('last_name', $user->last_name) }}" required autocomplete="last_name" />
                            @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required autocomplete="email" />
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($user->guardian)
                            <div class="mt-4">
                                <h5 class="fw-semibold mb-3">Guardian Information</h5>
                                <div class="mb-3">
                                    <label for="guardian_name" class="form-label">Guardian Name</label>
                                    <input id="guardian_name" name="guardian_name" type="text" class="form-control"
                                        value="{{ old('guardian_name', $user->guardian->guardian_name) }}" required
                                        autocomplete="guardian_name" />
                                    @error('guardian_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="guardian_relation" class="form-label">Guardian Relation</label>
                                    <input id="guardian_relation" name="guardian_relation" type="text"
                                        class="form-control"
                                        value="{{ old('guardian_relation', $user->guardian->guardian_relation) }}"
                                        required autocomplete="guardian_relation" />
                                    @error('guardian_relation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="guardian_contact" class="form-label">Guardian Contact</label>
                                    <input id="guardian_contact" name="guardian_contact" type="tel"
                                        class="form-control"
                                        value="{{ old('guardian_contact', $user->guardian->guardian_contact) }}"
                                        required autocomplete="guardian_contact" />
                                    @error('guardian_contact')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="d-flex align-items-center gap-4 mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <main class="col-12 col-md-9 col-lg-12">
        <div class=" align-items-center justify-content-between mb-3">
            <h2 class="h4 mb-0">Profile & Settings</h2>
            <p class="small-muted">Manage your account, privacy, notifications and learning preferences</p>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="card p-3">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="tab-profile"
                                data-bs-toggle="tab" data-bs-target="#pane-profile" type="button"
                                role="tab">Profile</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="tab-account"
                                data-bs-toggle="tab" data-bs-target="#pane-account" type="button"
                                role="tab">Account</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="tab-security"
                                data-bs-toggle="tab" data-bs-target="#pane-security" type="button"
                                role="tab">Security</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="tab-notifications"
                                data-bs-toggle="tab" data-bs-target="#pane-notifications" type="button"
                                role="tab">Notifications</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="tab-privacy"
                                data-bs-toggle="tab" data-bs-target="#pane-privacy" type="button"
                                role="tab">Data Control</button></li>



                        <li class="nav-item" role="presentation"><button class="nav-link" id="tab-connections"
                                data-bs-toggle="tab" data-bs-target="#pane-connections" type="button"
                                role="tab">Connected Accounts</button></li>

                    </ul>

                    <div class="tab-content mt-3">
                        <!-- Profile Pane -->
                        <div class="tab-pane fade show active" id="pane-profile" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-section">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <img id="avatarPreview"
                                                src="{{ old('avatar', $user->profile_picture ? $user->profile_picture : asset('images/avatars/default.png')) }}"
                                                alt="avatar" class="avatar-preview mb-2 img-fluid d-block mx-auto rounded-circle"
                                                width="120px" height="120px">
                                            <div class="mb-2">
                                                <small class="text-muted d-block">Select your avatar</small>

                                                <div class="avatar-selection-grid mt-2 d-flex flex-wrap justify-content-center">
                                                    <img width="50px" height="50px" src="https://placehold.co/50x50/FF8C5A/white?text=A" data-avatar-url="https://placehold.co/50x50/FF8C5A/white?text=A" class="avatar-option img-fluid rounded-circle m-1" alt="Avatar 1">
                                                    <img width="50px" height="50px" src="https://placehold.co/50x50/FF8C5A/white?text=B" data-avatar-url="https://placehold.co/50x50/FF8C5A/white?text=B" class="avatar-option img-fluid rounded-circle m-1" alt="Avatar 2">
                                                    <img width="50px" height="50px" src="https://placehold.co/50x50/FF8C5A/white?text=C" data-avatar-url="https://placehold.co/50x50/FF8C5A/white?text=C" class="avatar-option img-fluid rounded-circle m-1" alt="Avatar 3">
                                                    <img width="50px" height="50px" src="https://placehold.co/50x50/FF8C5A/white?text=D" data-avatar-url="https://placehold.co/50x50/FF8C5A/white?text=D" class="avatar-option img-fluid rounded-circle m-1" alt="Avatar 4">
                                                    <img width="50px" height="50px" src="https://placehold.co/50x50/FF8C5A/white?text=E" data-avatar-url="https://placehold.co/50x50/FF8C5A/white?text=E" class="avatar-option img-fluid rounded-circle m-1" alt="Avatar 5">
                                                    <img width="50px" height="50px" src="https://placehold.co/50x50/FF8C5A/white?text=F" data-avatar-url="https://placehold.co/50x50/FF8C5A/white?text=F" class="avatar-option img-fluid rounded-circle m-1" alt="Avatar 6">
                                                    <img width="50px" height="50px" src="https://placehold.co/50x50/FF8C5A/white?text=G" data-avatar-url="https://placehold.co/50x50/FF8C5A/white?text=G" class="avatar-option img-fluid rounded-circle m-1" alt="Avatar 7">
                                                    <img width="50px" height="50px" src="https://placehold.co/50x50/FF8C5A/white?text=H" data-avatar-url="https://placehold.co/50x50/FF8C5A/white?text=H" class="avatar-option img-fluid rounded-circle m-1" alt="Avatar 8">
                                                </div>
                                                <input type="hidden" id="selectedAvatar" name="avatar"
                                                       value="{{ old('avatar', $user->profile_picture ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <form id="StudentProfile" method="POST" action="javascript:void(0);">
                                    <div class="form-section">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <label class="col-form-label">First name</label>
                                                <input id="fullName" class="form-control"
                                                    value="{{ old('first_name', $user->first_name) }}"></div>
                                                    <div class="col-md-6 col-sm-12">
                                                        <label class="col-form-label">Last name</label>
                                                            <input id="displayName" class="form-control"
                                                                value="{{ old('last_name', $user->last_name) }}"></div>
                                        </div>





                                        <div class="mb-3 row">
                                            <div class="col-sm-9">
                                            <label class="col-form-label">Bio</label>
                                                <textarea id="bio" class="form-control" rows="3" maxlength="250"
                                                    placeholder="Short bio — what you'd like teachers & peers to know.">{{ $student_profile->bio }}</textarea>
                                                <small class="text-muted">Shown on your profile. Max 250
                                                    characters.</small>
                                            </div>
                                        </div>


                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small-muted">Education level</label>
                                                <select id="education" class="form-select">
                                                    <option value="university" {{ $student_profile->education_level == 'university' ? 'selected' : '' }}>University</option>
                                                    <option value="high" {{ $student_profile->education_level == 'high' ? 'selected' : '' }}>High School</option>
                                                    <option value="professional" {{ $student_profile->education_level == 'professional' ? 'selected' : '' }}>Professional</option>
                                                    <option value="other" {{ $student_profile->education_level == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small-muted">Interests</label>
                                                <input id="interests" class="form-control"
                                                    placeholder="e.g., calculus, robotics, IELTS" value="{{ $student_profile->interests }}">
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary" data-save="profile">Save profile</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>

                        <!-- Account Pane -->
                        <div class="tab-pane fade" id="pane-account" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <form id="AccountProfile" method="POST" action="javascript:void(0);">
                                    <div class="form-section">
                                        <h6>Account</h6>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input id="email" class="form-control" value="{{ $user->email }}"
                                                    type="email" readonly>
                                                <small class="text-muted">Primary email used for sign in and
                                                    notifications.</small>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label">Phone</label>
                                            <div class="col-sm-9">
                                                <input id="phone" placeholder="Enter your phone number" class="form-control" value="{{ $student_profile->phone ?? '' }}">
                                                <small class="text-muted">Used for SMS notifications</small>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Language</label>
                                                <select id="language" class="form-select">
                                                    <option value="en" {{ ($student_profile->language ?? '') == 'en' ? 'selected' : '' }}>English</option>
                                                    <option value="ar" {{ ($student_profile->language ?? '') == 'ar' ? 'selected' : '' }}>Arabic</option>
                                                    <option value="zh" {{ ($student_profile->language ?? '') == 'zh' ? 'selected' : '' }}>中文</option>
                                                    <option value="fr" {{ ($student_profile->language ?? '') == 'fr' ? 'selected' : '' }}>Français</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Timezone</label>
                                                <select id="timezone" class="form-select">
                                                    <option value="Asia/Dubai" {{ ($student_profile->timezone ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>UTC+04:00 — Asia/Dubai</option>
                                                    <option value="Europe/London" {{ ($student_profile->timezone ?? '') == 'Europe/London' ? 'selected' : '' }}>UTC+00:00 — Europe/London</option>
                                                    <option value="America/New_York" {{ ($student_profile->timezone ?? '') == 'America/New_York' ? 'selected' : '' }}>UTC-04:00 — America/New_York
                                                    </option>
                                                    <option value="Asia/Kolkata" {{ ($student_profile->timezone ?? '') == 'Asia/Kolkata' ? 'selected' : '' }}>UTC+05:30 — Asia/Kolkata</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-3 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary" data-save="account">Save account</button>
                                        </div>
                                    </div>
                                </form>
                                </div>


                            </div>
                        </div>

                        <!-- Security Pane -->
                        <div class="tab-pane fade" id="pane-security" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <form id="SecurityProfile" method="POST" action="javascript:void(0);">
                                    <div class="form-section">

                                        <div class="mb-2">
                                            <label class="form-label small-muted">Current password</label>
                                            <input id="curPass" name="current_password" class="form-control" type="password"
                                                placeholder="••••••">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small-muted">New password</label>
                                            <input id="newPass" name="password" class="form-control" type="password"
                                                placeholder="At least 8 characters">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small-muted">Confirm new</label>
                                            <input id="confirmPass" name="password_confirmation" class="form-control" type="password">
                                        </div>
                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-primary" data-save="password">Update
                                                password</button>
                                        </div>
                                    </div>
                                    </form>

                                </div>

                            </div>
                        </div>

                        <!-- Notifications Pane -->
                        <div class="tab-pane fade" id="pane-notifications" role="tabpanel">
                            <form id="NotificationProfile" method="POST" action="javascript:void(0);">
                                <div class="form-section">
                                    <h6>Notification preferences</h6>
                                    <p class="small-muted mb-3">Choose how you would like to receive platform updates,
                                        course alerts and marketing.</p>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <h6 class="mb-2">By channel</h6>
                                            <div class="form-check"><input class="form-check-input" id="notifyEmail"
                                                    type="checkbox" name="notify_email" value="1" checked><label class="form-check-label"
                                                    for="notifyEmail">Email</label></div>
                                            <div class="form-check"><input class="form-check-input" id="notifySMS"
                                                    type="checkbox" name="notify_sms" value="1"><label class="form-check-label"
                                                    for="notifySMS">SMS</label></div>
                                            <div class="form-check"><input class="form-check-input" id="notifyPush"
                                                    type="checkbox" name="notify_push" value="1" checked><label class="form-check-label"
                                                    for="notifyPush">Push (mobile)</label></div>
                                            <div class="form-check"><input class="form-check-input" id="notifyInApp"
                                                    type="checkbox" name="notify_in_app" value="1" checked><label class="form-check-label"
                                                    for="notifyInApp">In-app</label></div>
                                        </div>

                                        <div class="col-md-6">
                                            <h6 class="mb-2">By event</h6>
                                            <div class="form-check"><input class="form-check-input" id="evtCourseRelease"
                                                    type="checkbox" name="evt_course_release" value="1" checked><label class="form-check-label"
                                                    for="evtCourseRelease">New lesson released</label></div>
                                            <div class="form-check"><input class="form-check-input" id="evtAssignment"
                                                    type="checkbox" name="evt_assignment" value="1" checked><label class="form-check-label"
                                                    for="evtAssignment">Assignment & quiz results</label></div>
                                            <div class="form-check"><input class="form-check-input" id="evtPromos"
                                                    type="checkbox" name="evt_promos" value="1"><label class="form-check-label"
                                                    for="evtPromos">Promotions & offers</label></div>
                                            <div class="form-check"><input class="form-check-input" id="evtSystem"
                                                    type="checkbox" name="evt_system" value="1" checked><label class="form-check-label"
                                                    for="evtSystem">System notices (billing, security)</label></div>
                                        </div>
                                    </div>

                                    <div class="mt-3 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" data-save="notifications">Save
                                            notifications</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Privacy Pane -->
                        <div class="tab-pane fade" id="pane-privacy" role="tabpanel">
                            <div class="form-section">

                                <h6>Data controls</h6>
                                <div class="mb-3">
                                    <button class="btn btn-outline-primary" id="downloadDataBtn">Download a copy of my
                                        data</button>
                                    <button class="btn btn-outline-secondary ms-2" id="requestDeletionBtn">Request
                                        data deletion</button>
                                </div>

                                <div class="mt-3 text-danger">
                                    <strong>Danger zone</strong>
                                    <p class="small-muted">Delete your account and all associated data. This action is
                                        irreversible.</p>
                                    <button class="btn btn-danger" id="deleteAccountBtn">Delete account</button>
                                </div>
                            </div>
                        </div>

                        <!-- Connections Pane -->
                        <div class="tab-pane fade" id="pane-connections" role="tabpanel">
                            <div class="form-section">
                                <h6>Connected accounts</h6>

                                <div class="row g-3">
                                    @if ($user->google_id)
                                    <div class="col-md-6">
                                        <div
                                            class="border rounded p-3 d-flex align-items-center justify-content-between">
                                            <div><i
                                                    class="bi bi-google fs-3 text-danger me-2"></i><strong>Google</strong>
                                                <div class="small text-muted">Connected</div>
                                            </div>

                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div
                                            class="border rounded p-3 d-flex align-items-center justify-content-between">
                                            <div><i
                                                    class="bi bi-person fs-3 text-primary me-2"></i><strong>Register via email</strong>
                                                <div class="small text-muted">Connected</div>
                                            </div>

                                        </div>
                                    </div>
                                    @endif

                                </div>

                            </div>
                        </div>

                    </div> <!-- tab-content -->
                </div> <!-- card -->
            </div>
        </div>
    </main>
    <div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:1060"></div>

    @push('scripts')
        <script>
            // ---------- Helper: toasts ----------
            function showToast(message, type = 'info') {
                const tc = document.getElementById('toastContainer');
                const toastEl = document.createElement('div');
                toastEl.className = `toast align-items-center text-bg-${type} border-0`;
                toastEl.role = 'alert';
                toastEl.innerHTML = `
        <div class="d-flex">
          <div class="toast-body"><i class="bi me-2"></i>${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>`;
                tc.appendChild(toastEl);
                const t = new bootstrap.Toast(toastEl, {
                    delay: 3000
                });
                t.show();
                toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
            }

            // ---------- Avatar preview ----------
            const avatarPreview = document.getElementById('avatarPreview');
            const selectedAvatarInput = document.getElementById('selectedAvatar');
            const avatarOptions = document.querySelectorAll('.avatar-option');

            avatarOptions.forEach(option => {
                option.addEventListener('click', () => {
                    // Remove active class from all options
                    avatarOptions.forEach(opt => opt.classList.remove('active'));

                    // Add active class to the clicked option
                    option.classList.add('active');

                    const avatarUrl = option.dataset.avatarUrl;
                    avatarPreview.src = avatarUrl;
                    selectedAvatarInput.value = avatarUrl;
                    // showToast('Avatar selected', 'info');

                    // Make AJAX call to save avatar
                    fetch('{{ route('api.student.profile.update-avatar') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ avatar: avatarUrl })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Avatar saved successfully!', 'success');
                        } else {
                            showToast('Failed to save avatar.', 'danger');
                            console.error('Error saving avatar:', data.message);
                        }
                    })
                    .catch(error => {
                        showToast('An error occurred while saving avatar.', 'danger');
                        console.error('Fetch error:', error);
                    });
                });
            });

            // Set initial active avatar based on current user avatar (prevent flicker)
            document.addEventListener('DOMContentLoaded', () => {
                const currentAvatar = selectedAvatarInput.value;

                if (currentAvatar) {
                    // Keep the DB avatar
                    avatarPreview.src = currentAvatar;

                    // Highlight matching option if it exists
                    avatarOptions.forEach(option => {
                        if (option.dataset.avatarUrl === currentAvatar) {
                            option.classList.add('active');
                        }
                    });
                } else if (avatarOptions.length > 0) {
                    // Only use first avatar if no DB avatar exists
                    avatarOptions[0].classList.add('active');
                    avatarPreview.src = avatarOptions[0].dataset.avatarUrl;
                    selectedAvatarInput.value = avatarOptions[0].dataset.avatarUrl;
                }
            });

            // ---------- Sessions mock ----------
            const sessions = [{
                    id: 's1',
                    device: 'Chrome on macOS',
                    loc: 'Dubai, AE',
                    ip: '185.12.34.56',
                    last: '2 hours ago'
                },
                {
                    id: 's2',
                    device: 'iPhone (App)',
                    loc: 'Dubai, AE',
                    ip: '185.12.33.11',
                    last: 'Yesterday'
                }
            ];

            function renderSessions() {
                const list = document.getElementById('sessionsList');
                list.innerHTML = sessions.map(s => `<div class="d-flex justify-content-between align-items-center mb-2">
        <div><strong>${s.device}</strong><div class="small text-muted">${s.loc} • ${s.ip}</div></div>
        <div><small class="text-muted">${s.last}</small> <button class="btn btn-sm btn-outline-danger ms-2" data-revoke="${s.id}">Revoke</button></div>
      </div>`).join('');
                const cards = document.getElementById('sessionCards');
                cards.innerHTML = sessions.map(s => `<div class="list-group-item d-flex justify-content-between align-items-center">
        <div><i class="bi bi-laptop me-2"></i><strong>${s.device}</strong><div class="small text-muted">${s.loc} • ${s.last}</div></div>
        <div><button class="btn btn-sm btn-outline-danger" data-revoke="${s.id}">Revoke</button></div>
      </div>`).join('');
            }
            renderSessions();
            document.addEventListener('click', (e) => {
                if (e.target.dataset.revoke) {
                    const id = e.target.dataset.revoke;
                    const idx = sessions.findIndex(s => s.id === id);
                    if (idx > -1) sessions.splice(idx, 1);
                    renderSessions();
                    showToast('Session revoked', 'info');
                }
            });
            document.getElementById('revokeAllSessions').addEventListener('click', () => {
                if (!confirm('Sign out of all devices?')) return;
                sessions.length = 0;
                renderSessions();
                showToast('Signed out from all devices', 'success');
            });

            // ---------- 2FA toggle ----------
            const toggle2fa = document.getElementById('toggle2fa');
            const twoFaDetails = document.getElementById('twoFaDetails');
            document.getElementById('setup2faBtn').addEventListener('click', () => {
                toggle2fa.checked = true;
                twoFaDetails.style.display = 'block';
                showToast('2FA enabled — store your recovery codes', 'success');
            });
            toggle2fa.addEventListener('change', () => {
                twoFaDetails.style.display = toggle2fa.checked ? 'block' : 'none';
                showToast(toggle2fa.checked ? '2FA enabled' : '2FA disabled', 'info');
            });

            // ---------- Save handlers (simulate) ----------
            document.querySelectorAll('[data-save]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const key = e.currentTarget.dataset.save;
                    // gather minimal info per section (simulated)
                    showToast(`${capitalize(key)} settings saved`, 'success');
                });
            });
            document.getElementById('saveAllBtn').addEventListener('click', () => {
                showToast('All settings saved', 'success');
            });

            function capitalize(s) {
                return s.charAt(0).toUpperCase() + s.slice(1);
            }

            // ---------- Export / Data ----------
            document.getElementById('exportDataBtn').addEventListener('click', () => {
                showToast('Preparing data export. You will receive an email when ready.', 'info');
            });
            document.getElementById('downloadDataBtn').addEventListener('click', () => {
                showToast('Download started (simulated)', 'success');
            });
            document.getElementById('requestDeletionBtn').addEventListener('click', () => {
                if (confirm('Request account deletion? This will begin the deletion process.')) {
                    showToast('Deletion request submitted', 'info');
                }
            });
            document.getElementById('deleteAccountBtn').addEventListener('click', () => {
                if (confirm('Delete account permanently? This cannot be undone.')) {
                    showToast('Account deleted (simulated)', 'danger');
                }
            });

            // ---------- Billing actions ----------
            document.getElementById('addCardBtn').addEventListener('click', () => showToast(
                'Open add card modal (not implemented)', 'info'));
            document.getElementById('removeCardBtn').addEventListener('click', () => showToast('Card removed (simulated)',
                'info'));
            document.getElementById('cancelSubBtn').addEventListener('click', () => {
                if (confirm('Cancel subscription?')) showToast('Subscription cancelled', 'info');
            });
            document.getElementById('upgradeSubBtn').addEventListener('click', () => showToast(
                'Redirecting to upgrade (simulated)', 'info'));
            document.getElementById('downloadInvoicesBtn').addEventListener('click', () => showToast(
                'Invoices download started', 'info'));

            // ---------- Connections ----------
            document.getElementById('unlinkGoogle').addEventListener('click', () => showToast('Google disconnected', 'info'));
            document.getElementById('linkMicrosoft').addEventListener('click', () => showToast(
                'Microsoft connected (simulated)', 'success'));
            document.getElementById('linkFacebook').addEventListener('click', () => showToast('Facebook connected (simulated)',
                'success'));
            document.getElementById('enableICal').addEventListener('click', () => showToast(
                'iCal enabled — copy URL from integrations', 'info'));
            document.getElementById('manageOAuthBtn').addEventListener('click', () => showToast('Manage OAuth apps (simulated)',
                'info'));

            // ---------- Misc ----------
            document.getElementById('previewProfileBtn').addEventListener('click', () => showToast(
                'Opening public profile preview (simulated)', 'info'));
            document.getElementById('muteAllBtn').addEventListener('click', () => {
                ['notifyEmail', 'notifySMS', 'notifyPush', 'notifyInApp'].forEach(id => document.getElementById(id)
                    .checked = false);
                showToast('Non-essential notifications muted', 'info');
            });

            // Apply accessibility font-size toggle
            document.getElementById('fontSize').addEventListener('change', (e) => {
                const val = e.target.value;
                document.documentElement.style.fontSize = val === 'large' ? '18px' : val === 'xlarge' ? '20px' : '';
                showToast('Font size updated', 'info');
            });

            // Set some initial UI states
            if (toggle2fa.checked) twoFaDetails.style.display = 'block';
        </script>
        <script>
document.addEventListener("DOMContentLoaded", function () {

    $(document).on('submit', '#StudentProfile', function (e) {
        e.preventDefault();

        console.log("Form intercepted — JS working!");

        let formData = {
            first_name: $('#fullName').val(),
            last_name: $('#displayName').val(),
            bio: $('#bio').val(),
            education: $('#education').val(),
            interests: $('#interests').val(),
            email: $('#email').val() // Added email field back
        };

        fetch("{{ route('student.UserProfile.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name=\"csrf-token\"]')
                    .getAttribute("content")
            },
            body: JSON.stringify(formData)
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    showToast("Profile updated successfully ", "success");
                } else {
                    showToast("Update failed 😬", "danger");
                }
            })
            .catch((err) => {
                console.error(err);
                showToast("Server error occurred 😭", "danger");
            });
    });

    $(document).on('submit', '#AccountProfile', function (e) {
        e.preventDefault();
        let formData = {
            phone: $('#phone').val(),
            language: $('#language').val(),
            timezone: $('#timezone').val(),
        };
        fetch("{{ route('student.profile.updateAccount') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute("content")
            },
            body: JSON.stringify(formData)
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                showToast("Account settings updated successfully ", "success");
            } else {
                showToast("Update failed 😬", "danger");
            }
        })
        .catch((err) => {
            console.error(err);
            showToast("Server error occurred 😭", "danger");
        });
    });

    $(document).on('submit', '#SecurityProfile', function (e) {
        e.preventDefault();
        let formData = {
            current_password: $('#curPass').val(),
            password: $('#newPass').val(),
            password_confirmation: $('#confirmPass').val(),
        };
        fetch("{{ route('student.profile.updatePassword') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute("content")
            },
            body: JSON.stringify(formData)
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                showToast("Password updated successfully ", "success");
                $('#curPass').val('');
                $('#newPass').val('');
                $('#confirmPass').val('');
            } else {
                showToast("Password update failed", "danger");
            }
        })
        .catch((err) => {
            console.error(err);
            showToast("Something went wrong! Please try again.", "danger");
        });
    });

    $(document).on('submit', '#NotificationProfile', function (e) {
        e.preventDefault();
        let formData = {
            notify_email: $('#notifyEmail').is(':checked') ? 1 : 0,
            notify_sms: $('#notifySMS').is(':checked') ? 1 : 0,
            notify_push: $('#notifyPush').is(':checked') ? 1 : 0,
            notify_in_app: $('#notifyInApp').is(':checked') ? 1 : 0,
            evt_course_release: $('#evtCourseRelease').is(':checked') ? 1 : 0,
            evt_assignment: $('#evtAssignment').is(':checked') ? 1 : 0,
            evt_promos: $('#evtPromos').is(':checked') ? 1 : 0,
            evt_system: $('#evtSystem').is(':checked') ? 1 : 0,
        };
        fetch("{{ route('student.profile.updateNotifications') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute("content")
            },
            body: JSON.stringify(formData)
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                showToast("Notification settings updated successfully ", "success");
            } else {
                showToast("Update failed! Please try again.", "danger");
            }
        })
        .catch((err) => {
            console.error(err);
            showToast("Something went wrong! Please try again.", "danger");
        });
    });
});
</script>
    @endpush


</x-student-layout>
