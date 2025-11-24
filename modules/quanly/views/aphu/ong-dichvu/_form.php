<?php

use app\modules\APPConfig;
use app\widgets\maps\controls\Layers;
use app\widgets\maps\layers\DraggableMarker;
use app\widgets\maps\layers\TileLayer;
use app\widgets\maps\types\LatLng;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\LeafletMap;

LeafletMapAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\aphu\OngDichvu */
/* @var $form yii\widgets\ActiveForm */

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['search'] . ' ' . $controller->title, 'url' => $controller->url];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ong-dichvu-form">

    <div class="block block-themed">

        <div class="block-header">
            <h2 class="block-title"><?= $this->title ?></h2>
        </div>
        <div class="block-content">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row pb-2">
                <div class="col-lg-12">
                    <div id="map" style="height: 500px"></div>
                    <?= Html::hiddenInput('OngDichvu[geojson]', $model->geojson, ['id' => 'geojson']) ?>

                    <?php $center_view = Yii::$app->params['center'] ?>
                    <script>
                        // center of the map
                        var center = [11.808824, 107.242948];

                        // Create the map
                        var map = L.map('map').setView(center, 14);
                        var
                            baseMaps = {
                                "Bản đồ Google": L.tileLayer('http://{s}.google.com/vt/lyrs=' + 'r' + '&x={x}&y={y}&z={z}', {
                                    maxZoom: 24,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                }).addTo(map),
                                "Ảnh vệ tinh": L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                                    maxZoom: 24,
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
                        // add a marker in the given location
                        //L.marker(center).addTo(map);


                        // Initialise the FeatureGroup to store editable layers
                        var editableLayers = new L.FeatureGroup();
                        map.addLayer(editableLayers);

                        var drawPluginOptions = {
                            position: 'topleft',
                            draw: {
                                polygon: false,
                                // disable toolbar item by setting it to false
                                polyline: {
                                    shapeOptions: {
                                        color: '#f357a1',
                                        weight: 10
                                    }
                                },
                                polyline: true,
                                circle: false, // Turns off this drawing tool
                                circlemarker: false, // Turns off this drawing tool
                                rectangle: false,
                                marker: false,
                            },
                            edit: {
                                featureGroup: editableLayers, //REQUIRED!!
                                remove: true,
                                edit: true,
                            }
                        };

                        // Initialise the draw control and pass it the FeatureGroup of editable layers
                        var drawControl = new L.Control.Draw(drawPluginOptions);
                        map.addControl(drawControl);

                        <?php if($model->geojson != null) :?>

                        var states = [{
                            "type": "Feature",
                            "properties": {"": ""},
                            "geometry":  <?= $model->geojson ?>
                        }];

                        L.geoJSON(states, {
                            onEachFeature: function (feature, layer) {
                                if (layer instanceof L.Polygon) {
                                    L.polygon(layer.getLatLngs()).addTo(editableLayers);
                                }
                                if (layer instanceof L.Marker) {
                                    L.marker(layer.getLatLng()).addTo(editableLayers);
                                }
                                if (layer instanceof L.Polyline) {
                                    L.polyline(layer.getLatLngs()).addTo(editableLayers);
                                }

                            }
                        });

                        // Get bounds object
                        var bounds = editableLayers.getBounds()

                        //Fit the map to the polygon bounds
                        map.fitBounds(bounds)

                        // Or center on the polygon
                        var centerstates = bounds.getCenter()
                        map.panTo(centerstates)
                        <?php endif;?>


                        //var editableLayers = new L.FeatureGroup();
                        map.addLayer(editableLayers);
                        map.on('draw:created', function (e) {
                            var type = e.layerType,
                                layer = e.layer;
                            $('#geojson').val(JSON.stringify(layer.toGeoJSON().geometry));

                            editableLayers.addLayer(layer);
                        });

                        map.on('draw:edited', function (e) {
                            var layers = e.layers;
                            layers.eachLayer(function (layer) {
                                $('#geojson').val(JSON.stringify(layer.toGeoJSON().geometry));
                            });
                        });
                    </script>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'idduongong')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'chieudaiho')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'vatlieu')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'hieu')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'ngaylapdat')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'tinhtrang')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'coong')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'dbdonghonu')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'madma')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'diachi')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'namlapdat')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'dosau')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'created_us')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'created_da')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'last_edite')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'last_edi_1')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'globalid')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'enabled')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'bvtd')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'shape_leng')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($model, 'ghichu')->textInput(['maxlength' => true]) ?>
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
