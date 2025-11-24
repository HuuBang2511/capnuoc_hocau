<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\capnuocgd\danhmuc\GdDmSucoBienphapxuly */
?>
<div class="gd-dm-suco-bienphapxuly-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ten',
            'ma',
            'ghichu',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'status',
        ],
    ]) ?>

</div>
