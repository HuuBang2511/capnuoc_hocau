<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\aphu\HoThuyloi */
?>
<div class="ho-thuyloi-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'geom',
            'objectid',
            'tenho',
            'dungtich',
            'dientichma',
            'dosautrung',
            'shape_leng',
            'shape_area',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'status',
            'geojson:ntext',
            'ghichu:ntext',
        ],
    ]) ?>

</div>
