<?php
namespace app\controllers; // ĐÃ ĐỔI: Chạy ra ngoài cùng của dự án

use yii\web\Controller;
use yii\web\Response;

class IotController extends Controller
{
    public $enableCsrfValidation = false;

    // Link gọi: http://gis.capnuochocaumoi.vn/iot/update
    public function actionUpdate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $request = \Yii::$app->request;
        
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== 'Bearer SCADA_HOCAU_2024_SECRET_KEY') {
            return ['status' => 'error', 'message' => 'Sai API Key!'];
        }
        
        if ($request->isPost) {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if ($data && isset($data['ma_tram'])) {
                $file = \Yii::getAlias('@runtime/iot_realtime.json');
                
                $currentData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
                
                $data['last_update'] = date('Y-m-d H:i:s');
                $currentData[$data['ma_tram']] = $data;
                
                file_put_contents($file, json_encode($currentData));
                return ['status' => 'success', 'message' => 'Đã lưu data IOT thành công'];
            }
        }
        return ['status' => 'error', 'message' => 'Lỗi định dạng dữ liệu'];
    }

    // Link gọi: http://gis.capnuochocaumoi.vn/iot/get
    public function actionGet()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $file = \Yii::getAlias('@runtime/iot_realtime.json');
        
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return []; 
    }
}