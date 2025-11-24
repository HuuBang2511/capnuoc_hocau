<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="gd-dm-suco-ketcaumatduong-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ten')->textInput() ?>

    <?= $form->field($model, 'ma')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ghichu')->textInput() ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
