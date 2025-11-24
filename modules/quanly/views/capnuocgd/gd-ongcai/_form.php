<?php

use app\modules\APPConfig;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset;
use app\widgets\maps\LeafletDrawAsset;
use yii\helpers\Json;

LeafletMapAsset::register($this);
LeafletDrawAsset::register($this);
LeafletMeasureAsset::register($this);

/**
 * @var yii\web\View $this
 * @var app\modules\quanly\models\capnuocgd\GdOngcai $model
 * @var kartik\form\ActiveForm $form
 */

// 1. Gán biến gọn gàng hơn
$controller = Yii::$app->controller;
$actionId = $controller->action->id;
$this->title = Yii::t('app', ($controller->label[$actionId] ?? $actionId) . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => ($controller->label['search'] ?? 'Search') . ' ' . $controller->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// 2. Chuẩn bị dữ liệu GeoJSON để truyền sang JavaScript một cách an toàn
$geojson_data = !empty($model->geojson) ? Json::encode($model->geojson) : 'null';
?>

<div class="gd-ongcai-form">
    <div class="block block-themed">
        <div class="block-header">
            <h2 class="block-title"><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="block-content">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row pb-2">
                <div class="col-lg-12">
                    <!-- 3. Giao diện bản đồ và trường ẩn -->
                    <div id="map" style="height: 500px; width: 100%; border-radius: 4px; z-index: 1;"></div>
                    <?= $form->field($model, 'geojson')->hiddenInput(['id' => 'geojson'])->label(false) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'idduongong')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'chieudaiho')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'vatlieu')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'hieu')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'coong')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'tinhtrang')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'madma')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'vitrilapda')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'ghichu')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'namlapdat')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'dosau')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'loaicongtr')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'tencongtri')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'donvithiet')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'donvithico')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'tenduong')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'sohem')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'diemdau')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'diemcuoi')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'tuyen')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'cachletrai')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'cachlephai')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'maphuong')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'maquan')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'globalid')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'bvhc')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="form-group col-lg-12">
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary float-left']) ?>
                    <?= Html::button('Quay lại', ['class' => 'btn btn-light float-right', 'type' => 'button', 'onclick' => "history.back()"]) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
// 4. Quản lý toàn bộ script bằng registerJs
$js = <<<JS
// --- KHỞI TẠO BẢN ĐỒ VÀ CÁC LỚP NỀN ---
const defaultCenter = [10.804305026919454, 106.71788692474367];
const map = L.map('map').setView(defaultCenter, 16);

const baseLayers = {
    "Bản đồ Google": L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 22,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map),
    "Ảnh vệ tinh": L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 22,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    }),
};

// --- THÊM CÁC LỚP WMS MẶC ĐỊNH ---
const wmsBaseUrl = 'https://nongdanviet.net/geoserver/giscapnuoc/wms';
const wmsDefaultOptions = {
    format: 'image/png',
    transparent: true,
    maxZoom: 22,
};

const wmsLayersConfig = [
    // { layers: 'giscapnuoc:gd_data_logger' },
    { layers: 'giscapnuoc:v2_4326_DMA' },
    { layers: 'giscapnuoc:gd_hamkythuat' },
    { layers: 'giscapnuoc:gd_ongcai',},
    { layers: 'giscapnuoc:gd_ongnganh' },
    { layers: 'giscapnuoc:v2_4326_ONGTRUYENDAN' },
    { layers: 'giscapnuoc:v2_gd_suco' },
    { layers: 'giscapnuoc:gd_dongho_kh_gd' },
    { layers: 'giscapnuoc:gd_dongho_tong_gd' },
    { layers: 'giscapnuoc:gd_trambom' },
    { layers: 'giscapnuoc:gd_tramcuuhoa' },
    { layers: 'giscapnuoc:gd_vanphanphoi' },
];

wmsLayersConfig.forEach(layerInfo => {
    const options = { ...wmsDefaultOptions, ...layerInfo };
    L.tileLayer.wms(wmsBaseUrl, options).addTo(map);
});

// Chỉ thêm bảng điều khiển cho lớp nền
L.control.layers(baseLayers).addTo(map);


// --- CẤU HÌNH LEAFLET.DRAW ---

// Lớp để chứa các đối tượng được vẽ/chỉnh sửa
const editableLayers = new L.FeatureGroup();
map.addLayer(editableLayers);

// Cấu hình công cụ vẽ cho Polyline
const drawOptions = {
    position: 'topleft',
    draw: {
        polyline: {
            shapeOptions: { color: '#f357a1', weight: 10 }
        },
        polygon: false,
        rectangle: false,
        circle: false,
        marker: false,
        circlemarker: false
    },
    edit: {
        featureGroup: editableLayers,
        remove: true
    }
};

const drawControl = new L.Control.Draw(drawOptions);
map.addControl(drawControl);

// Hàm cập nhật trường input ẩn với dữ liệu GeoJSON
function updateGeoJsonInput() {
    const layers = editableLayers.getLayers();
    if (layers.length === 0) {
        $('#geojson').val('');
        return;
    }
    // Chỉ lấy geometry của đối tượng đầu tiên (giả sử chỉ vẽ 1 đường)
    const geoJsonGeometry = layers[0].toGeoJSON().geometry;
    $('#geojson').val(JSON.stringify(geoJsonGeometry));
}

// Tải dữ liệu GeoJSON đã có khi mở form
let initialGeoJson = {$geojson_data};
if (initialGeoJson) {
    // Dữ liệu từ server là một chuỗi JSON, cần parse lại
    const geoJsonFeature = {
        "type": "Feature",
        "properties": {},
        "geometry": JSON.parse(initialGeoJson)
    };
    
    L.geoJSON(geoJsonFeature, {
        onEachFeature: function (feature, layer) {
            editableLayers.addLayer(layer);
        }
    });

    // Zoom tới vùng đã vẽ
    if (editableLayers.getLayers().length > 0) {
        map.fitBounds(editableLayers.getBounds());
    }
}


// Xử lý sự kiện khi một đối tượng được VẼ XONG
map.on(L.Draw.Event.CREATED, function (e) {
    const layer = e.layer;
    // Xóa các đối tượng cũ đi để đảm bảo chỉ có 1 đối tượng trên bản đồ
    editableLayers.clearLayers();
    editableLayers.addLayer(layer);
    updateGeoJsonInput();
});

// Xử lý sự kiện khi một đối tượng được CHỈNH SỬA
map.on(L.Draw.Event.EDITED, function (e) {
    updateGeoJsonInput();
});

// Xử lý sự kiện khi một đối tượng được XÓA
map.on(L.Draw.Event.DELETED, function (e) {
    updateGeoJsonInput();
});

JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>
