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

<div class="device-view-container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1 text-primary">
                <i class="fa fa-microchip me-2"></i><?= Html::encode($model->madongho) ?>
            </h2>
            <div class="text-muted small">ID hệ thống: #<?= $model->id ?></div>
        </div>
        <div class="btn-group shadow-sm">
            <?= Html::a('<i class="fa fa-arrow-left"></i> Quay lại', Url::to(['index']), ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::a('<i class="fa fa-edit"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning text-white']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-cog text-secondary me-2"></i>Thông số kỹ thuật</h5>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-hover mb-0 detail-view-table'],
                        'attributes' => [
                            [
                                'attribute' => 'madongho',
                                'captionOptions' => ['style' => 'width: 40%'],
                                'contentOptions' => ['class' => 'fw-bold text-primary'],
                            ],
                            [
                                'label' => 'Hiệu đồng hồ',
                                'value' => $model->hieudongho ? $model->hieudongho->ten : 'Chưa xác định',
                            ],
                            'sothan',
                            'co',
                            'mavattu',
                            [
                                'label' => 'Tình trạng',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $statusText = $model->tinhtrang ? $model->tinhtrang->ten : 'N/A';
                                    return '<span class="badge rounded-pill bg-soft-success text-success border border-success px-3">' . $statusText . '</span>';
                                }
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-wrench text-danger me-2"></i>Thông tin lắp đặt</h5>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-hover mb-0 detail-view-table'],
                        'attributes' => [
                            'vitri',
                            'khuvuc',
                            'ngaylapdat',
                            'ghichu:ntext',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-folder-open text-warning me-2"></i>Tài liệu đính kèm</h5>
                </div>
                <div class="card-body py-2">
                    <?php if (!empty($files)) : ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($files as $i => $file) : ?>
                                <a href="<?= Yii::$app->homeUrl . $file['url'] ?>" target="_blank" class="list-group-item list-group-item-action border-0 px-0 d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="fa fa-file-pdf text-danger"></i>
                                    </div>
                                    <div class="text-truncate small fw-medium">
                                        <?= Html::encode($file['name']) ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-4 text-muted small italic">
                            <i class="fa fa-ghost fa-2x mb-2 opacity-25"></i><br>Không có dữ liệu file
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card border-0 shadow-sm overflow-hidden h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-map-marked-alt text-success me-2"></i>Vị trí lắp đặt thực tế</h5>
                    <div class="badge bg-light text-dark border fw-normal"><?= $model->lat ?>, <?= $model->long ?></div>
                </div>
                <div class="card-body p-0 position-relative">
                    <div id="map" style="height: 70vh; width: 100%; min-height: 600px;"></div>
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
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
    .detail-view-table th { background-color: #f9f9fb; color: #555; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; padding: 12px 15px; }
    .detail-view-table td { padding: 12px 15px; border-left: 1px solid #f1f1f1; }
    .card { border-radius: 12px; }
    #map { border: none !important; }
</style>

<script type="module">
    // Khởi tạo bản đồ
    const lat = <?= ($model->lat != null) ? $model->lat : '20.473381288809428' ?>;
    const lng = <?= ($model->long != null) ? $model->long : '106.31907196809175' ?>;

    const map = L.map('map', {
        scrollWheelZoom: true,
        fadeAnimation: true
    }).setView([lat, lng], 18);

    // Lớp nền
    const googleRoads = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    const googleSatellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    L.control.layers({ "Bản đồ Google": googleRoads, "Vệ tinh": googleSatellite }).addTo(map);

    // Marker
    const customIcon = L.icon({
        iconUrl: '<?= Yii::$app->homeUrl ?>images/icons8-map-marker-96.png',
        iconSize: [44, 44],
        iconAnchor: [22, 44],
        popupAnchor: [0, -40],
    });

    <?php if ($model->lat != null && $model->long != null) : ?>
        const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
        marker.bindPopup(`
            <div class="text-center p-1">
                <strong class="text-primary"><?= $model->madongho ?></strong><br>
                <small class="text-muted"><?= $model->vitri ?></small>
            </div>
        `).openPopup();
    <?php endif; ?>

    // Nút định vị
    L.control.locate({
        position: 'topleft',
        flyTo: true,
        strings: { title: "Vị trí của tôi" }
    }).addTo(map);

    // Xử lý lỗi mảng trắng bản đồ
    setTimeout(() => { map.invalidateSize(); }, 400);
</script>