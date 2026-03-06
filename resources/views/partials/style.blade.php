<link href="{{ asset('cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css') }}" rel="stylesheet" />
<link href="{{ asset('cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css') }}" rel="stylesheet" />
<link href="{{ asset('asset/css/styles.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
<link href="{{ asset('asset/css/dataTable.min.css') }}" rel="stylesheet" />
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

<link href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" />

<style type="text/css">
    #datatablesSimple_filter,
    #datatablesSimple_info,
    #datatablesSimple_paginate {
        display: none;
    }

    .navbar-custom {
        background: linear-gradient(40deg, #f3934f, #d54e14);
    }

    .navbar-custom-brand {
        background: #FFFF;
        color: #111;
    }

    .btn-red {
        background-color: #d54e14;
        color: #fff;
    }

    .btn-red:hover {
        background-color: #a10000;
        color: #fff;
    }

    footer {
        background-color: #003d82;
        color: #fff;
        padding: 15px;
        position: relative;
    }

    footer .highlight {
        color: #d54e14;
    }

    .admin-btn {
        position: absolute;
        bottom: 10px;
        right: 20px;
        background: #d54e14;
        padding: 8px 15px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        font-size: 14px;
    }

    .connect-btn {
        text-decoration: none;
        color: inherit;
        font-weight: bold;
        letter-spacing: 1px;
    }
</style>

<style>
    /* ── Page variables ────────────────────────────────────── */
    :root {
        --blue: #003d82;
        --orange: #d54e14;
        --green: #16a34a;
        --yellow: #ca8a04;
        --red: #dc2626;
        --surface: #f8fafc;
        --border: #e2e8f0;
        --shadow: 0 1px 6px rgba(0, 0, 0, .07);
    }

    /* ── Page header ───────────────────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, var(--blue) 0%, #1a5bb0 100%);
        border-radius: 5px;
        padding: 1.6rem 2rem;
        margin-bottom: 1.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .page-hero::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .06);
        pointer-events: none;
    }

    .page-hero .hero-title {
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -.3px;
    }

    .page-hero .hero-sub {
        font-size: .82rem;
        opacity: .75;
        margin-top: .25rem;
    }

    .page-hero .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 5px;
        padding: .22rem .75rem;
        font-size: .75rem;
        font-weight: 600;
    }

    /* ── Toolbar card ───────────────────────────────────────── */
    .toolbar-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 5px;
        padding: 1rem 1.25rem;
        box-shadow: var(--shadow);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
    }

    .toolbar-card .toolbar-label {
        font-size: .78rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .toolbar-card .form-select {
        border-radius: 5px;
        border: 1px solid var(--border);
        font-size: .85rem;
        padding: .42rem .85rem;
        box-shadow: none;
        min-width: 220px;
    }

    .toolbar-card .form-select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(0, 61, 130, .1);
    }

    /* ── Buttons ────────────────────────────────────────────── */
    .btn-primary-custom {
        background: var(--blue);
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: .42rem 1rem;
        font-size: .82rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: opacity .15s, transform .1s;
    }

    .btn-primary-custom:hover {
        opacity: .88;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-export {
        background: #fff;
        border: 1.5px solid var(--green);
        color: var(--green);
        border-radius: 5px;
        padding: .42rem 1rem;
        font-size: .82rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: background .15s, transform .1s;
    }

    .btn-export:hover {
        background: #f0fdf4;
        color: var(--green);
        transform: translateY(-1px);
    }

    .btn-print {
        background: #fff;
        border: 1.5px solid var(--blue);
        color: var(--blue);
        border-radius: 5px;
        padding: .42rem 1rem;
        font-size: .82rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: background .15s, transform .1s;
    }

    .btn-print:hover {
        background: #eff6ff;
        color: var(--blue);
        transform: translateY(-1px);
    }

    /* ── Table card ─────────────────────────────────────────── */
    .table-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 5px;
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .table-card-header {
        padding: 1rem 1.4rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: .65rem;
        background: var(--surface);
    }

    .table-card-header .tch-icon {
        width: 34px;
        height: 34px;
        border-radius: 5px;
        background: linear-gradient(135deg, var(--blue), #1a5bb0);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: .9rem;
        flex-shrink: 0;
    }

    .table-card-header .tch-title {
        font-size: .95rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .table-card-header .tch-sub {
        font-size: .74rem;
        color: #64748b;
    }

    /* ── Table styles ───────────────────────────────────────── */
    .table-exec {
        font-size: .78rem;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-exec thead tr:first-child th {
        background: linear-gradient(135deg, var(--blue) 0%, #1a5bb0 100%);
        color: #fff;
        font-weight: 600;
        padding: .65rem .5rem;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .table-exec thead tr:last-child th {
        background: #eef2fb;
        color: #334155;
        font-weight: 600;
        font-size: .72rem;
        padding: .5rem .4rem;
        position: sticky;
        top: 42px;
        z-index: 2;
    }

    .table-exec th.month-head {
        border-right: 2px solid rgba(255, 255, 255, .25) !important;
    }

    .table-exec td {
        padding: .38rem .45rem;
        border-color: var(--border);
        vertical-align: middle;
    }

    .table-exec tbody tr:hover td {
        background: #f8fafc;
    }

    .table-exec .col-code {
        font-weight: 700;
        color: var(--blue);
        text-align: center;
        white-space: nowrap;
    }

    .table-exec .col-libelle {
        font-weight: 600;
        color: #1e293b;
    }

    .table-exec td.border-month {
        border-right: 2px solid #cbd5e1 !important;
    }

    .table-exec td.prev {
        color: #1d4ed8;
    }

    .table-exec td.real {
        color: #15803d;
        font-weight: 600;
    }

    .table-exec td.ecart-pos {
        color: var(--green);
    }

    .table-exec td.ecart-neg {
        color: var(--red);
    }

    /* ── Standard table (non-wide) ──────────────────────── */
    .table-std thead th {
        background: linear-gradient(135deg, var(--blue) 0%, #1a5bb0 100%);
        color: #fff; font-weight: 600; font-size: .78rem;
        padding: .6rem .75rem; border: none; white-space: nowrap;
    }
    .table-std tbody td { font-size: .82rem; vertical-align: middle; border-color: var(--border); }
    .table-std tbody tr:hover td { background: #f8fafc; }

    /* ── Form card ───────────────────────────────────────── */
    .form-card {
        background: #fff; border: 1px solid var(--border);
        border-radius: 5px; box-shadow: var(--shadow);
        margin-bottom: 1.5rem; overflow: hidden;
    }
    .form-card-header {
        padding: .9rem 1.4rem; border-bottom: 1px solid var(--border);
        background: var(--surface); display: flex; align-items: center; gap: .65rem;
    }
    .form-card-header .fch-icon {
        width: 32px; height: 32px; border-radius: 5px;
        background: linear-gradient(135deg, var(--blue), #1a5bb0);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .85rem; flex-shrink: 0;
    }
    .form-card-header .fch-title { font-size: .9rem; font-weight: 700; color: #0f172a; margin: 0; }
    .form-card-body { padding: 1.4rem; }

    /* form controls inside form-card */
    .form-card .form-label { font-size: .8rem; font-weight: 600; color: #475569; margin-bottom: .3rem; }
    .form-card .form-control, .form-card .form-select {
        border-radius: 5px; border: 1px solid var(--border);
        font-size: .85rem; padding: .42rem .75rem;
        transition: border-color .15s, box-shadow .15s;
    }
    .form-card .form-control:focus, .form-card .form-select:focus {
        border-color: var(--blue); box-shadow: 0 0 0 3px rgba(0,61,130,.1); outline: none;
    }
    .form-card .form-control:disabled, .form-card .form-select:disabled {
        background: #f8fafc; color: #94a3b8;
    }

    /* ── Button variants ─────────────────────────────────── */
    .btn-warning-custom {
        background: #fff; border: 1.5px solid #d97706; color: #d97706;
        border-radius: 5px; padding: .32rem .75rem; font-size: .78rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
        transition: background .15s, transform .1s;
    }
    .btn-warning-custom:hover { background: #fffbeb; color: #d97706; transform: translateY(-1px); }

    .btn-danger-custom {
        background: #fff; border: 1.5px solid var(--red); color: var(--red);
        border-radius: 5px; padding: .32rem .75rem; font-size: .78rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
        transition: background .15s, transform .1s;
    }
    .btn-danger-custom:hover { background: #fef2f2; color: var(--red); transform: translateY(-1px); }

    .btn-secondary-custom {
        background: #fff; border: 1.5px solid #64748b; color: #64748b;
        border-radius: 5px; padding: .42rem 1rem; font-size: .82rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: .4rem;
        transition: background .15s, transform .1s;
    }
    .btn-secondary-custom:hover { background: #f8fafc; color: #475569; transform: translateY(-1px); }

    .btn-info-custom {
        background: #fff; border: 1.5px solid #0891b2; color: #0891b2;
        border-radius: 5px; padding: .32rem .75rem; font-size: .78rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
        transition: background .15s, transform .1s;
    }
    .btn-info-custom:hover { background: #ecfeff; color: #0891b2; transform: translateY(-1px); }

    /* ── Action group (in table rows) ───────────────────── */
    .action-group { display: flex; align-items: center; gap: .4rem; flex-wrap: nowrap; }

    /* ── Status badge ────────────────────────────────────── */
    .status-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .2rem .65rem; border-radius: 5px; font-size: .72rem; font-weight: 600; white-space: nowrap;
    }
    .status-badge.blue   { background: #eff6ff; color: var(--blue); }
    .status-badge.green  { background: #f0fdf4; color: var(--green); }
    .status-badge.orange { background: #fff7ed; color: var(--orange); }
    .status-badge.red    { background: #fef2f2; color: var(--red); }
    .status-badge.gray   { background: #f1f5f9; color: #64748b; }
    .status-badge.teal   { background: #ecfeff; color: #0891b2; }

    /* ── Info banner ─────────────────────────────────────── */
    .info-banner {
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 5px;
        padding: .75rem 1rem; font-size: .82rem; color: #1e40af;
        display: flex; align-items: flex-start; gap: .65rem; margin-bottom: 1rem;
    }

    /* ── Error list ──────────────────────────────────────── */
    .error-banner {
        background: #fef2f2; border: 1px solid #fecaca; border-radius: 5px;
        padding: .75rem 1rem; font-size: .82rem; color: #b91c1c; margin-bottom: 1rem;
    }
    .error-banner ul { margin: .25rem 0 0 1rem; }

    /* ── Modal header override ───────────────────────────── */
    .modal-header-blue {
        background: linear-gradient(135deg, var(--blue), #1a5bb0);
        color: #fff; border-bottom: none;
    }
    .modal-header-blue .btn-close { filter: invert(1); }
    .modal-header-blue .modal-title { font-size: .95rem; font-weight: 700; }
</style>

<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<script src="{{ asset('js/js_jquery/jquery.min.js') }}"></script>
<script src="{{ asset('js/js_jquery/jquery.easy.min.js') }}"></script>

<script data-search-pseudo-elements="" defer=""
    src="{{ asset('cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js') }}" crossorigin="anonymous">
</script>
<script src="{{ asset('cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js') }}"
    crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
