<style>
    :root {
        --primary: #0A2E5C;
        --sidebar-bg: #0A2E5C;
        --sidebar-hover: #1A3E6D;
        --sidebar-active: #E68A00;
        --bg-light: #F4F7F6;
        --text-dark: #333;
        --text-gray: #666;
        --text-light: #A0B2C6;
        --white: #fff;
        --border: #E2E8F0;
        --purple: #6366F1;
        --blue: #3B82F6;
        --green: #10B981;
        --red: #EF4444;
        --excel-header: #217346;
        --excel-header-text: #fff;
        --excel-selected: #cce5ff;
        --excel-editing: #fff8d6;
        --excel-saved: #d4edda;
        --excel-error: #f8d7da;
    }

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

    body { background: var(--bg-light); color: var(--text-dark); height: 100vh; overflow: hidden; }

    .app-container { display: flex; height: 100vh; }

    /* Sidebar */
    .sidebar { width: 260px; background: var(--sidebar-bg); color: var(--white); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-brand { padding: 20px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid rgba(255,255,255,.1); }
    .logo-icon { width: 32px; height: 32px; background: var(--white); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--sidebar-bg); font-weight: 700; font-size: 18px; }
    .brand-text h3 { font-size: 16px; font-weight: 700; }
    .brand-text p { font-size: 11px; color: var(--text-light); }
    .sidebar-menu { list-style: none; padding: 20px 0; overflow-y: auto; flex-grow: 1; }
    .menu-header { padding: 10px 20px; font-size: 11px; font-weight: 600; color: var(--text-light); letter-spacing: 1px; margin-top: 10px; }
    .menu-item { margin: 4px 12px; }
    .menu-item a, .menu-item button { display: flex; align-items: center; padding: 12px 16px; color: var(--white); text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: .2s; width: 100%; background: transparent; border: none; cursor: pointer; }
    .menu-item a:hover, .menu-item button:hover { background: var(--sidebar-hover); }
    .menu-item.active a { background: rgba(255,255,255,.1); border-left: 4px solid var(--sidebar-active); border-radius: 0 8px 8px 0; margin-left: -12px; padding-left: 24px; }
    .menu-item i { width: 20px; margin-right: 12px; text-align: center; font-style: normal; }

    /* Main */
    .main-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

    /* Topbar */
    .topbar { height: 70px; background: var(--white); display: flex; align-items: center; justify-content: space-between; padding: 0 30px; box-shadow: 0 1px 3px rgba(0,0,0,.05); z-index: 10; }
    .topbar-left { display: flex; align-items: center; gap: 20px; }
    .topbar-left h2 { font-size: 18px; font-weight: 600; }
    .topbar-right { display: flex; align-items: center; gap: 30px; }
    .academic-year { text-align: right; }
    .academic-year span { font-size: 11px; color: var(--text-gray); display: block; }
    .academic-year strong { font-size: 14px; color: var(--primary); }
    .user-profile { display: flex; align-items: center; gap: 12px; border-left: 1px solid var(--border); padding-left: 20px; }
    .user-info { text-align: right; }
    .user-info strong { font-size: 14px; display: block; }
    .user-info span { font-size: 11px; color: var(--text-gray); }
    .avatar { width: 40px; height: 40px; background: var(--primary); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; }

    /* Content */
    .content-wrapper { flex: 1; padding: 24px 30px; overflow-y: auto; background: var(--white); }

    /* Page header */
    .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px; }
    .page-title h1 { font-size: 22px; color: var(--primary); margin-bottom: 4px; }
    .page-title p { color: var(--text-gray); font-size: 13px; }
    .action-buttons { display: flex; gap: 10px; align-items: center; }

    /* Buttons */
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; transition: .2s; }
    .btn-search { background: var(--primary); color: var(--white); }
    .btn-search:hover { background: #15407D; }
    .btn-reset { background: #F3F4F6; color: var(--text-gray); }

    /* Tabs */
    .tabs { display: flex; border-bottom: 1px solid var(--border); margin-bottom: 20px; gap: 28px; }
    .tab-item { padding: 10px 0; font-size: 14px; color: var(--text-gray); font-weight: 500; text-decoration: none; position: relative; }
    .tab-item:hover { color: var(--purple); }
    .tab-item.active { color: var(--purple); }
    .tab-item.active::after { content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background: var(--purple); }

    /* Filter */
    .filter-bar { background: #F8FAFC; border: 1px solid var(--border); border-radius: 10px; padding: 14px 18px; margin-bottom: 16px; }
    .search-input { flex: 1; min-width: 240px; padding: 8px 12px; border: 1px solid var(--border); border-radius: 7px; font-size: 13px; outline: none; }
    .search-input:focus { border-color: var(--blue); }
    .filter-select { padding: 8px 12px; border: 1px solid var(--border); border-radius: 7px; font-size: 13px; background: var(--white); cursor: pointer; }
    .filter-info { margin-top: 8px; font-size: 13px; color: var(--text-gray); }

    /* Alerts */
    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; display: flex; align-items: flex-start; gap: 10px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #34d399; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
    .alert-icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert-content { flex: 1; white-space: pre-line; }

    /* Excel Grid */
    .excel-grid-wrapper { border: 1px solid #c0c0c0; border-radius: 6px; overflow: auto; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .excel-hint { background: #f0f4f8; border-bottom: 1px solid var(--border); padding: 7px 14px; font-size: 12px; color: var(--text-gray); }

    .excel-grid { width: 100%; border-collapse: collapse; font-size: 13px; }
    .excel-grid thead tr { background: var(--excel-header); color: var(--excel-header-text); position: sticky; top: 0; z-index: 5; }
    .excel-grid th { padding: 9px 12px; text-align: left; font-weight: 600; white-space: nowrap; border-right: 1px solid rgba(255,255,255,.2); user-select: none; }
    .excel-grid td { padding: 0; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; height: 34px; vertical-align: middle; white-space: nowrap; }

    .excel-grid td.row-num { background: #f5f5f5; color: #888; text-align: center; font-size: 11px; padding: 0 8px; width: 40px; border-right: 2px solid #c0c0c0; cursor: default; }
    .excel-grid th.row-num { width: 40px; background: #1a5c38; }

    .excel-grid td.editable { cursor: cell; padding: 0 8px; }
    .excel-grid td.editable:hover { background: #f0f7ff; }
    .excel-grid td.editable.selected { background: var(--excel-selected); outline: 2px solid var(--blue); outline-offset: -2px; }
    .excel-grid td.editable.editing { background: var(--excel-editing); padding: 0; }
    .excel-grid td.editable.saved { background: var(--excel-saved); transition: background .8s; }
    .excel-grid td.editable.error { background: var(--excel-error); }
    .excel-grid td.readonly { padding: 0 8px; color: var(--text-gray); background: #fafafa; cursor: default; }

    .excel-grid td input, .excel-grid td select {
        width: 100%; height: 100%; min-height: 34px;
        padding: 4px 8px; border: none; outline: none;
        font-size: 13px; font-family: inherit;
        background: transparent;
    }

    .excel-grid tr:hover td { background-color: #f5fbff; }
    .excel-grid tr:hover td.row-num { background: #e8e8e8; }
    .excel-grid tr:hover td.editing { background: var(--excel-editing); }
    .excel-grid tr:hover td.readonly { background: #f5f5f5; }

    .col-action { width: 52px !important; text-align: center !important; }
    .btn-delete-row { background: none; border: none; cursor: pointer; font-size: 15px; opacity: .5; transition: .15s; padding: 6px; }
    .btn-delete-row:hover { opacity: 1; transform: scale(1.2); }

    .empty-row { text-align: center; padding: 40px !important; color: var(--text-gray); }

    /* Save indicator */
    .save-indicator { position: fixed; bottom: 20px; right: 20px; background: #333; color: #fff; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 500; display: none; z-index: 1000; gap: 8px; align-items: center; }
    .save-indicator.visible { display: flex; animation: fadeIn .2s; }
    .save-indicator.success { background: #217346; }
    .save-indicator.error { background: #c0392b; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

    /* Pagination */
    .pagination-container { padding: 16px 0 0; }
    .pagination-container nav > div:first-child { display: none !important; }
    .pagination-container nav > div:last-child { display: flex; align-items: center; justify-content: space-between; }
    .pagination-container p { font-size: 13px; color: var(--text-gray); }
    .pagination-container .relative.z-0.inline-flex { display: flex; border: 1px solid var(--border); border-radius: 6px; overflow: hidden; }
    .pagination-container .relative.z-0.inline-flex a,
    .pagination-container .relative.z-0.inline-flex span[aria-disabled="true"] > span,
    .pagination-container .relative.z-0.inline-flex span[aria-current="page"] > span { display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 10px; font-size: 13px; color: var(--text-gray); text-decoration: none; border-right: 1px solid var(--border); background: #fff; transition: .15s; }
    .pagination-container .relative.z-0.inline-flex > :last-child, .pagination-container .relative.z-0.inline-flex > :last-child > span { border-right: none; }
    .pagination-container .relative.z-0.inline-flex a:hover { background: #f8fafc; color: var(--primary); }
    .pagination-container .relative.z-0.inline-flex span[aria-current="page"] > span { background: var(--purple); color: #fff; font-weight: 600; }
    .pagination-container .relative.z-0.inline-flex span[aria-disabled="true"] > span { color: var(--text-light); background: #f8fafc; }
    .pagination-container svg { width: 16px; height: 16px; }
</style>
