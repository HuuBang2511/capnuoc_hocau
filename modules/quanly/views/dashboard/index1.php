<?php
/* @var $this yii\web\View */
/* @var $totalLengthKm float */
/* @var $totalCustomers int */
/* @var $activeIncidents int */
/* @var $totalValves int */
/* @var $incidentLabels string */
/* @var $incidentData string */
/* @var $valveLabels string */
/* @var $valveData string */
/* @var $recentIncidents app\modules\quanly\models\hocau\Suco[] */

$this->title = 'Trung Tâm Điều Hành Cấp Nước';

// 1. CHÈN CDN XỊN (Bootstrap 5 + FontAwesome 6 + Chart.js 4.4)
// CSS
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');

// JS (Chart.js mới nhất)
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<style>
    :root {
        --primary-gradient: linear-gradient(45deg, #4e73df, #224abe);
        --success-gradient: linear-gradient(45deg, #1cc88a, #13855c);
        --warning-gradient: linear-gradient(45deg, #f6c23e, #dda20a);
        --danger-gradient: linear-gradient(45deg, #e74a3b, #be2617);
        --card-bg: #ffffff;
        --text-gray: #5a5c69;
    }
    
    body {
        background-color: #f8f9fc;
        font-family: 'Nunito', sans-serif;
    }

    .dashboard-title {
        font-weight: 800;
        color: #4e73df;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* KPI Cards */
    .kpi-card {
        background: var(--card-bg);
        border-radius: 15px;
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        position: relative;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
    }

    .kpi-card.blue::before { background: var(--primary-gradient); }
    .kpi-card.green::before { background: var(--success-gradient); }
    .kpi-card.red::before { background: var(--danger-gradient); }
    .kpi-card.yellow::before { background: var(--warning-gradient); }

    .kpi-content {
        padding: 20px 20px 20px 25px;
    }

    .kpi-label {
        color: #858796;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .kpi-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #5a5c69;
    }

    .kpi-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 2.5rem;
        opacity: 0.15;
    }

    /* Chart Containers */
    .chart-box {
        background: var(--card-bg);
        border-radius: 15px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        height: 400px; /* Cố định chiều cao */
    }

    .box-header {
        border-bottom: 1px solid #e3e6f0;
        padding-bottom: 10px;
        margin-bottom: 15px;
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Table VIP */
    .table-vip thead th {
        background-color: #4e73df;
        color: white;
        border: none;
        font-weight: 600;
    }
    .table-vip tbody tr:hover {
        background-color: #f8f9fc;
    }
    .badge-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
</style>

<div class="container-fluid dashboard-index">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 dashboard-title">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Xuất báo cáo
        </a>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="kpi-card blue">
                <div class="kpi-content">
                    <div class="kpi-label text-primary">Hạ tầng đường ống</div>
                    <div class="kpi-value"><?= number_format($totalLengthKm, 2) ?> <small>km</small></div>
                </div>
                <i class="fa-solid fa-network-wired kpi-icon text-primary"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="kpi-card green">
                <div class="kpi-content">
                    <div class="kpi-label text-success">Khách hàng</div>
                    <div class="kpi-value"><?= number_format($totalCustomers) ?></div>
                </div>
                <i class="fa-solid fa-users kpi-icon text-success"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="kpi-card red">
                <div class="kpi-content">
                    <div class="kpi-label text-danger">Sự cố chờ xử lý</div>
                    <div class="kpi-value"><?= $activeIncidents ?></div>
                </div>
                <i class="fa-solid fa-triangle-exclamation kpi-icon text-danger"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="kpi-card yellow">
                <div class="kpi-content">
                    <div class="kpi-label text-warning">Tổng số Van</div>
                    <div class="kpi-value"><?= number_format($totalValves) ?></div>
                </div>
                <i class="fa-solid fa-faucet kpi-icon text-warning"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="chart-box">
                <div class="box-header text-primary">
                    <span><i class="fa-solid fa-chart-pie"></i> Phân loại Sự cố</span>
                </div>
                <div style="height: 300px; position: relative;">
                    <canvas id="incidentChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="chart-box">
                <div class="box-header text-primary">
                    <span><i class="fa-solid fa-chart-column"></i> Tình trạng Van Mạng lưới</span>
                </div>
                <div style="height: 300px; position: relative;">
                    <canvas id="valveChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="chart-box" style="height: auto;">
                <div class="box-header text-primary">
                    <span><i class="fa-solid fa-list-check"></i> Sự cố mới phát sinh</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-vip" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mã SC</th>
                                <th>Vị trí</th>
                                <th>Ngày phát hiện</th>
                                <th>Nguyên nhân</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentIncidents as $sc): ?>
                                <tr>
                                    <td><strong>#<?= $sc->masuco ?? $sc->id ?></strong></td>
                                    <td><?= $sc->vitri ?></td>
                                    <td><?= Yii::$app->formatter->asDate($sc->created_at, 'php:d/m/Y H:i') ?></td>
                                    <td><?= $sc->nguyennhan ?></td>
                                    <td>
                                        <?php if($sc->status == 1): ?>
                                            <span class="badge bg-success badge-status">Đã xử lý</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger badge-status">Đang xử lý</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if(empty($recentIncidents)): ?>
                                <tr><td colspan="5" class="text-center text-muted">Hệ thống hoạt động ổn định.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Đợi DOM load xong mới chạy để tránh lỗi undefined
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Cấu hình Font và Màu mặc định (Cú pháp v4)
        Chart.defaults.font.family = "'Nunito', sans-serif";
        Chart.defaults.color = '#858796';

        // 2. BIỂU ĐỒ SỰ CỐ (Doughnut)
        const ctxIncident = document.getElementById("incidentChart");
        new Chart(ctxIncident, {
            type: 'doughnut',
            data: {
                labels: <?= $incidentLabels ?>,
                datasets: [{
                    data: <?= $incidentData ?>,
                    backgroundColor: ['#e74a3b', '#f6c23e', '#4e73df', '#858796'],
                    hoverBackgroundColor: ['#be2617', '#dda20a', '#224abe', '#60616f'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true }
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    }
                },
                cutout: '75%', // Tạo lỗ rỗng ở giữa (Style Donut)
            }
        });

        // 3. BIỂU ĐỒ VAN (Bar Chart)
        const ctxValve = document.getElementById("valveChart");
        new Chart(ctxValve, {
            type: 'bar',
            data: {
                labels: <?= $valveLabels ?>,
                datasets: [{
                    label: "Số lượng",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: <?= $valveData ?>,
                    borderRadius: 5, // Bo tròn đầu cột
                    barPercentage: 0.6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 6 }
                    },
                    y: {
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value) { return value; } // Format số
                        },
                        grid: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleColor: '#6e707e',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: false,
                    }
                }
            }
        });
    });
</script>