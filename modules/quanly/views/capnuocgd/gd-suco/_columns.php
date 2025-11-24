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
        'attribute'=>'masuco',
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'madanhba',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'sonha',
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'duong',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'ngayphathien',
     ],
//     [
//         'class'=>'\kartik\grid\DataColumn',
//         'attribute'=>'nguoiphathien',
//     ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'ngaysuachua',
    // ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'nguoisuachua',
     ],
     [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'xulysuco_id',
        'format' => 'raw',
        'value' => 'dm_xulysuco.ten',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'donvisuachua',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'hinhthucphuchoi',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'vitriphathien',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'nguyennhan',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'bienphapxuly',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'duongkinho',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'vatlieu_ong',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tailapmatduong',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'kichthuocp',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'ghichu',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tontai',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'lat',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'long',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'geojson',
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