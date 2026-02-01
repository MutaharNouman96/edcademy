<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Add Internal User</h3>
            <div class="text-muted">Creates a user with legacy role <code>admin</code> and optional Spatie roles/permissions.</div>
        </div>
        <a href="{{ route('admin.inapp-users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inapp-users.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">First name</label>
                    <input name="first_name" value="{{ old('first_name') }}" class="form-control" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last name</label>
                    <input name="last_name" value="{{ old('last_name') }}" class="form-control" />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirm password</label>
                    <input type="password" name="password_confirmation" class="form-control" required />
                </div>

                <div class="col-12">
                    <hr />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Roles (Spatie)</label>
                    <select name="roles[]" class="form-select" multiple size="8">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected(in_array($role->name, old('roles', []), true))>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Hold Cmd/Ctrl to select multiple.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Direct permissions (Spatie)</label>
                    <select name="permissions[]" class="form-select" multiple size="8">
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->name }}"
                                @selected(in_array($permission->name, old('permissions', []), true))>
                                {{ $permission->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Direct permissions are added in addition to role permissions.</div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle me-1"></i> Create user
                    </button>
                    <a href="{{ route('admin.inapp-users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

