<?php

use yii\helpers\Url;
use yii\widgets\DetailView;
use app\widgets\maps\LeafletMapAsset;
use yii\helpers\Html;
use app\widgets\gridview\GridView;

LeafletMapAsset::register($this);

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['search'] . ' ' . $controller->title, 'url' => $controller->url];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gd-ongcai-view">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title"><?= $this->title ?></h3>
                    <div class="block-options">
                        <a class="btn btn-warning btn-sm" href="<?= Url::to(['update', 'id' => $model->id]) ?>">Cập nhật</a>
                        <a class="btn btn-light btn-sm" href="<?= Url::to(['index']) ?>">Danh sách</a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-lg-12 pb-2">

                            <div id="map" style="height: 400px"></div>
                            <script>

                                // center of the map
                                var center = [10.737202088, 106.915000047];

                                // Create the map
                                var map = L.map('map').setView(center, 14);
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

                                var overlayers = {
                                    "Hành lang an toàn": L.tileLayer.wms('http://103.9.77.141:8080/geoserver/capnuoc_hocau/wms', {
                                    layers: 'capnuoc_hocau:network_hanglangantoan',
                                    format: 'image/png',
                                    transparent: true,
                                    maxZoom: 22 // Đặt maxZoom là 22
                                })
                                };

                                var layerControl = L.control.layers(baseMaps, overlayers);
                                layerControl.addTo(map);
                                <?php if($model->geojson != null) :?>
                                var states = [{
                                    "type": "Feature",
                                    "properties": {"": ""},
                                    "geometry": <?= $model->geojson ?>
                                }];

                                var polygon = L.geoJSON(states).addTo(map);

                                var bounds = polygon.getBounds()
                                map.fitBounds(bounds)

                                var centerpolygon = bounds.getCenter()
                                map.panTo(centerpolygon)
                                <?php endif;?>
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'hanhlang',
                                    
                                    [
                                        'label' => 'Tình trạng',
                                        'value' => function($model){
                                            return ($model->tinhtrang_id != null) ? $model->tinhtrang->ten : '';
                                        }
                                    ],
                                   
                                    'ghichu',
                                ],
                            ]) ?>
                        </div>
                    </div>

                    <h3>File đính kèm</h3>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên file</th>
                                    
                                </tr>
                                <?php if ($files != null) : ?>
                                    <?php foreach ($files as $i => $file) : ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><a href="<?= Yii::$app->homeUrl . $file['url'] ?>" target="_blank"><?= $file['name'] ?></a></td>
                                            
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <?= Html::a('Cập nhật',['update','id' => $model->id], ['class' => 'btn btn-warning float-left']) ?>
                            <?= Html::button('Quay lại', ['class' => 'btn btn-light float-right','type' => 'button', 'onclick' => "history.back()"]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
