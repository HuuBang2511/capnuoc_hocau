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
                                var center = [10.804291919691535, 106.69527258767485];

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
                                    // "MapBox": L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2thZGFtYmkiLCJhIjoiY2lqdndsZGg3MGNua3U1bTVmcnRqM2xvbiJ9.9I5ggqzhUVrErEQ328syYQ#3/0.00/0.00', {
                                    //     maxZoom: 18,
                                    //     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                                    //     id: 'streets-v9',
                                    // }),
                                    // "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    //     attribution: '© <a href="https://www.openstreetmap.org" target="_blank">OpenStreetMap</a>',
                                    //     maxZoom: 18
                                    // }),
                                };

                                var layerControl = L.control.layers(baseMaps);
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
                                    'id',
//                                    'geom',
                                    'objectid',
                                    'idduongong',
                                    'chieudaiho',
                                    'vatlieu',
                                    'hieu',
                                    'coong',
                                    'tinhtrang',
                                    'madma',
                                    'vitrilapda',
                                    'ghichu',
                                    'namlapdat',
                                    'dosau',
                                    'loaicongtr',
                                    'tencongtri',
                                    'donvithiet',
                                    'donvithico',
                                    'tenduong',
                                    'sohem',
                                    'diemdau',
                                    'diemcuoi',
                                    'tuyen',
                                    'cachletrai',
                                    'cachlephai',
                                    'maphuong',
                                    'maquan',
                                    'globalid',
                                    'bvhc',
                                    'shape_leng',
                                    'created_at',
                                    'created_by',
                                    'updated_at',
                                    'updated_by',
//                                    'status',
//                                    'geojson',
                                ],
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">

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
