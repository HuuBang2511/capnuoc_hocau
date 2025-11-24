<?php

use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset;
use app\widgets\maps\LeafletDrawAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;

// Đăng ký các asset cần thiết
LeafletMapAsset::register($this);
LeafletDrawAsset::register($this);
LeafletMeasureAsset::register($this);
LeafletLocateAsset::register($this);

// Các biến URL để dễ dàng quản lý và thay đổi
$wmsUrl = 'http://103.9.77.141:8080/geoserver/giscapnuoc/wms';
// SỬA ĐỔI: Đồng nhất URL cho chú thích
$legendWmsUrl = 'http://103.9.77.141:8080/geoserver/giscapnuoc/wms';
$apiBaseUrl = 'https://gisapi.giadinhwater.vn/gdw';
$detailViewUrl = 'http://hpngis.online/quanly/capnuocgd';

?>

<!-- Tải plugin Leaflet-LocateControl -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
<script src="https://unpkg.com/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>

<div class="map-form">
    <div class="block block-themed">
        <div class="block-header">
            <h2 class="block-title"><?= 'Water Network ' ?></h2>
        </div>
        <div class="block-content p-0"> <!-- Remove padding for full-width map -->
            <div class="row m-0"> <!-- Remove margin for full-width map -->
                <div class="col-lg-12 p-0"> <!-- Remove padding -->
                    <div id="map" style="width: 100%; height: 75vh; position:relative"></div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const center = [10.805279349519678, 106.71851132905113];
                            const map = L.map('map', {
                                defaultExtentControl: true,
                                zoomControl: false // We will add it in a different position
                            }).setView(center, 16);
                            
                            L.control.zoom({ position: 'topright' }).addTo(map);

                            const highlightLayer = L.featureGroup().addTo(map);

                            // --- CẤU HÌNH CÁC LỚP BẢN ĐỒ ---

                            const baseLayers = {
                                "Bản đồ Google": L.tileLayer('https://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
                                    maxZoom: 22,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                                    attribution: 'Google Maps'
                                }).addTo(map),
                                "Ảnh vệ tinh": L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                                    maxZoom: 22,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                                    attribution: 'Google Satellite'
                                }),
                                "Bản đồ nền": L.tileLayer.wms('<?= $wmsUrl ?>', {
                                    layers: 'giscapnuoc:basemap_capnuoc',
                                    format: 'image/png',
                                    transparent: true,
                                    minZoom: 0,
                                    maxZoom: 22,
                                    attribution: 'Base Map'
                                }),
                            };

                            const wmsLayersConfig = {
                                "Data logger": { layer: 'giscapnuoc:gd_data_logger', visible: false },
                                "Đồng hồ khách hàng": { layer: 'giscapnuoc:gd_dongho_kh_gd', visible: true },
                                "Đồng hồ tổng": { layer: 'giscapnuoc:gd_dongho_tong_gd', visible: true },
                                "Hầm kỹ thuật": { layer: 'giscapnuoc:gd_hamkythuat', visible: true },
                                "Ống cái Đang sử dụng": { layer: 'giscapnuoc:gd_ongcai', visible: true, cql_filter: "status = 1 AND tinhtrang <> 'DH'" },
                                "Ống cái Đã Hủy": { layer: 'giscapnuoc:gd_ongcai', visible: false, cql_filter: "status = 1 AND tinhtrang = 'DH'" },
                                "Ống ngánh": { layer: 'giscapnuoc:gd_ongnganh', visible: true },
                                "Ống truyền dẫn": { layer: 'giscapnuoc:v2_4326_ONGTRUYENDAN', visible: true },
                                "Trạm bơm": { layer: 'giscapnuoc:gd_trambom', visible: false },
                                "Trụ cứu hỏa": { layer: 'giscapnuoc:gd_tramcuuhoa', visible: true },
                                "Van phân phối": { layer: 'giscapnuoc:gd_vanphanphoi', visible: true },
                                "Sự cố điểm bể": { layer: 'giscapnuoc:v2_gd_suco', visible: false },
                                "DMA": { layer: 'giscapnuoc:v2_4326_DMA', visible: false },
                            };

                            const overlayLayers = { "Đối tượng được chọn": highlightLayer };

                            // Khởi tạo các lớp WMS từ cấu hình
                            for (const name in wmsLayersConfig) {
                                const config = wmsLayersConfig[name];
                                const wmsLayer = L.tileLayer.wms('<?= $wmsUrl ?>', {
                                    layers: config.layer,
                                    format: 'image/png',
                                    transparent: true,
                                    minZoom: config.minZoom || 0,
                                    maxZoom: 22,
                                    cql_filter: config.cql_filter || 'status = 1'
                                });

                                if (config.visible) {
                                    wmsLayer.addTo(map);
                                }
                                overlayLayers[name] = wmsLayer;
                            }

                            // --- THÊM CÁC CONTROL VÀO BẢN ĐỒ ---
                            L.control.layers(baseLayers, overlayLayers, { position: 'topright', collapsed: true }).addTo(map);
                            L.control.scale({ imperial: false, maxWidth: 150 }).addTo(map);

                            new L.Control.Measure({
                                position: 'topright',
                                primaryLengthUnit: 'meters',
                                secondaryLengthUnit: undefined,
                                primaryAreaUnit: 'sqmeters',
                                decPoint: ',',
                                thousandsSep: '.'
                            }).addTo(map);

                            new L.Control.Locate({
                                position: 'topright',
                                strings: { title: "Vị trí của bạn" },
                                drawCircle: true,
                                follow: true,
                                keepCurrentZoomLevel: true,
                            }).addTo(map);

                            // --- TẠO CHÚ THÍCH (LEGEND) ---
                            // SỬA ĐỔI: Đồng nhất workspace của các lớp trong chú thích
                            const legendConfig = [
                                { name: 'Đồng hồ KH', layer: 'giscapnuoc:gd_dongho_kh_gd' },
                                { name: 'Đồng hồ tổng', layer: 'giscapnuoc:gd_dongho_tong_gd' },
                                { name: 'Trạm bơm', layer: 'giscapnuoc:gd_trambom' },
                                { name: 'Trạm cứu hỏa', layer: 'giscapnuoc:gd_tramcuuhoa' },
                                { name: 'Van phân phối', layer: 'giscapnuoc:gd_vanphanphoi' },
                                { name: 'Hầm kỹ thuật', layer: 'giscapnuoc:gd_hamkythuat' },
                                { name: 'Ống cái', layer: 'giscapnuoc:gd_ongcai' },
                                { name: 'Ống ngánh', layer: 'giscapnuoc:gd_ongnganh' },
                                { name: 'Sự cố', layer: 'giscapnuoc:v2_gd_suco' },
                            ];
                            
                            const legend = L.control({ position: 'bottomright' });
                            legend.onAdd = function(map) {
                                const div = L.DomUtil.create('div', 'legend');
                                let content = '<h4>Chú thích</h4>';
                                const legendUrl = '<?= $legendWmsUrl ?>?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=';
                                legendConfig.forEach(item => {
                                    content += `<div><img src="${legendUrl}${item.layer}"><span>${item.name}</span></div>`;
                                });
                                div.innerHTML = content;
                                return div;
                            };
                            legend.addTo(map);
                            
                            const legendToggle = L.control({ position: 'bottomright' });
                            legendToggle.onAdd = function(map) {
                                const button = L.DomUtil.create('button', 'legend-toggle-btn');
                                button.title = "Hiện/Ẩn chú thích";
                                button.innerHTML = '<i class="fa fa-list"></i>'; // Sử dụng icon (cần FontAwesome)
                                L.DomEvent.on(button, 'click', function(e) {
                                    L.DomEvent.stop(e);
                                    const legendDiv = document.querySelector('.legend');
                                    legendDiv.style.display = (legendDiv.style.display === 'none' || legendDiv.style.display === '') ? 'block' : 'none';
                                });
                                return button;
                            };
                            legendToggle.addTo(map);


                            // --- HÀM TẠO NỘI DUNG POPUP ĐỘNG ---
                            const createPopupContent = (layerId, properties, featureId) => {
                                let content = "<table>";
                                for (const key in properties) {
                                    const value = properties[key] === null || properties[key] === undefined ? '' : properties[key];
                                    content += `<tr><td><strong>${key.charAt(0).toUpperCase() + key.slice(1)}:</strong></td><td>${value}</td></tr>`;
                                }

                                if (layerId.includes('gd_dongho_kh_gd')) {
                                    content += `<tr><td><strong>Bản Vẽ:</strong></td><td><p><a href="https://gisapi.giadinhwater.vn/gdw/banvehoancong/14091476272/" target="_blank">Hoàn Công</a></p></td></tr>`;
                                    if (featureId) {
                                        content += `<tr><td><strong>Xem chi tiết</strong></td><td><p><a href="<?= $detailViewUrl ?>/gd-dongho-kh-gd/view?id=${featureId}" target="_blank">Thông tin chi tiết</a></p></td></tr>`;
                                    }
                                }

                                return `<div class='popup-content'>${content}</table></div>`;
                            };

                            // --- XỬ LÝ SỰ KIỆN CLICK TRÊN BẢN ĐỒ ---
                            map.on('click', async (e) => {
                                const visibleLayers = [];
                                for (const name in overlayLayers) {
                                    if (map.hasLayer(overlayLayers[name]) && overlayLayers[name].wmsParams) {
                                        visibleLayers.push(overlayLayers[name].wmsParams.layers);
                                    }
                                }

                                if (visibleLayers.length === 0) return;

                                const size = map.getSize();
                                const point = map.latLngToContainerPoint(e.latlng, map.getZoom());
                                const bbox = map.getBounds().toBBoxString();
                                const url = `<?= $wmsUrl ?>?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&LAYERS=${visibleLayers.join(',')}&QUERY_LAYERS=${visibleLayers.join(',')}&BBOX=${bbox}&FEATURE_COUNT=10&HEIGHT=${size.y}&WIDTH=${size.x}&INFO_FORMAT=application/json&SRS=EPSG:4326&X=${Math.floor(point.x)}&Y=${Math.floor(point.y)}`;

                                try {
                                    const response = await fetch(url);
                                    if (!response.ok) throw new Error('Network response was not ok');
                                    const data = await response.json();

                                    if (data.features && data.features.length > 0) {
                                        const feature = data.features[0];
                                        
                                        const lastDotIndex = feature.id.lastIndexOf('.');
                                        let layerName = feature.id;
                                        let featureId = null;

                                        if (lastDotIndex !== -1) {
                                            layerName = feature.id.substring(0, lastDotIndex);
                                            featureId = feature.id.substring(lastDotIndex + 1);
                                        }

                                        const popupContent = createPopupContent(layerName, feature.properties, featureId);

                                        L.popup({ maxWidth: 400 }).setLatLng(e.latlng).setContent(popupContent).openOn(map);

                                        highlightLayer.clearLayers();
                                        const highlightedFeature = L.geoJSON(feature, {
                                            style: { color: '#ff00ff', weight: 5, opacity: 0.8 }
                                        });
                                        highlightLayer.addLayer(highlightedFeature);
                                    }
                                } catch (error) {
                                    console.error('Lỗi khi lấy thông tin đối tượng (GetFeatureInfo):', error);
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm Font Awesome cho icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    /* --- STYLE TỐI ƯU HÓA VÀ RESPONSIVE --- */

    /* Tùy chỉnh chung cho khung popup của Leaflet */
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 3px 14px rgba(0,0,0,0.4);
    }
    
    /* Vùng nội dung bên trong popup */
    .leaflet-popup-content {
        margin: 15px;
        max-height: 250px; /* Giới hạn chiều cao */
        overflow-y: auto;  /* Bật cuộn dọc khi cần */
        overflow-x: hidden;
    }

    /* CSS cho bảng thông tin bên trong popup */
    .popup-content table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        table-layout: fixed;
    }

    .popup-content td {
        padding: 8px 10px;
        border-bottom: 1px solid #eee;
        text-align: left;
        word-wrap: break-word;
    }
    .popup-content td:first-child {
        width: 35%;
    }

    .popup-content strong {
        font-weight: 600;
        color: #333;
    }

    .popup-content tr:last-child td {
        border-bottom: none;
    }
    .popup-content a {
        color: #007bff;
        text-decoration: none;
    }
    .popup-content a:hover {
        text-decoration: underline;
    }

    /* CSS cho Chú thích (Legend) */
    .legend {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        display: none; /* Mặc định ẩn */
        line-height: 1.8;
        max-height: 200px;
        overflow-y: auto;
    }
    .legend div {
        display: flex;
        align-items: center;
    }
    .legend img {
        width: 20px;
        height: 20px;
        margin-right: 8px;
    }
    
    .legend-toggle-btn {
        background-color: #fff;
        border: 2px solid rgba(0,0,0,0.2);
        border-radius: 4px;
        width: 34px;
        height: 34px;
        line-height: 30px;
        text-align: center;
        font-size: 1.2em;
        cursor: pointer;
    }
    .legend-toggle-btn:hover {
        background-color: #f4f4f4;
    }

    /* Responsive cho bộ điều khiển lớp (Layer Control) */
    .leaflet-control-layers-expanded {
        max-height: 250px;
        overflow-y: auto;
    }

    /* Responsive cho màn hình nhỏ */
    @media screen and (max-width: 768px) {
        .leaflet-popup {
            width: 85vw !important; 
        }
        .leaflet-popup-content {
            margin: 12px;
            max-height: 200px;
        }
        .leaflet-control-layers-expanded {
            max-height: 180px;
        }
    }
</style>
