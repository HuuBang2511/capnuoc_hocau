<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use yii\web\Controller;

class MapController extends QuanlyBaseController
{
    /**
     * Hiển thị bản đồ GIS Full-screen
     */
    public function actionHocau()
    {
        // Tắt layout chính của hệ thống để hiển thị bản đồ toàn màn hình
        $this->layout = false;
        
        return $this->render('hocau');
    }
}