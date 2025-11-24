<?php
namespace app\modules\services;

use app\modules\danhmuc\models\DmKtvhxh;
use app\modules\danhmuc\models\DmTongiao;
use app\modules\quanly\models\capnuocgd\danhmuc\GdDmSucoBienphapxuly;
use app\modules\quanly\models\capnuocgd\danhmuc\GdDmSucoKetcaumatduong;
use app\modules\quanly\models\capnuocgd\danhmuc\GdDmSucoNguyennhan;
use app\modules\quanly\models\capnuocgd\danhmuc\GdDmXulysuco;
use app\modules\quanly\models\RanhphuongThuduc;

class CategoriesService
{

    public static function getCategories()
    {
        $categories = [];
        $categories['phuong'] = RanhphuongThuduc::find()->where(['status'=>1])->orderBy('name_3')->asArray()->all();
        $categories['dm_ktvhxh'] = DmKtvhxh::find()->where(['status'=>1])->orderBy('dm_tv')->asArray()->all();
        $categories['dm_tongiao'] = DmTongiao::find()->where(['status'=>1])->orderBy('dm_tv')->asArray()->all();
        return $categories;
    }

    public static function getDanhmuc_suco() {
        $categories = [];
        $categories['dm_suco_bienphapxuly'] = GdDmSucoBienphapxuly::find()->select(['id','ten', 'ma'])->where(['status'=>1])->orderBy('ten')->asArray()->all();
        $categories['dm_suco_ketcaumatduong'] = GdDmSucoKetcaumatduong::find()->select(['id','ten', 'ma'])->where(['status'=>1])->orderBy('ten')->asArray()->all();
        $categories['dm_suco_nguyennhan'] = GdDmSucoNguyennhan::find()->select(['id','ten', 'ma'])->where(['status'=>1])->orderBy('ten')->asArray()->all();
        $categories['dm_xulysuco'] = GdDmXulysuco::find()->select(['id','ten'])->where(['status'=>1])->orderBy('ten')->asArray()->all();
        return $categories;
    }

}