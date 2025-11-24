<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="ho-thuyloi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'geom')->textInput() ?>

    <?= $form->field($model, 'objectid')->textInput() ?>

    <?= $form->field($model, 'tenho')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dungtich')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dientichma')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dosautrung')->textInput() ?>

    <?= $form->field($model, 'shape_leng')->textInput() ?>

    <?= $form->field($model, 'shape_area')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'geojson')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ghichu')->textarea(['rows' => 6]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
