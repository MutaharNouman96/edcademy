<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Admin Settings</h3>
            <div class="text-muted">Manage app settings and your admin account settings.</div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-app" type="button" role="tab">
                <i class="bi bi-sliders me-1"></i> App Settings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-account" type="button" role="tab">
                <i class="bi bi-person-gear me-1"></i> My Account
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-app" role="tabpanel">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.app.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="accordion" id="settingsAccordion">
                            @php($i = 0)
                            @foreach ($settings as $group => $items)
                                @php($i++)
                                @php($collapseId = 'collapse-' . $i)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-{{ $i }}">
                                        <button class="accordion-button @if ($i !== 1) collapsed @endif" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                            {{ strtoupper($group) }}
                                        </button>
                                    </h2>
                                    <div id="{{ $collapseId }}" class="accordion-collapse collapse @if ($i === 1) show @endif"
                                        data-bs-parent="#settingsAccordion">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width: 260px;">Key</th>
                                                            <th>Value</th>
                                                            <th style="width: 110px;">Type</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items as $setting)
                                                            <tr>
                                                                <td>
                                                                    <div class="fw-semibold">{{ $setting->key }}</div>
                                                                    @if ($setting->description)
                                                                        <div class="text-muted small">{{ $setting->description }}
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @php($name = "settings[{$setting->key}]")
                                                                    @php($oldKey = "settings.{$setting->key}")

                                                                    @if ($setting->type === 'bool')
                                                                        <input type="hidden" name="{{ $name }}" value="0" />
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="{{ $name }}" value="1"
                                                                                @checked(old($oldKey, (string) $setting->value) == '1')>
                                                                        </div>
                                                                    @elseif ($setting->type === 'int')
                                                                        <input type="number" step="1" class="form-control"
                                                                            name="{{ $name }}"
                                                                            value="{{ old($oldKey, $setting->value) }}" />
                                                                    @elseif ($setting->type === 'float')
                                                                        <input type="number" step="0.01" class="form-control"
                                                                            name="{{ $name }}"
                                                                            value="{{ old($oldKey, $setting->value) }}" />
                                                                    @elseif ($setting->type === 'json')
                                                                        @php($decoded = json_decode((string) $setting->value, true))
                                                                        @php($pretty = is_array($decoded) ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : (string) $setting->value)
                                                                        <textarea class="form-control" rows="4" name="{{ $name }}"
                                                                            placeholder="JSON">{{ old($oldKey, $pretty) }}</textarea>
                                                                    @else
                                                                        <input type="text" class="form-control" name="{{ $name }}"
                                                                            value="{{ old($oldKey, $setting->value) }}" />
                                                                    @endif

                                                                    @error($oldKey)
                                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-light text-muted border">
                                                                        {{ $setting->type }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-save2 me-1"></i> Save App Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-account" role="tabpanel">
            <div class="row g-3">
                <div class="col-lg-7">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <div class="fw-semibold">Profile</div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.settings.account.profile') }}" class="row g-3">
                                @csrf
                                @method('PUT')

                                <div class="col-md-6">
                                    <label class="form-label">First name</label>
                                    <input name="first_name" class="form-control"
                                        value="{{ old('first_name', $user->first_name) }}" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last name</label>
                                    <input name="last_name" class="form-control"
                                        value="{{ old('last_name', $user->last_name) }}" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <input name="username" class="form-control"
                                        value="{{ old('username', $user->username) }}" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required />
                                </div>
                                <div class="col-12">
                                    <div class="text-muted small">
                                        Role: <span class="badge bg-primary-subtle text-primary border">{{ $user->role }}</span>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-save2 me-1"></i> Save Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <div class="fw-semibold">Security</div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.settings.account.password') }}" class="row g-3">
                                @csrf
                                @method('PUT')

                                <div class="col-12">
                                    <label class="form-label">Current password</label>
                                    <input type="password" name="current_password" class="form-control" required />
                                    @error('current_password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">New password</label>
                                    <input type="password" name="password" class="form-control" required />
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Confirm new password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required />
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-shield-lock me-1"></i> Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

