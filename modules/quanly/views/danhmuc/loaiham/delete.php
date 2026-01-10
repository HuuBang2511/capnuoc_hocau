<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="dm-loaiham-form">

    <?php $form = ActiveForm::begin(); ?>

    <h4>Xóa  <?= $model->ten ?></h4>

    <?php ActiveForm::end(); ?>

</div>

