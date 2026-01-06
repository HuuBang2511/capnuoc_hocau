<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\crud\CrudAsset;
use app\modules\services\UtilityService;
use yii\widgets\DetailView;
use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;

LeafletMapAsset::register($this);
LeafletLocateAsset::register($this);
CrudAsset::register($this);

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;
//dd($controller);

$this->title = Yii::t('app', $label[$requestedAction->id].' '.$controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['index'].' '.$controller->title, 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<!-- JS -->
<script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>

<style>
#map {
    width: 100%;
    height: 50vh;
    border: 1px solid #0665d0
}
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="block block-themed">
            <div class="block-header">
                <h3 class="block-title"><?= $this->title ?></h3>
                <div class="block-options">
                    <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                </div>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-lg-4">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'vitri',
                                [
                                    'label' => 'Tình trạng',
                                    'value' => function($model){
                                        return ($model->tinhtrang_id != null) ? $model->tinhtrang->ten : '';
                                    }
                                ],
                            ],
                        ]) ?>
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
                    <div class="col-lg-8">
                        <div id="map"></div>
                    </div>
                </div>
                <div class="row px-3 py-3">
                    <div class="col-lg-12 form-group">
                        <a href="javascript:history.back()" class="btn btn-light float-end"><i class="fa fa-arrow-left"></i>
                            Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "size" => Modal::SIZE_EXTRA_LARGE,
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>


<script type="module">
   
    var map = L.map('map').setView([<?= ($model->lat != null) ? $model->lat : '20.473381288809428' ?>,
        <?= ($model->long != null) ? $model->long : '106.31907196809175' ?>
    ], 18);


    

    var layerGMapSatellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    var layerGmapStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });


    var baseLayers = {
        "GGMap": layerGmapStreets,
        "Vệ tinh": layerGMapSatellite,
    };

    
    L.control.layers(baseLayers).addTo(map);
    map.addLayer(layerGmapStreets, true);

    var icon = L.icon({
        iconUrl: '<?= Yii::$app->homeUrl ?>images/icons8-map-marker-96.png',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -48],
    });

    <?php if ($model->lat != null && $model->long != null) : ?>
    var marker = L.marker([<?= $model->lat ?>, <?= $model->long ?>], {
        'icon': icon,
    }).addTo(map);
    <?php endif; ?>

    L.control.locate({
        position: 'topleft',
        flyTo: true,
        keepCurrentZoomLevel: true,
        drawCircle: false,
        showPopup: false,
        strings: {
            title: "Định vị vị trí của bạn"
        },
        icon: 'fa fa-location-arrow', // nếu bạn dùng font-awesome
        locateOptions: {
            enableHighAccuracy: true,
            maxZoom: 18,
            watch: false
        },
        clickBehavior: {
            inView: 'stop', 
            outOfView: 'setView', 
            inViewNotFollowing: 'setView'
        }
    }).addTo(map);

    setTimeout(() => {
        const btn = document.querySelector('.leaflet-control-locate a');
        if (btn) {
            btn.addEventListener('touchstart', function (e) {
                e.preventDefault();
                btn.click(); // kích hoạt click bằng touch
            });
        }
    }, 1000);
    
</script>
