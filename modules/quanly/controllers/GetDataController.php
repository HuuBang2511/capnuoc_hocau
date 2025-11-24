<?php


namespace app\modules\quanly\controllers;


use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\Phonghoc;
use Yii;
use yii\web\Response;

class GetDataController extends QuanlyBaseController
{
    public function actionPhuongxa()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $quanhuyen = (string) $parents[0];
                $out = Phonghoc::find()->select(['id', 'maphong as name'])->where(['truonghoc_id' => $quanhuyen])->orderBy('ten')->asArray()->all();
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }
}