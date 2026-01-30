
<x-admin-layout>

    <h4 class="mb-3">Manage Students</h4>

    <form method="GET" action="{{ route('admin.manage.students') }}" class="filter-bar p-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Name or email">
            </div>
            <div class="col-md-4 text-md-end">
                <button class="btn btn-brand">Apply Filters</button>
            </div>
        </div>
    </form>

    <div class="kpi-card p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
<tr>
    <th>#</th>
    <th>Student</th>
    <th>Email</th>
    <th>Joined</th>
    <th class="text-end">Actions</th>
</tr>
                </thead>
                <tbody>
@forelse($students as $index => $student)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $student->full_name }}</td>
        <td>{{ $student->email }}</td>
        <td>{{ $student->created_at->format('M d, Y') }}</td>
        <td class="text-end">
            <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye"></i> View
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted">No students found</td>
    </tr>
@endforelse
</tbody>
            </table>
    </div>
    </div>

        @push('styles')
            <style>
                :root {
                    --brand: #0b3c77;
                    /* dark blue */
                    --brand-700: #093362;
                    --brand-600: #0c4b94;
                    --ink: #0f172a;
                    --muted: #6b7280;
                    --card-shadow: 0 10px 30px rgba(11, 60, 119, 0.08);
                    --soft: #f6f8fb;
                    --good: #16a34a;
                    --warn: #d97706;
                    --bad: #dc2626;
                }

                body {
                    background: var(--soft);
                }

                .navbar {
                    background: var(--brand);
                }

                .navbar .navbar-brand,
                .navbar .nav-link,
                .navbar .form-control::placeholder {
                    color: #fff;
                }

                .navbar .nav-link {
                    opacity: 0.9;
                }

                .navbar .nav-link:hover {
                    opacity: 1;
                }

                .brand-badge {
                    background: #fff;
                    color: var(--brand);
                    font-weight: 700;
                    padding: 0.25rem 0.5rem;
                    border-radius: 0.5rem;
                }

                .kpi-card {
                    border: 0;
                    border-radius: 1rem;
                    background: #fff;
                    box-shadow: var(--card-shadow);
                }

                .kpi-icon {
                    width: 48px;
                    height: 48px;
                    border-radius: 12px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    color: #fff;
                    background: var(--brand);
                    box-shadow: 0 6px 16px rgba(11, 60, 119, 0.25);
                }

                .kpi-value {
                    font-size: 1.75rem;
                    font-weight: 800;
                    color: var(--ink);
                }

                .kpi-label {
                    color: var(--muted);
                    font-weight: 600;
                }

                .delta {
                    font-size: 0.85rem;
                    font-weight: 600;
                }

                .delta.up {
                    color: var(--good);
                }

                .delta.down {
                    color: var(--bad);
                }

                .chip {
                    border-radius: 9999px;
                    padding: 0.25rem 0.6rem;
                    font-size: 0.75rem;
                    font-weight: 600;
                }

                .chip.soft {
                    background: rgba(11, 60, 119, 0.1);
                    color: var(--brand);
                }

                .section-title {
                    color: var(--ink);
                    font-weight: 800;
                }

                .table thead th {
                    color: var(--muted);
                    font-weight: 700;
                    border-bottom: 1px solid #e5e7eb;
                }

                .table>tbody>tr>td {
                    vertical-align: middle;
                }

                .btn-brand {
                    background: var(--brand);
                    border-color: var(--brand);
                    color: #fff;
                }

                .btn-brand:hover {
                    background: var(--brand-700);
                    border-color: var(--brand-700);
                }

                .filter-bar {
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 1rem;
                    box-shadow: var(--card-shadow);
                }

                .progress.brand .progress-bar {
                    background: var(--brand);
                }
            </style>
        @endpush
    </x-admin-layout>
