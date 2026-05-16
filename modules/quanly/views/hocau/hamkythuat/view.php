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

<div class="gd-hamkythuat-view">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 fw-bold mb-0 text-primary">
            <i class="fa fa-info-circle me-2"></i><?= Html::encode($this->title) ?>
        </h2>
        <div class="btn-group">
            <a class="btn btn-outline-primary shadow-sm" href="<?= Url::to(['index']) ?>">
                <i class="fa fa-list me-1"></i> Danh sách
            </a>
            <a class="btn btn-warning shadow-sm" href="<?= Url::to(['update', 'id' => $model->id]) ?>">
                <i class="fa fa-edit me-1"></i> Cập nhật
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0"><i class="fa fa-map-marker-alt text-danger me-2"></i>Vị trí trên bản đồ</h5>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 500px; width: 100%; border-bottom-left-radius: .35rem; border-bottom-right-radius: .35rem;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0"><i class="fa fa-th-list text-info me-2"></i>Thông tin chi tiết</h5>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-hover mb-0 detail-view'],
                        'attributes' => [
                            [
                                'attribute' => 'tinhtrang_id',
                                'label' => 'Tình trạng',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $ten = $model->tinhtrang_id != null ? $model->tinhtrang->ten : 'N/A';
                                    return '<span class="badge bg-info text-white">' . $ten . '</span>';
                                }
                            ],
                            'maham',
                            [
                                'label' => 'Loại hầm',
                                'value' => function($model){
                                    return ($model->loaiham_id != null) ? $model->loaiham->ten : '(Chưa rõ)';
                                }
                            ],
                            'kichthuoc',
                            'vatlieu',
                            'sonap',
                            'vitri',
                            'ngaylapdat',
                            'dvtk',
                            'dvtc',
                            'bvhc',
                            [
                                'attribute' => 'ghichu',
                                'contentOptions' => ['style' => 'font-style: italic; color: #666;']
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0"><i class="fa fa-paperclip text-warning me-2"></i>File đính kèm</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($files)) : ?>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="80">STT</th>
                                <th>Tên tài liệu</th>
                                <th width="150" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $i => $file) : ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><i class="fa fa-file-pdf text-danger me-2"></i><?= Html::encode($file['name']) ?></td>
                                    <td class="text-center">
                                        <a href="<?= Url::to(['/quanly/hocau/hamkythuat/download-file', 'path' => $file['url']]) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-download"></i> Xem/Tải về
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <p class="text-muted mb-0 italic">Không có file đính kèm nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var center = [10.804291919691535, 106.69527258767485];
        var map = L.map('map').setView(center, 14);

        var googleRoads = L.tileLayer('http://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
            maxZoom: 22,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 22,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        });

        var baseMaps = {
            "Bản đồ Google": googleRoads,
            "Ảnh vệ tinh": googleSat
        };

        var overlayers = {
            "hầm kỹ thuật": L.tileLayer.wms('https://cello.capnuochocaumoi.vn/geoserver/capnuoc_hocau/wms', {
                layers: 'capnuoc_hocau:network_hamkythuat',
                format: 'image/png',
                transparent: true,
                maxZoom: 22
            })
        };

        L.control.layers(baseMaps, overlayers).addTo(map);
        
        <?php if($model->geojson != null) :?>
        try {
            var states = [{
                "type": "Feature",
                "properties": {},
                "geometry": <?= $model->geojson ?>
            }];
            var polygon = L.geoJSON(states).addTo(map);
            var bounds = polygon.getBounds();
            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        } catch (e) {
            console.error('Lỗi GeoJSON: ', e);
        }
        <?php endif;?>

        // Fix map size when container changes
        setTimeout(function() { map.invalidateSize(); }, 200);
    });
</script>

<style>
    /* Tinh chỉnh giao diện */
    .detail-view th { width: 35%; background-color: #f8f9fa; color: #495057; font-weight: 600; }
    .card { border-radius: 8px; overflow: hidden; }
    .badge { padding: 0.5em 0.8em; }
    .btn-group .btn { border-radius: 4px !important; margin-left: 5px; }
</style>
