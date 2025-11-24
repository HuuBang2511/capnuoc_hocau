<?php

use app\modules\APPConfig;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use app\widgets\maps\types\LatLng;
use app\widgets\maps\layers\DraggableMarker;
use app\widgets\maps\LeafletMap;
use app\widgets\maps\layers\TileLayer;
use \app\widgets\maps\controls\Layers;
use app\widgets\maps\LeafletMapAsset;
use yii\helpers\Url;

LeafletMapAsset::register($this);


$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['search'] . ' ' . $controller->title, 'url' => $controller->url];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gd-data-logger-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="block block-themed">

        <div class="block-header">
            <h2 class="block-title"><?= $this->title ?></h2>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-lg-12">
                    <?php
                    if ($model->geojson != null) {
                        $geojson = json_decode($model->geojson, true);
                        $center = new LatLng(['lat' => $geojson['coordinates'][1], 'lng' => $geojson['coordinates'][0]]);
                    } elseif ($model->lat != null) {
                        $center = new LatLng(['lat' => $model->lat, 'lng' => $model->long]);
                    }
                    else {
                        $center = new LatLng(['lat' => 10.804207610432567, 'lng' => 106.6952618580442]);
                    }
                    $marker = new DraggableMarker([
                        'center' => $center,
                        'inputX' => '#inputX',
                        'inputY' => '#inputY',
                    ]);

                    $basemaps = [];
                    foreach (APPConfig::$BASEMAP as $i => $item) {
                        $basemaps[] = new TileLayer($item);
                    }
                    $layercontrol = new Layers();
                    $layercontrol->setBaseLayers($basemaps);

                    $leaflet = new LeafletMap([
                        'center' => $center
                    ]);

                    $leaflet->addControl($layercontrol);
                    $leaflet->addLayer($basemaps[0])->addLayer($marker);

                    echo $leaflet->widget(['styleOptions' => ['height' => '400px']]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'long')->textInput([
                        'id' => 'inputX',
                        'value' => isset($geojson['coordinates'][0]) ? $geojson['coordinates'][0] : 106.6952618580442
                    ])->label('Kinh độ') ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'lat')->textInput([
                        'id' => 'inputY',
                        'value' => isset($geojson['coordinates'][1]) ? $geojson['coordinates'][1] : 10.804207610432567
                    ])->label('Vĩ độ') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'iddiemtinh')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'aplucvao')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'aplucra')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'chucnang')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'dosau')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'doduc')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'doph')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'kenh')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'hieu')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'hamluongcl')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'luuluong')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'vitri')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'tinhtrang')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'madma')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'docao')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'globalid')->textInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($model, 'ghichu')->textInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-12">
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary float-left']) ?>
                    <?= Html::button('Quay lại', ['class' => 'btn btn-light float-right', 'type' => 'button', 'onclick' => "history.back()"]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
