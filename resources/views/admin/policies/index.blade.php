<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Policies</h3>
            <div class="text-muted">Create and manage policy pages (privacy, terms, refunds, etc.).</div>
        </div>
        <a href="{{ route('admin.policies.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Policy
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Policy name or slug" />
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button class="btn btn-outline-primary mt-4" type="submit">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.policies.index') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 90px;">ID</th>
                            <th>Name</th>
                            <th style="width: 260px;">Slug</th>
                            <th>Status</th>
                            <th style="width: 220px;">Updated</th>
                           
                            <th style="width: 220px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($policies as $policy)
                            <tr>
                                <td>{{ $policy->id }}</td>
                                <td class="fw-semibold">{{ $policy->name }}</td>
                                <td><code>{{ $policy->slug }}</code></td>
                                <td>{!!     $policy->deleted_at ? '<span class="badge bg-danger">Inactive</span>' : '<span class="badge bg-success">Active</span>' !!}</td>
                                <td>{{ optional($policy->updated_at)->format('M d, Y • h:i A') }}</td>
                               
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.policies.edit', $policy) }}">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    @if (!$policy->deleted_at)
                                    <form class="d-inline" method="POST" action="{{ route('admin.policies.destroy', $policy) }}"
                                        onsubmit="return confirm('Delete this policy?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                    @else
                                    <form class="d-inline" method="POST" action="{{ route('admin.policies.restore', $policy) }}"
                                        onsubmit="return confirm('Restore this policy?');">
                                        @csrf
                                        @method('POST')
                                        <button class="btn btn-sm btn-outline-success" type="submit">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-4 text-muted">No policies found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($policies->hasPages())
            <div class="card-footer">
                {{ $policies->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

