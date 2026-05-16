<?php

use yii\helpers\Url;
use yii\widgets\DetailView;
use app\widgets\maps\LeafletMapAsset;
use yii\helpers\Html;

LeafletMapAsset::register($this);

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['search'] . ' ' . $controller->title, 'url' => $controller->url];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gd-ongcai-view container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 fw-bold mb-0 text-primary">
            <i class="fa fa-project-diagram me-2"></i><?= Html::encode($this->title) ?>
        </h2>
        <div class="btn-group shadow-sm">
            <?= Html::a('<i class="fa fa-edit me-1"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('<i class="fa fa-list me-1"></i> Danh sách', ['index'], ['class' => 'btn btn-light']) ?>
            <button class="btn btn-secondary" onclick="history.back()"><i class="fa fa-arrow-left"></i></button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-success"><i class="fa fa-map-marked-alt me-2"></i>Sơ đồ tuyến ống thực địa</h5>
                    <span class="badge bg-light text-dark border">WMS: Ống phân phối</span>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 500px; width: 100%;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-7 col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-primary px-2">Thông tin kỹ thuật</h5>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-hover mb-0 detail-view-custom'],
                        'attributes' => [
                            [
                                'attribute' => 'mavattu',
                                'label' => 'Mã vật tư',
                                'contentOptions' => ['class' => 'fw-bold text-danger'],
                            ],
                            'vatlieu',
                            'coong',
                            'ngaylapdat',
                            'congtrinh',
                            'dvtk',
                            'dvtc',
                            'bvhc',
                            [
                                'label' => 'Loại ống / Tình trạng',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $loaiong   = (isset($model->loaiong)   && $model->loaiong   !== null && isset($model->loaiong->ten))   ? $model->loaiong->ten   : 'N/A';
                                    $tinhtrang = (isset($model->tinhtrang) && $model->tinhtrang !== null && isset($model->tinhtrang->ten)) ? $model->tinhtrang->ten : 'N/A';
                                    return Html::tag('span', $loaiong,   ['class' => 'badge bg-info me-2']) .
                                           Html::tag('span', $tinhtrang, ['class' => 'badge bg-success']);
                                }
                            ],
                            'ghichu:ntext',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa fa-folder-open text-warning me-2"></i>Hồ sơ hoàn công & Bản vẽ</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($files)) : ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($files as $i => $file) : ?>
                                <a href="<?= Url::to(['/quanly/hocau/ongphanphoi/download-file', 'path' => $file['url']]) ?>" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center border-0 px-0">
                                    <div class="avatar avatar-sm bg-light text-primary rounded me-3 text-center" style="width: 40px; height: 40px; line-height: 40px;">
                                        <i class="fa fa-file-pdf"></i>
                                    </div>
                                    <div class="flex-grow-1 text-truncate">
                                        <div class="fw-bold small text-dark"><?= Html::encode($file['name']) ?></div>
                                        <small class="text-muted text-uppercase">Tài liệu số <?= $i + 1 ?></small>
                                    </div>
                                    <i class="fa fa-download text-muted small"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-5">
                            <i class="fa fa-file-excel fa-3x text-light mb-3"></i>
                            <p class="text-muted">Chưa có file đính kèm</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .detail-view-custom th { background-color: #f8f9fa; width: 35%; font-size: 0.9rem; color: #495057; border-right: 1px solid #eee; }
    .detail-view-custom td { font-size: 0.95rem; vertical-align: middle; }
    .card { border-radius: 12px; }
    #map { z-index: 1; border-radius: 0 0 12px 12px; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var center = [10.737202088, 106.915000047];
        var map = L.map('map').setView(center, 14);

        var baseMaps = {
            "Bản đồ Google": L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(map),
            "Ảnh vệ tinh": L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            })
        };

        var overlayers = {
            "Ống phân phối": L.tileLayer.wms('https://cello.capnuochocaumoi.vn/geoserver/capnuoc_hocau/wms', {
                layers: 'capnuoc_hocau:network_ongphanphoi',
                format: 'image/png',
                transparent: true,
                maxZoom: 22
            })
        };

        L.control.layers(baseMaps, overlayers).addTo(map);

        <?php if($model->geojson != null) :?>
            try {
                var geoLayer = L.geoJSON(<?= $model->geojson ?>, {
                    style: {
                        color: "#007bff",
                        weight: 6,
                        opacity: 0.8
                    }
                }).addTo(map);
                
                var bounds = geoLayer.getBounds();
                map.fitBounds(bounds, { padding: [30, 30] });
                
                // Thêm tooltip thông tin ống
                geoLayer.bindTooltip("Ống cái: <?= $model->coong ?> mm", { sticky: true });
            } catch (e) {
                console.error("Lỗi GeoJSON:", e);
            }
        <?php endif;?>
        
        // Đảm bảo map render đúng kích thước sau khi load
        setTimeout(() => { map.invalidateSize(); }, 500);
    });
</script>
