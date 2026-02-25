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
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Thêm ngoại lệ: Cho phép khách (chưa đăng nhập) chọc vào 2 API này
        if (isset($behaviors['access']['rules'])) {
            array_unshift($behaviors['access']['rules'], [
                'actions' => ['update-iot', 'get-iot'],
                'allow' => true,
                'roles' => ['?', '@'], // '?' là khách, '@' là user đã login
            ]);
        }
        return $behaviors;
    }

    // ========================================================
    // [THÊM MỚI 2] - TẮT BẢO MẬT CSRF CỦA YII2 CHO API
    // ========================================================
    public function beforeAction($action)
    {
        if ($action->id == 'update-iot' || $action->id == 'get-iot') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    // ========================================================
    // [THÊM MỚI 3] - API HỨNG DỮ LIỆU TỪ SCADA
    // ========================================================
    public function actionUpdateIot()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $request = \Yii::$app->request;
        
        // BẢO MẬT: Kiểm tra mã API KEY. Tránh bị người ngoài phá dữ liệu.
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== 'Bearer SCADA_HOCAU_2024_SECRET_KEY') {
            throw new \yii\web\ForbiddenHttpException('Sai API Key! Từ chối truy cập.');
        }
        
        if ($request->isPost) {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if ($data && isset($data['ma_tram'])) {
                $file = \Yii::getAlias('@runtime/iot_realtime.json');
                
                $currentData = [];
                if (file_exists($file)) {
                    $currentData = json_decode(file_get_contents($file), true) ?: [];
                }
                
                $data['last_update'] = date('Y-m-d H:i:s');
                $currentData[$data['ma_tram']] = $data;
                
                file_put_contents($file, json_encode($currentData));
                return ['status' => 'success', 'message' => 'Đã lưu data IOT'];
            }
        }
        return ['status' => 'error', 'message' => 'Lỗi dữ liệu'];
    }

    // ========================================================
    // [THÊM MỚI 4] - API NHẢ DỮ LIỆU RA TRANG BẢN ĐỒ
    // ========================================================
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