<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Roles & Permissions</h3>
            <div class="text-muted">Manage Spatie roles, permissions, and role-permission mappings.</div>
        </div>
        <a href="{{ route('admin.inapp-users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-people me-1"></i> Internal Users
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <div class="fw-semibold">Create Role</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.roles.store') }}" class="row g-2">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Role name</label>
                            <input name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. support, finance" required />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Permissions</label>
                            <select name="permissions[]" class="form-select select2" multiple size="10">
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->name }}"
                                        @selected(in_array($permission->name, old('permissions', []), true))>
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- <div class="form-text">Hold Cmd/Ctrl to select multiple.</div> --}}
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-plus-circle me-1"></i> Create role
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <div class="fw-semibold">Roles</div>
                    <div class="text-muted small">{{ $roles->count() }} total</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    <th style="width: 200px;" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    @php($collapseId = 'role-edit-' . $role->id)
                                    <tr>
                                        <td class="fw-semibold">{{ $role->name }}</td>
                                        <td>
                                            @if ($role->permissions->isEmpty())
                                                <span class="text-muted">—</span>
                                            @else
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach ($role->permissions->pluck('name')->take(8) as $p)
                                                        <span class="badge bg-secondary-subtle text-secondary border">{{ $p }}</span>
                                                    @endforeach
                                                    @if ($role->permissions->count() > 8)
                                                        <span class="badge bg-light text-muted border">+{{ $role->permissions->count() - 8 }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}">
                                                <i class="bi bi-pencil me-1"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Delete role {{ $role->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                                    <i class="bi bi-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="{{ $collapseId }}">
                                        <td colspan="3" class="bg-light">
                                            <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="row g-2">
                                                @csrf
                                                @method('PUT')
                                                <div class="col-md-4">
                                                    <label class="form-label">Role name</label>
                                                    <input name="name" class="form-control" value="{{ $role->name }}" required />
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Permissions</label>
                                                    <select name="permissions[]" class="form-select" multiple size="8">
                                                        @foreach ($permissions as $permission)
                                                            <option value="{{ $permission->name }}"
                                                                @selected($role->permissions->contains('name', $permission->name))>
                                                                {{ $permission->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <button class="btn btn-sm btn-primary" type="submit">
                                                        <i class="bi bi-check2-circle me-1"></i> Save role
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center p-4 text-muted">No roles yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <div class="fw-semibold">Create Permission</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.permissions.store') }}" class="row g-2">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Permission name</label>
                            <input name="name" class="form-control" placeholder="e.g. manage payouts" required />
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-plus-circle me-1"></i> Create permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <div class="fw-semibold">Permissions</div>
                    <div class="text-muted small">{{ $permissions->count() }} total</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Permission</th>
                                    <th style="width: 120px;" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->name }}</td>
                                        <td class="text-end">
                                            <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST"
                                                onsubmit="return confirm('Delete permission {{ $permission->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                                    <i class="bi bi-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center p-4 text-muted">No permissions yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

