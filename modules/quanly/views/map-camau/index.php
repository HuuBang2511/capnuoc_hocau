<?php
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;
use kartik\depdrop\DepDrop;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

LeafletMapAsset::register($this);
\app\widgets\maps\plugins\leafletprint\PrintMapAsset::register($this);
\app\widgets\maps\plugins\markercluster\MarkerClusterAsset::register($this);
\app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset::register($this);
LeafletLocateAsset::register($this);

$this->title = 'Bản đồ';
$this->params['hideHero'] = true;
?>

<style>
#map {
    width: 100%;
    height: 100vh;
}

#mapInfo {
    display: flex;
    height: 100vh;
}

#mapTong {
    width: 80%;
    transition: width 0.3s;
    position: relative;
    z-index: 1000;
}

#map {
    position: relative;
    z-index: 0;
    height: 100%;
}

.leaflet-pane {
    z-index: 400;
}
.leaflet-overlay-pane {
    z-index: 650;
}

/* Tab styling */
#tabs {
    width: 20%;
    background: #fff;
    border-right: 1px solid #ccc;
    transition: transform 0.3s ease-in-out;
    position: relative;
    transform: translateX(0);
}

#tabs.toggling {
    pointer-events: none;
    transition: transform 0.3s ease-in-out;
}

#tabs.active {
    transform: translateX(0); /* Fully visible */
}

.tab-buttons {
    display: flex;
    border-bottom: 1px solid #ccc;
}

.tab-button {
    flex: 1;
    padding: 10px;
    text-align: center;
    cursor: pointer;
    background: #f0f0f0;
    border: none;
}

.tab-button.active {
    background: #fff;
    border-bottom: 2px solid #007bff;
}

.tab-content {
    display: none;
    padding: 10px;
    height: calc(100vh - 40px);
    overflow-y: auto;
}

.tab-content.active {
    display: block;
}

#layer-content h5, #info-content h5 {
    margin-top: 20px;
}

#layer-control label {
    display: block;
    margin: 5px 0;
}

/* Mobile-specific back button */
#back-to-map-btn {
    display: none;
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.tabs-header{
    display:flex;
    justify-content: space-between;
}

#back-to-map-mobile-btn{
    display:none;
}

@media screen and (max-width: 768px) {
    #mapInfo {
        flex-direction: column;
    }

    #tabs {
        width: 100%;
        position: absolute;
        top: 0;
        left: 0;
        transform: translateX(-100%); /* Hidden by default on mobile */
        z-index: 1001;
        height: 100vh;
        background: #fff;
    }

    #tabs.active {
        transform: translateX(0); /* Visible when active */
    }

    #mapTong {
        width: 100%;
        transition: width 0.3s;
    }

    #mapTong.toggling {
        transition: width 0.3s;
    }

    .tab-button {
        padding: 15px;
    }

    #back-to-map-btn {
        display: block;
    }

    #layer-content, #info-content {
        max-height: 70vh;
        overflow-y: scroll;
    }

    #back-to-map-mobile-btn{
        display:block;
        margin-top: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        height: 30px;
    }
}

/* Toggle button styling */
#toggle-tab-btn {
    position: absolute;
    top: 10px;
    z-index: 1000;
    background: #fff;
    border: 1px solid #ccc;
    padding: 5px 10px;
    cursor: pointer;
}

div#tabs {
    display: flex;
    flex-direction: column;
}

.popup-content {
    font-size: 16px;
    max-width: 100%;
    overflow-x: auto;
}

.popup-table {
    width: 100%;
    border-collapse: collapse;
}

.popup-table th {
    background-color: #f2f2f2;
    padding: 8px;
    text-align: left;
}

.popup-table td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
}

.popup-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.popup-table th:hover {
    background-color: #ddd;
}

@media screen and (max-width: 600px) {
    .popup-content {
        width: 100%;
    }

    .popup-table {
        overflow-x: auto;
    }
}

.legend {
    background-color: white;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    display: none;
}

.legend img {
    width: 20px;
    height: auto;
    margin-right: 5px;
}
</style>

<!-- Tải plugin Leaflet-LocateControl -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
<script src="https://unpkg.com/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>

