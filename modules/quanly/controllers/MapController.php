<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use yii\helpers\Url;
// Import Models danh mục để nạp vào bộ lọc (Giữ nguyên như cũ)
use app\modules\quanly\models\danhmuc\DmLoaiong;
use app\modules\quanly\models\danhmuc\DmHieudongho;
use app\modules\quanly\models\danhmuc\DmTinhtrang;
use app\modules\quanly\models\danhmuc\DmSucoNguyennhan;
use app\modules\quanly\models\danhmuc\DmLoaiham;
use app\modules\quanly\models\danhmuc\DmLoaimoinoi;
use yii\web\Response;

class MapController extends QuanlyBaseController
{
    public function beforeAction($action)
    {
        if ($action->id == 'update-iot') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    // --- THÊM MỚI: API hứng dữ liệu từ Tool Python SCADA ---
    public function actionUpdateIot()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $request = \Yii::$app->request;
        
        if ($request->isPost) {
            // Lấy raw data JSON từ tool Python bắn sang
            $data = json_decode(file_get_contents('php://input'), true);
            
            if ($data && isset($data['ma_tram'])) {
                // Lưu data vào file tạm trong thư mục runtime của Yii2
                $file = \Yii::getAlias('@runtime/iot_realtime.json');
                
                $currentData = [];
                if (file_exists($file)) {
                    $currentData = json_decode(file_get_contents($file), true) ?: [];
                }
                
                // Cập nhật trạm mới nhất, gán thêm thời gian server nhận được
                $data['last_update'] = date('Y-m-d H:i:s');
                $currentData[$data['ma_tram']] = $data;
                
                file_put_contents($file, json_encode($currentData));
                return ['status' => 'success', 'message' => 'Đã cập nhật data IOT'];
            }
        }
        return ['status' => 'error', 'message' => 'Dữ liệu không hợp lệ'];
    }

    // --- THÊM MỚI: API cho Frontend (bản đồ) gọi để lấy data vẽ lên ---
    public function actionGetIot()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $file = \Yii::getAlias('@runtime/iot_realtime.json');
        
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return [];
    }
    
    public function actionHocau()
    {
        $this->layout = false;
        
        // Lấy dữ liệu danh mục cho bộ lọc (Giữ nguyên)
        $filterData = [
            'loaiong' => DmLoaiong::find()->select(['id', 'ten'])->asArray()->all(),
            'hieudongho' => DmHieudongho::find()->select(['id', 'ten'])->asArray()->all(),
            'tinhtrang' => DmTinhtrang::find()->select(['id', 'ten'])->asArray()->all(),
            'nguyennhan' => DmSucoNguyennhan::find()->select(['id', 'ten'])->asArray()->all(),
            'loaiham' => DmLoaiham::find()->select(['id', 'ten'])->asArray()->all(),
            'loaimoinoi' => DmLoaimoinoi::find()->select(['id', 'ten'])->asArray()->all(),
        ];

        return $this->render('hocau', [
            'filterData' => $filterData
        ]);
    }
}