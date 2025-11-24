<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\capnuocgd\danhmuc\GdDmXulysuco */
?>
<div class="gd-dm-xulysuco-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ten',
            'ghichu:ntext',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'status',
        ],
    ]) ?>

</div>