<div id="mapInfo">
    <div id="tabs">
        <div class="tabs-header">
            <a href="<?= Yii::$app->homeUrl ?>" target="_blank">
                <img src="http://hpngis.online/resources/images/logo_hpngis.png" alt="Logo" style="width: 200px; height: auto; float: left; margin-right: 10px;">
            </a>
            <button id="back-to-map-mobile-btn" onclick="toggleTabVisibility()">X</button>
        </div>
        
        <div class="tab-buttons">
            <button class="tab-button active" onclick="openTab('layer')">Lớp dữ liệu</button>
            <button class="tab-button" onclick="openTab('info')">Thông tin chi tiết</button>
        </div>
        <div id="layer-content" class="tab-content active">
            <h5>Hiển thị lớp dữ liệu</h5>
            <div id="layer-control">
                <label><input type="checkbox" onchange="toggleLayer('wmsLoogerLayer')"> Data Logger</label><br>
                <label><input type="checkbox" checked onchange="toggleLayer('wmsDonghoLayer')"> Đồng hồ</label><br>
                <label><input type="checkbox" checked onchange="toggleLayer('wmsOngDichVuLayer')"> Ống dịch vụ</label><br>
                <label><input type="checkbox" checked onchange="toggleLayer('wmsOngPhanPhoiLayer')"> Ống phân phối</label><br>
                <label><input type="checkbox" checked onchange="toggleLayer('wmsVanLayer')"> Van</label><br>
                <label><input type="checkbox" checked onchange="toggleLayer('wmsThuaDatLayer')"> Thửa đất</label><br>
                <label><input type="checkbox" checked onchange="toggleLayer('wmsGiaoThongLayer')"> Giao thông</label><br>
                <label><input type="checkbox" checked onchange="toggleLayer('highlightLayer')"> Highlight</label><br>
                <button id="back-to-map-btn" onclick="toggleTabVisibility()">Quay lại map</button>
            </div>
        </div>
        <div id="info-content" class="tab-content">
            <h5>Thông tin chi tiết</h5>
            <div id="feature-info" style="height: calc(100vh - 60px); overflow-y: auto;">
                <div id="feature-details">Chọn một đối tượng trên bản đồ để xem thông tin</div>
                <button id="back-to-map-btn" onclick="toggleTabVisibility()">Quay lại map</button>
            </div>
        </div>
    </div>

    <div id="mapTong">
        <div id="map" style="height: 100vh;"></div>
    </div>
</div>

<script>
var center = [0.06245279349519678, 103.315951132905113];

// Create the map
var map = L.map('map', {
    defaultExtentControl: true,
    maxZoom: 25, // Cho phép zoom đến mức 22
    minZoom: 10  // Giới hạn zoom ra ở mức 10
}).setView(center, 18);

//Thêm lớp L.Control.Locate
var locateControl = new L.Control.Locate({
    position: 'bottomleft',
    strings: {
        title: "Hiện vị trí",
        popup: "Bạn đang ở đây"
    },
    drawCircle: true,
    follow: true,
});
map.addControl(locateControl);

var measureControl = new L.Control.Measure({
    position: 'bottomright',
    primaryLengthUnit: 'meters',
    secondaryLengthUnit: undefined,
    primaryAreaUnit: 'sqmeters',
    decPoint: ',',
    thousandsSep: '.'
});
measureControl.addTo(map);

L.control.scale({
    imperial: false,
    maxWidth: 150
}).addTo(map);

// Create custom panes to control layer order
var giaoThongPane = map.createPane('giaoThongPane');
giaoThongPane.style.zIndex = 401;

var thuaDatPane = map.createPane('thuaDatPane');
thuaDatPane.style.zIndex = 402;

var ongNuocPane = map.createPane('ongNuocPane');
ongNuocPane.style.zIndex = 403;

var thietBiPane = map.createPane('thietBiPane');
thietBiPane.style.zIndex = 404;

// Pane for the highlight layer to ensure it's on top
var highlightPane = map.createPane('highlightPane');
highlightPane.style.zIndex = 651;

var highlightLayer = L.featureGroup({pane: 'highlightPane'}).addTo(map); // Lớp để highlight đối tượng được chọn

var wmsDonghoLayer = L.tileLayer.wms('http://103.9.77.141:8080/geoserver/giscapnuoc/wms', {
    layers: 'giscapnuoc:swanbay_dongho',
    format: 'image/png',
    transparent: true,
    maxZoom: 25,
    pane: 'thietBiPane' // Assign to the top equipment pane
}).addTo(map);

