<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Internal App Users</h3>
            <div class="text-muted">Manage internal users, roles, and direct permissions.</div>
        </div>
        <a href="{{ route('admin.inapp-users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add User
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Name or email" />
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button class="btn btn-outline-primary mt-4" type="submit">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                    <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.inapp-users.index') }}">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>User</th>
                            <th style="width: 220px;">Roles</th>
                            <th style="width: 220px;">Direct Permissions</th>
                            <th style="width: 170px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $user->full_name ?: '—' }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </td>
                                <td>
                                    @php($roleNames = $user->roles->pluck('name')->values())
                                    @if ($roleNames->isEmpty())
                                        <span class="text-muted">—</span>
                                    @else
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($roleNames as $name)
                                                <span class="badge bg-primary-subtle text-primary border">{{ $name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @php($permNames = $user->permissions->pluck('name')->values())
                                    @if ($permNames->isEmpty())
                                        <span class="text-muted">—</span>
                                    @else
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($permNames->take(6) as $name)
                                                <span class="badge bg-secondary-subtle text-secondary border">{{ $name }}</span>
                                            @endforeach
                                            @if ($permNames->count() > 6)
                                                <span class="badge bg-light text-muted border">+{{ $permNames->count() - 6 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.inapp-users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.inapp-users.destroy', $user) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this internal user?');">
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
                                <td colspan="5" class="text-center p-4 text-muted">No internal users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

