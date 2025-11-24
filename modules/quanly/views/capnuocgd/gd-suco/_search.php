<?php

use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\capnuocgd\GdSucoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gd-suco-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'id' => 'gd-suco-form',
            'pjax' => true,
        ],
    ]); ?>

    <div class="block block-themed">
        <div class="block-header">
            <h3 class="block-title"><i class="fa fa-search"></i> Tìm kiếm</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'xulysuco_id')->widget(Select2::className(), [
                        'data' => ArrayHelper::map($categories['dm_xulysuco'], 'id', 'ten'),
                        'options' => [
//                            'id' => 'quanhuyen',
                            'prompt' => 'Chọn xử lý sự cố'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'nguyennhan')->widget(Select2::className(), [
                        'data' => ArrayHelper::map($categories['dm_suco_nguyennhan'], 'ma', 'ten'),
                        'options' => [
//                            'id' => 'quanhuyen',
                            'prompt' => 'Chọn nguyên nhân sự cố'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'field_date')->dropDownList(['ngayphathien' => 'Ngày phát hiện', 'ngaysuachua'=>'Ngày sửa chữa'])->label(false) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'date_from')->widget(DatePicker::className(), [
                        'pluginOptions' => [
                            'format' => 'mm-dd-yyyy',
                            'autoclose' => true,
                        ],
                        'language' => 'vn',
                        'options' => ['placeholder' => 'Từ ngày'],
                    ])->label(false) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'date_to')->widget(DatePicker::className(), [
                        'pluginOptions' => [
                            'format' => 'mm-dd-yyyy',
                            'autoclose' => true,
                        ],
                        'language' => 'vn',
                        'options' => ['placeholder' => 'Đến ngày'],
                    ])->label(false) ?>
                </div>
                <div class="col-md-2">

                        <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-primary']) ?>

                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