var wmsOngDichVuLayer = L.tileLayer.wms('http://103.9.77.141:8080/geoserver/giscapnuoc/wms', {
    layers: 'giscapnuoc:swanbay_ongdichvu',
    format: 'image/png',
    transparent: true,
    maxZoom: 25,
    pane: 'ongNuocPane' // Assign to the pipe pane
}).addTo(map);

var wmsOngPhanPhoiLayer = L.tileLayer.wms('http://103.9.77.141:8080/geoserver/giscapnuoc/wms', {
    layers: 'giscapnuoc:swanbay_ongphanphoi',
    format: 'image/png',
    transparent: true,
    maxZoom: 25,
    pane: 'ongNuocPane' // Assign to the pipe pane
}).addTo(map);

var wmsVanLayer = L.tileLayer.wms('http://103.9.77.141:8080/geoserver/giscapnuoc/wms', {
    layers: 'giscapnuoc:swanbay_van',
    format: 'image/png',
    transparent: true,
    maxZoom: 25,
    pane: 'thietBiPane' // Assign to the top equipment pane
}).addTo(map);

var wmsThuaDatLayer = L.tileLayer.wms('http://103.9.77.141:8080/geoserver/giscapnuoc/wms', {
    layers: 'giscapnuoc:swanbay_thuadat',
    format: 'image/png',
    transparent: true,
    interactive: false,
    maxZoom: 25,
    pane: 'thuaDatPane' // Assign to the land parcel pane
}).addTo(map);

var wmsGiaoThongLayer = L.tileLayer.wms('http://103.9.77.141:8080/geoserver/giscapnuoc/wms', {
    layers: 'giscapnuoc:swanbay_giaothong',
    format: 'image/png',
    transparent: true,
    interactive: false,
    maxZoom: 25,
    pane: 'giaoThongPane' // Assign to the bottom traffic pane
}).addTo(map);


function toggleLayer(layerName) {
    // A dummy wmsLoogerLayer to prevent errors if it's not defined elsewhere
    var wmsLoogerLayer = wmsLoogerLayer || L.tileLayer(''); 
    
    var layerMap = {
        "wmsLoogerLayer": wmsLoogerLayer,
        "wmsDonghoLayer": wmsDonghoLayer,
        "wmsOngDichVuLayer": wmsOngDichVuLayer,
        "wmsOngPhanPhoiLayer": wmsOngPhanPhoiLayer,
        "wmsVanLayer": wmsVanLayer,
        "wmsThuaDatLayer": wmsThuaDatLayer,
        "wmsGiaoThongLayer": wmsGiaoThongLayer,
        "highlightLayer": highlightLayer
    };

    var checkbox = event.target;
    var layer = layerMap[layerName];

    if(layer) {
        if (checkbox.checked) {
            layer.addTo(map);
        } else {
            map.removeLayer(layer);
        }
    }
}

function getFeatureInfoUrl(layer, latlng, url) {
    let size = map.getSize();
    let bbox = map.getBounds().toBBoxString();
    let point = map.latLngToContainerPoint(latlng, map.getZoom());

    const FeatureInfoUrl = url +
        `?SERVICE=WMS` +
        `&VERSION=1.1.1` +
        `&REQUEST=GetFeatureInfo` +
        `&LAYERS=${layer}` +
        `&QUERY_LAYERS=${layer}` +
        `&STYLES=` +
        `&BBOX=${bbox}` +
        `&FEATURE_COUNT=5` +
        `&HEIGHT=${size.y}` +
        `&WIDTH=${size.x}` +
        `&FORMAT=image/png` +
        `&INFO_FORMAT=application/json` +
        `&SRS=EPSG:4326` +
        `&X=${Math.floor(point.x)}` +
        `&Y=${Math.floor(point.y)}`;

    return FeatureInfoUrl;
}

