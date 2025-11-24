<?php

use app\modules\APPConfig;
use app\widgets\maps\controls\Layers;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\widgets\maps\LeafletMap;
use app\widgets\maps\types\LatLng;
use app\widgets\maps\layers\Marker;
use app\widgets\maps\layers\TileLayer;
use app\widgets\maps\LeafletMapAsset;

LeafletMapAsset::register($this);

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['search'] . ' ' . $controller->title, 'url' => $controller->url];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nhamay-nuoc-view">
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
                        <div class="col-lg-4 pb-3">
                            <h4>Thông tin</h4>
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'id',
//                                    'geom',
                                    'objectid',
                                    'congnghexl',
                                    'namxd',
                                    'nguon',
                                    'congsuat_1',
                                    'created_by',
                                    'created_at',
                                    'updated_by',
                                    'updated_at',
//                                    'status',
//                                    'geojson:ntext',
                                    'lat',
                                    'long',
                                    'ghichu:ntext',
                                ],
                            ]) ?>

                        </div>
                        <div class="col-lg-8 pb-3">
                            <?php
                            if ($model->geojson != null) {
                                $geojson = json_decode($model->geojson, true);
                                $center = new LatLng(['lat' => $geojson['coordinates'][1], 'lng' => $geojson['coordinates'][0]]);
                            } elseif ($model->geom != null) {
                                $center = new LatLng(['lat'=> $model->lat, 'lng'=>$model->long]);
                            } elseif ($model->lat != null) {
                                $center = new LatLng(['lat'=> $model->lat, 'lng'=>$model->long]);
                            } else {
                                $center = new LatLng(['lat' => 10.782105, 'lng' => 106.764542]);
                            }
                            $marker = new Marker(['latLng' => $center]);

                            $leaflet = new LeafletMap([
                                'center' => $center
                            ]);

                            $basemaps = [];
                            foreach (APPConfig::$BASEMAP as $i => $item) {
                                $basemaps[] = new TileLayer($item);
                            }
                            $layercontrol = new Layers();
                            $layercontrol->setBaseLayers($basemaps);
                            $leaflet->addControl($layercontrol);
                            $leaflet->addLayer($basemaps[0])->addLayer($marker);

                            echo $leaflet->widget(['styleOptions' => ['height' => '100%']]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>