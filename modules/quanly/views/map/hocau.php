<?php

use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset;
use app\widgets\maps\LeafletDrawAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;

// Assets
LeafletMapAsset::register($this);
LeafletDrawAsset::register($this);
LeafletMeasureAsset::register($this);
LeafletLocateAsset::register($this);

// Endpoint
$geoserverWmsUrl = 'http://103.9.77.141:8080/geoserver/capnuoc_hocau/wms';
$geoserverWfsUrl = 'http://103.9.77.141:8080/geoserver/capnuoc_hocau/wfs';
$geoserverWmtsUrl = 'http://103.9.77.141:8080/geoserver/gwc/service/wmts'; 
// ... (Các cấu hình GeoServer cũ)

// CẤU HÌNH LIÊN KẾT TRANG CHI TIẾT (Mapping GeoServer Layer -> Yii2 Route)
// Key là phần định danh lớp (thường là tên bảng trong GeoServer bỏ phần prefix namespace)
$detailLinks = [
    'network_cocmoc'        => Url::to(['/quanly/hocau/cocmoc/view']),
    'network_donghonhamay'  => Url::to(['/quanly/hocau/donghonhamay/view']),
    'network_donghotong'    => Url::to(['/quanly/hocau/donghotong/view']),
    'network_hamkythuat'    => Url::to(['/quanly/hocau/hamkythuat/view']),
    'network_moinoi'        => Url::to(['/quanly/hocau/moinoi/view']),
    'network_ongphanphoi'   => Url::to(['/quanly/hocau/ongphanphoi/view']),
    'network_ongtruyendan'  => Url::to(['/quanly/hocau/ongtruyendan/view']),
    'network_van'           => Url::to(['/quanly/hocau/van/view']),
    'network_suco'          => Url::to(['/quanly/hocau/suco/view']),
    'network_hanglangantoan'=> Url::to(['/quanly/hocau/hanglangantoan/view']),
    'network_nhamaynuoc'    => Url::to(['/quanly/hocau/nhamaynuoc/view']),
];

// Chuyển mảng này sang JSON để Javascript sử dụng
$jsonDetailLinks = json_encode($detailLinks);

