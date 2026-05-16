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
?>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 fw-bold mb-0 text-primary"><i class="fa fa-info-circle me-2"></i><?= $this->title ?></h2>
            <div class="btn-group">
                <?= Html::a('<i class="fa fa-edit"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning shadow-sm']) ?>
                <?= Html::a('<i class="fa fa-arrow-left"></i> Quay lại', 'javascript:history.back()', ['class' => 'btn btn-light shadow-sm']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold">Thông số kỹ thuật</h5>
                    </div>
                    <div class="card-body p-0">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-hover mb-0 detail-view-custom'],
                            'attributes' => [
                                [
                                    'attribute' => 'mavitri',
                                    'label' => 'Mã vị trí',
                                    'contentOptions' => ['class' => 'fw-bold text-primary'],
                                ],
                                'kichthuoc',
                                'vattu',
                                [
                                    'label' => 'Loại mối nối',
                                    'value' => $model->loaimoinoi_id ? $model->loaimoinoi0->ten : 'N/A',
                                ],
                                [
                                    'label' => 'Tình trạng',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        $status = (isset($model->tinhtrang) && $model->tinhtrang !== null && isset($model->tinhtrang->ten)) ? $model->tinhtrang->ten : 'N/A';
                                        return Html::tag('span', $status, ['class' => 'badge bg-info text-white px-3']);
                                    }
                                ],
                                'ghichu:ntext',
                            ],
                        ]) ?>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="fa fa-paperclip me-2 text-muted"></i>Tài liệu</h5>
                    </div>
                    <div class="card-body py-2">
                        <?php if (!empty($files)) : ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($files as $i => $file) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div class="text-truncate" style="max-width: 85%;">
                                            <i class="fa fa-file-alt text-danger me-2"></i>
                                            <a href="<?= Url::to(['/quanly/hocau/moinoi/download-file', 'path' => $file['url']]) ?>" target="_blank" class="text-decoration-none text-dark small">
                                                <?= Html::encode($file['name']) ?>
                                            </a>
                                        </div>
                                        <span class="text-muted small">#<?= $i + 1 ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p class="text-muted small mb-0 py-2">Không có file đính kèm.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-sm overflow-hidden h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-success"><i class="fa fa-map-marked-alt me-2"></i>Vị trí thực địa</h5>
                        <div class="small text-muted">Tọa độ: <?= $model->lat ?>, <?= $model->long ?></div>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 70vh; min-height: 500px; width: 100%;"></div>
                    </div>
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
    .detail-view-custom th { background-color: #f8f9fa; width: 35%; color: #495057; font-weight: 600; font-size: 0.9rem; }
    .detail-view-custom td { font-size: 0.95rem; }
    .card { border-radius: 12px; }
    #map { z-index: 1; }
</style>

<script type="module">
    const lat = <?= ($model->lat != null) ? $model->lat : '20.473381288809428' ?>;
    const lng = <?= ($model->long != null) ? $model->long : '106.31907196809175' ?>;

    var map = L.map('map').setView([lat, lng], 18);

    var layerGMapSatellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    var layerGmapStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    const moinoi = L.tileLayer.wms('https://cello.capnuochocaumoi.vn/geoserver/capnuoc_hocau/wms', {
        layers: 'capnuoc_hocau:network_moinoi',
        format: 'image/png',
        transparent: true,
        maxZoom: 22
    });

    L.control.layers({ "Bản đồ": layerGmapStreets, "Vệ tinh": layerGMapSatellite }, { "Mối nối" : moinoi }).addTo(map);

    var icon = L.icon({
        iconUrl: '<?= Yii::$app->homeUrl ?>images/icons8-map-marker-96.png',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -48],
    });

    <?php if ($model->lat != null && $model->long != null) : ?>
    var marker = L.marker([lat, lng], { 'icon': icon }).addTo(map);
    marker.bindPopup("<b>Mã vị trí: <?= $model->mavitri ?></b>").openPopup();
    <?php endif; ?>

    L.control.locate({
        position: 'topleft',
        flyTo: true,
        strings: { title: "Vị trí của tôi" }
    }).addTo(map);

    // Fix bug map render size
    setTimeout(() => { map.invalidateSize(); }, 500);
</script>