map.on('click', function(e) {
    const layers = map._layers;
    const isMobile = window.innerWidth <= 768;
    let tabShown = false;

    for (const idx in layers) {
        const layer = layers[idx];
        if (layer.wmsParams && layer._url && layer.wmsParams.layers != "") {
            let url = getFeatureInfoUrl(layer.wmsParams.layers, e.latlng, layer._url);

            let layerName = layer.wmsParams.layers;
            layerName = layerName.split(':')[1]; // Get the layer name after the colon
            layerName = String(layerName);

            fetch(url)
                .then(function(res) {
                    return res.json()
                })
                .then(function(geojsonData) {
                    if (geojsonData.features && geojsonData.features.length > 0) {
                        var properties = geojsonData.features[0].properties;
                        var popupContent = ''; // Initialize popupContent

                        if (layer.wmsParams.layers) {
                            switch (layerName) {
                                // New cases for swanbay layers
                                case 'swanbay_dongho':
                                    popupContent = "<div class='popup-content'><table class='popup-table'>" +
                                        "<tr><td><strong>Cỡ ĐH:</strong></td><td>" + (properties.CoDH || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Hiệu ĐH:</strong></td><td>" + (properties.HieuDH || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Khách Hàng:</strong></td><td>" + (properties.KhachHang || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Địa Chỉ:</strong></td><td>" + (properties.DiaChi || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Ngày Lắp Đặt:</strong></td><td>" + (properties.NgayLapDat ? new Date(properties.NgayLapDat).toLocaleDateString('vi-VN') : 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Tình Trạng:</strong></td><td>" + (properties.TinhTrangN || 'N/A') + "</td></tr>" +
                                        "</table></div>";
                                    break;
                                case 'swanbay_ongphanphoi':
                                    popupContent = "<div class='popup-content'><table class='popup-table'>" +
                                        "<tr><td><strong>Vật Liệu:</strong></td><td>" + (properties.VatLieu || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Cỡ Ống:</strong></td><td>" + (properties.CoOng || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Loại Ống:</strong></td><td>" + (properties.LoaiOng || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Thời Gian Lắp Đặt:</strong></td><td>" + (properties.ThoiGianLa ? new Date(properties.ThoiGianLa).toLocaleDateString('vi-VN') : 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Tình Trạng:</strong></td><td>" + (properties.TinhTrang || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>ĐV Thiết Kế:</strong></td><td>" + (properties.DVTK || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>ĐV Thi Công:</strong></td><td>" + (properties.DVTC || 'N/A') + "</td></tr>" +
                                        "</table></div>";
                                    break;
                                case 'swanbay_ongdichvu':
                                    popupContent = "<div class='popup-content'><table class='popup-table'>" +
                                        "<tr><td><strong>Entity:</strong></td><td>" + (properties.Entity || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Layer:</strong></td><td>" + (properties.Layer || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Linetype:</strong></td><td>" + (properties.Linetype || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Chiều dài:</strong></td><td>" + (properties.Shape_Leng ? properties.Shape_Leng.toFixed(2) + ' m' : 'N/A') + "</td></tr>" +
                                        "</table></div>";
                                    break;
                                case 'swanbay_van':
                                     popupContent = "<div class='popup-content'><table class='popup-table'>" +
                                        "<tr><td><strong>Chức Năng:</strong></td><td>" + (properties.ChucNangVa || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Cỡ Van:</strong></td><td>" + (properties.CoVan || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Tình Trạng:</strong></td><td>" + (properties.TinhTrangN || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Số Vòng:</strong></td><td>" + (properties.SoVong || 'N/A') + "</td></tr>" +
                                        "</table></div>";
                                    break;
                                case 'swanbay_thuadat':
                                    popupContent = "<div class='popup-content'><table class='popup-table'>" +
                                        "<tr><td><strong>Số Tờ:</strong></td><td>" + (properties.SoTo || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Số Thửa:</strong></td><td>" + (properties.SoThua || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Chủ Sở Hữu:</strong></td><td>" + (properties.ChuSoHuu || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Diện Tích:</strong></td><td>" + (properties.Shape_Area ? properties.Shape_Area.toFixed(2) + ' m²' : 'N/A') + "</td></tr>" +
                                        "</table></div>";
                                    break;
                                case 'swanbay_giaothong':
                                    popupContent = "<div class='popup-content'><table class='popup-table'>" +
                                        "<tr><td><strong>Loại Mặt Đường:</strong></td><td>" + (properties.LoatMat || 'N/A') + "</td></tr>" +
                                        "<tr><td><strong>Diện Tích:</strong></td><td>" + (properties.SHAPE_Area ? properties.SHAPE_Area.toFixed(2) + ' m²' : 'N/A') + "</td></tr>" +
                                        "</table></div>";
                                    break;

                                // Keep old cases for other potential layers
                                case 'gd_data_logger':
                                    popupContent = "<div class='popup-content'>" +
                                        "<table>" +
                                        "<tr><td><strong>Chức năng:</strong></td><td>" +
                                        properties.chucnang + "</td></tr>" +
                                        "<tr><td><strong>Vị trí:</strong></td><td>" +
                                        properties.vitri + "</td></tr>" +
                                        "<tr><td><strong>Tình trạng:</strong></td><td>" +
                                        properties.tinhtrang + "</td></tr>" +
                                        "<tr><td><strong>Ghi chú:</strong></td><td>" +
                                        properties.ghichu + "</td></tr>" +
                                        "</table>" +
                                        "</div>";
                                    break;
                                // ... other old cases ...
                            }
                        }
                        
                        if(popupContent){
                            document.getElementById('feature-details').innerHTML = popupContent;
                            highlightLayer.clearLayers(); // Xóa highlight trước đó (nếu có)
                            var highlightedFeature = L.geoJSON(geojsonData.features[0]);
                            highlightLayer.addLayer(highlightedFeature);
                            
                            // Automatically switch to the "Info" tab
                            openTab('info');

                            // On mobile, if the tab panel is hidden, show it.
                            if (isMobile) {
                                const tabs = document.getElementById('tabs');
                                if (!tabs.classList.contains('active')) {
                                    toggleTabVisibility();
                                    tabShown = true;
                                }
                            }
                        }
                    }
                })
        }
    }
});

function openTab(tabName) {
    var tabs = document.getElementsByClassName('tab-content');
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove('active');
    }
    document.getElementById(tabName + '-content').classList.add('active');

    var buttons = document.getElementsByClassName('tab-button');
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove('active');
    }
    document.querySelector(`[onclick="openTab('${tabName}')"]`).classList.add('active');
}

function toggleTabVisibility() {
    var tabs = document.getElementById('tabs');
    var mapTong = document.getElementById('mapTong');
    var isActive = tabs.classList.contains('active');

    // Prevent multiple toggles if already in progress
    if (tabs.classList.contains('toggling')) return;

    tabs.classList.add('toggling');
    if (isActive) {
        tabs.classList.remove('active');
        mapTong.style.width = '100%';
    } else {
        tabs.classList.add('active');
        mapTong.style.width = '80%';
    }

    // Ensure the transition completes before removing the toggling class
    setTimeout(() => {
        tabs.classList.remove('toggling');
    }, 300); // Match the CSS transition duration
}

// Add toggle button for tabs
var toggleTabBtn = L.control({ position: 'topleft' });
toggleTabBtn.onAdd = function(map) {
    var div = L.DomUtil.create('div', 'leaflet-bar');
    div.innerHTML = '<button id="toggle-tab-btn" style="background: #fff; border: 1px solid #ccc; padding: 5px 10px; cursor: pointer;">☰</button>';
    return div;
};
toggleTabBtn.addTo(map);

document.getElementById('toggle-tab-btn').addEventListener('click', toggleTabVisibility);

// Tạo legend control
var legendControl = L.control({
    position: 'bottomright'
});

legendControl.onAdd = function(map) {
    var div = L.DomUtil.create('div', 'legend');
    div.innerHTML += '<h4>Legend</h4>';
    div.innerHTML +=
        '<img src="http://103.9.77.141:8080/geoserver/giscapnuoc/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giscapnuoc:swanbay_dongho"> Đồng hồ<br>';
    div.innerHTML +=
        '<img src="http://103.9.77.141:8080/geoserver/giscapnuoc/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giscapnuoc:swanbay_van"> Van<br>';
    return div;
};

legendControl.addTo(map);

var legendToggleControl = L.control({
    position: 'bottomright'
});

legendToggleControl.onAdd = function(map) {
    var div = L.DomUtil.create('div', 'legend-toggle');
    div.innerHTML = '<button id="legend-toggle-btn"> Chú thích</button>';
    return div;
};

legendToggleControl.addTo(map);

document.getElementById('legend-toggle-btn').addEventListener('click', function() {
    var legendDiv = document.querySelector('.legend');
    if (legendDiv.style.display === 'none' || legendDiv.style.display === '') {
        legendDiv.style.display = 'block';
    } else {
        legendDiv.style.display = 'none';
    }
});
</script>

