<?php


namespace app\modules\quanly\controllers;


use app\modules\danhmuc\models\HcPhuongxa;
use yii\web\Controller;
use Yii;
use yii\web\Response;

class CategoriesController extends Controller
{
    public function actionPhuongxa()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $quanhuyen = (string) $parents[0];
                $out = HcPhuongxa::find()->select('id as id, ten as name')
                    ->where(['quanhuyen_id' => $quanhuyen])
                    ->orderBy('ten')
                    ->asArray()
                    ->all();
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }
}