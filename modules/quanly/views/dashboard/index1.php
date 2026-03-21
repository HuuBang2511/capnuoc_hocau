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
        background:linear-gradient(135deg, #0f1629 0%, #131d38 100%);
        border-radius:16px; padding:1.75rem; margin-bottom:1.5rem;
        border:1px solid var(--sl-border);
        box-shadow:0 20px 60px rgba(0,0,0,.25);
    }
    .sl-section-header {
        display:flex; justify-content:space-between; align-items:center;
        margin-bottom:1.5rem; padding-bottom:1rem;
        border-bottom:1px solid rgba(54,153,255,.15);
    }
    .sl-section-title {
        font-size:1.1rem; font-weight:700; color:#fff;
        display:flex; align-items:center; gap:10px; margin:0;
    }
    .sl-section-title .icon-badge {
        width:36px; height:36px; border-radius:9px;
        background:linear-gradient(135deg,#3699ff,#00d4ff);
        display:flex; align-items:center; justify-content:center;
        font-size:.95rem; color:#fff; flex-shrink:0;
    }

    /* Tab switcher */
    .sl-tabs { display:flex; gap:6px; }
    .sl-tab {
        padding:6px 14px; border-radius:8px; border:1px solid rgba(54,153,255,.25);
        background:transparent; color:#5a82a8; font-size:.78rem; font-weight:600;
        cursor:pointer; transition:all .2s; letter-spacing:.3px;
    }
    .sl-tab:hover, .sl-tab.active {
        background:rgba(54,153,255,.18); border-color:var(--sl-blue); color:#7ab8ff;
    }

    /* KPI mini cards trong section sản lượng */
    .sl-kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:1.5rem; }
    .sl-kpi {
        background:rgba(255,255,255,.04); border:1px solid rgba(54,153,255,.12);
        border-radius:10px; padding:14px 16px; position:relative; overflow:hidden;
        transition:all .2s;
    }
    .sl-kpi::before {
        content:''; position:absolute; top:0; left:0; right:0; height:3px;
        background:var(--kpi-color, var(--sl-blue));
    }
    .sl-kpi:hover { background:rgba(54,153,255,.08); border-color:rgba(54,153,255,.3); }
    .sl-kpi-label { font-size:.7rem; color:#4d6d8a; font-weight:600; text-transform:uppercase; letter-spacing:.6px; margin-bottom:6px; }
    .sl-kpi-val   { font-size:1.55rem; font-weight:800; color:#fff; line-height:1.1; }
    .sl-kpi-unit  { font-size:.72rem; color:#3d6080; margin-left:3px; font-weight:400; }
    .sl-kpi-trend {
        font-size:.72rem; margin-top:5px; display:flex; align-items:center; gap:4px;
    }
    .trend-up   { color:#1bc5bd; }
    .trend-down { color:#f64e60; }

    /* Chart panels trong dark section */
    .sl-chart-grid { display:grid; grid-template-columns:2fr 1fr; gap:14px; margin-bottom:14px; }
    .sl-chart-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
    .sl-card {
        background:rgba(255,255,255,.04); border:1px solid rgba(54,153,255,.1);
        border-radius:12px; padding:16px; display:flex; flex-direction:column;
    }
    .sl-card-title {
        font-size:.78rem; font-weight:700; color:#7ab8ff; text-transform:uppercase;
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
        color:#3d5a78; font-weight:700; font-size:.68rem;
        text-transform:uppercase; letter-spacing:.5px;
        border-bottom:1px solid rgba(54,153,255,.12);
    }
    .rt-table td { padding:7px 10px; border-bottom:1px solid rgba(255,255,255,.04); color:#cdd9ee; }
    .rt-table tr:last-child td { border-bottom:none; }
    .rt-table tr:hover td { background:rgba(54,153,255,.06); }
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
        min-height:160px; color:#3d5a78; font-size:.85rem; gap:10px;
    }
    .sl-spinner { width:20px; height:20px; border:2px solid #1a2d50; border-top-color:#3699ff; border-radius:50%; animation:spin .8s linear infinite; }
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
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-layer-group text-primary me-2"></i>Dashboard Tổng Hợp</h3>
        <button class="btn btn-sm btn-light text-primary fw-bold shadow-sm" onclick="location.reload()">
            <i class="fas fa-sync me-1"></i> Làm mới
        </button>
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
            <div class="sl-tabs">
                <button class="sl-tab active" onclick="switchSLTab('ngay',this)">Theo Ngày</button>
                <button class="sl-tab" onclick="switchSLTab('thang',this)">Theo Tháng</button>
                <button class="sl-tab" onclick="switchSLTab('nam',this)">Theo Năm</button>
                <button class="sl-tab" onclick="switchSLTab('khachhang',this)">Khách Hàng</button>
                <button class="sl-tab" onclick="switchSLTab('realtime',this)">Realtime</button>
            </div>
        </div>

        <!-- KPI mini tự cập nhật theo tab -->
        <div class="sl-kpi-grid" id="sl-kpi-row">
            <div class="sl-kpi" style="--kpi-color:#3699ff;">
                <div class="sl-kpi-label">Sản lượng nước sạch</div>
                <div class="sl-kpi-val" id="kpi-nuoc">—<span class="sl-kpi-unit">m³</span></div>
                <div class="sl-kpi-trend" id="kpi-nuoc-trend"></div>
            </div>
            <div class="sl-kpi" style="--kpi-color:#ffa800;">
                <div class="sl-kpi-label">Điện năng tiêu thụ</div>
                <div class="sl-kpi-val" id="kpi-dien">—<span class="sl-kpi-unit">KWh</span></div>
                <div class="sl-kpi-trend" id="kpi-dien-trend"></div>
            </div>
            <div class="sl-kpi" style="--kpi-color:#1bc5bd;">
                <div class="sl-kpi-label">Lưu lượng hiện tại</div>
                <div class="sl-kpi-val" id="kpi-flow">—<span class="sl-kpi-unit">m³/h</span></div>
                <div class="sl-kpi-trend" id="kpi-flow-trend"></div>
            </div>
            <div class="sl-kpi" style="--kpi-color:#f64e60;">
                <div class="sl-kpi-label">Áp lực TB mạng lưới</div>
                <div class="sl-kpi-val" id="kpi-ap">—<span class="sl-kpi-unit">m</span></div>
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
    let   slCharts  = {};   // lưu chart instances
    let   curTab    = 'ngay';
    let   rtData    = null; // cache realtime

    // Màu palette dark theme
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
        if (n >= 1e6) return (n/1e6).toFixed(2) + 'M';
        if (n >= 1e3) return (n/1e3).toFixed(1) + 'K';
        return parseFloat(n).toFixed(1);
    }
    function trendHtml(cur, prev, unit) {
        if (!prev) return '';
        const d = cur - prev;
        const pct = prev ? Math.abs(d/prev*100).toFixed(1) : 0;
        const icon = d >= 0 ? '▲' : '▼';
        const cls  = d >= 0 ? 'trend-up' : 'trend-down';
        return `<span class="${cls}">${icon} ${pct}%</span> so kỳ trước`;
    }

    const darkChartDefaults = {
        color: '#5a82a8',
        scale: { grid:{ color:'rgba(54,153,255,.07)' }, ticks:{ color:'#3d6080' } }
    };

    function destroyCharts() {
        Object.values(slCharts).forEach(c => c.destroy());
        slCharts = {};
    }

    // ── Fetch & render tab ──────────────────────────────────────
    function loadSLTab(loai) {
        const content = document.getElementById('sl-content');
        content.innerHTML = '<div class="sl-loading"><div class="sl-spinner"></div> Đang tải...</div>';
        destroyCharts();

        fetch(`${IOT_BASE}?action=sanluong&loai=${loai}&key=${IOT_KEY}`)
            .then(r => r.json())
            .then(data => {
                if      (loai === 'ngay')      renderNgay(data);
                else if (loai === 'thang')     renderThang(data);
                else if (loai === 'nam')       renderNam(data);
                else if (loai === 'khachhang') renderKhachHang(data);
                else if (loai === 'realtime')  renderRealtime(data);
            })
            .catch(() => {
                content.innerHTML = '<div class="sl-loading" style="color:#f64e60;"><i class="fa-solid fa-circle-exclamation me-2"></i>Không kết nối được SCADA server</div>';
            });
    }

    // ── Tab: Theo Ngày ──────────────────────────────────────────
    function renderNgay(d) {
        if (!d.labels || !d.labels.length) { showEmpty(); return; }
        const last   = d.nuoc_sach[d.nuoc_sach.length-1];
        const prev   = d.nuoc_sach[d.nuoc_sach.length-2];
        const lastD  = d.dien_nang[d.dien_nang.length-1];

        updateKPI(fmt(last)+'<span class="sl-kpi-unit">m³</span>', trendHtml(last,prev,'m³'),
                  fmt(lastD)+'<span class="sl-kpi-unit">KWh</span>', '',
                  null, null, null, null);

        document.getElementById('sl-content').innerHTML = `
            <div class="sl-chart-grid">
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#3699ff;"></span>Sản lượng nước sạch (m³) — 30 ngày</div>
                    <div class="sl-canvas-wrap"><canvas id="slNuocNgay"></canvas></div>
                </div>
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#ffa800;"></span>Điện năng (KWh)</div>
                    <div class="sl-canvas-wrap"><canvas id="slDienNgay"></canvas></div>
                </div>
            </div>`;

        // Nuoc sach - area chart
        const ctx1 = document.getElementById('slNuocNgay');
        slCharts.nuoc = new Chart(ctx1, {
            type:'line',
            data:{ labels:d.labels, datasets:[{
                label:'m³', data:d.nuoc_sach,
                borderColor:C.blue, backgroundColor:mkGrad(ctx1.getContext('2d'),C.blue2,'rgba(54,153,255,0)'),
                fill:true, tension:.4, borderWidth:2,
                pointRadius:d.labels.length>20?0:3, pointHoverRadius:5,
            }]},
            options:{ ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{display:false}, tooltip:{backgroundColor:'#0d1829',titleColor:'#7ab8ff',bodyColor:'#fff',callbacks:{label:c=>` ${fmt(c.parsed.y)} m³`}} },
                scales:{ x:{ticks:{color:'#3d6080',maxTicksLimit:8,font:{size:10}},grid:{color:'rgba(54,153,255,.06)'}},
                         y:{ticks:{color:'#3d6080',font:{size:10},callback:v=>fmt(v)},grid:{color:'rgba(54,153,255,.08)'}} }
            }
        });

        // Dien nang - bar
        slCharts.dien = new Chart(document.getElementById('slDienNgay'), {
            type:'bar',
            data:{ labels:d.labels, datasets:[{
                label:'KWh', data:d.dien_nang,
                backgroundColor:C.amber2, borderColor:C.amber,
                borderWidth:1, borderRadius:4,
            }]},
            options:{ ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{display:false} },
                scales:{ x:{ticks:{color:'#3d6080',maxTicksLimit:8,font:{size:10}},grid:{display:false}},
                         y:{ticks:{color:'#3d6080',font:{size:10},callback:v=>fmt(v)},grid:{color:'rgba(54,153,255,.08)'}} }
            }
        });
    }

    // ── Tab: Theo Tháng ─────────────────────────────────────────
    function renderThang(d) {
        if (!d.labels || !d.labels.length) { showEmpty(); return; }
        const last = d.nuoc_sach[d.nuoc_sach.length-1];
        const prev = d.nuoc_sach[d.nuoc_sach.length-2];
        updateKPI(fmt(last)+'<span class="sl-kpi-unit">m³</span>', trendHtml(last,prev,''),
                  fmt(d.dien_nang[d.dien_nang.length-1])+'<span class="sl-kpi-unit">KWh</span>','',
                  fmt(d.pac[d.pac.length-1])+'<span class="sl-kpi-unit">kg</span>','',
                  fmt(d.chlorin[d.chlorin.length-1])+'<span class="sl-kpi-unit">kg</span>','');
        // Update labels kpi 3,4
        document.getElementById('kpi-flow').innerHTML = fmt(d.pac[d.pac.length-1])+'<span class="sl-kpi-unit">kg PAC</span>';
        document.getElementById('kpi-ap').innerHTML   = fmt(d.chlorin[d.chlorin.length-1])+'<span class="sl-kpi-unit">kg Clo</span>';

        document.getElementById('sl-content').innerHTML = `
            <div class="sl-chart-grid">
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#3699ff;"></span>Sản lượng nước sạch & điện năng — 24 tháng</div>
                    <div class="sl-canvas-wrap" style="min-height:220px;"><canvas id="slComboThang"></canvas></div>
                </div>
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#1bc5bd;"></span>Hóa chất xử lý (kg)</div>
                    <div class="sl-canvas-wrap"><canvas id="slHoaChatThang"></canvas></div>
                </div>
            </div>`;

        const ctx = document.getElementById('slComboThang');
        slCharts.combo = new Chart(ctx, {
            type:'bar',
            data:{ labels:d.labels, datasets:[
                { type:'bar',  label:'Nước sạch (m³)', data:d.nuoc_sach, backgroundColor:C.blue2, borderColor:C.blue, borderWidth:1, borderRadius:3, yAxisID:'y' },
                { type:'line', label:'Điện năng (KWh)', data:d.dien_nang, borderColor:C.amber, backgroundColor:'transparent', borderWidth:2, tension:.4, pointRadius:3, yAxisID:'y1' },
            ]},
            options:{ ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}} },
                scales:{
                    x:{ticks:{color:'#3d6080',maxTicksLimit:12,font:{size:10}},grid:{display:false}},
                    y:{ticks:{color:'#3d6080',font:{size:10},callback:v=>fmt(v)},grid:{color:'rgba(54,153,255,.07)'},title:{display:true,text:'m³',color:'#3d6080',font:{size:10}}},
                    y1:{position:'right',ticks:{color:'#5a6080',font:{size:10},callback:v=>fmt(v)},grid:{display:false},title:{display:true,text:'KWh',color:'#5a6080',font:{size:10}}}
                }
            }
        });

        slCharts.hc = new Chart(document.getElementById('slHoaChatThang'), {
            type:'bar',
            data:{ labels:d.labels, datasets:[
                { label:'PAC (kg)',    data:d.pac,    backgroundColor:'rgba(27,197,189,.25)', borderColor:C.green, borderWidth:1, borderRadius:3 },
                { label:'Chlorin (kg)',data:d.chlorin,backgroundColor:'rgba(137,80,252,.25)', borderColor:C.purple,borderWidth:1, borderRadius:3 },
            ]},
            options:{ ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}} },
                scales:{ x:{ticks:{color:'#3d6080',maxTicksLimit:8,font:{size:10}},grid:{display:false}},
                         y:{ticks:{color:'#3d6080',font:{size:10}},grid:{color:'rgba(54,153,255,.07)'}} }
            }
        });
    }

    // ── Tab: Theo Năm ───────────────────────────────────────────
    function renderNam(d) {
        if (!d.labels || !d.labels.length) { showEmpty(); return; }
        const last = d.nuoc_sach[d.nuoc_sach.length-1];
        updateKPI(fmt(last)+'<span class="sl-kpi-unit">m³</span>','',
                  fmt(d.dien_nang[d.dien_nang.length-1])+'<span class="sl-kpi-unit">KWh</span>','',
                  null,null,null,null);

        document.getElementById('sl-content').innerHTML = `
            <div class="sl-chart-grid-3">
                <div class="sl-card" style="grid-column:1/3;">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#3699ff;"></span>Tăng trưởng sản lượng nước sạch theo năm</div>
                    <div class="sl-canvas-wrap" style="min-height:220px;"><canvas id="slNamBar"></canvas></div>
                </div>
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#ffa800;"></span>Điện năng / năm</div>
                    <div class="sl-canvas-wrap"><canvas id="slNamDien"></canvas></div>
                </div>
            </div>`;

        // Tinh % tang truong
        const growth = d.nuoc_sach.map((v,i) => i===0?0:parseFloat(((v-d.nuoc_sach[i-1])/d.nuoc_sach[i-1]*100).toFixed(1)));

        slCharts.namBar = new Chart(document.getElementById('slNamBar'), {
            type:'bar',
            data:{ labels:d.labels, datasets:[
                { type:'bar',  label:'Sản lượng (m³)', data:d.nuoc_sach, backgroundColor:d.labels.map((_,i)=>i===d.labels.length-1?C.blue:C.blue2), borderColor:C.blue, borderWidth:1, borderRadius:6, yAxisID:'y' },
                { type:'line', label:'Tăng trưởng (%)', data:growth, borderColor:C.green, backgroundColor:'transparent', borderWidth:2, tension:.3, pointRadius:4, yAxisID:'y1' },
            ]},
            options:{ ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8}} },
                scales:{
                    x:{ticks:{color:'#3d6080',font:{size:12}},grid:{display:false}},
                    y:{ticks:{color:'#3d6080',font:{size:10},callback:v=>fmt(v)},grid:{color:'rgba(54,153,255,.07)'}},
                    y1:{position:'right',ticks:{color:'#1bc5bd',font:{size:10},callback:v=>v+'%'},grid:{display:false}}
                }
            }
        });

        slCharts.namDien = new Chart(document.getElementById('slNamDien'), {
            type:'doughnut',
            data:{ labels:d.labels, datasets:[{data:d.dien_nang,backgroundColor:[C.amber2,'rgba(255,168,0,.35)','rgba(255,168,0,.5)',C.amber,'rgba(255,140,0,.8)'],borderWidth:0,hoverBorderWidth:2,hoverBorderColor:C.amber}] },
            options:{ responsive:true, maintainAspectRatio:false, cutout:'65%',
                plugins:{ legend:{position:'bottom',labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}},
                          tooltip:{backgroundColor:'#0d1829',titleColor:'#7ab8ff',bodyColor:'#fff',callbacks:{label:c=>` ${fmt(c.parsed)} KWh`}} }
            }
        });
    }

    // ── Tab: Khách Hàng ─────────────────────────────────────────
    function renderKhachHang(d) {
        if (!d.kh_labels || !d.kh_labels.length) { showEmpty(); return; }

        // Top khach hang hom nay
        const totalKH = d.kh_values.reduce((a,b)=>a+b, 0);
        updateKPI(fmt(totalKH)+'<span class="sl-kpi-unit">m³</span>','Tổng sản lượng khách hàng lớn',
                  null,null,null,null,null,null);

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
            options:{ ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:11}}} },
                scales:{ x:{ticks:{color:'#3d6080',font:{size:10}},grid:{display:false}},
                         y:{ticks:{color:'#3d6080',font:{size:10},callback:v=>fmt(v)},grid:{color:'rgba(54,153,255,.07)'}} }
            }
        });

        slCharts.khShare = new Chart(document.getElementById('slKHShare'), {
            type:'doughnut',
            data:{ labels:d.kh_labels, datasets:[{data:d.kh_values,backgroundColor:colors.slice(0,d.kh_labels.length),borderWidth:0,hoverOffset:6}] },
            options:{ responsive:true, maintainAspectRatio:false, cutout:'60%',
                plugins:{ legend:{position:'right',labels:{color:'#5a82a8',usePointStyle:true,boxWidth:8,font:{size:10}}},
                          tooltip:{backgroundColor:'#0d1829',titleColor:'#7ab8ff',bodyColor:'#fff',callbacks:{label:c=>` ${fmt(c.parsed)} m³`}} }
            }
        });
    }

    // ── Tab: Realtime ───────────────────────────────────────────
    function renderRealtime(d) {
        if (!d.trams || !d.trams.length) { showEmpty(); return; }
        rtData = d.trams;

        // Tinh KPI tong hop
        const trams     = d.trams.filter(t => t.ap_luc > 0);
        const totalFlow = d.trams.reduce((s,t)=>s+t.luu_luong,0);
        const avgAp     = trams.length ? trams.reduce((s,t)=>s+t.ap_luc,0)/trams.length : 0;

        updateKPI(null,null,
                  fmt(totalFlow)+'<span class="sl-kpi-unit">m³/h</span>','Tổng lưu lượng mạng lưới',
                  avgAp.toFixed(2)+'<span class="sl-kpi-unit">m</span>','Áp lực TB '+trams.length+' trạm');

        // Sort by ap_luc desc
        const sorted = [...d.trams].sort((a,b)=>b.ap_luc-a.ap_luc);

        document.getElementById('sl-content').innerHTML = `
            <div class="sl-chart-grid">
                <div class="sl-card" style="max-height:400px;overflow-y:auto;">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#1bc5bd;"></span>Trạng thái ${d.trams.length} trạm SCADA (realtime)</div>
                    <table class="rt-table">
                        <thead><tr><th>Trạm</th><th>Áp lực</th><th>Lưu lượng</th><th>Cập nhật</th></tr></thead>
                        <tbody id="rt-tbody"></tbody>
                    </table>
                </div>
                <div class="sl-card">
                    <div class="sl-card-title"><span class="dot" style="--dot-color:#3699ff;"></span>Phân bổ áp lực (top 15 trạm)</div>
                    <div class="sl-canvas-wrap" style="min-height:280px;"><canvas id="slRTBar"></canvas></div>
                </div>
            </div>`;

        // Render table
        const tbody = document.getElementById('rt-tbody');
        sorted.forEach(t => {
            const ap = t.ap_luc;
            let cls, lbl;
            if      (ap >= 25)           { cls='ap-high';  lbl=ap.toFixed(2)+'m'; }
            else if (ap >= 15)           { cls='ap-med';   lbl=ap.toFixed(2)+'m'; }
            else if (ap >= 1)            { cls='ap-low';   lbl=ap.toFixed(2)+'m'; }
            else if (ap > 0)             { cls='ap-alert'; lbl=ap.toFixed(2)+'m'; }
            else                         { cls='ap-none';  lbl='—'; }
            tbody.innerHTML += `<tr>
                <td style="font-weight:600;">${t.ten}</td>
                <td><span class="ap-badge ${cls}">${lbl}</span></td>
                <td style="color:#ffa800;">${t.luu_luong>0?t.luu_luong.toFixed(1)+' m³/h':'—'}</td>
                <td style="color:#3d5a78;font-size:.72rem;">${t.timestamp}</td>
            </tr>`;
        });

        // Bar chart top 15
        const top15 = sorted.filter(t=>t.ap_luc>0).slice(0,15);
        slCharts.rtBar = new Chart(document.getElementById('slRTBar'), {
            type:'bar',
            data:{ labels:top15.map(t=>t.ten), datasets:[{
                label:'Áp lực (m)',
                data:top15.map(t=>t.ap_luc),
                backgroundColor:top15.map(t=>{
                    if(t.ap_luc>=25) return 'rgba(54,153,255,.6)';
                    if(t.ap_luc>=15) return 'rgba(27,197,189,.6)';
                    if(t.ap_luc>=1)  return 'rgba(255,168,0,.6)';
                    return 'rgba(246,78,96,.6)';
                }),
                borderColor:top15.map(t=>{
                    if(t.ap_luc>=25) return '#3699ff';
                    if(t.ap_luc>=15) return '#1bc5bd';
                    if(t.ap_luc>=1)  return '#ffa800';
                    return '#f64e60';
                }),
                borderWidth:1, borderRadius:4,
            }]},
            options:{ indexAxis:'y', ...darkChartDefaults, responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{display:false},
                          tooltip:{backgroundColor:'#0d1829',titleColor:'#7ab8ff',bodyColor:'#fff',callbacks:{label:c=>` ${c.parsed.x.toFixed(2)} m`}} },
                scales:{
                    x:{ticks:{color:'#3d6080',font:{size:10}},grid:{color:'rgba(54,153,255,.07)'},
                       // Duong nguong ap luc
                    },
                    y:{ticks:{color:'#c0d0e8',font:{size:10}},grid:{display:false}}
                }
            }
        });
    }

    // ── Helpers ─────────────────────────────────────────────────
    function showEmpty() {
        document.getElementById('sl-content').innerHTML = '<div class="sl-loading" style="color:#3d5a78;"><i class="fa-solid fa-database me-2"></i>Chưa có dữ liệu cho kỳ này</div>';
    }

    function updateKPI(nuoc, nuocTrend, dien, dienTrend, flow, flowTrend, ap, apTrend) {
        if (nuoc  !== null && nuoc  !== undefined) document.getElementById('kpi-nuoc').innerHTML  = nuoc;
        if (dien  !== null && dien  !== undefined) document.getElementById('kpi-dien').innerHTML  = dien;
        if (flow  !== null && flow  !== undefined) document.getElementById('kpi-flow').innerHTML  = flow;
        if (ap    !== null && ap    !== undefined) document.getElementById('kpi-ap').innerHTML    = ap;
        if (nuocTrend) document.getElementById('kpi-nuoc-trend').innerHTML = nuocTrend;
        if (dienTrend) document.getElementById('kpi-dien-trend').innerHTML = dienTrend;
        if (flowTrend) document.getElementById('kpi-flow-trend').innerHTML = flowTrend;
        if (apTrend)   document.getElementById('kpi-ap-trend').innerHTML   = apTrend;
    }

    // ── Load realtime KPI từ iot_realtime.json ──────────────────
    function loadRealtimeKPI() {
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
                if (curTab !== 'realtime' && curTab !== 'khachhang') {
                    document.getElementById('kpi-flow').innerHTML = fmt(totalFlow)+'<span class="sl-kpi-unit">m³/h</span>';
                    document.getElementById('kpi-ap').innerHTML   = avgAp+'<span class="sl-kpi-unit">m</span>';
                }
            })
            .catch(()=>{});
    }

    window.switchSLTab = function(loai, btn) {
        curTab = loai;
        document.querySelectorAll('.sl-tab').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        // Reset KPI labels
        document.querySelector('.sl-kpi:nth-child(3) .sl-kpi-label').textContent = 'Lưu lượng hiện tại';
        document.querySelector('.sl-kpi:nth-child(4) .sl-kpi-label').textContent = 'Áp lực TB mạng lưới';
        loadSLTab(loai);
    };

    // Khởi tạo
    loadSLTab('ngay');
    loadRealtimeKPI();
    setInterval(loadRealtimeKPI, 30000);
});
</script>