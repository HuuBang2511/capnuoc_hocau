<?php


namespace app\modules\quanly\controllers;


use app\modules\quanly\base\QuanlyBaseController;
use yii\web\Controller;

class MapController extends QuanlyBaseController
{
    public function actionDuctrong()
    {
        return $this->render('ductrong');
    }

    public function actionGiadinh()
    {
        return $this->render('giadinh');
    }

    public function actionMaptest()
    {
        return $this->render('maptest');
    }

    public function actionMap_iot()
    {
        return $this->render('map_iot');
    }
}