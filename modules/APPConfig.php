<?php

namespace app\modules;


use app\widgets\maps\layers\TileLayer;

class APPConfig
{
    public function convertRoute($route)
    {
        return '.' . str_replace('/', '.', $route) . '.index';
    }

    public static $SITENAME = 'GIS - Cấp nước';
    public static $CONFIG = [
        'adminSidebar' => [
            [
                'name' => 'Quản lý người dùng',
                'icon' => 'fa fa-users',
                'url' => '/user/auth-user',
                'key' => '.user.auth-user.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Quản lý nhóm quyền',
                'icon' => 'fa fa-th-list',
                'url' => '/user/auth-group',
                'key' => '.user.auth-group.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Quản lý quyền truy cập',
                'icon' => 'fa fa-th-list',
                'url' => '/user/auth-role',
                'key' => '.user.auth-role.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Quản lý hoạt động',
                'icon' => 'fa fa-th-list',
                'url' => '/user/auth-action',
                'key' => '.user.auth-action.index',
                'hasChild' => false,
            ],
        ],
        // 'vientham' => [
        //     [
        //         'name' => 'Kết quả phân tích',
        //         'icon' => 'fa fa-list',
        //         'url' => 'quanly/ketqua-vientham',
        //         'key'=>'quanly.ketqua-vientham.index',
        //         'hasChild' => false,
        //     ],
        //     [
        //         'name' => 'Module Viễn thám',
        //         'icon' => 'fa fa-list',
        //         'url' => 'quanly/anhvientham',
        //         'key'=>'quanly.anhvientham.index',
        //         'hasChild' => false,
        //     ]
        // ],
//         'aphu' => [
//             [
//                 'name' => 'Đồng hồ KH',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/dongho-kh',
//                 'key'=>'quanly.aphu/dongho-kh.index',
//                 'hasChild' => false,
//             ],
// //            [
// //                'name' => 'Hồ Thủy Lợi',
// //                'icon' => 'fa fa-list',
// //                'url' => 'quanly/aphu/ho-thuyloi',
// //                'key'=>'quanly.aphu/ho-thuyloi.index',
// //                'hasChild' => false,
// //            ],
//             [
//                 'name' => 'Nhà máy nước',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/nhamay-nuoc',
//                 'key'=>'quanly.aphu/nhamay-nuoc.index',
//                 'hasChild' => false,
//             ],
//             [
//                 'name' => 'Ống dịch vụ',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/ong-dichvu',
//                 'key'=>'quanly.aphu/ong-dichvu.index',
//                 'hasChild' => false,
//             ],
//             [
//                 'name' => 'Ống nước thô',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/ong-nuoctho',
//                 'key'=>'quanly.aphu/ong-nuoctho.index',
//                 'hasChild' => false,
//             ],
//             [
//                 'name' => 'Ống phân phối',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/ong-phanphoi',
//                 'key'=>'quanly.aphu/ong-phanphoi.index',
//                 'hasChild' => false,
//             ],
//             [
//                 'name' => 'Van mạng lưới',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/van-mangluoi',
//                 'key'=>'quanly.aphu/van-mangluoi.index',
//                 'hasChild' => false,
//             ],
//         ],
        'giadinh' => [
            [
                'name' => 'Cọc mốc',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/cocmoc',
                'key'=>'quanly.hocau/cocmoc.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Đồng hồ nhà máy',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/donghonhamay',
                'key'=>'quanly.hocau/donghonhamay.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Đồng hồ tổng',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/dongho-tong',
                'key'=>'quanly.hocau/dongho-tong.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Hầm kỹ thuật',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/hamkythuat',
                'key'=>'quanly.hocau/hamkythuat.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Mối nối',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/moinoi',
                'key'=>'quanly.hocau/moinoi.index',
                'hasChild' => false,
            ],
            [
                'name' => 'ống phân phối',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/ongphanphoi',
                'key'=>'quanly.hocau/ongphanphoi.index',
                'hasChild' => false,
            ],
            [
                'name' => 'ống truyền dẫn',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/ongtruyendan',
                'key'=>'quanly.hocau/ongtruyendan.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Van phân phối',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/van',
                'key'=>'quanly.hocau/van.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Sự cố',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/suco',
                'key'=>'quanly.hocau/suco.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Hành lang an toàn',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/hanglangantoan',
                'key'=>'quanly.hocau/hanglangantoan.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Nhà máy nước',
                'icon' => 'fa fa-list',
                'url' => 'quanly/hocau/nhamaynuoc',
                'key'=>'quanly.hocau/nhamaynuoc.index',
                'hasChild' => false,
            ],
        ],
        'map' => [
            [
                'name' => 'Bản đồ hệ thống mạng lưới cấp nước',
                'icon' => 'fa fa-list',
                'url' => 'quanly/map/hocau',
                'key'=>'quanly.map.hocau',
                'hasChild' => false,
            ],
            
        ],
        'danhmuc' => [
            // [
            //     'name' => 'Danh mục Sự cố biện pháp xử lý',
            //     'icon' => 'fa fa-list',
            //     'url' => 'quanly/capnuocgd/danhmuc/gd-dm-suco-bienphapxuly',
            //     'key'=>'quanly.capnuocgd.danhmuc/gd-dm-suco-bienphapxuly.index',
            //     'hasChild' => false,
            // ],
            // [
            //     'name' => 'Danh mục Sự cố kết cấu mặt đường',
            //     'icon' => 'fa fa-list',
            //     'url' => 'quanly/capnuocgd/danhmuc/gd-dm-suco-ketcaumatduong',
            //     'key'=>'quanly.capnuocgd.danhmuc/gd-dm-suco-ketcaumatduong.index',
            //     'hasChild' => false,
            // ],
            // [
            //     'name' => 'Danh mục Sự cố nguyên nhân',
            //     'icon' => 'fa fa-list',
            //     'url' => 'quanly/capnuocgd/danhmuc/gd-dm-suco-nguyennhan',
            //     'key'=>'quanly.capnuocgd.danhmuc/gd-dm-suco-nguyennhan.index',
            //     'hasChild' => false,
            // ],
            // [
            //     'name' => 'Danh mục xử lý Sự cố',
            //     'icon' => 'fa fa-list',
            //     'url' => 'quanly/capnuocgd/danhmuc/gd-dm-xulysuco',
            //     'key'=>'quanly.capnuocgd.danhmuc/gd-dm-xulysuco.index',
            //     'hasChild' => false,
            // ],
        ],

    ];

