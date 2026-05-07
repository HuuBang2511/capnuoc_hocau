<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Trung Tâm Điều Hành Hạ Tầng Cấp Nước';

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js');
?>

<style>
    :root {
        --bg-body: #f4f6f9;
        --card-bg: #ffffff;
        --primary-color: #3699ff;
        --success-color: #1bc5bd;
        --warning-color: #ffa800;
        --danger-color:  #f64e60;
        --info-color:    #8950fc;
        --dark-color:    #181c32;
        --text-muted:    #b5b5c3;

        /* Sản lượng theme */
        --sl-dark:   #0f1629;
        --sl-card:   #161e35;
        --sl-border: rgba(54,153,255,.18);
        --sl-blue:   #3699ff;
        --sl-cyan:   #00d4ff;
        --sl-green:  #1bc5bd;
        --sl-amber:  #ffa800;
        --sl-rose:   #f64e60;
    }

    body { background-color:var(--bg-body); font-family:'Segoe UI',sans-serif; }

    /* ── KPI CARDS ─────────────────────────────────────────────── */
    .kpi-row { display:grid; grid-template-columns:repeat(5,1fr); gap:1.5rem; margin-bottom:1.5rem; }
    .kpi-card {
        background:var(--card-bg); border-radius:12px; padding:1.25rem;
        box-shadow:0 0 20px 0 rgba(76,87,125,.02); transition:all .3s ease;
        text-decoration:none; color:inherit; position:relative; overflow:hidden;
        border-bottom:4px solid transparent; display:flex; flex-direction:column;
        justify-content:space-between; height:100%;
    }
    .kpi-card:hover { transform:translateY(-5px); box-shadow:0 10px 30px 0 rgba(76,87,125,.1); }
    .border-blue   { border-bottom-color:var(--primary-color); }
    .border-green  { border-bottom-color:var(--success-color); }
    .border-orange { border-bottom-color:var(--warning-color); }
    .border-red    { border-bottom-color:var(--danger-color); }
    .border-purple { border-bottom-color:var(--info-color); }
    .kpi-icon-wrapper { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; }
    .kpi-icon { width:45px; height:45px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
    .bg-light-blue   { background:#e1f0ff; color:var(--primary-color); }
    .bg-light-green  { background:#c9f7f5; color:var(--success-color); }
    .bg-light-orange { background:#fff4de; color:var(--warning-color); }
    .bg-light-red    { background:#ffe2e5; color:var(--danger-color); }
    .bg-light-purple { background:#eee5ff; color:var(--info-color); }
    .kpi-value { font-size:1.8rem; font-weight:800; color:var(--dark-color); line-height:1.2; }
    .kpi-title { font-size:.85rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px; margin-top:5px; }
    .kpi-unit  { font-size:.8rem; font-weight:500; color:var(--text-muted); }

    /* ── CHART PANEL (cũ - white) ───────────────────────────────── */
    .chart-panel {
        background:var(--card-bg); border-radius:12px; padding:1.5rem;
        box-shadow:0 0 20px 0 rgba(76,87,125,.02);
        height:100%; min-height:380px; display:flex; flex-direction:column;
    }
    .panel-header {
        display:flex; justify-content:space-between; align-items:center;
        margin-bottom:1rem; border-bottom:1px dashed #ebedf3; padding-bottom:.5rem;
    }
    .panel-title { font-weight:700; font-size:1rem; color:var(--dark-color); margin:0; display:flex; align-items:center; }
    .panel-title i { margin-right:8px; opacity:.7; }
    .chart-wrapper { flex-grow:1; position:relative; width:100%; }

    /* ── SẢN LƯỢNG SECTION — DARK THEME ────────────────────────── */
    .sl-section {
        background:#ffffff;
        border-radius:16px; padding:1.75rem; margin-bottom:1.5rem;
        border:1px solid #e2e8f0;
        box-shadow:0 0 20px 0 rgba(76,87,125,.08);
    }
    .sl-section-header {
        display:flex; flex-wrap:wrap; gap:10px;
        justify-content:space-between; align-items:flex-start;
        margin-bottom:1.5rem; padding-bottom:1rem;
        border-bottom:1px solid #e2e8f0;
    }
    .sl-section-title {
        font-size:1rem; font-weight:700; color:#181c32;
        display:flex; align-items:center; gap:10px; margin:0;
        flex-shrink:0;
    }
    .sl-section-title .icon-badge {
        width:32px; height:32px; border-radius:8px;
        background:linear-gradient(135deg,#3699ff,#00d4ff);
        display:flex; align-items:center; justify-content:center;
        font-size:.85rem; color:#fff; flex-shrink:0;
    }

    /* Tab switcher - wrap tren mobile */
    .sl-tabs { display:flex; flex-wrap:wrap; gap:5px; }
    .sl-tab {
        padding:6px 14px; border-radius:8px; border:1px solid #e2e8f0;
        background:#f8fafc; color:#64748b; font-size:.78rem; font-weight:600;
        cursor:pointer; transition:all .2s; letter-spacing:.3px;
    }
    .sl-tab:hover, .sl-tab.active {
        background:#3699ff; border-color:#3699ff; color:#fff;
    }

    /* KPI mini cards trong section sản lượng */
    .sl-kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:1.5rem; }
    .sl-kpi {
        background:#f8fafc; border:1px solid #e2e8f0;
        border-radius:10px; padding:14px 16px; position:relative; overflow:hidden;
        transition:all .2s;
    }
    .sl-kpi::before {
        content:''; position:absolute; top:0; left:0; right:0; height:3px;
        background:var(--kpi-color, var(--sl-blue));
    }
    .sl-kpi:hover { background:#eff6ff; border-color:#93c5fd; }
    .sl-kpi-label { font-size:.7rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:.6px; margin-bottom:6px; }
    .sl-kpi-val   { font-size:1.55rem; font-weight:800; color:#181c32; line-height:1.1; }
    .sl-kpi-unit  { font-size:.72rem; color:#94a3b8; margin-left:3px; font-weight:400; }
    .sl-kpi-trend {
        font-size:.72rem; margin-top:5px; display:flex; align-items:center; gap:4px;
    }
    .trend-up   { color:#1bc5bd; }
    .trend-down { color:#f64e60; }

    /* Chart panels trong dark section */
    .sl-chart-grid { display:grid; grid-template-columns:2fr 1fr; gap:14px; margin-bottom:14px; }
    .sl-chart-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
    .sl-card {
        background:#f8fafc; border:1px solid #e2e8f0;
        border-radius:12px; padding:16px; display:flex; flex-direction:column;
    }
    .sl-card-title {
        font-size:.78rem; font-weight:700; color:#3699ff; text-transform:uppercase;
        letter-spacing:.5px; margin-bottom:12px; display:flex; align-items:center; gap:7px;
    }
    .sl-card-title span.dot {
        width:8px; height:8px; border-radius:50%;
        background:var(--dot-color, var(--sl-blue)); flex-shrink:0;
    }
    .sl-canvas-wrap { flex:1; position:relative; min-height:180px; }

    /* Bảng trạm realtime */
    .rt-table { width:100%; border-collapse:collapse; font-size:.8rem; }
    .rt-table th {
        padding:7px 10px; text-align:left;
        color:#64748b; font-weight:700; font-size:.68rem;
        text-transform:uppercase; letter-spacing:.5px;
        border-bottom:1px solid rgba(54,153,255,.12);
    }
    .rt-table td { padding:7px 10px; border-bottom:1px solid #f1f5f9; color:#334155; }
    .rt-table tr:last-child td { border-bottom:none; }
    .rt-table tr:hover td { background:#eff6ff; }
    .ap-badge {
        display:inline-flex; align-items:center; gap:5px;
        padding:3px 8px; border-radius:5px; font-weight:700; font-size:.75rem;
    }
    .ap-high   { background:rgba(26,109,255,.15); color:#60a5fa; }
    .ap-med    { background:rgba(27,197,189,.15); color:#1bc5bd; }
    .ap-low    { background:rgba(255,168,0,.15);  color:#ffa800; }
    .ap-alert  { background:rgba(246,78,96,.15);  color:#f64e60; }
    .ap-none   { background:rgba(100,116,139,.12);color:#64748b; }

    /* Loading spinner */
    .sl-loading {
        display:flex; align-items:center; justify-content:center;
        min-height:160px; color:#94a3b8; font-size:.85rem; gap:10px;
    }
    .sl-spinner { width:20px; height:20px; border:2px solid #e2e8f0; border-top-color:#3699ff; border-radius:50%; animation:spin .8s linear infinite; }
    @keyframes spin { to { transform:rotate(360deg); } }

    /* Count-up animation */
    @keyframes fadeInUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    .sl-kpi { animation:fadeInUp .4s ease both; }
    .sl-kpi:nth-child(1){ animation-delay:.05s; }
    .sl-kpi:nth-child(2){ animation-delay:.1s;  }
    .sl-kpi:nth-child(3){ animation-delay:.15s; }
    .sl-kpi:nth-child(4){ animation-delay:.2s;  }

    /* ── RESPONSIVE ──────────────────────────────────────────────── */
    @media (max-width:1200px) { .kpi-row { grid-template-columns:repeat(3,1fr); } .sl-kpi-grid { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:992px)  { .sl-chart-grid,.sl-chart-grid-3 { grid-template-columns:1fr; } }
    @media (max-width:768px)  { .kpi-row { grid-template-columns:repeat(2,1fr); } .sl-kpi-grid { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:576px)  { .kpi-row { grid-template-columns:1fr; } }

    /* ── MOBILE (< 576px) ───────────────────────────────── */
    @media (max-width:575px) {
        .sl-section { padding:1.25rem 1rem; }
        .tt-section  { padding:1.25rem 1rem; }
        .sl-section-title { font-size:.9rem; }
        .tt-title         { font-size:.9rem; }

        /* Tabs: nho hon de vua hang */
        .sl-tab {
            padding:4px 10px;
            font-size:.72rem;
        }
        .tt-days-btn { padding:4px 10px; font-size:.72rem; }

        /* KPI grid: 2 cot tren mobile */
        .sl-kpi-grid { grid-template-columns:repeat(2,1fr); gap:8px; }
        .sl-kpi-val  { font-size:1.25rem; }

        /* Bang that thoat: font nho hon, scroll ngang ro rang hon */
        .tt-wrap {
            overflow-x:auto;
            -webkit-overflow-scrolling:touch;
            margin:0 -1rem;
            padding:0;  /* bo padding de sticky left:0 sat le trai */
        }
        .tt-table { font-size:.75rem; min-width:420px; }
        .tt-table thead tr th { padding:8px 10px; font-size:.65rem; }
        .tt-table tbody tr td { padding:8px 10px; }
        .tt-table tbody tr td:first-child {
            padding:8px 10px;
            min-width:130px;
            max-width:150px;
            font-size:.75rem;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-layer-group text-primary me-2"></i>Dashboard Tổng Hợp</h3>
        <button class="btn btn-sm btn-light text-primary fw-bold shadow-sm" onclick="location.reload()">
            <i class="fas fa-sync me-1"></i> Làm mới
        </button>
    </div>

    <!-- ══════════════════════════════════════════════════════════════
         BẢNG THẤT THOÁT NƯỚC THEO NGÀY
    ══════════════════════════════════════════════════════════════════ -->
    <div class="tt-section">
        <div class="tt-header">
            <h4 class="tt-title">
                <span class="icon-badge"><i class="fa-solid fa-droplet-slash"></i></span>
                Bảng Theo Dõi Sản Lượng & Thất Thoát Nước
            </h4>
            <div class="tt-controls">
                <input type="date" id="tt-date-from" class="tt-date-input" title="Từ ngày">
                <span style="color:#94a3b8;font-size:.75rem;">→</span>
                <input type="date" id="tt-date-to" class="tt-date-input" title="Đến ngày" value="<?= date('Y-m-d') ?>">
                <button class="tt-days-btn" onclick="loadTTTableRange()" title="Xem theo ngày chọn">
                    <i class="fa-solid fa-search"></i>
                </button>
                <a href="/quanly/nhat-ky/san-luong-dong-ho" class="tt-report-btn green" title="Sản lượng đồng hồ KH">
                    <i class="fa-solid fa-gauge-high"></i><span>Đồng hồ KH</span>
                </a>
            </div>
        </div>
        <div id="tt-content">
            <div class="tt-loading"><div class="sl-spinner"></div> Đang tải...</div>
        </div>
    </div>

    <!-- ══ HÀNG 1: KPI 5 đối tượng cấp cao ══════════════════════ -->
    <div class="kpi-row">
        <a href="<?= $urlNhaMay ?>" class="kpi-card border-blue">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-blue"><i class="fa-solid fa-industry"></i></div>
                <span class="badge bg-light text-dark">Hoạt động</span>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($cntNhaMay) ?></span>
                <div class="kpi-title">Nhà máy nước</div>
            </div>
        </a>
        <a href="<?= $urlTruyenDan ?>" class="kpi-card border-blue">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-blue"><i class="fa-solid fa-code-branch"></i></div>
                <span class="badge bg-primary text-white">Chính</span>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($lenTruyenDan,1) ?> <span class="kpi-unit">km</span></span>
                <div class="kpi-title">Ống truyền dẫn</div>
            </div>
        </a>
        <a href="<?= $urlPhanPhoi ?>" class="kpi-card border-purple">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-purple"><i class="fa-solid fa-network-wired"></i></div>
                <span class="badge bg-info text-white">Mạng lưới</span>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($lenPhanPhoi,1) ?> <span class="kpi-unit">km</span></span>
                <div class="kpi-title">Ống phân phối</div>
            </div>
        </a>
        <a href="<?= $urlDHTong ?>" class="kpi-card border-green">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-green"><i class="fa-solid fa-gauge-high"></i></div>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($cntDHTong) ?></span>
                <div class="kpi-title">Đồng hồ tổng</div>
            </div>
        </a>
        <a href="<?= $urlDHKhuVuc ?>" class="kpi-card border-green">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-green"><i class="fa-solid fa-users-viewfinder"></i></div>
                <span class="badge bg-success text-white">Khách hàng</span>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($cntDHKhuVuc) ?></span>
                <div class="kpi-title">Đồng hồ hộ dân</div>
            </div>
        </a>
    </div>

    <!-- ══ HÀNG 2: KPI 5 đối tượng chi tiết ══════════════════════ -->
    <div class="kpi-row">
        <a href="<?= $urlVan ?>" class="kpi-card border-orange">
            <div class="kpi-icon-wrapper"><div class="kpi-icon bg-light-orange"><i class="fa-solid fa-faucet"></i></div></div>
            <div><span class="kpi-value"><?= number_format($cntVan) ?></span><div class="kpi-title">Van mạng lưới</div></div>
        </a>
        <a href="<?= $urlMoiNoi ?>" class="kpi-card border-orange">
            <div class="kpi-icon-wrapper"><div class="kpi-icon bg-light-orange"><i class="fa-solid fa-link"></i></div></div>
            <div><span class="kpi-value"><?= number_format($cntMoiNoi) ?></span><div class="kpi-title">Điểm mối nối</div></div>
        </a>
        <a href="<?= $urlHam ?>" class="kpi-card border-orange">
            <div class="kpi-icon-wrapper"><div class="kpi-icon bg-light-orange"><i class="fa-solid fa-dungeon"></i></div></div>
            <div><span class="kpi-value"><?= number_format($cntHam) ?></span><div class="kpi-title">Hầm kỹ thuật</div></div>
        </a>
        <a href="<?= $urlCoc ?>" class="kpi-card border-orange">
            <div class="kpi-icon-wrapper"><div class="kpi-icon bg-light-orange"><i class="fa-solid fa-map-pin"></i></div></div>
            <div><span class="kpi-value"><?= number_format($cntCoc) ?></span><div class="kpi-title">Cọc mốc giới</div></div>
        </a>
        <a href="<?= $urlSuCo ?>" class="kpi-card border-red">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-red"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <span class="badge bg-danger text-white">Cảnh báo</span>
            </div>
            <div><span class="kpi-value"><?= number_format($cntSuCo) ?></span><div class="kpi-title">Sự cố chờ xử lý</div></div>
        </a>
    </div>

    <!-- ══ HÀNG 3: BIỂU ĐỒ PHÂN TÍCH GIS (6 charts) ══════════════ -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-chart-bar text-primary"></i> Vật liệu Ống phân phối</h5></div>
                <div class="chart-wrapper"><canvas id="pipeChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-chart-pie text-success"></i> Hãng Đồng hồ</h5></div>
                <div class="chart-wrapper"><canvas id="meterChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-chart-column text-danger"></i> Nguyên nhân sự cố</h5></div>
                <div class="chart-wrapper"><canvas id="incidentChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-gauge text-warning"></i> Tình trạng Van</h5></div>
                <div class="chart-wrapper"><canvas id="valveChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-cubes text-info"></i> Phân loại Hầm kỹ thuật</h5></div>
                <div class="chart-wrapper"><canvas id="hamChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-link text-dark"></i> Loại Mối nối</h5></div>
                <div class="chart-wrapper"><canvas id="moinoiChart"></canvas></div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════
         SẢN LƯỢNG SCADA REALTIME — DARK SECTION
    ══════════════════════════════════════════════════════════════════ -->
    <div class="sl-section">
        <div class="sl-section-header">
            <h4 class="sl-section-title">
                <span class="icon-badge"><i class="fa-solid fa-satellite-dish"></i></span>
                Sản Lượng & Vận Hành SCADA
                <span id="sl-live-dot" style="width:8px;height:8px;border-radius:50%;background:#1bc5bd;animation:pulse-dot 2s infinite;" title="Live data"></span>
            </h4>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <div class="sl-tabs">
                    <button class="sl-tab active" onclick="switchSLTab('ngay',this)">Theo Ngày</button>
                    <button class="sl-tab" onclick="switchSLTab('thang',this)">Theo Tháng</button>
                    <button class="sl-tab" onclick="switchSLTab('nam',this)">Theo Năm</button>
                    <button class="sl-tab" onclick="switchSLTab('khachhang',this)">Khách Hàng</button>
                    <button class="sl-tab" onclick="switchSLTab('realtime',this)">Realtime</button>
                </div>
                <a href="/quanly/nhat-ky/bao-cao" class="tt-report-btn" title="Báo cáo hàng ngày">
                    <i class="fa-solid fa-file-excel"></i><span>Báo cáo</span>
                </a>
            </div>
        </div>

        <!-- KPI mini tự cập nhật theo tab -->
        <div class="sl-kpi-grid" id="sl-kpi-row" style="grid-template-columns:repeat(4,1fr);">
            <div class="sl-kpi" style="--kpi-color:#3699ff;">
                <div class="sl-kpi-label" id="kpi-nuoc-label">Sản lượng nước sạch</div>
                <div class="sl-kpi-val" id="kpi-nuoc">—<span class="sl-kpi-unit">m³</span></div>
                <div class="sl-kpi-trend" id="kpi-nuoc-trend"></div>
            </div>
            <div class="sl-kpi" style="--kpi-color:#ffa800;">
                <div class="sl-kpi-label" id="kpi-dien-label">Điện năng tiêu thụ</div>
                <div class="sl-kpi-val" id="kpi-dien">—<span class="sl-kpi-unit">KWh</span></div>
                <div class="sl-kpi-trend" id="kpi-dien-trend"></div>
            </div>
            <div class="sl-kpi" style="--kpi-color:#1bc5bd;">
                <div class="sl-kpi-label" id="kpi-flow-label">PAC tiêu thụ</div>
                <div class="sl-kpi-val" id="kpi-flow">—<span class="sl-kpi-unit">kg</span></div>
                <div class="sl-kpi-trend" id="kpi-flow-trend"></div>
            </div>
            <div class="sl-kpi" style="--kpi-color:#8950fc;">
                <div class="sl-kpi-label" id="kpi-ap-label">Chlorine tiêu thụ</div>
                <div class="sl-kpi-val" id="kpi-ap">—<span class="sl-kpi-unit">kg</span></div>
                <div class="sl-kpi-trend" id="kpi-ap-trend"></div>
            </div>
        </div>

        <!-- Khu vực chart chính -->
        <div id="sl-content">
            <div class="sl-loading"><div class="sl-spinner"></div> Đang tải dữ liệu SCADA...</div>
        </div>
    </div>


    <!-- ══ DANH SÁCH SỰ CỐ GẦN ĐÂY ══════════════════════════════ -->
    <div class="row">
        <div class="col-12">
            <div class="chart-panel" style="height:auto;min-height:auto;">
                <div class="panel-header border-0">
                    <h5 class="panel-title"><i class="fa-solid fa-list-check text-danger"></i> Nhật ký sự cố mới nhất</h5>
                    <a href="<?= $urlSuCo ?>" class="btn btn-sm btn-light text-primary fw-bold">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Mã SC</th>
                                <th>Sự cố & Vị trí</th>
                                <th>Nguyên nhân</th>
                                <th>Thời gian báo</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-4">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentIncidents as $sc): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?= $sc->masuco ?? $sc->id ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $sc->loaisuco ? $sc->loaisuco->ten : 'Chưa phân loại' ?></div>
                                    <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> <?= $sc->vitri ?></small>
                                </td>
                                <td><?= $sc->nguyennhansuco ? $sc->nguyennhansuco->ten : '-' ?></td>
                                <td><?= Yii::$app->formatter->asDate($sc->created_at,'php:d/m/Y H:i') ?></td>
                                <td>
                                    <?php if($sc->status==1): ?>
                                        <span class="badge bg-light text-success fw-bold px-3 py-2">Hoàn thành</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-danger fw-bold px-3 py-2">Đang xử lý</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= Url::to(['hocau/suco/view','id'=>$sc->id]) ?>" class="btn btn-icon btn-sm btn-light-primary"><i class="fa-solid fa-arrow-right"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse-dot {
    0%,100%{ opacity:1; transform:scale(1); }
    50%    { opacity:.4; transform:scale(1.3); }
}

    /* ── TRẠM REALTIME BLOCK ────────────────────────────────── */
    .rt-station-block {
        border-bottom:1px solid #f1f5f9; padding:8px 0; margin-bottom:2px;
    }
    .rt-station-block:last-child { border-bottom:none; }
    .rt-station-name {
        font-size:.78rem; font-weight:700; color:#334155;
        margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }

    /* ── BẢNG THẤT THOÁT NƯỚC ─────────────────────────────────── */
    .tt-section {
        background:#ffffff;
        border-radius:16px; padding:1.75rem; margin-bottom:1.5rem;
        border:1px solid #e2e8f0;
        box-shadow:0 0 20px 0 rgba(76,87,125,.08);
    }
    .tt-header {
        display:flex; flex-wrap:wrap; gap:8px;
        justify-content:space-between; align-items:flex-start;
        margin-bottom:1.25rem; padding-bottom:1rem;
        border-bottom:1px solid #e2e8f0;
    }
    .tt-title {
        font-size:1.05rem; font-weight:700; color:#181c32;
        display:flex; align-items:center; gap:10px; margin:0;
    }
    .tt-title .icon-badge {
        width:34px; height:34px; border-radius:8px;
        background:linear-gradient(135deg,#f64e60,#ff8a00);
        display:flex; align-items:center; justify-content:center;
        font-size:.85rem; color:#fff; flex-shrink:0;
    }
    .tt-controls { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .tt-date-input {
        padding:4px 8px; border-radius:7px; font-size:.75rem;
        border:1px solid rgba(54,153,255,.25); background:rgba(255,255,255,.05);
        color:#334155; outline:none; cursor:pointer; color-scheme:light;
    }
    .tt-date-input:focus { border-color:#3699ff; }
    .tt-report-btn {
        display:inline-flex; align-items:center; gap:6px;
        padding:5px 12px; border-radius:7px; font-size:.75rem; font-weight:600;
        border:1px solid #e2e8f0; background:#f8fafc;
        color:#64748b; cursor:pointer; transition:all .2s; text-decoration:none;
    }
    .tt-report-btn:hover { background:#3699ff; border-color:#3699ff; color:#fff; }
    .tt-report-btn.green { border-color:#1bc5bd; color:#1bc5bd; background:#f0fdf9; }
    .tt-report-btn.green:hover { background:#1bc5bd; border-color:#1bc5bd; color:#fff; }
    .tt-days-btn {
        padding:5px 12px; border-radius:7px; font-size:.75rem; font-weight:600;
        border:1px solid #e2e8f0; background:#f8fafc;
        color:#64748b; cursor:pointer; transition:all .2s;
    }
    .tt-days-btn.active, .tt-days-btn:hover {
        background:#3699ff; border-color:#3699ff; color:#fff;
    }
    .tt-loading {
        display:flex; align-items:center; justify-content:center;
        min-height:120px; color:#94a3b8; font-size:.85rem; gap:8px;
    }
    .tt-wrap { overflow-x:auto; }
    .tt-table { width:100%; border-collapse:separate; border-spacing:0; font-size:.82rem; }
    .tt-table thead tr th {
        padding:10px 14px; text-align:right; font-size:.72rem; font-weight:700;
        color:#fff; text-transform:uppercase; letter-spacing:.5px;
        background:#3699ff; border-bottom:1px solid rgba(54,153,255,.15);
        white-space:nowrap;
    }
    .tt-table thead tr th:first-child { text-align:left; }
    .tt-table tbody tr td:first-child {
        text-align:left; font-weight:600; color:#334155;
        white-space:nowrap; padding:11px 14px;
        border-right:1px solid #e2e8f0;
        background:#ffffff;
        position:sticky; left:0; z-index:2;
        min-width:160px; max-width:200px;
    }
    .tt-table thead tr th:first-child {
        position:sticky; left:0; z-index:3;
        background:#3699ff;
    }
    .tt-table tbody tr td {
        padding:11px 14px; text-align:right;
        border-bottom:1px solid #f1f5f9; color:#334155;
    }
    .tt-table tbody tr:hover td { background:#eff6ff; }
    .tt-table tbody tr:last-child td { border-bottom:none; }
    .tt-row-raw td:first-child { border-left:3px solid #3699ff; }
    .tt-row-cap td:first-child { border-left:3px solid #1bc5bd; }
    .tt-row-kh  td:first-child { border-left:3px solid #ffa800; }
    .tt-row-nrw td:first-child { border-left:3px solid #f64e60; }
    .tt-row-tl  td:first-child { border-left:3px solid #8950fc; }
    .val-raw  { color:#60a5fa; font-weight:600; }
    .val-cap  { color:#1bc5bd; font-weight:600; }
    .val-kh   { color:#ffa800; font-weight:600; }
    .val-nrw  { color:#f87171; font-weight:600; }
    .val-tl   { font-weight:700; }
    .val-tl-ok   { color:#a3e635; }
    .val-tl-warn { color:#fbbf24; }
    .val-tl-bad  { color:#f87171; }
    .tt-today      { background:#dbeafe !important; }
    .tt-today-head { background:#2563eb !important; color:#fff !important; }
    .tt-sum-col    { background:#eff6ff !important; color:#3699ff !important; font-weight:700; }

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── GIS Charts (giữ nguyên) ──────────────────────────────────
    Chart.defaults.font.family = "'Segoe UI', sans-serif";
    Chart.defaults.color       = '#7e8299';
    Chart.defaults.scale.grid.color = '#f3f6f9';

    function handleChartClick(urlBase, paramName, ids) {
        return (e, elements) => {
            if (elements.length > 0) {
                window.location.href = urlBase + '?' + paramName + '=' + ids[elements[0].index];
            }
        };
    }

    new Chart(document.getElementById('pipeChart'), {
        type:'bar',
        data:{ labels:<?= $pipeLabels ?>, datasets:[{label:'Số lượng',data:<?= $pipeValues ?>,backgroundColor:'#3699ff',borderRadius:4,barThickness:20}] },
        options:{ indexAxis:'y',maintainAspectRatio:false,plugins:{legend:{display:false}},onClick:handleChartClick('<?= Url::to(['hocau/ongphanphoi/index']) ?>','OngphanphoiSearch[loaiong_id]',<?= $pipeIds ?>) }
    });
    new Chart(document.getElementById('meterChart'), {
        type:'doughnut',
        data:{ labels:<?= $meterLabels ?>, datasets:[{data:<?= $meterValues ?>,backgroundColor:['#1bc5bd','#3699ff','#8950fc','#ffa800'],borderWidth:0}] },
        options:{ maintainAspectRatio:false,cutout:'70%',plugins:{legend:{position:'right',labels:{boxWidth:10,usePointStyle:true}}},onClick:handleChartClick('<?= Url::to(['hocau/donghonhamay/index']) ?>','DonghonhamaySearch[hieudongho_id]',<?= $meterIds ?>) }
    });
    new Chart(document.getElementById('incidentChart'), {
        type:'bar',
        data:{ labels:<?= $incidentLabels ?>, datasets:[{label:'Vụ việc',data:<?= $incidentValues ?>,backgroundColor:'#f64e60',borderRadius:4}] },
        options:{ maintainAspectRatio:false,plugins:{legend:{display:false}},onClick:handleChartClick('<?= Url::to(['hocau/suco/index']) ?>','SucoSearch[nguyennhansuco_id]',<?= $incidentIds ?>) }
    });
    new Chart(document.getElementById('valveChart'), {
        type:'pie',
        data:{ labels:<?= $valveLabels ?>, datasets:[{data:<?= $valveValues ?>,backgroundColor:['#1bc5bd','#ffa800','#e4e6ef'],borderWidth:0}] },
        options:{ maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{usePointStyle:true}}},onClick:handleChartClick('<?= Url::to(['hocau/van/index']) ?>','VanSearch[tinhtrang_id]',<?= $valveIds ?>) }
    });
    new Chart(document.getElementById('hamChart'), {
        type:'polarArea',
        data:{ labels:<?= $hamLabels ?>, datasets:[{data:<?= $hamValues ?>,backgroundColor:['rgba(137,80,252,.7)','rgba(54,153,255,.7)','rgba(27,197,189,.7)'],borderWidth:1}] },
        options:{ maintainAspectRatio:false,scales:{r:{ticks:{display:false}}},plugins:{legend:{position:'right',labels:{boxWidth:10}}},onClick:handleChartClick('<?= Url::to(['hocau/hamkythuat/index']) ?>','HamkythuatSearch[loaiham_id]',<?= $hamIds ?>) }
    });
    new Chart(document.getElementById('moinoiChart'), {
        type:'bar',
        data:{ labels:<?= $moinoiLabels ?>, datasets:[{label:'Số lượng',data:<?= $moinoiValues ?>,backgroundColor:'#181c32',borderRadius:4}] },
        options:{ maintainAspectRatio:false,plugins:{legend:{display:false}},onClick:handleChartClick('<?= Url::to(['hocau/moinoi/index']) ?>','MoinoiSearch[loaimoinoi_id]',<?= $moinoiIds ?>) }
    });

    // ── SẢN LƯỢNG SCADA ─────────────────────────────────────────
    const IOT_BASE  = '/iot_api.php';
    const IOT_KEY   = 'SCADA_HOCAU_2024_SECRET_KEY';
    let   slCharts  = {};
    let   curTab    = 'ngay';
    let   rtData    = null;

    const C = {
        blue:   '#3699ff', cyan: '#00d4ff', green: '#1bc5bd',
        amber:  '#ffa800', rose: '#f64e60', purple:'#8950fc',
        blue2:  'rgba(54,153,255,.18)', cyan2:'rgba(0,212,255,.18)',
        green2: 'rgba(27,197,189,.18)',  amber2:'rgba(255,168,0,.18)',
    };

    function mkGrad(ctx, colorTop, colorBot) {
        const g = ctx.createLinearGradient(0,0,0,ctx.canvas.height||200);
        g.addColorStop(0, colorTop); g.addColorStop(1, colorBot);
        return g;
    }
    function fmt(n) {
        if (n === null || n === undefined || isNaN(n)) return '—';
        if (n >= 1e6) return (n/1e6).toFixed(2) + 'M';
        if (n >= 1e3) return (n/1e3).toFixed(1) + 'K';
        return parseFloat(n).toFixed(1);
    }
    function fmtN(n) {
        if (!n) return '—';
        return Math.round(n).toLocaleString('vi-VN');
    }
    function trendHtml(cur, prev) {
        if (!prev || !cur) return '';
        const d = cur - prev;
        const pct = Math.abs(d/prev*100).toFixed(1);
        const icon = d >= 0 ? '▲' : '▼';
        const cls  = d >= 0 ? 'trend-up' : 'trend-down';
        return `<span class="${cls}">${icon} ${pct}%</span> so kỳ trước`;
    }

    // Tính cumulative sum
    function cumsum(arr) {
        let s = 0;
        return arr.map(v => { s += (v||0); return s; });
    }

    const darkChartDefaults = {
        color: '#5a82a8',
        scale: { grid:{ color:'rgba(54,153,255,.07)' }, ticks:{ color:'#3d6080' } }
    };

    function destroyCharts() {
        Object.values(slCharts).forEach(c => { try{c.destroy();}catch(e){} });
        slCharts = {};
    }

    // ── Helpers KPI labels ──────────────────────────────────────
    function setKpiLabels(l1, l2, l3, l4) {
        document.getElementById('kpi-nuoc-label').textContent  = l1 || 'Sản lượng nước sạch';
        document.getElementById('kpi-dien-label').textContent  = l2 || 'Điện năng tiêu thụ';
        document.getElementById('kpi-flow-label').textContent  = l3 || 'PAC tiêu thụ';
        document.getElementById('kpi-ap-label').textContent    = l4 || 'Chlorine tiêu thụ';
    }
    function updateKPI(nuoc, nuocTrend, dien, dienTrend, flow, flowTrend, ap, apTrend) {
        if (nuoc  != null) document.getElementById('kpi-nuoc').innerHTML  = nuoc;
        if (dien  != null) document.getElementById('kpi-dien').innerHTML  = dien;
        if (flow  != null) document.getElementById('kpi-flow').innerHTML  = flow;
        if (ap    != null) document.getElementById('kpi-ap').innerHTML    = ap;
        if (nuocTrend != null) document.getElementById('kpi-nuoc-trend').innerHTML = nuocTrend;
        if (dienTrend != null) document.getElementById('kpi-dien-trend').innerHTML = dienTrend;
        if (flowTrend != null) document.getElementById('kpi-flow-trend').innerHTML = flowTrend;
        if (apTrend   != null) document.getElementById('kpi-ap-trend').innerHTML   = apTrend;
    }

    // ── Fetch & render tab ──────────────────────────────────────
    function loadSLTab(loai) {
        const content = document.getElementById('sl-content');
        content.innerHTML = '<div class="sl-loading"><div class="sl-spinner"></div> Đang tải...</div>';
        destroyCharts();

        if (loai === 'realtime') {
            // Realtime: fetch cả sanluong?loai=realtime + action=get
            Promise.all([
                fetch(`${IOT_BASE}?action=sanluong&loai=realtime&key=${IOT_KEY}`).then(r=>r.json()),
                fetch(`${IOT_BASE}?action=get&key=${IOT_KEY}`).then(r=>r.json()),
            ]).then(([slData, rtRaw]) => renderRealtime(slData, rtRaw))
              .catch(() => {
                content.innerHTML = '<div class="sl-loading" style="color:#f64e60;"><i class="fa-solid fa-circle-exclamation me-2"></i>Không kết nối được SCADA server</div>';
              });
            return;
        }

        // Các tab khác dùng loai=ngay/thang/nam/khachhang
        const apiLoai = (loai === 'ngay' || loai === 'thang') ? 'ngay'
                      : loai === 'nam' ? 'thang'
                      : loai;

        fetch(`${IOT_BASE}?action=sanluong&loai=${apiLoai}&key=${IOT_KEY}`)
            .then(r => r.json())
            .then(data => {
                if      (loai === 'ngay')      renderNgay(data);
                else if (loai === 'thang')     renderThang(data);
                else if (loai === 'nam')       renderNam(data);
                else if (loai === 'khachhang') {
                    fetch(`${IOT_BASE}?action=sanluong&loai=khachhang&key=${IOT_KEY}`)
                        .then(r=>r.json()).then(renderKhachHang);
                }
            })
            .catch(() => {
                content.innerHTML = '<div class="sl-loading" style="color:#f64e60;"><i class="fa-solid fa-circle-exclamation me-2"></i>Không kết nối được SCADA server</div>';
            });
    }

    // ── Helper: lọc dữ liệu theo tháng/năm hiện tại ────────────
    function filterByMonth(labels, ...arrays) {
        const now = new Date();
        const ym  = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}`;
        const idx = labels.reduce((acc,l,i) => { if(l && l.startsWith(ym)) acc.push(i); return acc; }, []);
        return {
            labels: idx.map(i=>labels[i]),
            arrays: arrays.map(arr => idx.map(i=>(arr&&arr[i])||0)),
        };
    }
    function filterByYear(labels, ...arrays) {
        const yr = String(new Date().getFullYear());
        const idx = labels.reduce((acc,l,i) => { if(l && l.startsWith(yr)) acc.push(i); return acc; }, []);
        return {
            labels: idx.map(i=>labels[i]),
            arrays: arrays.map(arr => idx.map(i=>(arr&&arr[i])||0)),
        };
    }

    // ── Combo chart lũy kế (dùng lại cho Ngày & Tháng) ─────────
    function renderCumComboChart(canvasId, labels, nuoc, dien, labelNuoc, labelDien) {
        const cumNuoc = cumsum(nuoc);
        const cumDien = cumsum(dien);
        const ctx = document.getElementById(canvasId);
        slCharts[canvasId] = new Chart(ctx, {
            type:'bar',
            data:{ labels, datasets:[
                { type:'bar',  label: labelNuoc, data: cumNuoc,
                  backgroundColor: C.blue2, borderColor: C.blue, borderWidth:1, borderRadius:3, yAxisID:'y' },
                { type:'line', label: labelDien, data: cumDien,
                  borderColor: C.amber, backgroundColor:'transparent', borderWidth:2.5,
                  tension:.3, pointRadius:3, pointBackgroundColor:C.amber, yAxisID:'y1' },
            ]},
            options:{ ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}} },
                scales:{
                    x:{ticks:{color:'#3d6080',maxTicksLimit:14,font:{size:10}},grid:{display:false}},
                    y:{ticks:{color:'#3d6080',font:{size:10},callback:v=>fmt(v)},
                       grid:{color:'rgba(54,153,255,.07)'},
                       title:{display:true,text:'m³',color:'#3d6080',font:{size:10}}},
                    y1:{position:'right',ticks:{color:'#c08000',font:{size:10},callback:v=>fmt(v)},
                        grid:{display:false},
                        title:{display:true,text:'KWh',color:'#c08000',font:{size:10}}}
                }
            }
        });
        return {cumNuoc, cumDien};
    }

    // ── Tab: Theo Ngày (lũy kế từ đầu tháng) ──────────────────
    function renderNgay(d) {
        if (!d.labels || !d.labels.length) { showEmpty(); return; }

        // Lọc dữ liệu tháng hiện tại
        const f = filterByMonth(d.labels, d.nuoc_sach, d.dien_nang, d.pac||[], d.chlorin||[]);
        const [nuocM, dienM, pacM, cloM] = f.arrays;
        const labels = f.labels;

        if (!labels.length) { showEmpty(); return; }

        const cumNuoc = cumsum(nuocM);
        const cumDien = cumsum(dienM);
        const cumPac  = cumsum(pacM);
        const cumClo  = cumsum(cloM);

        const totNuoc = cumNuoc[cumNuoc.length-1] || 0;
        const totDien = cumDien[cumDien.length-1] || 0;
        const totPac  = cumPac[cumPac.length-1]   || 0;
        const totClo  = cumClo[cumClo.length-1]   || 0;

        const now = new Date();
        const monthLabel = `tháng ${now.getMonth()+1}/${now.getFullYear()}`;

        setKpiLabels(
            `Nước sạch lũy kế ${monthLabel}`,
            `Điện năng lũy kế ${monthLabel}`,
            `PAC lũy kế ${monthLabel}`,
            `Chlorine lũy kế ${monthLabel}`
        );
        updateKPI(
            fmtN(totNuoc)+'<span class="sl-kpi-unit">m³</span>', '',
            fmtN(totDien)+'<span class="sl-kpi-unit">KWh</span>', '',
            fmt(totPac)+'<span class="sl-kpi-unit">kg</span>', '',
            fmt(totClo)+'<span class="sl-kpi-unit">kg</span>', ''
        );

        document.getElementById('sl-content').innerHTML = `
            <div class="sl-chart-grid" style="margin-bottom:14px;">
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#3699ff;"></span>
                        Lũy kế Nước sạch & Điện năng — ${monthLabel}
                    </div>
                    <div class="sl-canvas-wrap" style="min-height:200px;"><canvas id="slCumNgay"></canvas></div>
                </div>
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#1bc5bd;"></span>
                        Lũy kế Hóa chất xử lý — ${monthLabel}
                    </div>
                    <div class="sl-canvas-wrap"><canvas id="slHoaChatNgay"></canvas></div>
                </div>
            </div>`;

        // Chart lũy kế nước + điện
        renderCumComboChart('slCumNgay', labels, nuocM, dienM, 'Nước sạch (m³)', 'Điện năng (KWh)');

        // Chart lũy kế hoá chất
        const cumPacArr = cumsum(pacM);
        const cumCloArr = cumsum(cloM);
        slCharts.hcNgay = new Chart(document.getElementById('slHoaChatNgay'), {
            type:'bar',
            data:{ labels, datasets:[
                { type:'bar',  label:'PAC lũy kế (kg)',     data:cumPacArr,
                  backgroundColor:'rgba(27,197,189,.25)', borderColor:C.green, borderWidth:1, borderRadius:3 },
                { type:'line', label:'Chlorine lũy kế (kg)',data:cumCloArr,
                  borderColor:C.purple, backgroundColor:'transparent', borderWidth:2.5,
                  tension:.3, pointRadius:3, pointBackgroundColor:C.purple },
            ]},
            options:{ ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}} },
                scales:{ x:{ticks:{color:'#3d6080',maxTicksLimit:14,font:{size:10}},grid:{display:false}},
                         y:{ticks:{color:'#3d6080',font:{size:10}},grid:{color:'rgba(54,153,255,.07)'}} }
            }
        });
    }

    // ── Tab: Theo Tháng (lũy kế từ đầu năm) ────────────────────
    function renderThang(d) {
        if (!d.labels || !d.labels.length) { showEmpty(); return; }

        // d là dữ liệu 30 ngày, ta cần group theo tháng rồi lũy kế theo năm hiện tại
        // Tổng hợp theo tháng từ daily data
        const monthMap = {};
        d.labels.forEach((lbl, i) => {
            const ym = lbl ? lbl.substring(0,7) : null;
            if (!ym) return;
            if (!monthMap[ym]) monthMap[ym] = {nuoc:0, dien:0, pac:0, clo:0};
            monthMap[ym].nuoc += (d.nuoc_sach[i]||0);
            monthMap[ym].dien += (d.dien_nang[i]||0);
            monthMap[ym].pac  += ((d.pac||[])[i]||0);
            monthMap[ym].clo  += ((d.chlorin||[])[i]||0);
        });

        // Lấy 24 tháng gần nhất từ monthMap
        const mLabels = Object.keys(monthMap).sort();
        const mNuoc = mLabels.map(k=>monthMap[k].nuoc);
        const mDien = mLabels.map(k=>monthMap[k].dien);
        const mPac  = mLabels.map(k=>monthMap[k].pac);
        const mClo  = mLabels.map(k=>monthMap[k].clo);

        // Lọc năm hiện tại
        const yr = String(new Date().getFullYear());
        const yIdx = mLabels.reduce((acc,l,i)=>{ if(l.startsWith(yr)) acc.push(i); return acc;}, []);
        const yLabels = yIdx.map(i=>mLabels[i]);
        const yNuoc   = yIdx.map(i=>mNuoc[i]);
        const yDien   = yIdx.map(i=>mDien[i]);
        const yPac    = yIdx.map(i=>mPac[i]);
        const yClo    = yIdx.map(i=>mClo[i]);

        // Nếu không có data năm hiện tại thì dùng data gốc (fallback: fetch thang)
        const useLabels = yLabels.length ? yLabels : mLabels;
        const useNuoc   = yLabels.length ? yNuoc   : mNuoc;
        const useDien   = yLabels.length ? yDien   : mDien;
        const usePac    = yLabels.length ? yPac    : mPac;
        const useClo    = yLabels.length ? yClo    : mClo;

        // Tính lũy kế
        const cumNuoc = cumsum(useNuoc);
        const cumDien = cumsum(useDien);
        const cumPac  = cumsum(usePac);
        const cumClo  = cumsum(useClo);

        const totNuoc = cumNuoc[cumNuoc.length-1]||0;
        const totDien = cumDien[cumDien.length-1]||0;
        const totPac  = cumPac[cumPac.length-1]  ||0;
        const totClo  = cumClo[cumClo.length-1]  ||0;

        const yrLabel = `năm ${yr}`;
        setKpiLabels(
            `Nước sạch lũy kế ${yrLabel}`,
            `Điện năng lũy kế ${yrLabel}`,
            `PAC lũy kế ${yrLabel}`,
            `Chlorine lũy kế ${yrLabel}`
        );
        updateKPI(
            fmtN(totNuoc)+'<span class="sl-kpi-unit">m³</span>', '',
            fmtN(totDien)+'<span class="sl-kpi-unit">KWh</span>', '',
            fmt(totPac)+'<span class="sl-kpi-unit">kg</span>', '',
            fmt(totClo)+'<span class="sl-kpi-unit">kg</span>', ''
        );

        document.getElementById('sl-content').innerHTML = `
            <div class="sl-chart-grid" style="margin-bottom:14px;">
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#3699ff;"></span>
                        Lũy kế Nước sạch & Điện năng — ${yrLabel}
                    </div>
                    <div class="sl-canvas-wrap" style="min-height:220px;"><canvas id="slCumThang"></canvas></div>
                </div>
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#1bc5bd;"></span>
                        Lũy kế Hóa chất xử lý — ${yrLabel}
                    </div>
                    <div class="sl-canvas-wrap"><canvas id="slHoaChatThang"></canvas></div>
                </div>
            </div>`;

        renderCumComboChart('slCumThang', useLabels, useNuoc, useDien, 'Nước sạch (m³)', 'Điện năng (KWh)');

        slCharts.hcThang = new Chart(document.getElementById('slHoaChatThang'), {
            type:'bar',
            data:{ labels:useLabels, datasets:[
                { type:'bar',  label:'PAC lũy kế (kg)',      data:cumPac,
                  backgroundColor:'rgba(27,197,189,.25)', borderColor:C.green, borderWidth:1, borderRadius:3 },
                { type:'line', label:'Chlorine lũy kế (kg)', data:cumClo,
                  borderColor:C.purple, backgroundColor:'transparent', borderWidth:2.5,
                  tension:.3, pointRadius:3, pointBackgroundColor:C.purple },
            ]},
            options:{ ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}} },
                scales:{ x:{ticks:{color:'#3d6080',maxTicksLimit:12,font:{size:10}},grid:{display:false}},
                         y:{ticks:{color:'#3d6080',font:{size:10}},grid:{color:'rgba(54,153,255,.07)'}} }
            }
        });
    }

    // ── Tab: Theo Năm (fetch thang, group theo năm, lũy kế toàn bộ) ──
    function renderNam(d) {
        // d là dữ liệu 30 ngày từ apiLoai='thang', group theo năm
        if (!d.labels || !d.labels.length) { showEmpty(); return; }

        const yrMap = {};
        d.labels.forEach((lbl, i) => {
            const yr = lbl ? lbl.substring(0,4) : null;
            if (!yr) return;
            if (!yrMap[yr]) yrMap[yr] = {nuoc:0, dien:0, pac:0, clo:0};
            yrMap[yr].nuoc += (d.nuoc_sach[i]||0);
            yrMap[yr].dien += (d.dien_nang[i]||0);
            yrMap[yr].pac  += ((d.pac||[])[i]||0);
            yrMap[yr].clo  += ((d.chlorin||[])[i]||0);
        });

        const yLabels = Object.keys(yrMap).sort();
        const yNuoc   = yLabels.map(k=>yrMap[k].nuoc);
        const yDien   = yLabels.map(k=>yrMap[k].dien);
        const yPac    = yLabels.map(k=>yrMap[k].pac);
        const yClo    = yLabels.map(k=>yrMap[k].clo);

        // Lũy kế toàn bộ lịch sử
        const cumNuoc = cumsum(yNuoc);
        const cumDien = cumsum(yDien);
        const cumPac  = cumsum(yPac);
        const cumClo  = cumsum(yClo);

        const totNuoc = cumNuoc[cumNuoc.length-1]||0;
        const totDien = cumDien[cumDien.length-1]||0;

        setKpiLabels('Tổng nước sạch (toàn lịch sử)', 'Tổng điện năng (toàn lịch sử)', 'Tổng PAC (toàn lịch sử)', 'Tổng Chlorine (toàn lịch sử)');
        updateKPI(
            fmtN(totNuoc)+'<span class="sl-kpi-unit">m³</span>', '',
            fmtN(totDien)+'<span class="sl-kpi-unit">KWh</span>', '',
            fmt(cumPac[cumPac.length-1])+'<span class="sl-kpi-unit">kg</span>', '',
            fmt(cumClo[cumClo.length-1])+'<span class="sl-kpi-unit">kg</span>', ''
        );

        document.getElementById('sl-content').innerHTML = `
            <div class="sl-chart-grid" style="margin-bottom:14px;">
                <div class="sl-card" style="grid-column:1/3;">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#3699ff;"></span>
                        Lũy kế Nước sạch & Điện năng — Toàn bộ dữ liệu
                    </div>
                    <div class="sl-canvas-wrap" style="min-height:220px;"><canvas id="slCumNam"></canvas></div>
                </div>
            </div>
            <div class="sl-chart-grid-3">
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#ffa800;"></span>Điện năng / năm</div>
                    <div class="sl-canvas-wrap"><canvas id="slNamDien"></canvas></div>
                </div>
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#1bc5bd;"></span>PAC & Chlorine / năm (kg)</div>
                    <div class="sl-canvas-wrap"><canvas id="slNamHoaChat"></canvas></div>
                </div>
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#3699ff;"></span>Tăng trưởng nước sạch (%)</div>
                    <div class="sl-canvas-wrap"><canvas id="slNamGrowth"></canvas></div>
                </div>
            </div>`;

        // Lũy kế combo
        renderCumComboChart('slCumNam', yLabels, yNuoc, yDien, 'Nước sạch (m³)', 'Điện năng (KWh)');

        // Điện năm
        slCharts.namDien = new Chart(document.getElementById('slNamDien'), {
            type:'doughnut',
            data:{ labels:yLabels, datasets:[{data:yDien,backgroundColor:[C.amber2,'rgba(255,168,0,.35)','rgba(255,168,0,.5)',C.amber,'rgba(255,140,0,.8)'],borderWidth:0}] },
            options:{ responsive:true,maintainAspectRatio:false,cutout:'65%',
                plugins:{legend:{position:'bottom',labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}},
                         tooltip:{callbacks:{label:c=>` ${fmt(c.parsed)} KWh`}}} }
        });

        // Hoá chất theo năm
        slCharts.namHC = new Chart(document.getElementById('slNamHoaChat'), {
            type:'bar',
            data:{ labels:yLabels, datasets:[
                { label:'PAC (kg)',    data:yPac, backgroundColor:'rgba(27,197,189,.4)',  borderColor:C.green,  borderWidth:1, borderRadius:3 },
                { label:'Chlorin (kg)',data:yClo, backgroundColor:'rgba(137,80,252,.4)', borderColor:C.purple, borderWidth:1, borderRadius:3 },
            ]},
            options:{ ...darkChartDefaults,responsive:true,maintainAspectRatio:false,
                plugins:{legend:{labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}}},
                scales:{x:{ticks:{color:'#3d6080',font:{size:10}},grid:{display:false}},
                        y:{ticks:{color:'#3d6080',font:{size:10}},grid:{color:'rgba(54,153,255,.07)'}}}
            }
        });

        // Tăng trưởng nước
        const growth = yNuoc.map((v,i)=>i===0?0:yNuoc[i-1]?parseFloat(((v-yNuoc[i-1])/yNuoc[i-1]*100).toFixed(1)):0);
        slCharts.namGrowth = new Chart(document.getElementById('slNamGrowth'), {
            type:'bar',
            data:{ labels:yLabels, datasets:[{
                label:'Tăng trưởng (%)', data:growth,
                backgroundColor: growth.map(v=>v>=0?'rgba(27,197,189,.5)':'rgba(246,78,96,.5)'),
                borderColor: growth.map(v=>v>=0?C.green:C.rose),
                borderWidth:1, borderRadius:4,
            }]},
            options:{ ...darkChartDefaults,responsive:true,maintainAspectRatio:false,
                plugins:{legend:{display:false}},
                scales:{x:{ticks:{color:'#3d6080',font:{size:10}},grid:{display:false}},
                        y:{ticks:{color:'#3d6080',font:{size:10},callback:v=>v+'%'},grid:{color:'rgba(54,153,255,.07)'}}}
            }
        });
    }

    // ── Tab: Khách Hàng ─────────────────────────────────────────
    function renderKhachHang(d) {
        if (!d.kh_labels || !d.kh_labels.length) { showEmpty(); return; }
        const totalKH = d.kh_values.reduce((a,b)=>a+b, 0);
        setKpiLabels('Tổng sản lượng KH lớn', 'Sản lượng Gò Dầu', 'Sản lượng NT6', 'Sản lượng CNNT');
        updateKPI(
            fmt(totalKH)+'<span class="sl-kpi-unit">m³</span>', '',
            d.godau && d.godau.length ? fmt(d.godau[d.godau.length-1])+'<span class="sl-kpi-unit">m³</span>' : null, '',
            d.nt6   && d.nt6.length   ? fmt(d.nt6[d.nt6.length-1])+'<span class="sl-kpi-unit">m³</span>'   : null, '',
            d.cnnt  && d.cnnt.length  ? fmt(d.cnnt[d.cnnt.length-1])+'<span class="sl-kpi-unit">m³</span>' : null, ''
        );

        document.getElementById('sl-content').innerHTML = `
            <div class="sl-chart-grid">
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#3699ff;"></span>Xu hướng sản lượng 5 khách hàng lớn nhất — 7 ngày</div>
                    <div class="sl-canvas-wrap" style="min-height:220px;"><canvas id="slKHTrend"></canvas></div>
                </div>
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#1bc5bd;"></span>Phân bổ sản lượng theo khách hàng (hôm nay)</div>
                    <div class="sl-canvas-wrap"><canvas id="slKHShare"></canvas></div>
                </div>
            </div>`;

        const colors = [C.blue,C.green,C.amber,C.rose,C.purple,'#00d4ff','#f64e60','#1bc5bd','#ffa800','#3699ff'];
        slCharts.khTrend = new Chart(document.getElementById('slKHTrend'), {
            type:'line',
            data:{ labels:d.labels, datasets:[
                { label:'Gò Dầu',   data:d.godau,   borderColor:C.blue,   backgroundColor:'transparent',borderWidth:2,tension:.4,pointRadius:3 },
                { label:'NT6',      data:d.nt6,     borderColor:C.green,  backgroundColor:'transparent',borderWidth:2,tension:.4,pointRadius:3 },
                { label:'CNNT',     data:d.cnnt,    borderColor:C.amber,  backgroundColor:'transparent',borderWidth:2,tension:.4,pointRadius:3 },
                { label:'Vinatex',  data:d.vinatex, borderColor:C.rose,   backgroundColor:'transparent',borderWidth:2,tension:.4,pointRadius:3 },
                { label:'NT5 D300', data:d.nt5_d300,borderColor:C.purple, backgroundColor:'transparent',borderWidth:2,tension:.4,pointRadius:3 },
            ]},
            options:{ ...darkChartDefaults,responsive:true,maintainAspectRatio:false,
                plugins:{legend:{labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}}},
                scales:{x:{ticks:{color:'#3d6080',font:{size:10}},grid:{display:false}},
                        y:{ticks:{color:'#3d6080',font:{size:10},callback:v=>fmt(v)},grid:{color:'rgba(54,153,255,.07)'}}}
            }
        });
        slCharts.khShare = new Chart(document.getElementById('slKHShare'), {
            type:'doughnut',
            data:{ labels:d.kh_labels, datasets:[{data:d.kh_values,backgroundColor:colors.slice(0,d.kh_labels.length),borderWidth:0,hoverOffset:6}] },
            options:{ responsive:true,maintainAspectRatio:false,cutout:'60%',
                plugins:{legend:{position:'right',labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:10}}},
                         tooltip:{callbacks:{label:c=>` ${fmt(c.parsed)} m³`}}}
            }
        });
    }

    // ── Tab: Realtime — layout 3 cột như hình ──────────────────
    function renderRealtime(slData, rtRaw) {
        // rtRaw = object { ma_tram: { ten_tram, channels:{...}, ap_luc, luu_luong_thuan, ... } }
        const trams = slData && slData.trams ? slData.trams : [];

        // Tách trạm bơm/nhà máy (loai=tram_bom/nha_may) vs trạm đo (dong_ho/...)
        const allRt = Object.values(rtRaw || {});
        const tramBom  = allRt.filter(t => t.loai === 'tram_bom' || t.loai === 'nha_may');
        const tramDo   = allRt.filter(t => t.loai !== 'tram_bom' && t.loai !== 'nha_may');

        // KPI từ realtime
        const totalFlow = allRt.reduce((s,t)=>s+parseFloat(t.luu_luong_thuan||t.luu_luong||0),0);
        const apArr     = allRt.map(t=>parseFloat(t.ap_luc||t.ap_luc_sau_van||0)).filter(v=>v>0);
        const avgAp     = apArr.length ? (apArr.reduce((a,b)=>a+b,0)/apArr.length).toFixed(2) : '—';

        setKpiLabels('Tổng lưu lượng', 'Áp lực TB mạng lưới', 'Số trạm SCADA', 'Trạm bơm hoạt động');
        updateKPI(
            fmt(totalFlow)+'<span class="sl-kpi-unit">m³/h</span>', '',
            avgAp+'<span class="sl-kpi-unit">m</span>', '',
            allRt.length+'<span class="sl-kpi-unit">trạm</span>', '',
            tramBom.length+'<span class="sl-kpi-unit">trạm</span>', ''
        );

        // Helper render một thông số trạm
        function tramDoRow(t) {
            const ch = t.channels || {};
            const rows = [];
            const fieldMap = [
                ['do_duc',   'Độ Đục',         'ntu',    '#5a82a8'],
                ['luu_luong_thuan','Lưu Lượng', 'm³/h',  '#3699ff'],
                ['ph',       'pH',              'ph',     '#1bc5bd'],
                ['clo',      'Clo',             'ppm',    '#8950fc'],
                ['muc_be',   'Mức Bể Chứa',    'm',      '#ffa800'],
            ];
            fieldMap.forEach(([key, label, unit, color]) => {
                if (ch[key] !== undefined) {
                    const val = ch[key];
                    const v   = val && val.value !== undefined ? val.value : val;
                    const ts  = val && val.timestamp ? val.timestamp.substring(5,16) : (t.timestamp||'').substring(5,16);
                    rows.push(`<tr>
                        <td style="color:#5a8ab5;font-weight:500;font-size:.75rem;">${label}</td>
                        <td style="color:#888;font-size:.7rem;">${ts}</td>
                        <td style="color:${color};font-weight:700;font-size:.82rem;text-align:right;">${parseFloat(v).toFixed(2)} ${unit}</td>
                    </tr>`);
                }
            });
            if (!rows.length) return '';
            return `<div class="rt-station-block">
                <div class="rt-station-name"><a href="#" style="color:#3699ff;text-decoration:none;">${t.ten_tram||t.ma_tram}</a></div>
                <table style="width:100%;border-collapse:collapse;">${rows.join('')}</table>
            </div>`;
        }

        function tramBomBlock(t) {
            const ch = t.channels || {};
            const fields = [
                ['ap_suat_cai_dat', 'Áp Suất Cài Đặt', '#5a82a8'],
                ['ap_luc',          'Áp Suất Thực tế',  '#3699ff'],
                ['chi_so_dien',     'Chỉ số điện',      '#ffa800'],
                ['cong_suat',       'Công suất',         '#f64e60'],
                ['luu_luong_nghich','Lưu Lượng Nghịch',  '#8950fc'],
                ['luu_luong_thuan', 'Lưu Lượng Thuận',  '#1bc5bd'],
                ['tan_so_bom_1',    'Tần Số Bơm 1',     '#3699ff'],
                ['tan_so_bom_2',    'Tần Số Bơm 2',     '#00d4ff'],
            ];
            const rows = fields.map(([key, label, color]) => {
                if (ch[key] === undefined) return '';
                const val = ch[key];
                const v   = val && val.value !== undefined ? val.value : val;
                const u   = val && val.unit  ? val.unit : '';
                const ts  = val && val.timestamp ? val.timestamp.substring(5,16) : (t.timestamp||'').substring(5,16);
                return `<tr>
                    <td style="color:#5a8ab5;font-weight:500;font-size:.75rem;">${label}</td>
                    <td style="color:#888;font-size:.7rem;">${ts}</td>
                    <td style="color:${color};font-weight:700;font-size:.82rem;text-align:right;">${parseFloat(v).toFixed(2)} ${u}</td>
                </tr>`;
            }).join('');

            // Sản lượng ngày từ channels (nếu có last_index)
            let slNgay = '';
            const llch = ch['luu_luong_thuan'];
            if (llch && llch.last_index) {
                slNgay = `<tr>
                    <td style="color:#5a8ab5;font-weight:500;font-size:.75rem;">Sản lượng ngày</td>
                    <td style="color:#888;font-size:.7rem;">${(llch.timestamp||'').substring(5,16)}</td>
                    <td style="color:#1bc5bd;font-weight:700;font-size:.82rem;text-align:right;">${fmtN(llch.last_index)} m³</td>
                </tr>`;
            }

            return `<div class="rt-station-block" style="margin-bottom:10px;">
                <div class="rt-station-name" style="color:#ffa800;">${t.ten_tram||t.ma_tram}</div>
                <table style="width:100%;border-collapse:collapse;">${rows}${slNgay}</table>
            </div>`;
        }

        // Sort trams áp lực desc
        const sorted = [...trams].sort((a,b)=>b.ap_luc-a.ap_luc);
        const top15  = sorted.filter(t=>t.ap_luc>0).slice(0,15);

        document.getElementById('sl-content').innerHTML = `
            <div style="display:grid;grid-template-columns:1fr 1fr 1.3fr;gap:14px;align-items:start;">

                <!-- Cột trái: trạm đo chất lượng nước nhóm 1 -->
                <div class="sl-card" style="max-height:460px;overflow-y:auto;padding:12px;">
                    <div class="sl-card-title" style="margin-bottom:10px;">
                        <span class="dot" style="--dot-color:#1bc5bd;"></span>Trạm đo chất lượng nước
                    </div>
                    <div id="rt-col-left">
                        ${tramDo.length ? tramDo.slice(0, Math.ceil(tramDo.length/2)).map(tramDoRow).join('') : '<div style="color:#5a8ab5;font-size:.8rem;padding:10px 0;">Không có dữ liệu trạm đo</div>'}
                    </div>
                </div>

                <!-- Cột giữa: trạm đo chất lượng nước nhóm 2 -->
                <div class="sl-card" style="max-height:460px;overflow-y:auto;padding:12px;">
                    <div class="sl-card-title" style="margin-bottom:10px;">
                        <span class="dot" style="--dot-color:#00d4ff;"></span>Trạm đo mạng lưới
                    </div>
                    <div id="rt-col-mid">
                        ${tramDo.length > 1 ? tramDo.slice(Math.ceil(tramDo.length/2)).map(tramDoRow).join('') : '<div style="color:#5a8ab5;font-size:.8rem;padding:10px 0;">—</div>'}
                    </div>
                </div>

                <!-- Cột phải: trạm bơm + chart áp lực -->
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div class="sl-card" style="padding:12px;">
                        <div class="sl-card-title" style="margin-bottom:10px;">
                            <span class="dot" style="--dot-color:#ffa800;"></span>Trạm bơm / Nhà máy
                        </div>
                        <div style="max-height:230px;overflow-y:auto;" id="rt-col-right">
                            ${tramBom.length ? tramBom.map(tramBomBlock).join('') : '<div style="color:#5a8ab5;font-size:.8rem;padding:10px 0;">Không có dữ liệu trạm bơm</div>'}
                        </div>
                    </div>
                    <div class="sl-card" style="padding:12px;">
                        <div class="sl-card-title" style="margin-bottom:10px;">
                            <span class="dot" style="--dot-color:#3699ff;"></span>Phân bổ áp lực (Top 15 trạm)
                        </div>
                        <div class="sl-canvas-wrap" style="min-height:220px;"><canvas id="slRTBar"></canvas></div>
                    </div>
                </div>

            </div>`;

        // Nếu không có tramDo, fallback sang bảng cũ (trams từ slData)
        if (!tramDo.length && trams.length) {
            document.getElementById('rt-col-left').innerHTML = trams.slice(0,Math.ceil(trams.length/2)).map(t=>`
                <div class="rt-station-block">
                    <div class="rt-station-name">${t.ten||t.ten_tram||t.ma_tram}</div>
                    <table style="width:100%;border-collapse:collapse;">
                        <tr><td style="color:#5a8ab5;font-size:.75rem;">Áp lực</td><td></td>
                            <td style="color:#3699ff;font-weight:700;text-align:right;">${t.ap_luc?t.ap_luc.toFixed(2)+' m':'—'}</td></tr>
                        <tr><td style="color:#5a8ab5;font-size:.75rem;">Lưu lượng</td><td></td>
                            <td style="color:#ffa800;font-weight:700;text-align:right;">${t.luu_luong?t.luu_luong.toFixed(1)+' m³/h':'—'}</td></tr>
                    </table>
                </div>`).join('');
            document.getElementById('rt-col-mid').innerHTML = trams.slice(Math.ceil(trams.length/2)).map(t=>`
                <div class="rt-station-block">
                    <div class="rt-station-name">${t.ten||t.ten_tram||t.ma_tram}</div>
                    <table style="width:100%;border-collapse:collapse;">
                        <tr><td style="color:#5a8ab5;font-size:.75rem;">Áp lực</td><td></td>
                            <td style="color:#3699ff;font-weight:700;text-align:right;">${t.ap_luc?t.ap_luc.toFixed(2)+' m':'—'}</td></tr>
                        <tr><td style="color:#ffa800;font-size:.75rem;">Lưu lượng</td><td></td>
                            <td style="color:#ffa800;font-weight:700;text-align:right;">${t.luu_luong?t.luu_luong.toFixed(1)+' m³/h':'—'}</td></tr>
                    </table>
                </div>`).join('');
        }

        // Bar chart top 15 áp lực
        const chartTrams = top15.length ? top15 : sorted.slice(0,15);
        slCharts.rtBar = new Chart(document.getElementById('slRTBar'), {
            type:'bar',
            data:{ labels:chartTrams.map(t=>t.ten||t.ten_tram||t.ma_tram), datasets:[{
                label:'Áp lực (m)',
                data:chartTrams.map(t=>t.ap_luc),
                backgroundColor:chartTrams.map(t=>{
                    if(t.ap_luc>=25) return 'rgba(54,153,255,.6)';
                    if(t.ap_luc>=15) return 'rgba(27,197,189,.6)';
                    if(t.ap_luc>=1)  return 'rgba(255,168,0,.6)';
                    return 'rgba(246,78,96,.6)';
                }),
                borderColor:chartTrams.map(t=>{
                    if(t.ap_luc>=25) return '#3699ff';
                    if(t.ap_luc>=15) return '#1bc5bd';
                    if(t.ap_luc>=1)  return '#ffa800';
                    return '#f64e60';
                }),
                borderWidth:1, borderRadius:4,
            }]},
            options:{ indexAxis:'y', ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{legend:{display:false},
                         tooltip:{callbacks:{label:c=>` ${c.parsed.x.toFixed(2)} m`}}},
                scales:{
                    x:{ticks:{color:'#3d6080',font:{size:10}},grid:{color:'rgba(54,153,255,.07)'}},
                    y:{ticks:{color:'#c0d0e8',font:{size:9}},grid:{display:false}}
                }
            }
        });
    }

    // ── Helpers ─────────────────────────────────────────────────
    function showEmpty() {
        document.getElementById('sl-content').innerHTML = '<div class="sl-loading" style="color:#3d5a78;"><i class="fa-solid fa-database me-2"></i>Chưa có dữ liệu cho kỳ này</div>';
    }

    // ── Load realtime KPI từ iot_realtime.json ──────────────────
    function loadRealtimeKPI() {
        if (curTab === 'realtime') return; // tab realtime tự load đủ
        fetch(`${IOT_BASE}?action=get&key=${IOT_KEY}`)
            .then(r => r.json())
            .then(data => {
                let totalFlow = 0, sumAp = 0, cntAp = 0;
                Object.values(data).forEach(d => {
                    totalFlow += parseFloat(d.luu_luong_thuan || d.luu_luong || 0);
                    const ap = parseFloat(d.ap_luc || d.ap_luc_sau_van || 0);
                    if (ap > 0) { sumAp += ap; cntAp++; }
                });
                const avgAp = cntAp ? (sumAp/cntAp).toFixed(2) : '—';
                // Chỉ update KPI flow/ap khi tab không dùng 4 ô đó
                if (curTab === 'ngay' || curTab === 'thang' || curTab === 'nam') {
                    // Các tab này dùng cả 4 ô cho hoá chất/nước — không ghi đè
                }
            })
            .catch(()=>{});
    }

    window.switchSLTab = function(loai, btn) {
        curTab = loai;
        document.querySelectorAll('.sl-tab').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        // Reset KPI
        ['kpi-nuoc','kpi-dien','kpi-flow','kpi-ap'].forEach(id=>{
            document.getElementById(id).innerHTML = '—';
        });
        ['kpi-nuoc-trend','kpi-dien-trend','kpi-flow-trend','kpi-ap-trend'].forEach(id=>{
            document.getElementById(id).innerHTML = '';
        });
        if (loai === 'khachhang') {
            fetch(`${IOT_BASE}?action=sanluong&loai=khachhang&key=${IOT_KEY}`)
                .then(r=>r.json()).then(renderKhachHang)
                .catch(()=>{ document.getElementById('sl-content').innerHTML='<div class="sl-loading" style="color:#f64e60;">Lỗi kết nối</div>'; });
            destroyCharts();
            document.getElementById('sl-content').innerHTML='<div class="sl-loading"><div class="sl-spinner"></div> Đang tải...</div>';
            return;
        }
        loadSLTab(loai);
    };

    // Khởi tạo
    loadSLTab('ngay');
    loadRealtimeKPI();
    setInterval(loadRealtimeKPI, 60000);

    // ================================================================
    // BẢNG THẤT THOÁT NƯỚC
    // ================================================================
    window.ttCurrentDays = 7;

    window.loadTTTable = function(days, btn) {
        ttCurrentDays = days;
        document.querySelectorAll('.tt-days-btn').forEach(b => b.classList.remove('active'));
        if (btn && btn.classList.contains('tt-days-btn')) btn.classList.add('active');

        document.getElementById('tt-content').innerHTML =
            '<div class="tt-loading"><div class="sl-spinner"></div> Đang tải...</div>';

        fetch(IOT_BASE + '?action=sanluong&loai=thatthoat&key=' + IOT_KEY)
            .then(r => r.json())
            .then(data => {
                if (!data.days || !data.days.length) {
                    document.getElementById('tt-content').innerHTML =
                        '<div class="tt-loading" style="color:#3d5a78;">Chưa có dữ liệu</div>';
                    return;
                }

                // Chi lay `days` ngay cuoi
                const allDays = data.days;
                const rows = allDays.slice(-days);
                renderTTRows(rows, days);
            })
            .catch(() => {
                document.getElementById('tt-content').innerHTML =
                    '<div class="tt-loading" style="color:#f64e60;"><i class="fa-solid fa-circle-exclamation me-2"></i>Không kết nối được SCADA server</div>';
            });
    };

    // Load khi trang mo
    loadTTTable(7, null);

    // Auto refresh moi 60 giay
    setInterval(function() {
        if (!document.hidden) {
            if (window.ttCurrentDays) window.loadTTTable(window.ttCurrentDays, null);
            if (curTab === 'ngay' || curTab === 'realtime') loadSLTab(curTab);
        }
    }, 60000);

    // Ham load theo khoang ngay tu date picker
    window.loadTTTableRange = function() {
        const from = document.getElementById('tt-date-from').value;
        const to   = document.getElementById('tt-date-to').value;
        if (!from || !to) { alert('Vui lòng chọn đủ từ ngày và đến ngày'); return; }
        if (from > to)    { alert('Từ ngày phải nhỏ hơn đến ngày'); return; }

        document.querySelectorAll('.tt-days-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tt-content').innerHTML =
            '<div class="tt-loading"><div class="sl-spinner"></div> Đang tải...</div>';

        fetch(IOT_BASE + '?action=sanluong&loai=thatthoat&key=' + IOT_KEY)
            .then(r => r.json())
            .then(function(data) {
                if (!data.days || !data.days.length) {
                    document.getElementById('tt-content').innerHTML =
                        '<div class="tt-loading" style="color:#3d5a78;">Chưa có dữ liệu</div>';
                    return;
                }
                const filtered = data.days.filter(function(d) {
                    if (!d.ngay) return false;
                    let iso = d.ngay;
                    if (d.ngay.includes('/')) {
                        const p = d.ngay.split('/');
                        iso = p[2] + '-' + p[1] + '-' + p[0];
                    }
                    return iso >= from && iso <= to;
                });
                if (!filtered.length) {
                    document.getElementById('tt-content').innerHTML =
                        '<div class="tt-loading" style="color:#3d5a78;">Không có dữ liệu trong khoảng này</div>';
                    return;
                }
                // Goi lai loadTTTable voi so ngay tuong duong de render
                const days = filtered.length;
                window.ttCurrentDays = days;
                // Thay the data.days bang filtered roi render
                const origFetch = window.fetch;
                const fakeResponse = { days: filtered };
                // Render truc tiep bang cach copy logic tu loadTTTable
                renderTTRows(filtered, days);
            })
            .catch(function() {
                document.getElementById('tt-content').innerHTML =
                    '<div class="tt-loading" style="color:#f64e60;">Lỗi kết nối SCADA</div>';
            });
    };

    // Tach rieng phan render de tai su dung
    function renderTTRows(rows, days) {
        const today    = new Date();
        const todayStr = today.toLocaleDateString('vi-VN', {day:'2-digit',month:'2-digit',year:'numeric'}).replace(/\//g,'/');

        const sumHeadCols = `
            <th style="background:rgba(54,153,255,.12);color:#7ab8ff;white-space:nowrap;text-align:center;">
                Tổng<br><small style="font-size:.65rem;opacity:.7;">${days} ngày</small>
            </th>
            <th style="background:rgba(54,153,255,.08);color:#5a9fd4;white-space:nowrap;text-align:center;">
                TB / ngày
            </th>`;

        let headCols = rows.map(function(d, i) {
            const isToday = d.ngay === todayStr || i === rows.length - 1;
            return `<th class="${isToday ? 'tt-today-head' : ''}">${d.ngay}</th>`;
        }).join('');

        const validRows   = rows.filter(function(d) { return d.ti_le !== null && d.ti_le !== undefined && d.nuoc_cap > 0 && d.nuoc_kh > 0; });
        const sumRaw      = rows.reduce(function(s,d){ return s+(d.nuoc_tho||0); }, 0);
        const sumCap      = rows.reduce(function(s,d){ return s+(d.nuoc_cap||0); }, 0);
        const sumKH       = validRows.reduce(function(s,d){ return s+(d.nuoc_kh||0); }, 0);
        const sumNRW      = validRows.reduce(function(s,d){ return s+(d.that_thoat||0); }, 0);
        const sumCapValid = validRows.reduce(function(s,d){ return s+(d.nuoc_cap||0); }, 0);
        const avgTL       = sumCapValid > 0 ? sumNRW / sumCapValid * 100 : 0;

        const ROWS_DEF = [
            { key:'nuoc_tho',   label:'Sản lượng nước thô (m³)',      cls:'tt-row-raw', valCls:'val-raw', fmt:function(v){ return v>0?v.toLocaleString('vi-VN'):'—'; }, sum:sumRaw },
            { key:'nuoc_cap',   label:'Nước sạch cấp ra mạng (m³)',   cls:'tt-row-cap', valCls:'val-cap', fmt:function(v){ return v>0?v.toLocaleString('vi-VN'):'—'; }, sum:sumCap },
            { key:'nuoc_kh',    label:'Sản lượng khách hàng (m³)',    cls:'tt-row-kh',  valCls:'val-kh',  fmt:function(v){ return v>0?v.toLocaleString('vi-VN'):'—'; }, sum:sumKH  },
            { key:'that_thoat', label:'Lượng nước thất thoát (m³)',   cls:'tt-row-nrw', valCls:'val-nrw', fmt:function(v){ return v>0?v.toLocaleString('vi-VN'):'—'; }, sum:sumNRW },
            { key:'ti_le',      label:'Tỷ lệ thất thoát (%)',         cls:'tt-row-tl',  valCls:null,
              fmt:function(v) {
                if (v===null||v===undefined) return '<span style="color:#3d5a78;font-size:.75rem;">—</span>';
                const cls  = v<15?'val-tl-ok':v<=20?'val-tl-warn':'val-tl-bad';
                const icon = v<15?'↓':v<=20?'→':'↑';
                return `<span class="val-tl ${cls}">${icon} ${v.toFixed(2)}%</span>`;
              }, sum:null, avgTL:avgTL },
        ];

        let bodyRows = '';
        ROWS_DEF.forEach(function(row) {
            let cells = rows.map(function(d, i) {
                const isToday = i === rows.length - 1;
                const val     = d[row.key];
                const hasNote = d.note && row.key === 'ti_le';
                let disp = row.fmt(val);
                if (hasNote) disp = `<span style="color:#4d6d8a;font-size:.72rem;font-style:italic;">${d.note}</span>`;
                const vCls  = row.valCls || '';
                const style = (d.note && row.key !== 'ti_le') ? 'opacity:.5;' : '';
                return `<td class="${isToday?'tt-today':''} ${vCls}" style="${style}">${disp}</td>`;
            }).join('');

            let sumCell = '';
            if (row.key === 'ti_le') {
                if (validRows.length > 0) {
                    const cls  = avgTL<15?'val-tl-ok':avgTL<=20?'val-tl-warn':'val-tl-bad';
                    const note = validRows.length < rows.length
                        ? `<br><small style="opacity:.55;font-size:.65rem">${validRows.length}/${rows.length} ngày hợp lệ</small>` : '';
                    sumCell = `<td class="tt-sum-col" style="text-align:center;">—</td>`
                            + `<td class="tt-sum-col" style="text-align:center;background:rgba(54,153,255,.06)"><span class="val-tl ${cls}">${avgTL.toFixed(2)}%</span>${note}</td>`;
                } else {
                    sumCell = '<td class="tt-sum-col" style="color:#3d5a78;text-align:center;">—</td>'
                            + '<td class="tt-sum-col" style="color:#3d5a78;text-align:center;background:rgba(54,153,255,.06)">—</td>';
                }
            } else {
                const sumVal = row.key==='nuoc_kh'?sumKH:row.key==='that_thoat'?sumNRW:(row.sum||0);
                const countForAvg = (row.key==='nuoc_kh'||row.key==='that_thoat')
                    ? validRows.length
                    : rows.filter(function(d){ return (d[row.key]||0)>0; }).length;
                const tbVal = countForAvg > 0 ? sumVal / countForAvg : 0;
                const vCls  = row.valCls || '';
                const fmtN  = function(v){ return v>0?Math.round(v).toLocaleString('vi-VN'):'—'; };
                sumCell = `<td class="tt-sum-col ${vCls}">${fmtN(sumVal)}</td>`
                        + `<td class="tt-sum-col ${vCls}" style="background:rgba(54,153,255,.06)">${fmtN(tbVal)}</td>`;
            }
            bodyRows += `<tr class="${row.cls}"><td>${row.label}</td>${sumCell}${cells}</tr>`;
        });

        document.getElementById('tt-content').innerHTML = `
            <div class="tt-wrap">
                <table class="tt-table">
                    <thead><tr>
                        <th style="min-width:220px;text-align:left;">Chỉ tiêu</th>
                        ${sumHeadCols}
                        ${headCols}
                    </tr></thead>
                    <tbody>${bodyRows}</tbody>
                </table>
            </div>`;
    }

});
</script>