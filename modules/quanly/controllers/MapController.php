<?php


namespace app\modules\quanly\controllers;


use app\modules\quanly\base\QuanlyBaseController;
use yii\web\Controller;

class MapController extends QuanlyBaseController
{
    public function actionHocau()
    {
        return $this->render('hocau');
    }
}