$this->title = 'Hệ thống GIS Cấp nước Hồ Cầu';
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->registerCsrfMetaTags() ?>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        /* GIỮ NGUYÊN CSS CŨ CỦA BẠN, KHÔNG THAY ĐỔI */
        :root { --primary-color: #0d6efd; --sidebar-width: 400px; }
        body { margin: 0; padding: 0; overflow: hidden; font-family: 'Segoe UI', system-ui, sans-serif; background: #f8f9fa; }
        #app-container { display: flex; height: 100vh; width: 100vw; position: relative; }
        #sidebar { width: var(--sidebar-width); background: #fff; display: flex; flex-direction: column; box-shadow: 4px 0 24px rgba(0,0,0,0.08); z-index: 1000; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: absolute; top: 0; left: 0; bottom: 0; }
        #sidebar.collapsed { transform: translateX(calc(var(--sidebar-width) * -1)); }
        .sidebar-header { height: 60px; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; }
        .brand-link { color: white; text-decoration: none; font-weight: 700; font-size: 1.1rem; }
        .nav-tabs-custom { display: flex; background: #f8f9fa; border-bottom: 1px solid #dee2e6; margin: 0; padding: 0; list-style: none; }
        .nav-tabs-custom li { flex: 1; text-align: center; }
        .nav-tabs-custom button { width: 100%; border: none; background: transparent; padding: 12px 0; color: #6c757d; font-weight: 600; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.2s; font-size: 0.95rem; }
        .nav-tabs-custom button:hover { background: #e9ecef; }
        .nav-tabs-custom button.active { color: var(--primary-color); background: #fff; border-bottom-color: var(--primary-color); }
        .sidebar-content { flex: 1; overflow-y: auto; position: relative; }
        .tab-panel { display: none; padding: 20px; }
        .tab-panel.active { display: block; }
        .layer-group { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 12px; overflow: hidden; }
        .layer-group-header { background: #f8f9fa; padding: 12px 15px; cursor: pointer; font-weight: 600; font-size: 0.95rem; display: flex; justify-content: space-between; align-items: center; user-select: none; }
        .layer-group-body { padding: 10px 15px; border-top: 1px solid #e2e8f0; display: block; }
        .form-check-custom { display: flex; align-items: center; margin-bottom: 8px; }
        .form-check-input { cursor: pointer; margin-right: 10px; width: 1.1em; height: 1.1em; }
        #map-wrapper { flex: 1; position: relative; height: 100%; width: 100%; z-index: 1; }
        #map { width: 100%; height: 100%; outline: none; }
        #sidebar-toggle { position: absolute; top: 20px; left: 420px; z-index: 1001; width: 44px; height: 44px; border-radius: 50%; background: white; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: var(--primary-color); font-size: 1.2rem; }
        .floating-toolbar { position: absolute; top: 20px; right: 20px; z-index: 999; display: flex; flex-direction: column; gap: 10px; }
        .tool-btn { width: 44px; height: 44px; border-radius: 8px; background: white; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); color: #495057; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .tool-btn:hover { background: #f8f9fa; color: var(--primary-color); }
        #legend-panel { position: absolute; bottom: 30px; left: 420px; z-index: 999; background: rgba(255, 255, 255, 0.95); padding: 15px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); width: 260px; max-height: 350px; overflow-y: auto; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: none; }
        .leaflet-popup-content-wrapper { padding: 0; border-radius: 8px; }
        .leaflet-popup-content { margin: 0; width: 320px !important; }
        .popup-header { background: var(--primary-color); color: white; padding: 10px 15px; font-weight: 600; }
        .popup-body { max-height: 200px; overflow-y: auto; padding: 0; }
        .info-table td { padding: 6px 12px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        #loading-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 9999; display: none; align-items: center; justify-content: center; }
        .search-item { padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; }
        .search-item:hover { background: #f1f8ff; }
        /* STATS CSS */
        .stat-card { background: #fff; border: 1px solid #eeffff; border-radius: 8px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; align-items: center; }
        .stat-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-right: 15px; background: #f1f8ff; color: var(--primary-color); }
        .stat-info h4 { margin: 0; font-size: 1.2rem; font-weight: 700; color: #333; }
        .stat-info p { margin: 0; font-size: 0.85rem; color: #777; }
        .chart-container { position: relative; height: 200px; width: 100%; margin-top: 20px; }
    </style>
    
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="app-container">
    <div id="sidebar">
        <div class="sidebar-header">
            <a href="<?= Url::home() ?>" class="brand-link"><i class="fa-solid fa-droplet me-2"></i> QUẢN LÝ CẤP NƯỚC</a>
        </div>
        <ul class="nav-tabs-custom">
            <li><button class="active" onclick="switchTab(event, 'tab-layers')"><i class="fa-solid fa-layer-group me-1"></i> Lớp</button></li>
            <li><button onclick="switchTab(event, 'tab-filter')"><i class="fa-solid fa-filter me-1"></i> Lọc</button></li>
            <li><button onclick="switchTab(event, 'tab-stats')"><i class="fa-solid fa-chart-pie me-1"></i> T.Kê</button></li>
            <li><button onclick="switchTab(event, 'tab-search')"><i class="fa-solid fa-search me-1"></i> Tìm</button></li>
        </ul>

        <div class="sidebar-content">
            <div id="tab-layers" class="tab-panel active">
                <div class="mb-4">
                    <label class="form-label text-uppercase fw-bold text-muted small">Bản đồ nền</label>
                    <select id="basemap-select" class="form-select">
                        <option value="google_road" selected>Google Maps (Giao thông)</option>
                        <option value="hocau_base">Bản đồ Quy hoạch Hồ Cầu</option>
                        <option value="google_satellite">Google Maps (Vệ tinh)</option>
                        <option value="osm">OpenStreetMap</option>
                        <option value="none">Không nền</option>
                    </select>
                </div>
                <?php 
                // Cấu trúc lớp giữ nguyên
                $layerGroups = [
                    'Mạng Lưới Đường Ống' => [
                        ['id' => 'truyendan', 'layer' => 'capnuoc_hocau:network_ongtruyendan', 'label' => 'Ống truyền dẫn', 'checked' => true],
                        ['id' => 'phanphoi', 'layer' => 'capnuoc_hocau:network_ongphanphoi', 'label' => 'Ống phân phối', 'checked' => true],
                        ['id' => 'moinoi', 'layer' => 'capnuoc_hocau:network_moinoi', 'label' => 'Mối nối', 'checked' => false],
                    ],
                    'Thiết Bị & Đồng Hồ' => [
                        ['id' => 'van', 'layer' => 'capnuoc_hocau:network_van', 'label' => 'Van mạng lưới', 'checked' => true],
                        ['id' => 'dhtong', 'layer' => 'capnuoc_hocau:network_donghotong', 'label' => 'Đồng hồ tổng', 'checked' => true],
                        ['id' => 'dhnhamay', 'layer' => 'capnuoc_hocau:network_donghonhamay', 'label' => 'Đồng hồ nhà máy', 'checked' => false],
                    ],
                    'Công Trình & Khác' => [
                        ['id' => 'nhamay', 'layer' => 'capnuoc_hocau:network_nhamaynuoc', 'label' => 'Nhà máy nước', 'checked' => true],
                        ['id' => 'ham', 'layer' => 'capnuoc_hocau:network_hamkythuat', 'label' => 'Hầm kỹ thuật', 'checked' => false],
                        ['id' => 'cocmoc', 'layer' => 'capnuoc_hocau:network_cocmoc', 'label' => 'Cọc mốc', 'checked' => false],
                        ['id' => 'suco', 'layer' => 'capnuoc_hocau:network_suco', 'label' => 'Điểm sự cố', 'checked' => true],
                    ]
                ];
                foreach ($layerGroups as $groupName => $layers): 
                    $groupId = 'group-' . md5($groupName);
                ?>
                <div class="layer-group">
                    <div class="layer-group-header" onclick="toggleGroup('<?= $groupId ?>')">
                        <span><?= $groupName ?></span>
                        <i class="fa-solid fa-chevron-up icon-<?= $groupId ?>"></i>
                    </div>
                    <div class="layer-group-body" id="<?= $groupId ?>">
                        <?php foreach ($layers as $layer): ?>
                        <div class="form-check-custom">
                            <input class="form-check-input wms-layer" type="checkbox" 
                                   id="<?= $layer['id'] ?>" 
                                   data-layer="<?= $layer['layer'] ?>"
                                   data-label="<?= $layer['label'] ?>"
                                   <?= $layer['checked'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="<?= $layer['id'] ?>"><?= $layer['label'] ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div id="tab-filter" class="tab-panel">
                <div class="alert alert-light border shadow-sm mb-3 text-muted small">
                    <i class="fa-solid fa-filter me-1"></i> Chọn lớp để xem các tiêu chí lọc tương ứng.
                </div>
                <form id="filter-form">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Lớp dữ liệu</label>
                        <select class="form-select" id="filter-layer-select" onchange="onFilterLayerChange()">
                            <option value="">-- Chọn đối tượng --</option>
                            </select>
                    </div>

                    <div id="dynamic-filter-container" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted" id="dynamic-filter-label">Tiêu chí</label>
                            <select class="form-select" id="dynamic-filter-value">
                                </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="button" class="btn btn-primary" onclick="applySmartFilter()">
                            <i class="fa-solid fa-check me-1"></i> Áp dụng
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearSmartFilter()">
                            <i class="fa-solid fa-rotate-left me-1"></i> Xóa lọc
                        </button>
                    </div>
                </form>
            </div>

            <div id="tab-stats" class="tab-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">Thống kê sơ bộ</h5>
                    <button class="btn btn-sm btn-light border" onclick="loadStats()" title="Cập nhật"><i class="fa-solid fa-sync text-primary"></i></button>
                </div>
                <div id="stats-content">
                    <div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>
                </div>
                <div id="stats-template" style="display:none;">
                    <div class="stat-card border-start border-4 border-primary">
                        <div class="stat-icon"><i class="fa-solid fa-code-branch"></i></div>
                        <div class="stat-info"><h4 id="stat-ong">0 km</h4><p>Tổng chiều dài ống</p></div>
                    </div>
                    <div class="stat-card border-start border-4 border-warning">
                        <div class="stat-icon text-warning bg-light-warning"><i class="fa-solid fa-faucet"></i></div>
                        <div class="stat-info"><h4 id="stat-van">0</h4><p>Van mạng lưới</p></div>
                    </div>
                    <div class="stat-card border-start border-4 border-danger">
                        <div class="stat-icon text-danger bg-light-danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="stat-info"><h4 id="stat-suco">0</h4><p>Sự cố chưa xử lý</p></div>
                    </div>
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title fw-bold small text-muted text-uppercase">Tình trạng Van</h6>
                            <div class="chart-container" style="height: 180px;"><canvas id="miniChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-search" class="tab-panel">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="search-keyword" placeholder="Nhập mã, tên...">
                    <button class="btn btn-primary" type="button" onclick="searchObject()"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="mb-2">
                    <select class="form-select form-select-sm" id="search-layer">
                        <option value="capnuoc_hocau:network_donghonhamay">Đồng hồ nhà máy</option>
                        <option value="capnuoc_hocau:network_van">Van mạng lưới</option>
                    </select>
                </div>
                <div id="search-results" class="border rounded bg-light mt-3" style="min-height: 150px; overflow-y: auto;">
                    <div class="text-center text-muted p-3 small">Kết quả tìm kiếm...</div>
                </div>
            </div>
        </div>
    </div>
    
    <button id="sidebar-toggle" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>

    <div id="map-wrapper">
        <div class="floating-toolbar">
            <button class="tool-btn" onclick="map.measureControl.toggle()" title="Đo đạc"><i class="fa-solid fa-ruler-combined"></i></button>
            <button class="tool-btn" onclick="locateUser()" title="Vị trí"><i class="fa-solid fa-location-crosshairs"></i></button>
            <button class="tool-btn" onclick="resetMap()" title="Reset"><i class="fa-solid fa-house"></i></button>
        </div>
        <div id="legend-panel">
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <span class="fw-bold small text-uppercase">Chú giải</span>
                <i class="fa-solid fa-xmark text-muted cursor-pointer" onclick="document.getElementById('legend-panel').style.display='none'"></i>
            </div>
            <div id="legend-content"></div>
        </div>
        <div id="map"></div>
    </div>
    <div id="loading-overlay">
        <div class="bg-white p-3 rounded shadow d-flex align-items-center gap-3">
            <div class="spinner-border text-primary" role="status"></div>
            <span class="fw-bold text-primary">Đang xử lý...</span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>

<script>
    let map;
    let wmsLayers = {};
    let statsChart = null;
    
    const geoserverWms = '<?= $geoserverWmsUrl ?>';
    const geoserverWfs = '<?= $geoserverWfsUrl ?>';
    const geoserverWmts = '<?= $geoserverWmtsUrl ?>';
    const statsApiUrl = '<?= Url::to(['map/thongke-api']) ?>';

    // --- CẤU HÌNH BỘ LỌC ĐỘNG (KEYWORD: FILTER CONFIG) ---
    // Mapping giữa Tên lớp GeoServer và Trường dữ liệu cần lọc + Danh sách giá trị (Inject từ PHP)
    const FILTER_CONFIG = {
        'capnuoc_hocau:network_ongphanphoi': {
            field: 'loaiong_id', 
            label: 'Chất liệu ống',
            options: <?= Json::encode($filterData['loaiong'] ?? []) ?>
        },
        'capnuoc_hocau:network_donghonhamay': {
            field: 'hieudongho_id',
            label: 'Hiệu đồng hồ',
            options: <?= Json::encode($filterData['hieudongho'] ?? []) ?>
        },
        'capnuoc_hocau:network_van': {
            field: 'tinhtrang_id',
            label: 'Trạng thái hoạt động',
            options: <?= Json::encode($filterData['tinhtrang'] ?? []) ?>
        },
        'capnuoc_hocau:network_suco': {
            field: 'nguyennhansuco_id',
            label: 'Nguyên nhân sự cố',
            options: <?= Json::encode($filterData['nguyennhan'] ?? []) ?>
        },
        'capnuoc_hocau:network_hamkythuat': {
            field: 'loaiham_id',
            label: 'Loại hầm',
            options: <?= Json::encode($filterData['loaiham'] ?? []) ?>
        },
        'capnuoc_hocau:network_moinoi': {
            field: 'loaimoinoi_id',
            label: 'Kiểu mối nối',
            options: <?= Json::encode($filterData['loaimoinoi'] ?? []) ?>
        }
    };

    const defaultCenter = [10.737202, 106.915000];
    const defaultZoom = 14;

    document.addEventListener('DOMContentLoaded', function() {
        initMap();
        initLayers();
        populateFilterLayerList(); // Hàm mới để tạo list lớp
        
        if (typeof bootstrap !== 'undefined') {
            [].slice.call(document.querySelectorAll('[title]')).map(function (el) { return new bootstrap.Tooltip(el) });
        }
    });

    // --- CORE & LAYER FUNCTIONS ---
    function initMap() {
        map = L.map('map', { zoomControl: false, attributionControl: false }).setView(defaultCenter, defaultZoom);
        L.control.zoom({ position: 'bottomright' }).addTo(map);
        L.control.scale({ imperial: false, position: 'bottomright' }).addTo(map);
        if(L.Control.Measure) {
            map.measureControl = new L.Control.Measure({ position: 'topright', primaryLengthUnit: 'meters', activeColor: '#0d6efd' });
            map.measureControl.addTo(map);
            const defBtn = document.querySelector('.leaflet-control-measure-toggle');
            if(defBtn) defBtn.style.display = 'none';
        }
        document.getElementById('basemap-select').addEventListener('change', (e) => setBasemap(e.target.value));
        setBasemap('google_road');
        L.control.locate({strings: {title: "Vị trí"}}).addTo(map);
    }

    function setBasemap(type) {
        if (window.currentBasemap) map.removeLayer(window.currentBasemap);
        let layer;
        if (type === 'hocau_base') {
            layer = L.tileLayer(geoserverWmts + '?service=WMTS&request=GetTile&version=1.0.0&layer=capnuoc_hocau:base_thuadat&style=&tilematrixset=EPSG:900913&format=image/png&tilematrix=EPSG:900913:{z}&tilerow={y}&tilecol={x}', { maxZoom: 22 });
        } else if (type.includes('google')) {
            let s = type === 'google_satellite' ? 's,h' : 'm';
            layer = L.tileLayer(`https://{s}.google.com/vt/lyrs=${s}&x={x}&y={y}&z={z}`, { maxZoom: 22, subdomains: ['mt0','mt1','mt2','mt3'] });
        } else if (type === 'osm') {
            layer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
        }
        if (layer && type !== 'none') {
            layer.addTo(map); layer.bringToBack(); window.currentBasemap = layer;
        }
    }

    function initLayers() {
        document.querySelectorAll('.wms-layer').forEach(chk => {
            const name = chk.dataset.layer;
            const layer = L.tileLayer.wms(geoserverWms, {
                layers: name, format: 'image/png', transparent: true, version: '1.1.0', tiled: true, maxZoom: 22
            });
            wmsLayers[name] = layer;
            if (chk.checked) toggleLayer(name, true);
            chk.addEventListener('change', function() { toggleLayer(name, this.checked); });
        });
        map.on('click', getFeatureInfo);
    }

    function toggleLayer(name, show) {
        if (show) wmsLayers[name].addTo(map);
        else map.removeLayer(wmsLayers[name]);
        updateLegend();
    }

    // --- UI LOGIC ---
    function switchTab(evt, tabId) {
        const panels = document.getElementsByClassName("tab-panel");
        for (let i = 0; i < panels.length; i++) panels[i].className = panels[i].className.replace(" active", "");
        const buttons = document.querySelector(".nav-tabs-custom").getElementsByTagName("button");
        for (let i = 0; i < buttons.length; i++) buttons[i].className = "";
        document.getElementById(tabId).className += " active";
        evt.currentTarget.className += " active";
        if(tabId === 'tab-stats') loadStats();
    }
    
    function toggleSidebar() {
        const sb = document.getElementById('sidebar');
        const btn = document.getElementById('sidebar-toggle');
        const leg = document.getElementById('legend-panel');
        sb.classList.toggle('collapsed');
        const isCollapsed = sb.classList.contains('collapsed');
        btn.style.left = isCollapsed ? '20px' : '420px';
        leg.style.left = isCollapsed ? '20px' : '420px';
    }
    function toggleGroup(id) {
        const body = document.getElementById(id);
        const icon = document.querySelector(`.icon-${id}`);
        body.style.display = body.style.display === 'none' ? 'block' : 'none';
        icon.classList.toggle('fa-chevron-up'); icon.classList.toggle('fa-chevron-down');
    }

    // --- LOGIC LỌC THÔNG MINH (SMART FILTER) ---
    
    // 1. Chỉ hiển thị các lớp có trong FILTER_CONFIG vào dropdown
    function populateFilterLayerList() {
        const select = document.getElementById('filter-layer-select');
        select.innerHTML = '<option value="">-- Chọn đối tượng --</option>';
        
        document.querySelectorAll('.wms-layer').forEach(chk => {
            const layerName = chk.dataset.layer;
            const layerLabel = chk.dataset.label;
            
            // Chỉ thêm vào nếu lớp này có cấu hình lọc
            if (FILTER_CONFIG[layerName]) {
                const opt = document.createElement('option');
                opt.value = layerName;
                opt.text = layerLabel;
                select.appendChild(opt);
            }
        });
    }

    // 2. Sự kiện khi chọn lớp -> Hiển thị dropdown giá trị tương ứng
    function onFilterLayerChange() {
        const layerSelect = document.getElementById('filter-layer-select');
        const layerName = layerSelect.value;
        const container = document.getElementById('dynamic-filter-container');
        const valueSelect = document.getElementById('dynamic-filter-value');
        const label = document.getElementById('dynamic-filter-label');

        valueSelect.innerHTML = ''; // Clear cũ

        if (!layerName || !FILTER_CONFIG[layerName]) {
            container.style.display = 'none';
            return;
        }

        const config = FILTER_CONFIG[layerName];
        container.style.display = 'block';
        label.innerText = config.label; // Ví dụ: "Chất liệu ống"

        // Add default option
        const defOpt = document.createElement('option');
        defOpt.value = "";
        defOpt.text = "-- Tất cả --";
        valueSelect.appendChild(defOpt);

        // Add options from config
        if (config.options && config.options.length > 0) {
            config.options.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.text = item.ten; // Model trả về 'ten'
                valueSelect.appendChild(opt);
            });
        } else {
            const opt = document.createElement('option');
            opt.text = "Không có dữ liệu danh mục";
            valueSelect.appendChild(opt);
        }
    }

    // 3. Áp dụng bộ lọc CQL
    function applySmartFilter() {
        const layerName = document.getElementById('filter-layer-select').value;
        const value = document.getElementById('dynamic-filter-value').value;
        
        if (!layerName) { alert('Vui lòng chọn lớp dữ liệu!'); return; }

        showLoading(true);

        const fieldName = FILTER_CONFIG[layerName].field;
        const layer = wmsLayers[layerName];

        if (layer) {
            if (value) {
                // Có giá trị -> Áp dụng lọc
                const cql = `${fieldName} = ${value}`;
                layer.setParams({ cql_filter: cql });
            } else {
                // Nếu value rỗng (chọn -- Tất cả --) -> Xóa lọc giống hệt hàm clearSmartFilter
                if (layer.wmsParams.cql_filter) {
                    delete layer.wmsParams.cql_filter;
                    layer.redraw();
                }
            }
            
            // Tự bật lớp nếu đang tắt để người dùng thấy kết quả
            const chk = document.querySelector(`.wms-layer[data-layer="${layerName}"]`);
            if(chk && !chk.checked) {
                chk.checked = true;
                toggleLayer(layerName, true);
            }
        }
        setTimeout(() => { showLoading(false); }, 500);
    }

function clearSmartFilter() {
        // 1. Reset giao diện về mặc định
        document.getElementById('filter-layer-select').value = "";
        document.getElementById('dynamic-filter-container').style.display = 'none';
        
        // 2. Xóa tham số cql_filter khỏi tất cả các lớp
        for (let key in wmsLayers) {
            let layer = wmsLayers[key];
            
            // Kiểm tra xem lớp này có đang bị gán bộ lọc không
            if (layer.wmsParams && layer.wmsParams.cql_filter) {
                // Cách cũ: layer.setParams({ cql_filter: null }); -> Gây lỗi
                
                // CÁCH KHẮC PHỤC: Xóa cứng tham số khỏi object wmsParams
                delete layer.wmsParams.cql_filter; 
                
                // Yêu cầu Leaflet vẽ lại lớp đó (sẽ tạo URL mới không có cql_filter)
                layer.redraw();
            }
        }
        alert('Đã xóa bộ lọc, hiển thị lại toàn bộ dữ liệu.');
    }

    // --- OTHER UTILS ---
    function loadStats() {
        const content = document.getElementById('stats-content');
        content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
        fetch(statsApiUrl).then(res => res.json()).then(data => {
            if(data.success) {
                const tpl = document.getElementById('stats-template').cloneNode(true);
                tpl.style.display = 'block'; tpl.id = '';
                tpl.querySelector('#stat-ong').innerText = (data.stats.truyendan + data.stats.phanphoi).toFixed(1) + ' km';
                tpl.querySelector('#stat-van').innerText = data.stats.van;
                tpl.querySelector('#stat-suco').innerText = data.stats.suco;
                content.innerHTML = ''; content.appendChild(tpl);
                renderMiniChart(data.chart_van);
            }
        }).catch(err => content.innerHTML = '<div class="alert alert-danger">Lỗi tải dữ liệu.</div>');
    }

    function renderMiniChart(chartData) {
        const ctx = document.getElementById('miniChart');
        if(!ctx) return;
        if(statsChart) statsChart.destroy();
        statsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData.labels,
                datasets: [{ data: chartData.data, backgroundColor: ['#0d6efd', '#dc3545'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, font: {size: 11} } } } }
        });
    }

    function updateLegend() {
        const box = document.getElementById('legend-panel');
        const content = document.getElementById('legend-content');
        content.innerHTML = '';
        let hasLayer = false;
        document.querySelectorAll('.wms-layer:checked').forEach(chk => {
            hasLayer = true;
            const name = chk.dataset.layer;
            const label = chk.dataset.label;
            const url = `${geoserverWms}?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=${name}`;
            const div = document.createElement('div');
            div.className = 'legend-item d-flex align-items-center mb-2';
            div.innerHTML = `<img src="${url}" class="me-2" style="width:20px;"> <span style="font-size:0.9rem">${label}</span>`;
            content.appendChild(div);
        });
        box.style.display = hasLayer ? 'block' : 'none';
    }

    // Biến global chứa danh sách link (được inject từ PHP)
    const DETAIL_LINKS = <?= $jsonDetailLinks ?>;

    function getFeatureInfo(e) {
        const activeLayers = Array.from(document.querySelectorAll('.wms-layer:checked')).map(c => c.dataset.layer);
        if (activeLayers.length === 0) return;
        
        document.body.style.cursor = 'wait';
        
        const params = {
            request: 'GetFeatureInfo', service: 'WMS', srs: 'EPSG:4326', version: '1.1.0', 
            format: 'image/png', bbox: map.getBounds().toBBoxString(), 
            height: map.getSize().y, width: map.getSize().x, 
            layers: activeLayers.join(','), query_layers: activeLayers.join(','), 
            info_format: 'application/json', feature_count: 5,
            x: Math.round(e.containerPoint.x), y: Math.round(e.containerPoint.y)
        };

        fetch(geoserverWms + L.Util.getParamString(params, geoserverWms, true))
            .then(r => r.json())
            .then(data => {
                document.body.style.cursor = 'default';
                if (data.features && data.features.length > 0) {
                    // Lấy đối tượng đầu tiên tìm thấy
                    const feature = data.features[0]; 
                    const props = feature.properties;
                    
                    // --- 1. XỬ LÝ NỘI DUNG POPUP ---
                    let html = '<table class="info-table" width="100%">';
                    
                    // Danh sách các từ khóa cần loại bỏ (Geometry/GeoJSON)
                    const ignoredKeys = ['geom', 'geojson', 'geometry', 'bbox', 'shape_leng', 'shape_area', 'st_area', 'st_length'];

                    for (let k in props) {
                        // Kiểm tra: Có giá trị VÀ không nằm trong danh sách loại bỏ
                        if (props[k] !== null && props[k] !== undefined && !ignoredKeys.includes(k.toLowerCase())) {
                            html += `<tr><td style="font-weight:bold;color:#666;white-space:nowrap;padding-right:10px;">${k}</td><td>${props[k]}</td></tr>`;
                        }
                    }
                    html += '</table>';

                    // --- 2. XỬ LÝ NÚT "XEM CHI TIẾT" ---
                    // Feature ID của GeoServer thường có dạng: "network_van.123"
                    // Ta tách lấy phần tên lớp (network_van) và ID (123)
                    let btnHtml = '';
                    if (feature.id && feature.id.includes('.')) {
                        const parts = feature.id.split('.');
                        const layerKey = parts[0]; // VD: network_van
                        const objId = props.id || props.objectid || parts[1]; // Ưu tiên lấy ID từ thuộc tính, nếu không lấy từ FID

                        // Kiểm tra xem lớp này có link cấu hình không
                        if (DETAIL_LINKS[layerKey] && objId) {
                            // Tạo URL: /web/quanly/hocau/van/view?id=123
                            const url = `${DETAIL_LINKS[layerKey]}?id=${objId}`;
                            btnHtml = `
                                <div class="mt-2 text-end border-top pt-2">
                                    <a href="${url}" target="_blank" class="btn btn-sm btn-primary text-white">
                                        <i class="fa-solid fa-circle-info me-1"></i> Xem chi tiết
                                    </a>
                                </div>`;
                        }
                    }

                    // --- 3. HIỂN THỊ ---
                    L.popup({ maxWidth: 320 })
                     .setLatLng(e.latlng)
                     .setContent(`<div class="popup-header">Thông tin đối tượng</div><div class="popup-body">${html}${btnHtml}</div>`)
                     .openOn(map);
                }
            })
            .catch((err) => {
                console.error(err);
                document.body.style.cursor = 'default';
            });
    }

    /* --- CẤU HÌNH TÌM KIẾM NÂNG CAO (SEARCH CONFIG) --- */
    // Định nghĩa các trường cần tìm cho từng lớp
    const SEARCH_CONFIG = {
        'capnuoc_hocau:network_donghonhamay': {
            fields: ['objectid', 'shd', 'ten_khach_hang', 'dia_chi'], // Tìm cả Mã, Số HĐ, Tên, Địa chỉ
            display: (p) => `<b>${p.ten_khach_hang || 'Chưa cập nhật'}</b><br><small>${p.dia_chi || ''}</small>`
        },
        'capnuoc_hocau:network_van': {
            fields: ['objectid', 'vitri', 'lydoghi'], // Tìm Mã, Vị trí lắp, Ghi chú
            display: (p) => `<b>Van: ${p.objectid}</b><br><small>${p.vitri || 'Không có vị trí'}</small>`
        },
        'capnuoc_hocau:network_suco': {
            fields: ['masuco', 'vitri', 'nguyennhan', 'ghichu'],
            display: (p) => `<b>SC: ${p.masuco || p.id}</b><br><small>${p.vitri || ''}</small>`
        },
        // Mặc định cho các lớp khác nếu chưa cấu hình
        'default': {
            fields: ['objectid'],
            display: (p) => `<b>${p.objectid || 'Đối tượng'}</b>`
        }
    };

    /**
     * HÀM TÌM KIẾM "XỊN" (MULTI-FIELD + CASE INSENSITIVE)
     */
    function searchObject() {
        const keyword = document.getElementById('search-keyword').value.trim();
        const layer = document.getElementById('search-layer').value;
        
        if (!keyword) {
            alert('Vui lòng nhập từ khóa!');
            return;
        }

        showLoading(true);
        const container = document.getElementById('search-results');
        container.innerHTML = '';

        // 1. Xác định các trường cần tìm dựa trên Config
        const config = SEARCH_CONFIG[layer] || SEARCH_CONFIG['default'];
        const fields = config.fields;

        // 2. Xây dựng bộ lọc XML chuẩn OGC với logic OR (Hoặc)
        // Cấu trúc: <Filter><Or> <PropertyIsLike>Col1</PropertyIsLike> <PropertyIsLike>Col2</PropertyIsLike> </Or></Filter>
        let filterInner = '';
        
        fields.forEach(field => {
            // matchCase="false" để tìm không phân biệt hoa thường
            // wildCard="*" : đại diện cho chuỗi bất kỳ
            filterInner += `
                <PropertyIsLike wildCard="*" singleChar="." escapeChar="!" matchCase="false">
                    <PropertyName>${field}</PropertyName>
                    <Literal>*${keyword}*</Literal>
                </PropertyIsLike>
            `;
        });

        // Nếu có nhiều trường thì bọc trong thẻ <Or>, nếu 1 trường thì không cần
        const filterXml = fields.length > 1 
            ? `<Filter xmlns="http://www.opengis.net/ogc"><Or>${filterInner}</Or></Filter>`
            : `<Filter xmlns="http://www.opengis.net/ogc">${filterInner}</Filter>`;

        // 3. Gọi WFS Service
        const url = `${geoserverWfs}?service=WFS` + 
                    `&version=1.1.0` + 
                    `&request=GetFeature` + 
                    `&typeName=${layer}` + 
                    `&outputFormat=application/json` + 
                    `&filter=${encodeURIComponent(filterXml)}` + 
                    `&maxFeatures=10`; // Lấy tối đa 10 kết quả

        fetch(url)
            .then(res => res.json())
            .then(data => {
                showLoading(false);
                if (data.features && data.features.length > 0) {
                    data.features.forEach(f => {
                        // Render kết quả ra HTML đẹp mắt
                        const div = document.createElement('div');
                        div.className = 'search-item d-flex align-items-start';
                        div.style.padding = '10px';
                        div.style.borderBottom = '1px solid #eee';
                        div.style.cursor = 'pointer';
                        
                        // Icon dựa theo lớp (trang trí)
                        let icon = '<i class="fa-solid fa-location-dot text-primary mt-1 me-2"></i>';
                        
                        // Nội dung hiển thị lấy từ hàm display trong Config
                        const contentHtml = config.display(f.properties);

                        div.innerHTML = `${icon}<div>${contentHtml}</div>`;
                        
                        // Sự kiện Click vào kết quả
                        div.onclick = () => {
                            // Highlight đối tượng trên bản đồ
                            const l = L.geoJSON(f, {
                                pointToLayer: function (feature, latlng) {
                                    // Tạo Marker tròn đỏ nổi bật
                                    return L.circleMarker(latlng, {
                                        radius: 8, fillColor: "#ff0000", color: "#fff", weight: 2, opacity: 1, fillOpacity: 0.8
                                    });
                                },
                                style: { color: "#ff0000", weight: 5, opacity: 0.6 } // Style cho đường ống nếu tìm đường
                            });
                            
                            // Zoom và Popup
                            map.fitBounds(l.getBounds(), { maxZoom: 19 });
                            l.addTo(map).bindPopup(contentHtml).openPopup();
                            
                            // Xóa highlight sau 5 giây
                            setTimeout(() => map.removeLayer(l), 5000);
                        };
                        
                        container.appendChild(div);
                    });
                } else {
                    container.innerHTML = '<div class="text-center text-muted p-3"><i class="fa-solid fa-circle-xmark mb-2"></i><br>Không tìm thấy kết quả nào.</div>';
                }
            })
            .catch(err => {
                showLoading(false);
                console.error(err);
                container.innerHTML = '<div class="text-center text-danger p-3">Lỗi kết nối Server!</div>';
            });
    }

    function locateUser() { map.locate({setView: true, maxZoom: 16}); }
    function resetMap() { map.setView(defaultCenter, defaultZoom); }
    function showLoading(show) { document.getElementById('loading-overlay').style.display = show ? 'flex' : 'none'; }
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>