<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Trung Tâm Điều Hành Hạ Tầng Cấp Nước';

// CDN Resources
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js');
?>

<style>
    :root {
        --bg-body: #f4f6f9;
        --card-bg: #ffffff;
        --primary-color: #3699ff; /* Xanh dương */
        --success-color: #1bc5bd; /* Xanh lá */
        --warning-color: #ffa800; /* Cam vàng */
        --danger-color: #f64e60; /* Đỏ */
        --info-color: #8950fc; /* Tím */
        --dark-color: #181c32;
        --text-muted: #b5b5c3;
    }
    
    body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; }

    /* --- KPI CARD STYLING (VIP 3D EFFECT) --- */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr); /* 5 cột đều nhau */
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .kpi-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 0 20px 0 rgba(76, 87, 125, 0.02);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid transparent;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px 0 rgba(76, 87, 125, 0.1);
    }

    /* Màu sắc viền dưới cho từng loại */
    .border-blue { border-bottom-color: var(--primary-color); }
    .border-green { border-bottom-color: var(--success-color); }
    .border-orange { border-bottom-color: var(--warning-color); }
    .border-red { border-bottom-color: var(--danger-color); }
    .border-purple { border-bottom-color: var(--info-color); }

    .kpi-icon-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .kpi-icon {
        width: 45px; height: 45px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        background: rgba(128, 128, 128, 0.1);
    }
    
    .bg-light-blue { background: #e1f0ff; color: var(--primary-color); }
    .bg-light-green { background: #c9f7f5; color: var(--success-color); }
    .bg-light-orange { background: #fff4de; color: var(--warning-color); }
    .bg-light-red { background: #ffe2e5; color: var(--danger-color); }
    .bg-light-purple { background: #eee5ff; color: var(--info-color); }

    .kpi-value { font-size: 1.8rem; font-weight: 800; color: var(--dark-color); line-height: 1.2; }
    .kpi-title { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 5px;}
    .kpi-unit { font-size: 0.8rem; font-weight: 500; color: var(--text-muted); }

    /* --- CHART SECTION --- */
    .chart-panel {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 0 20px 0 rgba(76, 87, 125, 0.02);
        height: 100%;
        min-height: 380px;
        display: flex; flex-direction: column;
    }
    .panel-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1rem; border-bottom: 1px dashed #ebedf3; padding-bottom: 0.5rem;
    }
    .panel-title { font-weight: 700; font-size: 1rem; color: var(--dark-color); margin: 0; display: flex; align-items: center;}
    .panel-title i { margin-right: 8px; opacity: 0.7; }
    .chart-wrapper { flex-grow: 1; position: relative; width: 100%; }

    /* --- RESPONSIVE GRID --- */
    @media (max-width: 1200px) { .kpi-row { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .kpi-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .kpi-row { grid-template-columns: 1fr; } }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-layer-group text-primary me-2"></i>Dashboard Tổng Hợp</h3>
        <button class="btn btn-sm btn-light text-primary fw-bold shadow-sm"><i class="fas fa-sync me-1"></i> Làm mới</button>
    </div>

    <!-- HÀNG 1: 5 ĐỐI TƯỢNG CẤP CAO -->
    <div class="kpi-row">
        <!-- 1. Nhà máy -->
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

        <!-- 2. Ống truyền dẫn -->
        <a href="<?= $urlTruyenDan ?>" class="kpi-card border-blue">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-blue"><i class="fa-solid fa-code-branch"></i></div>
                <span class="badge bg-primary text-white">Chính</span>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($lenTruyenDan, 1) ?> <span class="kpi-unit">km</span></span>
                <div class="kpi-title">Ống truyền dẫn</div>
            </div>
        </a>

        <!-- 3. Ống phân phối -->
        <a href="<?= $urlPhanPhoi ?>" class="kpi-card border-purple">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-purple"><i class="fa-solid fa-network-wired"></i></div>
                <span class="badge bg-info text-white">Mạng lưới</span>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($lenPhanPhoi, 1) ?> <span class="kpi-unit">km</span></span>
                <div class="kpi-title">Ống phân phối</div>
            </div>
        </a>

        <!-- 4. Đồng hồ tổng -->
        <a href="<?= $urlDHTong ?>" class="kpi-card border-green">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-green"><i class="fa-solid fa-gauge-high"></i></div>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($cntDHTong) ?></span>
                <div class="kpi-title">Đồng hồ tổng</div>
            </div>
        </a>

        <!-- 5. Đồng hồ khách hàng -->
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

    <!-- HÀNG 2: 5 ĐỐI TƯỢNG CHI TIẾT & SỰ CỐ -->
    <div class="kpi-row">
        <!-- 6. Van -->
        <a href="<?= $urlVan ?>" class="kpi-card border-orange">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-orange"><i class="fa-solid fa-faucet"></i></div>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($cntVan) ?></span>
                <div class="kpi-title">Van mạng lưới</div>
            </div>
        </a>

        <!-- 7. Mối nối -->
        <a href="<?= $urlMoiNoi ?>" class="kpi-card border-orange">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-orange"><i class="fa-solid fa-link"></i></div>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($cntMoiNoi) ?></span>
                <div class="kpi-title">Điểm mối nối</div>
            </div>
        </a>

        <!-- 8. Hầm kỹ thuật -->
        <a href="<?= $urlHam ?>" class="kpi-card border-orange">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-orange"><i class="fa-solid fa-dungeon"></i></div>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($cntHam) ?></span>
                <div class="kpi-title">Hầm kỹ thuật</div>
            </div>
        </a>

        <!-- 9. Cọc mốc -->
        <a href="<?= $urlCoc ?>" class="kpi-card border-orange">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-orange"><i class="fa-solid fa-map-pin"></i></div>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($cntCoc) ?></span>
                <div class="kpi-title">Cọc mốc giới</div>
            </div>
        </a>

        <!-- 10. Sự cố -->
        <a href="<?= $urlSuCo ?>" class="kpi-card border-red">
            <div class="kpi-icon-wrapper">
                <div class="kpi-icon bg-light-red"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <span class="badge bg-danger text-white blink">Cảnh báo</span>
            </div>
            <div>
                <span class="kpi-value"><?= number_format($cntSuCo) ?></span>
                <div class="kpi-title">Sự cố chờ xử lý</div>
            </div>
        </a>
    </div>

    <!-- HÀNG 3: BIỂU ĐỒ (6 Chart Grid) -->
    <div class="row g-4 mb-4">
        <!-- Chart 1: Vật liệu ống -->
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-chart-bar text-primary"></i> Vật liệu Ống phân phối</h5></div>
                <div class="chart-wrapper"><canvas id="pipeChart"></canvas></div>
            </div>
        </div>

        <!-- Chart 2: Đồng hồ -->
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-chart-pie text-success"></i> Hãng Đồng hồ</h5></div>
                <div class="chart-wrapper"><canvas id="meterChart"></canvas></div>
            </div>
        </div>

        <!-- Chart 3: Sự cố -->
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-chart-column text-danger"></i> Nguyên nhân sự cố</h5></div>
                <div class="chart-wrapper"><canvas id="incidentChart"></canvas></div>
            </div>
        </div>

        <!-- Chart 4: Tình trạng Van -->
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-gauge text-warning"></i> Tình trạng Van</h5></div>
                <div class="chart-wrapper"><canvas id="valveChart"></canvas></div>
            </div>
        </div>

        <!-- Chart 5: Loại Hầm -->
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-cubes text-info"></i> Phân loại Hầm kỹ thuật</h5></div>
                <div class="chart-wrapper"><canvas id="hamChart"></canvas></div>
            </div>
        </div>

        <!-- Chart 6: Loại Mối nối -->
        <div class="col-lg-4 col-md-6">
            <div class="chart-panel">
                <div class="panel-header"><h5 class="panel-title"><i class="fa-solid fa-link text-dark"></i> Loại Mối nối</h5></div>
                <div class="chart-wrapper"><canvas id="moinoiChart"></canvas></div>
            </div>
        </div>
    </div>
    
    <!-- HÀNG 4: DANH SÁCH SỰ CỐ GẦN ĐÂY -->
    <div class="row">
        <div class="col-12">
            <div class="chart-panel" style="height: auto; min-height: auto;">
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
                                <td><?= Yii::$app->formatter->asDate($sc->created_at, 'php:d/m/Y H:i') ?></td>
                                <td>
                                    <?php if($sc->status == 1): ?>
                                        <span class="badge bg-light text-success fw-bold px-3 py-2">Hoàn thành</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-danger fw-bold px-3 py-2">Đang xử lý</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= Url::to(['hocau/suco/view', 'id' => $sc->id]) ?>" class="btn btn-icon btn-sm btn-light-primary"><i class="fa-solid fa-arrow-right"></i></a>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // --- CẤU HÌNH CHART JS CHUNG ---
    Chart.defaults.font.family = "'Segoe UI', sans-serif";
    Chart.defaults.color = '#7e8299';
    Chart.defaults.scale.grid.color = "#f3f6f9";
    
    // Hàm xử lý Click Drill-down
    function handleChartClick(urlBase, paramName, ids) {
        return (e, elements) => {
            if (elements.length > 0) {
                const index = elements[0].index;
                const selectedId = ids[index];
                window.location.href = urlBase + '?' + paramName + '=' + selectedId;
            }
        };
    }

    // --- 1. BIỂU ĐỒ ỐNG (Bar Ngang) ---
    new Chart(document.getElementById("pipeChart"), {
        type: 'bar',
        data: {
            labels: <?= $pipeLabels ?>,
            datasets: [{ label: 'Số lượng', data: <?= $pipeValues ?>, backgroundColor: '#3699ff', borderRadius: 4, barThickness: 20 }]
        },
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            onClick: handleChartClick('<?= Url::to(['hocau/ongphanphoi/index']) ?>', 'OngphanphoiSearch[loaiong_id]', <?= $pipeIds ?>)
        }
    });

    // --- 2. BIỂU ĐỒ ĐỒNG HỒ (Doughnut) ---
    new Chart(document.getElementById("meterChart"), {
        type: 'doughnut',
        data: {
            labels: <?= $meterLabels ?>,
            datasets: [{ data: <?= $meterValues ?>, backgroundColor: ['#1bc5bd', '#3699ff', '#8950fc', '#ffa800'], borderWidth: 0 }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { position: 'right', labels: { boxWidth: 10, usePointStyle: true } } },
            onClick: handleChartClick('<?= Url::to(['hocau/donghonhamay/index']) ?>', 'DonghonhamaySearch[hieudongho_id]', <?= $meterIds ?>)
        }
    });

    // --- 3. BIỂU ĐỒ SỰ CỐ (Bar Dọc) ---
    new Chart(document.getElementById("incidentChart"), {
        type: 'bar',
        data: {
            labels: <?= $incidentLabels ?>,
            datasets: [{ label: 'Vụ việc', data: <?= $incidentValues ?>, backgroundColor: '#f64e60', borderRadius: 4 }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            onClick: handleChartClick('<?= Url::to(['hocau/suco/index']) ?>', 'SucoSearch[nguyennhansuco_id]', <?= $incidentIds ?>)
        }
    });

    // --- 4. BIỂU ĐỒ VAN (Pie) ---
    new Chart(document.getElementById("valveChart"), {
        type: 'pie',
        data: {
            labels: <?= $valveLabels ?>,
            datasets: [{ data: <?= $valveValues ?>, backgroundColor: ['#1bc5bd', '#ffa800', '#e4e6ef'], borderWidth: 0 }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } },
            onClick: handleChartClick('<?= Url::to(['hocau/van/index']) ?>', 'VanSearch[tinhtrang_id]', <?= $valveIds ?>)
        }
    });

    // --- 5. BIỂU ĐỒ HẦM (Polar Area - Mới lạ) ---
    new Chart(document.getElementById("hamChart"), {
        type: 'polarArea',
        data: {
            labels: <?= $hamLabels ?>,
            datasets: [{ data: <?= $hamValues ?>, backgroundColor: ['rgba(137, 80, 252, 0.7)', 'rgba(54, 153, 255, 0.7)', 'rgba(27, 197, 189, 0.7)'], borderWidth: 1 }]
        },
        options: {
            maintainAspectRatio: false,
            scales: { r: { ticks: { display: false } } },
            plugins: { legend: { position: 'right', labels: { boxWidth: 10 } } },
            onClick: handleChartClick('<?= Url::to(['hocau/hamkythuat/index']) ?>', 'HamkythuatSearch[loaiham_id]', <?= $hamIds ?>)
        }
    });

    // --- 6. BIỂU ĐỒ MỐI NỐI (Bar) ---
    new Chart(document.getElementById("moinoiChart"), {
        type: 'bar',
        data: {
            labels: <?= $moinoiLabels ?>,
            datasets: [{ label: 'Số lượng', data: <?= $moinoiValues ?>, backgroundColor: '#181c32', borderRadius: 4 }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            onClick: handleChartClick('<?= Url::to(['hocau/moinoi/index']) ?>', 'MoinoiSearch[loaimoinoi_id]', <?= $moinoiIds ?>)
        }
    });

});
</script>