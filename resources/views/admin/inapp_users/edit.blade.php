<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Edit Internal User</h3>
            <div class="text-muted">{{ $user->email }}</div>
        </div>
        <a href="{{ route('admin.inapp-users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inapp-users.update', $user) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">First name</label>
                    <input name="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last name</label>
                    <input name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control" />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label">New password (optional)</label>
                    <input type="password" name="password" class="form-control" />
                    <div class="form-text">Leave blank to keep the current password.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirm new password</label>
                    <input type="password" name="password_confirmation" class="form-control" />
                </div>

                <div class="col-12">
                    <hr />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Roles (Spatie)</label>
                    <select name="roles[]" class="form-select" multiple size="10">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                @selected(in_array($role->name, old('roles', $userRoleNames), true))>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Direct permissions (Spatie)</label>
                    <select name="permissions[]" class="form-select" multiple size="10">
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->name }}"
                                @selected(in_array($permission->name, old('permissions', $userPermissionNames), true))>
                                {{ $permission->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle me-1"></i> Save changes
                    </button>
                    <a href="{{ route('admin.inapp-users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

