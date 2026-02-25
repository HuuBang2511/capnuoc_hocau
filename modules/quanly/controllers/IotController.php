<?php
namespace app\modules\quanly\controllers;

use yii\web\Controller; // Kế thừa trực tiếp từ Core của Yii2, KHÔNG dùng hcmgis nữa
use yii\web\Response;

class IotController extends Controller
{
    // Tắt kiểm tra CSRF cho toàn bộ Controller API này
    public $enableCsrfValidation = false;

    // API HỨNG DATA TỪ SCADA (Link: /quanly/iot/update)
    public function actionUpdate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $request = \Yii::$app->request;
        
        // BẢO MẬT BẰNG API KEY
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== 'Bearer SCADA_HOCAU_2024_SECRET_KEY') {
            return ['status' => 'error', 'message' => 'Sai API Key! Từ chối truy cập.'];
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
                return ['status' => 'success', 'message' => 'Đã lưu data IOT thành công'];
            }
        }
        return ['status' => 'error', 'message' => 'Lỗi định dạng dữ liệu'];
    }

    // API NHẢ DATA RA BẢN ĐỒ (Link: /quanly/iot/get)
    public function actionGet()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $file = \Yii::getAlias('@runtime/iot_realtime.json');
        
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return []; // Trả về mảng rỗng nếu chưa có file
    }
}