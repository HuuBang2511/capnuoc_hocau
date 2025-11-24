<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'geom',
//    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'objectid',
//    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idtrambom',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'loaitram',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tentram',
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'diadiem',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'namxaydung',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'congsuat',
     ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'soluongbom',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'donviquanl',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'madma',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'ghichu',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'globalid',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_at',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_by',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'updated_at',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'updated_by',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'status',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'geojson',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'lat',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'long',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'width' => '180px',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons' => [
            'view' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-info"></span>',$url,['class' => 'btn btn-info btn-sm','data-pjax'=>0,'title'=>'Xem']);
            },
            'update' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-pen"></span>',$url,['class' => 'btn btn-warning btn-sm','data-pjax'=>0,'title'=>'Cập nhật']);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-trash"></span>',$url,['class' => 'btn btn-danger btn-sm','role' => 'modal-remote','title'=>'Xóa']);
            },
        ],
    ],

];   