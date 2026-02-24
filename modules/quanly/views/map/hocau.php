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
$geoserverWmsUrl = 'http://gis.capnuochocaumoi.vn/geoserver/capnuoc_hocau/wms';
$geoserverWfsUrl = 'http://gis.capnuochocaumoi.vn/geoserver/capnuoc_hocau/wfs';
$geoserverWmtsUrl = 'http://gis.capnuochocaumoi.vn/geoserver/gwc/service/wmts'; 

// CẤU HÌNH LIÊN KẾT TRANG CHI TIẾT
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
    
    <style>
        /* CORE STYLES */
        :root { --primary-color: #0d6efd; --sidebar-width: 400px; }
        body { margin: 0; padding: 0; overflow: hidden; font-family: 'Segoe UI', system-ui, sans-serif; background: #f8f9fa; }
        #app-container { display: flex; height: 100vh; width: 100vw; position: relative; }
        
        /* SIDEBAR */
        #sidebar { width: var(--sidebar-width); background: #fff; display: flex; flex-direction: column; box-shadow: 4px 0 24px rgba(0,0,0,0.08); z-index: 1000; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: absolute; top: 0; left: 0; bottom: 0; }
        #sidebar.collapsed { transform: translateX(calc(var(--sidebar-width) * -1)); }
        .sidebar-header { height: 60px; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; }
        .brand-link { color: white; text-decoration: none; font-weight: 700; font-size: 1.1rem; }
        
        /* TABS */
        .nav-tabs-custom { display: flex; background: #f8f9fa; border-bottom: 1px solid #dee2e6; margin: 0; padding: 0; list-style: none; }
        .nav-tabs-custom li { flex: 1; text-align: center; }
        .nav-tabs-custom button { width: 100%; border: none; background: transparent; padding: 12px 0; color: #6c757d; font-weight: 600; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.2s; font-size: 0.95rem; }
        .nav-tabs-custom button:hover { background: #e9ecef; }
        .nav-tabs-custom button.active { color: var(--primary-color); background: #fff; border-bottom-color: var(--primary-color); }
        .sidebar-content { flex: 1; overflow-y: auto; position: relative; }
        .tab-panel { display: none; padding: 20px; }
        .tab-panel.active { display: block; }

        /* LAYER ITEMS */
        .layer-group { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 12px; overflow: hidden; }
        .layer-group-header { background: #f8f9fa; padding: 12px 15px; cursor: pointer; font-weight: 600; font-size: 0.95rem; display: flex; justify-content: space-between; align-items: center; user-select: none; }
        .layer-group-body { padding: 10px 15px; border-top: 1px solid #e2e8f0; display: block; }
        .form-check-custom { display: flex; align-items: center; margin-bottom: 8px; }
        .form-check-input { cursor: pointer; margin-right: 10px; width: 1.1em; height: 1.1em; }
        
        /* MAP CONTROLS */
        #map-wrapper { flex: 1; position: relative; height: 100%; width: 100%; z-index: 1; }
        #map { width: 100%; height: 100%; outline: none; }
        #sidebar-toggle { position: absolute; top: 20px; left: 420px; z-index: 1001; width: 44px; height: 44px; border-radius: 50%; background: white; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: var(--primary-color); font-size: 1.2rem; }
        .floating-toolbar { position: absolute; top: 20px; right: 20px; z-index: 999; display: flex; flex-direction: column; gap: 10px; }
        .tool-btn { width: 44px; height: 44px; border-radius: 8px; background: white; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); color: #495057; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .tool-btn:hover { background: #f8f9fa; color: var(--primary-color); }
        
        /* LEGEND & POPUP */
        #legend-panel { position: absolute; bottom: 30px; left: 420px; z-index: 999; background: rgba(255, 255, 255, 0.95); padding: 15px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); width: 260px; max-height: 350px; overflow-y: auto; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: none; }
        .leaflet-popup-content-wrapper { padding: 0; border-radius: 8px; }
        .leaflet-popup-content { margin: 0; width: 320px !important; }
        .popup-header { background: var(--primary-color); color: white; padding: 10px 15px; font-weight: 600; }
        .popup-body { max-height: 200px; overflow-y: auto; padding: 0; }
        .info-table td { padding: 6px 12px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        #loading-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 9999; display: none; align-items: center; justify-content: center; }
        .search-item { padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; }
        .search-item:hover { background: #f1f8ff; }

        .iot-tooltip { background: transparent; border: none; box-shadow: none; }
.iot-tooltip::before { display: none; }
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
            <li><button onclick="switchTab(event, 'tab-download')"><i class="fa-solid fa-cloud-arrow-down me-1"></i> Tải về</button></li>
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
                $layerGroups = [
                    'Mạng Lưới Đường Ống' => [
                        ['id' => 'truyendan', 'layer' => 'capnuoc_hocau:network_ongtruyendan', 'label' => 'Ống truyền dẫn', 'checked' => true, 'zIndex' => 20],
                        ['id' => 'phanphoi', 'layer' => 'capnuoc_hocau:network_ongphanphoi', 'label' => 'Ống phân phối', 'checked' => true, 'zIndex' => 20],
                        ['id' => 'moinoi', 'layer' => 'capnuoc_hocau:network_moinoi', 'label' => 'Mối nối', 'checked' => false, 'zIndex' => 30],
                    ],
                    'Thiết Bị & Đồng Hồ' => [
                        ['id' => 'van', 'layer' => 'capnuoc_hocau:network_van', 'label' => 'Van mạng lưới', 'checked' => true, 'zIndex' => 30],
                        ['id' => 'dhtong', 'layer' => 'capnuoc_hocau:network_donghotong', 'label' => 'Đồng hồ tổng', 'checked' => true, 'zIndex' => 30],
                        ['id' => 'dhnhamay', 'layer' => 'capnuoc_hocau:network_donghonhamay', 'label' => 'Đồng hồ nhà máy', 'checked' => false, 'zIndex' => 30],
                    ],
                    'Công Trình & Khác' => [
                        ['id' => 'nhamay', 'layer' => 'capnuoc_hocau:network_nhamaynuoc', 'label' => 'Nhà máy nước', 'checked' => true, 'zIndex' => 10],
                        ['id' => 'ham', 'layer' => 'capnuoc_hocau:network_hamkythuat', 'label' => 'Hầm kỹ thuật', 'checked' => false, 'zIndex' => 10],
                        ['id' => 'hanhlang', 'layer' => 'capnuoc_hocau:network_hanglangantoan', 'label' => 'Hành lang an toàn', 'checked' => false, 'zIndex' => 10],
                        ['id' => 'cocmoc', 'layer' => 'capnuoc_hocau:network_cocmoc', 'label' => 'Cọc mốc', 'checked' => false, 'zIndex' => 30],
                        ['id' => 'suco', 'layer' => 'capnuoc_hocau:network_suco', 'label' => 'Điểm sự cố', 'checked' => true, 'zIndex' => 40],
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
                                   data-zindex="<?= $layer['zIndex'] ?>" 
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
                            <select class="form-select" id="dynamic-filter-value"></select>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="button" class="btn btn-primary" onclick="applySmartFilter()"><i class="fa-solid fa-check me-1"></i> Áp dụng</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearSmartFilter()"><i class="fa-solid fa-rotate-left me-1"></i> Xóa lọc</button>
                    </div>
                </form>
            </div>

            <div id="tab-download" class="tab-panel">
                <div class="alert alert-light border shadow-sm mb-3 text-muted small">
                    <i class="fa-solid fa-download me-1"></i> Tải xuống dữ liệu GIS gốc từ Server.
                </div>
                
                <form id="download-form">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">1. Chọn lớp dữ liệu</label>
                        <select class="form-select" id="download-layer-select">
                            <option value="">-- Chọn lớp --</option>
                            </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">2. Định dạng file</label>
                        <select class="form-select" id="download-format-select">
                            <option value="shape-zip">Esri Shapefile (.zip)</option>
                            <option value="application/vnd.google-earth.kml+xml">Google Earth (.kml)</option>
                            <option value="application/json">GeoJSON (.json)</option>
                            <option value="csv">Excel / CSV (.csv)</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="button" class="btn btn-success" onclick="executeDownload()">
                            <i class="fa-solid fa-cloud-arrow-down me-2"></i> Tải xuống ngay
                        </button>
                    </div>
                </form>

                <div class="mt-4 p-3 bg-light rounded small text-muted">
                    <i class="fa-solid fa-circle-info me-1"></i> <strong>Lưu ý:</strong><br>
                    Dữ liệu tải về là dữ liệu mới nhất từ máy chủ GeoServer. File Shapefile cần phần mềm chuyên dụng (ArcGIS, QGIS) để mở.
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
                        <option value="capnuoc_hocau:network_suco">Sự cố</option>
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
    const geoserverWms = '<?= $geoserverWmsUrl ?>';
    const geoserverWfs = '<?= $geoserverWfsUrl ?>';
    const geoserverWmts = '<?= $geoserverWmtsUrl ?>';
    const DETAIL_LINKS = <?= $jsonDetailLinks ?>;

    // --- CẤU HÌNH BỘ LỌC ĐỘNG ---
    const FILTER_CONFIG = {
        'capnuoc_hocau:network_ongphanphoi': { field: 'loaiong_id', label: 'Chất liệu ống', options: <?= Json::encode($filterData['loaiong'] ?? []) ?> },
        'capnuoc_hocau:network_donghonhamay': { field: 'hieudongho_id', label: 'Hiệu đồng hồ', options: <?= Json::encode($filterData['hieudongho'] ?? []) ?> },
        'capnuoc_hocau:network_van': { field: 'tinhtrang_id', label: 'Trạng thái hoạt động', options: <?= Json::encode($filterData['tinhtrang'] ?? []) ?> },
        'capnuoc_hocau:network_suco': { field: 'nguyennhansuco_id', label: 'Nguyên nhân sự cố', options: <?= Json::encode($filterData['nguyennhan'] ?? []) ?> },
        'capnuoc_hocau:network_hamkythuat': { field: 'loaiham_id', label: 'Loại hầm', options: <?= Json::encode($filterData['loaiham'] ?? []) ?> },
        'capnuoc_hocau:network_moinoi': { field: 'loaimoinoi_id', label: 'Kiểu mối nối', options: <?= Json::encode($filterData['loaimoinoi'] ?? []) ?> }
    };
    
    // --- CẤU HÌNH TÌM KIẾM ---
    const SEARCH_CONFIG = {
        'capnuoc_hocau:network_donghonhamay': { fields: ['objectid', 'shd', 'ten_khach_hang', 'dia_chi'], display: (p) => `<b>${p.ten_khach_hang || 'Chưa cập nhật'}</b><br><small>${p.dia_chi || ''}</small>` },
        'capnuoc_hocau:network_van': { fields: ['objectid', 'vitri', 'lydoghi'], display: (p) => `<b>Van: ${p.objectid}</b><br><small>${p.vitri || 'Không có vị trí'}</small>` },
        'capnuoc_hocau:network_suco': { fields: ['masuco', 'vitri', 'nguyennhan', 'ghichu'], display: (p) => `<b>SC: ${p.masuco || p.id}</b><br><small>${p.vitri || ''}</small>` },
        'default': { fields: ['objectid'], display: (p) => `<b>${p.objectid || 'Đối tượng'}</b>` }
    };

    const defaultCenter = [10.737202, 106.915000];
    const defaultZoom = 14;

    document.addEventListener('DOMContentLoaded', function() {
        initMap();
        initLayers();
        populateFilterLayerList(); // Nạp cho Tab Lọc
        populateDownloadOptions(); // Nạp cho Tab Tải xuống (MỚI)
        
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
            const zIndexVal = parseInt(chk.dataset.zindex) || 10;
            const layer = L.tileLayer.wms(geoserverWms, {
                layers: name, format: 'image/png', transparent: true, version: '1.1.0', tiled: true, maxZoom: 22, zIndex: zIndexVal
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

    // --- FILTER LOGIC ---
    function populateFilterLayerList() {
        const select = document.getElementById('filter-layer-select');
        select.innerHTML = '<option value="">-- Chọn đối tượng --</option>';
        document.querySelectorAll('.wms-layer').forEach(chk => {
            const layerName = chk.dataset.layer;
            if (FILTER_CONFIG[layerName]) {
                const opt = document.createElement('option');
                opt.value = layerName; opt.text = chk.dataset.label;
                select.appendChild(opt);
            }
        });
    }

    function onFilterLayerChange() {
        const layerName = document.getElementById('filter-layer-select').value;
        const container = document.getElementById('dynamic-filter-container');
        const valueSelect = document.getElementById('dynamic-filter-value');
        const label = document.getElementById('dynamic-filter-label');
        valueSelect.innerHTML = ''; 

        if (!layerName || !FILTER_CONFIG[layerName]) {
            container.style.display = 'none'; return;
        }

        const config = FILTER_CONFIG[layerName];
        container.style.display = 'block';
        label.innerText = config.label; 
        const defOpt = document.createElement('option'); defOpt.value = ""; defOpt.text = "-- Tất cả --"; valueSelect.appendChild(defOpt);

        if (config.options && config.options.length > 0) {
            config.options.forEach(item => {
                const opt = document.createElement('option'); opt.value = item.id; opt.text = item.ten; valueSelect.appendChild(opt);
            });
        }
    }

    function applySmartFilter() {
        const layerName = document.getElementById('filter-layer-select').value;
        const value = document.getElementById('dynamic-filter-value').value;
        if (!layerName) { alert('Vui lòng chọn lớp dữ liệu!'); return; }
        showLoading(true);
        const fieldName = FILTER_CONFIG[layerName].field;
        const layer = wmsLayers[layerName];

        if (layer) {
            if (value) {
                const cql = `${fieldName} = ${value}`;
                layer.setParams({ cql_filter: cql });
            } else {
                if (layer.wmsParams.cql_filter) { delete layer.wmsParams.cql_filter; layer.redraw(); }
            }
            const chk = document.querySelector(`.wms-layer[data-layer="${layerName}"]`);
            if(chk && !chk.checked) { chk.checked = true; toggleLayer(layerName, true); }
        }
        setTimeout(() => { showLoading(false); }, 500);
    }

    function clearSmartFilter() {
        document.getElementById('filter-layer-select').value = "";
        document.getElementById('dynamic-filter-container').style.display = 'none';
        for (let key in wmsLayers) {
            let layer = wmsLayers[key];
            if (layer.wmsParams && layer.wmsParams.cql_filter) { delete layer.wmsParams.cql_filter; layer.redraw(); }
        }
        alert('Đã xóa bộ lọc.');
    }

    // --- DOWNLOAD LOGIC (NEW) ---
    function populateDownloadOptions() {
        const select = document.getElementById('download-layer-select');
        select.innerHTML = '<option value="">-- Chọn lớp dữ liệu --</option>';
        // Lấy tất cả các lớp có trong bản đồ để cho phép tải về
        document.querySelectorAll('.wms-layer').forEach(chk => {
            const opt = document.createElement('option');
            opt.value = chk.dataset.layer; 
            opt.text = chk.dataset.label;
            select.appendChild(opt);
        });
    }

    function executeDownload() {
        const layer = document.getElementById('download-layer-select').value;
        const format = document.getElementById('download-format-select').value;

        if (!layer) {
            alert('Vui lòng chọn lớp dữ liệu cần tải!');
            return;
        }

        // Tạo URL WFS GetFeature để tải về
        // service=WFS & request=GetFeature & typeName=... & outputFormat=...
        const url = `${geoserverWfs}?service=WFS&version=1.0.0&request=GetFeature` +
                    `&typeName=${layer}&outputFormat=${format}`;
        
        // Mở tab mới để tải
        window.open(url, '_blank');
    }

    // --- OTHER UTILS ---
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
        fetch(geoserverWms + L.Util.getParamString(params, geoserverWms, true)).then(r => r.json()).then(data => {
            document.body.style.cursor = 'default';
            if (data.features && data.features.length > 0) {
                const feature = data.features[0]; 
                const props = feature.properties;
                let html = '<table class="info-table" width="100%">';
                const ignoredKeys = ['geom', 'the_geom', 'geometry', 'geojson', 'bbox', 'coordinates', 'type', 'shape_leng', 'shape_area', 'st_area', 'st_length', 'len', 'shape_length', 'lat', 'long', 'lng', 'x', 'y', 'objectid_1', 'gid', 'id_0', 'status'];
                for (let k in props) {
                    if (props[k] !== null && props[k] !== undefined && props[k] !== '') {
                        if (!ignoredKeys.includes(k.toLowerCase())) {
                            let label = k.charAt(0).toUpperCase() + k.slice(1).replace(/_/g, ' ');
                            html += `<tr><td style="font-weight:bold;color:#666;white-space:nowrap;padding-right:10px;vertical-align:top;">${label}</td><td>${props[k]}</td></tr>`;
                        }
                    }
                }
                html += '</table>';
                let btnHtml = '';
                if (feature.id && feature.id.includes('.')) {
                    const parts = feature.id.split('.'); const layerKey = parts[0]; const objId = props.id || props.objectid || parts[1]; 
                    if (DETAIL_LINKS[layerKey] && objId) {
                        const url = `${DETAIL_LINKS[layerKey]}?id=${objId}`;
                        btnHtml = `<div class="mt-2 text-end border-top pt-2"><a href="${url}" target="_blank" class="btn btn-sm btn-primary text-white" style="text-decoration:none;"><i class="fa-solid fa-circle-info me-1"></i> Xem chi tiết</a></div>`;
                    }
                }
                L.popup({ maxWidth: 320 }).setLatLng(e.latlng).setContent(`<div class="popup-header">Thông tin đối tượng</div><div class="popup-body">${html}${btnHtml}</div>`).openOn(map);
            }
        }).catch((err) => { console.error(err); document.body.style.cursor = 'default'; });
    }

    function searchObject() {
        const keyword = document.getElementById('search-keyword').value.trim();
        const layer = document.getElementById('search-layer').value;
        if (!keyword) { alert('Vui lòng nhập từ khóa!'); return; }
        showLoading(true);
        const container = document.getElementById('search-results'); container.innerHTML = '';
        const config = SEARCH_CONFIG[layer] || SEARCH_CONFIG['default'];
        const fields = config.fields;
        let filterInner = '';
        fields.forEach(field => {
            filterInner += `<PropertyIsLike wildCard="*" singleChar="." escapeChar="!" matchCase="false"><PropertyName>${field}</PropertyName><Literal>*${keyword}*</Literal></PropertyIsLike>`;
        });
        const filterXml = fields.length > 1 ? `<Filter xmlns="http://www.opengis.net/ogc"><Or>${filterInner}</Or></Filter>` : `<Filter xmlns="http://www.opengis.net/ogc">${filterInner}</Filter>`;
        const url = `${geoserverWfs}?service=WFS&version=1.1.0&request=GetFeature&typeName=${layer}&outputFormat=application/json&filter=${encodeURIComponent(filterXml)}&maxFeatures=10`;

        fetch(url).then(res => res.json()).then(data => {
            showLoading(false);
            if (data.features && data.features.length > 0) {
                data.features.forEach(f => {
                    const div = document.createElement('div'); div.className = 'search-item d-flex align-items-start'; div.style.padding = '10px'; div.style.borderBottom = '1px solid #eee'; div.style.cursor = 'pointer';
                    let icon = '<i class="fa-solid fa-location-dot text-primary mt-1 me-2"></i>';
                    const contentHtml = config.display(f.properties);
                    div.innerHTML = `${icon}<div>${contentHtml}</div>`;
                    div.onclick = () => {
                        const l = L.geoJSON(f, { pointToLayer: function (feature, latlng) { return L.circleMarker(latlng, { radius: 8, fillColor: "#ff0000", color: "#fff", weight: 2, opacity: 1, fillOpacity: 0.8 }); }, style: { color: "#ff0000", weight: 5, opacity: 0.6 } });
                        map.fitBounds(l.getBounds(), { maxZoom: 19 });
                        l.addTo(map).bindPopup(contentHtml).openPopup();
                        setTimeout(() => map.removeLayer(l), 5000);
                    };
                    container.appendChild(div);
                });
            } else { container.innerHTML = '<div class="text-center text-muted p-3"><i class="fa-solid fa-circle-xmark mb-2"></i><br>Không tìm thấy kết quả nào.</div>'; }
        }).catch(err => { showLoading(false); console.error(err); container.innerHTML = '<div class="text-center text-danger p-3">Lỗi kết nối Server!</div>'; });
    }

    function locateUser() { map.locate({setView: true, maxZoom: 16}); }
    function resetMap() { map.setView(defaultCenter, defaultZoom); }
    function showLoading(show) { document.getElementById('loading-overlay').style.display = show ? 'flex' : 'none'; }

    // ==========================================
    // MODULE TÍCH HỢP SCADA IOT REALTIME
    // ==========================================
    let iotMarkers = {}; // Lưu trữ các marker IOT để cập nhật tránh vẽ đè nhiều lần
    
    // Hàm này sẽ chạy ngầm định kỳ
    function fetchIotDataRealtime() {
        // Gọi API lấy data IOT mới nhất
        fetch('<?= Url::to(['/quanly/map/get-iot']) ?>')
            .then(res => res.json())
            .then(data => {
                for (let ma_tram in data) {
                    let iotData = data[ma_tram];
                    hienThiThongSoIotLenMap(ma_tram, iotData);
                }
            })
            .catch(err => console.log("Lỗi lấy data IOT:", err));
    }

    function hienThiThongSoIotLenMap(ma_tram, iotData) {
        // TẠM THỜI: Mày phải nhập tọa độ tĩnh của cái trạm SCADA vào đây 
        // (Vì data SCADA bắn về không có tọa độ Lat/Long)
        // Ví dụ tọa độ của cái trạm "vilog_94e686709b90":
        let toa_do_tram = null;
        
        if (ma_tram === "vilog_94e686709b90") {
            toa_do_tram = [10.737202, 106.915000]; // Mày SỬA LẠI TỌA ĐỘ THỰC TẾ của trạm này nhé
        }
        
        if (!toa_do_tram) return; // Nếu không có tọa độ thì không vẽ được

        // Format cái bảng thông số
        let tooltipContent = `
            <div style="background:#000; color:#0f0; padding:8px; border-radius:5px; font-family:monospace; min-width:140px; border: 1px solid #0f0;">
                <div style="font-weight:bold; border-bottom:1px dashed #0f0; margin-bottom:5px; padding-bottom:3px;">
                    <i class="fa-solid fa-satellite-dish me-1"></i> Trạm: ${ma_tram}
                </div>
                <div>Áp lực: <b style="color:#fff">${iotData.ap_luc}</b> bar</div>
                <div>Lưu lượng: <b style="color:#fff">${iotData.luu_luong}</b> m3/h</div>
                <div style="font-size:0.8em; color:#888; margin-top:5px;">Cập nhật: ${iotData.timestamp}</div>
            </div>
        `;

        // Nếu marker trạm này đã có trên map -> Chỉ update nội dung
        if (iotMarkers[ma_tram]) {
            iotMarkers[ma_tram].setTooltipContent(tooltipContent);
        } else {
            // Nếu chưa có -> Tạo marker mới
            let marker = L.circleMarker(toa_do_tram, {
                radius: 8,
                fillColor: "#00ff00", // Màu xanh lá cây
                color: "#000",
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);
            
            // Gắn tooltip hiển thị vĩnh viễn (permanent: true)
            marker.bindTooltip(tooltipContent, {
                permanent: true, 
                direction: 'right',
                className: 'iot-tooltip',
                offset: [10, 0]
            });
            
            iotMarkers[ma_tram] = marker;
        }
    }

    // Khởi động vòng lặp gọi API mỗi 5 giây (5000 ms) sau khi bản đồ load xong
    setTimeout(() => {
        fetchIotDataRealtime();
        setInterval(fetchIotDataRealtime, 5000); 
    }, 2000);
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>