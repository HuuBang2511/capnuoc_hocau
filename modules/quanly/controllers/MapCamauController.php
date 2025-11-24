<?php


namespace app\modules\quanly\controllers;


use app\modules\quanly\base\QuanlyBaseController;
use yii\web\Controller;

class MapCamauController extends QuanlyBaseController
{
    public $layout = '@app/views/layouts/map/main';
    public function actionIndex()
    {
        return $this->render('index');
    }

   
}