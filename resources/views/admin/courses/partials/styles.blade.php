<style>
    :root {
        --brand: #0b3c77;
        --brand-700: #093362;
        --ink: #0f172a;
        --muted: #6b7280;
        --card-shadow: 0 10px 30px rgba(11, 60, 119, 0.08);
        --soft: #f6f8fb;
        --good: #16a34a;
        --warn: #d97706;
        --bad: #dc2626;
    }

    .course-hero {
        border-radius: 18px;
        padding: 1.25rem;
        background: linear-gradient(130deg, rgba(11, 60, 119, 0.96), rgba(30, 108, 190, 0.94));
        color: #fff;
        box-shadow: 0 18px 38px rgba(11, 60, 119, 0.22);
    }

    .course-hero .badge {
        background: rgba(255, 255, 255, 0.16) !important;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .course-subnav .nav-link {
        border-radius: 999px;
        font-weight: 600;
        color: var(--brand);
        background: #fff;
        border: 1px solid rgba(11, 60, 119, 0.12);
        box-shadow: var(--card-shadow);
    }

    .course-subnav .nav-link.active {
        background: var(--brand);
        color: #fff;
        border-color: var(--brand);
    }

    .kpi-card {
        border: 0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: var(--card-shadow);
    }

    .kpi-value {
        font-size: clamp(1.35rem, 2vw, 1.75rem);
        font-weight: 800;
        color: var(--ink);
    }

    .kpi-label {
        color: var(--muted);
        font-weight: 600;
        font-size: 0.85rem;
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

    .section-title {
        color: var(--ink);
        font-weight: 800;
    }

    .course-thumb {
        width: 150px;
        height: 110px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-row i {
        color: var(--brand);
    }

    .stat-box {
        background: var(--soft);
        border-radius: 0.75rem;
        padding: 0.75rem 0.5rem;
    }

    .breadcrumb-item a {
        color: var(--brand);
        text-decoration: none;
    }

    .status-select {
        font-weight: 600;
    }

    .status-select.status-published { color: var(--good); }
    .status-select.status-draft { color: var(--warn); }
    .status-select.status-scheduled { color: var(--brand); }

    .btn-brand {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }

    .btn-brand:hover {
        background: var(--brand-700);
        border-color: var(--brand-700);
        color: #fff;
    }

    .modern-table thead th {
        color: var(--muted);
        font-weight: 700;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.84rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }

    .modern-table > tbody > tr > td {
        vertical-align: middle;
        border-color: #eff3f8;
    }

    .mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.84rem;
    }

    .summary-link-card {
        display: block;
        text-decoration: none;
        color: inherit;
        border: 1px solid #e8edf5;
        border-radius: 14px;
        padding: 1rem;
        background: #fbfcff;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .summary-link-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow);
        color: inherit;
    }
</style>
