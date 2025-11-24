<?php

use app\widgets\maps\LeafletMapAsset;

LeafletMapAsset::register($this);
?>

<div class="map-form">
    <div class="block block-themed">
        <div class="block-header">
            <h2 class="block-title"><?= 'Water Network Duc Trong' ?></h2>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-lg-12">
                    <div id="map" style="height: 500px"></div>

                    <script>
                        var center = [11.808824, 107.242948];

                        // Create the map
                        var map = L.map('map', {defaultExtentControl: true}).setView(center, 16);

                        var baseMaps = {
                            "Bản đồ Google": L.tileLayer('http://{s}.google.com/vt/lyrs=' + 'r' + '&x={x}&y={y}&z={z}', {
                                maxZoom: 22,
                                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                            }).addTo(map),
                            "Ảnh vệ tinh": L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                                maxZoom: 22,
                                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                            }),
                        };
                        // Thêm lớp L.Control.Locate
                        var locateControl = new L.Control.Locate({
                            position: 'bottomleft',
                            strings: {
                                title: "Hiện vị trí",
                                popup: "Bạn đang ở đây"
                            },
                            drawCircle: true,
                            follow: true,
                        });

                        // Thêm lớp locateControl vào bản đồ
                        map.addControl(locateControl);

                        L.control.scale({imperial: false, maxWidth: 150}).addTo(map);

                        // Function to handle click events and display popup
                        function handleClickEvent(e, layer) {
                            getFeatureInfo(e.latlng, layer);
                        }

                        // Function to get feature information from WMS layer
                        function getFeatureInfo(latlng, wmsLayer) {
                            var url = wmsLayer._url;
                            var point = map.latLngToContainerPoint(latlng, map.getZoom());
                            var size = map.getSize();

                            var params = {
                                request: 'GetFeatureInfo',
                                service: 'WMS',
                                srs: 'EPSG:4326',
                                styles: wmsLayer.wmsParams.styles,
                                transparent: wmsLayer.wmsParams.transparent,
                                version: wmsLayer.wmsParams.version,
                                format: wmsLayer.wmsParams.format,
                                bbox: map.getBounds().toBBoxString(),
                                height: size.y,
                                width: size.x,
                                layers: wmsLayer.wmsParams.layers,
                                query_layers: wmsLayer.wmsParams.layers,
                                info_format: 'application/json',
                                feature_count: 1,
                                x: point.x,
                                y: point.y,
                            };

                            $.ajax({
                                type: 'GET',
                                url: url,
                                data: params,
                                dataType: 'json',
                                success: function (data) {
                                    if (data.features && data.features.length > 0) {
                                        var properties = data.features[0].properties;
                                        var popupContent = "<center>" +
                                            "<strong>ID: " + properties.objectid + "</strong>";

                                        // Customize popup content based on layer type
                                        switch (wmsLayer.options.layers) {
                                            case 'aphu:dongho_kh':
                                                popupContent += "<br/>Danh Bạ: " + properties.dbdonghonu +
                                                    "<br/>Địa Chỉ: " + properties.diachi+
                                                    "<br/>Khách Hàng: " + properties.tenkhachha+
                                                    "<br/>Điện Thoại: " + properties.dtdd+
                                                    "<br/>Cỡ Đồng Hồ: " + properties.codongho+
                                                    "<br/>Hiệu Đồng Hồ: " + properties.hieudongho+
                                                    "<br/>Loại Đồng Hồ: " + properties.loaidongho+
                                                    "<br/>Số Thân Đồng Hồ: " + properties.sothandong+
                                                    "<br/>Mã Lộ Trình: " + properties.malotrinh;
                                                break;
                                            case 'aphu:nhamay_nuoc':
                                                popupContent += "<br/>" + "congnghexl: " + properties.congnghexl
                                                    + "<br/>" + "congsuat_1: " + properties.congsuat_1;
                                                break;
                                            case 'aphu:ho_thuyloi':
                                                popupContent += "<br/>" + "Tên: " + properties.tenho;
                                                break;
                                            case 'aphu:ong_dichvu':
                                                popupContent += "<br/>" + "Vật Liệu: " + properties.vatlieu+
                                                "<br/>Cỡ Ống: " + properties.coong;
                                                break;
                                            case 'aphu:ong_nuoctho':
                                                popupContent += "<br/>" + "Tên: " + properties.shape_leng;
                                                break;
                                            case 'aphu:ong_phanphoi':
                                                popupContent +="<br/>" + "Vật Liệu: " + properties.vatlieu+
                                                "<br/>Cỡ Ống: " + properties.coong+
                                                "<br/>Hiệu Ống: " + properties.hieu+
                                                "<br/>Năm Lắp Đặt: " + properties.namlapdat+
                                                "<br/>Độ Sâu: " + properties.dosau+
                                                "<br/>Tên Công Trình: " + properties.tencongtri+
                                                "<br/>Loại Công Trình: " + properties.loaicongtr+
                                                "<br/>ĐVTK: " + properties.donvithiet+
                                                "<br/>ĐVTC: " + properties.donvithico;
                                                break;
                                            case 'aphu:van_mangluoi':
                                                popupContent += "<br/>" + "Mã Van: " + properties.idvan+
                                                "<br/>Cỡ Van: " + properties.covan+
                                                "<br/>Cỡ Chìa Khóa: " + properties.cochiakhoa+
                                                "<br/>Trạng Thái: " + properties.trangthai+
                                                "<br/>Hiệu Ống: " + properties.hieu+
                                                "<br/>Năm Lắp Đặt: " + properties.namlapdat;
                                                break;
                                            // Add additional cases for other layers if needed
                                        }

                                        popupContent += "</center>";

                                        L.popup()
                                            .setLatLng(latlng)
                                            .setContent(popupContent)
                                            .openOn(map);
                                    }
                                }
                            });
                        }

                        // Create WMS layers
                        var wmsDonghoLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/aphu/wms', {
                            layers: 'aphu:dongho_kh',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsHoThuyLoiLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/aphu/wms', {
                            layers: 'aphu:ho_thuyloi',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsNhaMayNuocLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/aphu/wms', {
                            layers: 'aphu:nhamay_nuoc',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsOngDichVuLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/aphu/wms', {
                            layers: 'aphu:ong_dichvu',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsOngNuocThoLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/aphu/wms', {
                            layers: 'aphu:ong_nuoctho',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsOngPhanPhoiLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/aphu/wms', {
                            layers: 'aphu:ong_phanphoi',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsVanMangLuoiLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/aphu/wms', {
                            layers: 'aphu:van_mangluoi',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        // Add event listener for click events on each layer
                        map.on('click', function (e) {
                            handleClickEvent(e, wmsDonghoLayer);
                            handleClickEvent(e, wmsHoThuyLoiLayer);
                            handleClickEvent(e, wmsNhaMayNuocLayer);
                            handleClickEvent(e, wmsOngDichVuLayer);
                            handleClickEvent(e, wmsOngNuocThoLayer);
                            handleClickEvent(e, wmsOngPhanPhoiLayer);
                            handleClickEvent(e, wmsVanMangLuoiLayer);
                            // Add more layers as needed
                        });

                        // Add control layers to the map
                        var overlayMaps = {
                            "Đồng hồ KH": wmsDonghoLayer,
                            "Hồ thủy lợi": wmsHoThuyLoiLayer,
                            "Nhà máy nước": wmsNhaMayNuocLayer,
                            "Ống dịch vụ": wmsOngDichVuLayer,
                            "Ống nước thô": wmsOngNuocThoLayer,
                            "Ống phân phối": wmsOngPhanPhoiLayer,
                            "Van mạng lưới": wmsVanMangLuoiLayer,
                        };

                        var layerControl = L.control.layers(baseMaps, overlayMaps);
                        layerControl.addTo(map);
                        L.DomEvent.addListener(map, 'click', function (e) {
    if (marker) {
        map.removeLayer(marker);
    }

    marker = new L.Marker(e.latlng);
    map.addLayer(marker);

    if (position1 === null) {
        position1 = e.latlng;
    } else {
        position2 = e.latlng;
        var distance = L.GeometryUtil.distance(map, position1, position2);
        alert("Khoảng cách: " + distance.toFixed(2) + " mét");
        position1 = null;
        position2 = null;
        map.removeLayer(marker);
    }
});
var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

var drawControl = new L.Control.Draw({
    edit: {
        featureGroup: drawnItems
    }
});

map.addControl(drawControl);

map.on('draw:created', function (e) {
    var type = e.layerType,
        layer = e.layer;

    if (type === 'polygon' || type === 'rectangle') {
        var area = L.GeometryUtil.geodesicArea(layer.getLatLngs()[0]);
        alert("Diện tích: " + area.toFixed(2) + " mét vuông");
    }

    drawnItems.addLayer(layer);
});

                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
