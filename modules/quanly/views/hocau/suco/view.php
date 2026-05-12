<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\crud\CrudAsset;
use yii\widgets\DetailView;
use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;

LeafletMapAsset::register($this);
LeafletLocateAsset::register($this);
CrudAsset::register($this);

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id].' '.$controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['index'].' '.$controller->title, 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;

// Định nghĩa màu sắc theo tình trạng sự cố
$statusClass = 'secondary';
if ($model->tinhtrangsuco_id == 1) $statusClass = 'danger'; // Chưa xử lý
if ($model->tinhtrangsuco_id == 2) $statusClass = 'warning'; // Đang xử lý
if ($model->tinhtrangsuco_id == 3) $statusClass = 'success'; // Đã hoàn thành
?>

<div class="gd-suco-view container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white shadow-sm rounded">
        <div>
            <h2 class="h4 fw-bold mb-1 text-primary">
                <i class="fa fa-exclamation-triangle me-2 text-danger"></i><?= Html::encode($model->masuco) ?>
            </h2>
            <div class="d-flex align-items-center">
                <span class="badge bg-<?= $statusClass ?> me-2">
                    <?= (isset($model->tinhtrangsuco) && $model->tinhtrangsuco !== null && isset($model->tinhtrangsuco->ten)) ? $model->tinhtrangsuco->ten : 'N/A' ?>
                </span>
                <small class="text-muted"><i class="fa fa-clock me-1"></i>Phát hiện: <?= $model->n_phathien ?></small>
            </div>
        </div>
        <div class="btn-group shadow-sm">
            <?= Html::a('<i class="fa fa-edit me-1"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
            <a href="javascript:history.back()" class="btn btn-light"><i class="fa fa-arrow-left"></i> Quay lại</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5 col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-info-circle text-primary me-2"></i>Thông tin chi tiết sự cố</h5>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-hover mb-0 detail-view-custom'],
                        'attributes' => [
                            'masuco',
                            'mataisan',
                            [
                                'attribute' => 'loaisuco_id',
                                'label' => 'Loại sự cố',
                                'value' => (isset($model->loaisuco) && $model->loaisuco !== null && isset($model->loaisuco->ten)) ? $model->loaisuco->ten : '',
                            ],
                            'vitri',
                            [
                                'attribute' => 'n_phathien',
                                'label' => 'Thời gian phát hiện',
                                'format' => 'raw',
                                'value' => '<span class="text-danger fw-bold">'.$model->n_phathien.'</span>'
                            ],
                            'n_xuly',
                            'n_hoancong',
                            'nguyennhan:ntext',
                            'cachxuly:ntext',
                            'ghichu:ntext',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-camera text-info me-2"></i>Hình ảnh & Hồ sơ hiện trường</h5>
                </div>
                <div class="card-body py-2">
                    <?php if (!empty($files)) : ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($files as $i => $file) : ?>
                                <a href="<?= Yii::$app->homeUrl . $file['url'] ?>" target="_blank" class="list-group-item list-group-item-action border-0 px-0 d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3 text-center" style="width: 40px;">
                                        
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-medium text-dark"><?= Html::encode($file['name']) ?></div>
                                    </div>
                                    <i class="fa fa-external-link-alt text-muted small"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-4 text-muted small">Chưa có ảnh hiện trường</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-7 col-lg-6">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px; z-index: 1000;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-map-marker-alt text-danger me-2"></i>Vị trí sự cố trên bản đồ</h5>
                    <div class="text-muted small"><?= $model->lat ?>, <?= $model->long ?></div>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 70vh; min-height: 500px; width: 100%;"></div>
                </div>
                <div class="card-footer bg-light p-2 text-center small text-muted">
                    Sử dụng nút <i class="fa fa-location-arrow"></i> trên bản đồ để dẫn đường đến vị trí này.
                </div>
            </div>
        </div>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "size" => Modal::SIZE_EXTRA_LARGE,
    "footer" => "",
]) ?>
<?php Modal::end(); ?>

<style>
    .detail-view-custom th { background-color: #f8f9fa; width: 40%; font-size: 0.85rem; color: #555; text-transform: uppercase; border-right: 1px solid #eee; }
    .detail-view-custom td { font-size: 0.95rem; }
    .card { border-radius: 12px; overflow: hidden; }
    #map { border: none !important; }
</style>

<script type="module">
    var map = L.map('map').setView([
        <?= ($model->lat != null) ? $model->lat : '10.8231' ?>,
        <?= ($model->long != null) ? $model->long : '106.6297' ?>
    ], 18);

    var layerGMapSatellite = L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map); // Ưu tiên vệ tinh cho sự cố

    var layerGmapStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    const suco = L.tileLayer.wms('http://gis.capnuochocaumoi.vn/geoserver/capnuoc_hocau/wms', {
        layers: 'capnuoc_hocau:network_suco',
        format: 'image/png',
        transparent: true,
        maxZoom: 22
    });

    L.control.layers({ "Vệ tinh": layerGMapSatellite, "Địa giới": layerGmapStreets }, { "Sự cố" : suco }).addTo(map);

    var icon = L.icon({
        iconUrl: '<?= Yii::$app->homeUrl ?>images/icons8-map-marker-96.png',
        iconSize: [46, 46],
        iconAnchor: [23, 46],
        popupAnchor: [0, -40],
    });

    <?php if ($model->lat != null && $model->long != null) : ?>
        L.marker([<?= $model->lat ?>, <?= $model->long ?>], {icon: icon})
         .addTo(map)
         .bindPopup("<strong>Sự cố: <?= $model->masuco ?></strong><br><?= $model->vitri ?>")
         .openPopup();
    <?php endif; ?>

    L.control.locate({
        position: 'topleft',
        flyTo: true,
        strings: { title: "Vị trí của tôi" },
        icon: 'fa fa-crosshairs'
    }).addTo(map);

    setTimeout(() => { map.invalidateSize(); }, 400);
</script>