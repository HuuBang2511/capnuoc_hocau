<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\capnuocgd\GdSuco */
?>
<div class="gd-suco-update">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'hinhanh' => $hinhanh,
    ]) ?>

</div>
