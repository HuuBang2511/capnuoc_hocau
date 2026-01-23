<?php

use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset;
use app\widgets\maps\LeafletDrawAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;
use yii\helpers\Url;
use yii\helpers\Html;

// 1. Đăng ký các Assets của Yii2 (Leaflet & Plugins)
LeafletMapAsset::register($this);
LeafletDrawAsset::register($this);
LeafletMeasureAsset::register($this);
LeafletLocateAsset::register($this);

// 2. Cấu hình Endpoint GeoServer
$geoserverWmsUrl = 'http://103.9.77.141:8080/geoserver/capnuoc_hocau/wms';
$geoserverWfsUrl = 'http://103.9.77.141:8080/geoserver/capnuoc_hocau/wfs';
$geoserverWmtsUrl = 'http://103.9.77.141:8080/geoserver/gwc/service/wmts'; 

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
        /* --- CORE STYLES --- */
        :root { --primary-color: #0d6efd; --sidebar-width: 380px; }
        body { margin: 0; padding: 0; overflow: hidden; font-family: 'Segoe UI', system-ui, sans-serif; background: #f8f9fa; }
        
        #app-container { display: flex; height: 100vh; width: 100vw; position: relative; }
        
        /* --- SIDEBAR --- */
        #sidebar {
            width: var(--sidebar-width); background: #fff; display: flex; flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,0.08); z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute; top: 0; left: 0; bottom: 0;
        }
        #sidebar.collapsed { transform: translateX(calc(var(--sidebar-width) * -1)); }

        .sidebar-header {
            height: 60px; background: var(--primary-color); color: white;
            display: flex; align-items: center; justify-content: space-between; padding: 0 20px;
        }
        .brand-link { color: white; text-decoration: none; font-weight: 700; font-size: 1.1rem; }
        
        /* Custom Tabs */
        .nav-tabs-custom { display: flex; background: #f8f9fa; border-bottom: 1px solid #dee2e6; margin: 0; padding: 0; list-style: none; }
        .nav-tabs-custom li { flex: 1; text-align: center; }
        .nav-tabs-custom button {
            width: 100%; border: none; background: transparent; padding: 12px 0;
            color: #6c757d; font-weight: 600; border-bottom: 3px solid transparent;
            cursor: pointer; transition: all 0.2s; font-size: 0.95rem;
        }
        .nav-tabs-custom button:hover { background: #e9ecef; }
        .nav-tabs-custom button.active { color: var(--primary-color); background: #fff; border-bottom-color: var(--primary-color); }

        .sidebar-content { flex: 1; overflow-y: auto; position: relative; }
        .tab-panel { display: none; padding: 20px; }
        .tab-panel.active { display: block; }

        /* Layer Items */
        .layer-group { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 12px; overflow: hidden; }
        .layer-group-header {
            background: #f8f9fa; padding: 12px 15px; cursor: pointer; font-weight: 600; font-size: 0.95rem;
            display: flex; justify-content: space-between; align-items: center; user-select: none;
        }
        .layer-group-body { padding: 10px 15px; border-top: 1px solid #e2e8f0; display: block; }
        .form-check-custom { display: flex; align-items: center; margin-bottom: 8px; }
        .form-check-input { cursor: pointer; margin-right: 10px; width: 1.1em; height: 1.1em; }
        .form-check-label { cursor: pointer; font-size: 0.95rem; flex: 1; }

        /* --- MAP CONTROLS --- */
        #map-wrapper { flex: 1; position: relative; height: 100%; width: 100%; z-index: 1; }
        #map { width: 100%; height: 100%; outline: none; }

        #sidebar-toggle {
            position: absolute; top: 20px; left: 400px; z-index: 1001;
            width: 44px; height: 44px; border-radius: 50%; background: white; border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: var(--primary-color); font-size: 1.2rem;
        }

        .floating-toolbar {
            position: absolute; top: 20px; right: 20px; z-index: 999;
            display: flex; flex-direction: column; gap: 10px;
        }
        .tool-btn {
            width: 44px; height: 44px; border-radius: 8px; background: white; border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15); color: #495057; font-size: 1.2rem;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
        }
        .tool-btn:hover { background: #f8f9fa; color: var(--primary-color); }

        /* Legend & Popup */
        #legend-panel {
            position: absolute; bottom: 30px; left: 400px; z-index: 999;
            background: rgba(255, 255, 255, 0.95); padding: 15px; border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15); width: 260px; max-height: 350px; overflow-y: auto;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: none;
        }
        .legend-item { display: flex; align-items: center; margin-bottom: 8px; font-size: 0.9rem; }
        .legend-img { width: 24px; height: 24px; object-fit: contain; margin-right: 10px; }

        .leaflet-popup-content-wrapper { padding: 0; border-radius: 8px; }
        .leaflet-popup-content { margin: 0; width: 320px !important; }
        .popup-header { background: var(--primary-color); color: white; padding: 10px 15px; font-weight: 600; }
        .popup-body { max-height: 200px; overflow-y: auto; padding: 0; }
        .info-table td { padding: 6px 12px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        
        #loading-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7);
            z-index: 9999; display: none; align-items: center; justify-content: center;
        }
        .search-item { padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; }
        .search-item:hover { background: #f1f8ff; }
    </style>
    
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="app-container">
    
    <div id="sidebar">
        <div class="sidebar-header">
            <a href="<?= Url::home() ?>" class="brand-link">
                <i class="fa-solid fa-droplet me-2"></i> QUẢN LÝ CẤP NƯỚC
            </a>
        </div>

        <ul class="nav-tabs-custom">
            <li><button class="active" onclick="switchTab(event, 'tab-layers')"><i class="fa-solid fa-layer-group me-1"></i> Lớp</button></li>
            <li><button onclick="switchTab(event, 'tab-filter')"><i class="fa-solid fa-filter me-1"></i> Lọc</button></li>
            <li><button onclick="switchTab(event, 'tab-search')"><i class="fa-solid fa-search me-1"></i> Tìm</button></li>
        </ul>

        <div class="sidebar-content">
            <div id="tab-layers" class="tab-panel active">
                <div class="mb-4">
                    <label class="form-label text-uppercase fw-bold text-muted" style="font-size: 0.8rem;">Bản đồ nền</label>
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
                        ['id' => 'hanhlang', 'layer' => 'capnuoc_hocau:network_hanglangantoan', 'label' => 'Hành lang an toàn', 'checked' => false],
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
                    <i class="fa-solid fa-info-circle me-1"></i> Lọc dữ liệu hiển thị trên bản đồ.
                </div>
                <form id="filter-form">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Trạng thái</label>
                        <select class="form-select" id="filter-status">
                            <option value="">-- Tất cả --</option>
                            <option value="1">Đang sử dụng</option>
                            <option value="2">Hỏng / Cần sửa</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" onclick="applyFilter()">Áp dụng</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilter()">Xóa lọc</button>
                    </div>
                </form>
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
    const geoserverWms = '<?= $geoserverWmsUrl ?>';
    const geoserverWfs = '<?= $geoserverWfsUrl ?>';
    const geoserverWmts = '<?= $geoserverWmtsUrl ?>'; 

    const defaultCenter = [10.737202, 106.915000];
    const defaultZoom = 14;

    document.addEventListener('DOMContentLoaded', function() {
        initMap();
        initLayers();
        
        if (typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }
    });

    /* --- 1. CORE FUNCTIONS --- */
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
        
        // MẶC ĐỊNH LÀ GOOGLE MAPS
        setBasemap('google_road');
        
        L.control.locate({strings: {title: "Vị trí"}}).addTo(map);
    }

    function setBasemap(type) {
        if (window.currentBasemap) map.removeLayer(window.currentBasemap);
        let layer;
        
        if (type === 'hocau_base') {
            // WMTS cho layer base_thuadat
            layer = L.tileLayer(geoserverWmts + '?service=WMTS&request=GetTile&version=1.0.0' +
                '&layer=capnuoc_hocau:base_thuadat' + 
                '&style=' + 
                '&tilematrixset=EPSG:900913' + 
                '&format=image/png' + 
                '&tilematrix=EPSG:900913:{z}' + 
                '&tilerow={y}' + 
                '&tilecol={x}', {
                    maxZoom: 22,
                    attribution: 'Bản đồ Quy hoạch Hồ Cầu'
                }
            );

        } else if (type.includes('google')) {
            let s = 'm'; 
            if (type === 'google_satellite') s = 's,h';
            layer = L.tileLayer(`https://{s}.google.com/vt/lyrs=${s}&x={x}&y={y}&z={z}`, { maxZoom: 22, subdomains: ['mt0','mt1','mt2','mt3'] });
        } else if (type === 'osm') {
            layer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
        }
        
        if (layer && type !== 'none') {
            layer.addTo(map); 
            layer.bringToBack(); 
            window.currentBasemap = layer;
        }
    }

    /* --- 2. LAYER LOGIC --- */
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

    /* --- 3. UI INTERACTION (TAB & SIDEBAR) --- */
    function switchTab(evt, tabId) {
        const panels = document.getElementsByClassName("tab-panel");
        for (let i = 0; i < panels.length; i++) {
            panels[i].className = panels[i].className.replace(" active", "");
        }
        const buttons = document.querySelector(".nav-tabs-custom").getElementsByTagName("button");
        for (let i = 0; i < buttons.length; i++) {
            buttons[i].className = "";
        }
        document.getElementById(tabId).className += " active";
        evt.currentTarget.className += " active";
    }

    function toggleSidebar() {
        const sb = document.getElementById('sidebar');
        const btn = document.getElementById('sidebar-toggle');
        const leg = document.getElementById('legend-panel');
        sb.classList.toggle('collapsed');
        const isCollapsed = sb.classList.contains('collapsed');
        btn.style.left = isCollapsed ? '20px' : '400px';
        leg.style.left = isCollapsed ? '20px' : '400px';
    }
    
    function toggleGroup(id) {
        const body = document.getElementById(id);
        const icon = document.querySelector(`.icon-${id}`);
        if(body.style.display === 'none') {
            body.style.display = 'block';
            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        } else {
            body.style.display = 'none';
            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        }
    }

    /* --- 4. LEGEND & INFO --- */
    function updateLegend() {
        const box = document.getElementById('legend-panel');
        const content = document.getElementById('legend-content');
        content.innerHTML = '';
        let hasLayer = false;
        document.querySelectorAll('.wms-layer:checked').forEach(chk => {
            hasLayer = true;
            const name = chk.dataset.layer;
            const label = chk.nextElementSibling.innerText;
            const url = `${geoserverWms}?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=${name}`;
            const div = document.createElement('div');
            div.className = 'legend-item';
            div.innerHTML = `<img src="${url}" class="legend-img"> <span>${label}</span>`;
            content.appendChild(div);
        });
        box.style.display = hasLayer ? 'block' : 'none';
    }

    function getFeatureInfo(e) {
        const activeLayers = Array.from(document.querySelectorAll('.wms-layer:checked')).map(c => c.dataset.layer);
        if (activeLayers.length === 0) return;
        
        // ĐÃ SỬA: Không hiển thị showLoading(true) để tránh "xoay xoay" toàn màn hình khi click
        // Thay vào đó đổi con trỏ chuột để báo hiệu đang xử lý
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
                document.body.style.cursor = 'default'; // Trả lại con trỏ chuột
                if (data.features?.length) {
                    let html = '<table class="info-table" width="100%">';
                    const props = data.features[0].properties;
                    for (let k in props) {
                        if (props[k] && !['geom','the_geom','bbox'].includes(k)) 
                            html += `<tr><td style="font-weight:bold;color:#666">${k}</td><td>${props[k]}</td></tr>`;
                    }
                    html += '</table>';
                    L.popup({maxWidth:300}).setLatLng(e.latlng)
                     .setContent(`<div class="popup-header">Thông tin</div><div class="popup-body">${html}</div>`).openOn(map);
                }
            }).catch(() => {
                document.body.style.cursor = 'default';
            });
    }

    /* --- 5. SEARCH & FILTER --- */
    function searchObject() {
        const keyword = document.getElementById('search-keyword').value.trim();
        const layer = document.getElementById('search-layer').value;
        if (!keyword) return;
        showLoading(true); // Vẫn giữ xoay xoay khi tìm kiếm vì tác vụ này lâu hơn
        const container = document.getElementById('search-results');
        container.innerHTML = '';
        
        const filter = `<Filter xmlns="http://www.opengis.net/ogc"><PropertyIsLike wildCard="*" singleChar="." escapeChar="!"><PropertyName>objectid</PropertyName><Literal>*${keyword}*</Literal></PropertyIsLike></Filter>`;
        const url = `${geoserverWfs}?service=WFS&version=1.1.0&request=GetFeature&typeName=${layer}&outputFormat=application/json&filter=${encodeURIComponent(filter)}&maxFeatures=10`;

        fetch(url).then(res => res.json()).then(data => {
            showLoading(false);
            if (data.features?.length) {
                data.features.forEach(f => {
                    const div = document.createElement('div');
                    div.className = 'search-item';
                    div.innerHTML = `<i class="fa-solid fa-location-dot text-primary me-2"></i> ${f.properties.objectid || f.id}`;
                    div.onclick = () => {
                        const l = L.geoJSON(f);
                        map.fitBounds(l.getBounds(), { maxZoom: 18 });
                        l.addTo(map).bindPopup("Tìm thấy").openPopup();
                        setTimeout(() => map.removeLayer(l), 4000);
                    };
                    container.appendChild(div);
                });
            } else container.innerHTML = '<div class="text-center text-muted p-3">Không có kết quả</div>';
        }).catch(err => { showLoading(false); console.error(err); });
    }

    function applyFilter() {
        const status = document.getElementById('filter-status').value;
        let cql = status ? `tinhtrang_id = ${status}` : null;
        for (let key in wmsLayers) {
            wmsLayers[key].setParams({ cql_filter: cql });
        }
        alert('Đã áp dụng bộ lọc');
    }
    
    function clearFilter() {
        document.getElementById('filter-status').value = "";
        for (let key in wmsLayers) wmsLayers[key].setParams({ cql_filter: null });
        alert('Đã xóa bộ lọc');
    }

    function locateUser() { map.locate({setView: true, maxZoom: 16}); }
    function resetMap() { map.setView(defaultCenter, defaultZoom); }
    function showLoading(show) { document.getElementById('loading-overlay').style.display = show ? 'flex' : 'none'; }
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>