    public static $ROOT_URL = 'app/';
    public static $URL_KEY = 'hcdcythd2022';
//    public static $HCMGIS_MAP = 'https://thuduc-maps.hcmgis.vn/thuducserver/gwc/service/wmts?layer=thuduc:thuduc_maps&style=&tilematrixset=EPSG:900913&Service=WMTS&Request=GetTile&Version=1.0.0&Format=image/png&TileMatrix=EPSG:900913:{z}&TileCol={x}&TileRow={y}';

    public static $BASEMAP = [
        'GoogleMap' => [
            'urlTemplate' => 'http://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}',
            'layerName' => 'Google Map',
            'clientOptions' => [
                'attribution' => '© GoogleMap contributors',
                'maxZoom' => 24,
                'subdomains' => ['mt0', 'mt1', 'mt2', 'mt3']
            ],
        ],
        'GoogleEarth' => [
            'urlTemplate' => 'http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',
            'layerName' => 'Ảnh vệ tinh',
            'clientOptions' => [
//                'attribution' => '© GoogleMap contributors',
                'maxZoom' => 24,
                'subdomains' => ['mt0', 'mt1', 'mt2', 'mt3']
            ],
        ],
//        'OpenStreetMap' => [
//            'urlTemplate' => 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
//            'layerName' => 'OSM',
//            'clientOptions' => [
//                'attribution' => '© OpenStreetMap contributors',
//                'maxZoom' => 22,
//            ],
//        ],

    ];

    public static function getUrl($url)
    {
        return \Yii::$app->homeUrl . self::$ROOT_URL . $url;
    }
}