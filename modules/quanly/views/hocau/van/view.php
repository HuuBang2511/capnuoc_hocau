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

// Badge trạng thái van
$statusLabel = $model->tinhtrang ? $model->tinhtrang->ten : 'N/A';
$statusClass = (strpos(mb_strtolower($statusLabel), 'hỏng') !== false) ? 'danger' : 'success';
?>

<div class="gd-van-view container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3 bg-white p-3 shadow-sm rounded border-start border-primary border-4">
        <div>
            <h2 class="h4 fw-bold mb-1 text-primary"><i class="fa fa-dot-circle me-2"></i><?= Html::encode($model->mavan) ?></h2>
            <div class="d-flex align-items-center">
                <span class="badge bg-<?= $statusClass ?> me-2"><?= $statusLabel ?></span>
                <span class="text-muted small"><i class="fa fa-map-marker-alt me-1"></i><?= Html::encode($model->vitri) ?></span>
            </div>
        </div>
        <div class="btn-group shadow-sm">
            <?= Html::a('<i class="fa fa-edit me-1"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
            <button class="btn btn-light border" onclick="history.back()"><i class="fa fa-arrow-left"></i> Quay lại</button>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-cog fa-spin me-2"></i>Thông số vận hành</h5>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0 text-center border-bottom">
                        <div class="col-6 py-3 border-end">
                            <div class="text-muted small text-uppercase">Số vòng quay</div>
                            <div class="h3 fw-bold mb-0 text-primary"><?= $model->sovong ?: '0' ?></div>
                        </div>
                        <div class="col-6 py-3">
                            <div class="text-muted small text-uppercase">Chiều đóng</div>
                            <div class="h3 fw-bold mb-0 text-dark">
                                <i class="fa <?= (mb_strtolower($model->chieudong) == 'phải' || mb_strtolower($model->chieudong) == 'thuận') ? 'fa-redo' : 'fa-undo' ?> me-1"></i>
                                <?= $model->chieudong ?: 'N/A' ?>
                            </div>
                        </div>
                    </div>
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table mb-0 detail-view-custom'],
                        'attributes' => [
                            [
                                'attribute' => 'dongmo',
                                'label' => 'Trạng thái Đóng/Mở',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $val = mb_strtolower($model->dongmo);
                                    $color = (strpos($val, 'đóng') !== false) ? 'danger' : 'success';
                                    return "<span class='fw-bold text-$color text-uppercase'>$model->dongmo</span>";
                                }
                            ],
                            'covan',
                            'cochiakhoa',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4 text-dark">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Thông tin tài sản</h5>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table mb-0 detail-view-custom'],
                        'attributes' => [
                            [
                                'label' => 'Loại van',
                                'value' => $model->loaivan ? $model->loaivan->ten : '',
                            ],
                            'ngaylapdat',
                            'ghichu:ntext',
                        ],
                    ]) ?>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-paperclip me-2 text-warning"></i>Tài liệu đính kèm</h5>
                </div>
                <div class="card-body py-2">
                    <?php if (!empty($files)) : ?>
                        <?php foreach ($files as $file) : ?>
                            <a href="<?= Yii::$app->homeUrl . $file['url'] ?>" target="_blank" class="d-flex align-items-center p-2 text-decoration-none border-bottom mb-1 text-dark">
                                <i class="fa fa-file-pdf text-danger me-2"></i>
                                <span class="small text-truncate"><?= Html::encode($file['name']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="text-center py-3 text-muted small">Chưa có hồ sơ</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm overflow-hidden sticky-top" style="top: 20px;">
                <div class="card-body p-0">
                    <div id="map" style="height: 75vh; width: 100%;"></div>
                </div>
                <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center p-3">
                    <span class="text-muted small"><i class="fa fa-crosshairs me-1"></i>Tọa độ: <?= $model->lat ?>, <?= $model->long ?></span>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="map.setView([<?= $model->lat ?>, <?= $model->long ?>], 20)"><i class="fa fa-search-plus"></i> Phóng to Van</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .detail-view-custom th { background-color: #fcfcfc; width: 45%; color: #666; font-weight: 500; font-size: 0.85rem; padding-left: 1.5rem; }
    .detail-view-custom td { font-weight: 500; color: #333; }
    .card { border-radius: 12px; }
    #map { z-index: 1; }
</style>

<script type="module">
    var map = L.map('map').setView([
        <?= ($model->lat != null) ? $model->lat : '10.762622' ?>,
        <?= ($model->long != null) ? $model->long : '106.660172' ?>
    ], 19);

    var layerGMapSatellite = L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    var layerGmapStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    L.control.layers({ "Vệ tinh (Lai)": layerGMapSatellite, "Bản đồ đường": layerGmapStreets }).addTo(map);

    var icon = L.icon({
        iconUrl: '<?= Yii::$app->homeUrl ?>images/icons8-map-marker-96.png',
        iconSize: [44, 44],
        iconAnchor: [22, 44],
        popupAnchor: [0, -40],
    });

    <?php if ($model->lat != null && $model->long != null) : ?>
    L.marker([<?= $model->lat ?>, <?= $model->long ?>], {icon: icon})
     .addTo(map)
     .bindPopup("<div class='text-center'><strong>Van: <?= $model->mavan ?></strong><br>Cỡ: <?= $model->covan ?> mm</div>")
     .openPopup();
    <?php endif; ?>

    L.control.locate({ 
        position: 'topleft', 
        strings: { title: "Vị trí của tôi" },
        flyTo: true 
    }).addTo(map);

    // Tự động căn chỉnh map khi load
    setTimeout(() => { map.invalidateSize(); }, 500);
</script>