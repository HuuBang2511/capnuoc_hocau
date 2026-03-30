<?php
namespace app\modules\quanly\controllers;

use Yii;
use yii\web\Controller;
use app\modules\quanly\models\hocau\NkChatLuongGio;
use app\modules\quanly\models\hocau\NkGiaoCa;
use app\modules\quanly\components\BaoCaoNgayExcel;

class NhatKyController extends Controller
{
    public function actionBaoCao()
    {
        return $this->render('/hocau/bao_cao/index');
    }

    public function actionChatLuongGio($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');
        $gio  = date('H') . ':00:00';
        $ca   = (date('H') >= 7 && date('H') < 19) ? 1 : 2;

        $model = NkChatLuongGio::findOne([
            'thoi_gian' => $ngay . ' ' . $gio,
            'ca'        => $ca,
        ]);
        if (!$model) {
            $model = new NkChatLuongGio();
            $model->thoi_gian  = $ngay . ' ' . $gio;
            $model->ca         = $ca;
            $model->nguoi_nhap = Yii::$app->user->identity->username ?? '';
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Da luu chat luong nuoc ' . date('H:i'));
                return $this->redirect(['chat-luong-gio', 'ngay' => $ngay]);
            }
        }

        $lichSu = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->orderBy(['thoi_gian' => SORT_ASC])
            ->all();

        return $this->render('/hocau/chat_luong_gio/index', [
            'model'  => $model,
            'lichSu' => $lichSu,
            'ngay'   => $ngay,
            'QCVN'   => NkChatLuongGio::QCVN,
        ]);
    }

    public function actionGiaoCa($ngay = null, $ca = null)
    {
        $ngay = $ngay ?? date('Y-m-d');
        $ca   = $ca   ?? ((date('H') >= 7 && date('H') < 19) ? 1 : 2);

        $model = NkGiaoCa::findOne(['ngay' => $ngay, 'ca' => $ca]);
        if (!$model) {
            $model = new NkGiaoCa();
            $model->ngay       = $ngay;
            $model->ca         = $ca;
            $model->nguoi_nhap = Yii::$app->user->identity->username ?? '';
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Da luu so giao ca');
                return $this->redirect(['giao-ca', 'ngay' => $ngay, 'ca' => $ca]);
            }
        }

        return $this->render('/hocau/giao_ca/index', [
            'model' => $model,
            'ngay'  => $ngay,
            'ca'    => (int)$ca,
        ]);
    }

    public function actionApiCln($ngay = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ngay = $ngay ?? date('Y-m-d');

        $cln = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->orderBy('thoi_gian')
            ->all();

        $rows = [];
        foreach ($cln as $r) {
            $rows[] = [
                'gio'     => date('H:i', strtotime($r->thoi_gian)),
                'ca'      => $r->ca,
                'ns_ph'   => $r->ns_ph,  'ns_ntu' => $r->ns_ntu,
                'nt_ph'   => $r->nt_ph,  'nt_ntu' => $r->nt_ntu,
                'nl1_ph'  => $r->nl1_ph, 'nl1_ntu' => $r->nl1_ntu,
                'nl2_ph'  => $r->nl2_ph, 'nl2_ntu' => $r->nl2_ntu,
                'clo_du'  => $r->clo_du,
            ];
        }

        $ca_ngay = NkGiaoCa::findOne(['ngay' => $ngay, 'ca' => 1]);
        $ca_dem  = NkGiaoCa::findOne(['ngay' => $ngay, 'ca' => 2]);

        return [
            'count'   => count($rows),
            'rows'    => $rows,
            'ca_ngay' => $ca_ngay ? ['sl_cap' => $ca_ngay->getSanLuongCap()] : null,
            'ca_dem'  => $ca_dem  ? ['sl_cap' => $ca_dem->getSanLuongCap()]  : null,
        ];
    }

    public function actionXuatBaoCaoNgay($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');

        $chatLuong = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->orderBy('thoi_gian')
            ->all();

        $giaoCa = NkGiaoCa::findAll(['ngay' => $ngay]);

        // Lay data SCADA
        $scadaData = [];
        $iotUrl = 'http://192.168.31.11/iot_api.php?action=sanluong&loai=thatthoat&key=SCADA_HOCAU_2024_SECRET_KEY';
        $json = @file_get_contents($iotUrl);
        if ($json) {
            $data = json_decode($json, true);
            $ngay_vn = date('d/m/Y', strtotime($ngay));
            foreach (($data['days'] ?? []) as $d) {
                if ($d['ngay'] === $ngay_vn) {
                    $scadaData = $d;
                    break;
                }
            }
        }

        $builder = new BaoCaoNgayExcel($ngay, $scadaData, $chatLuong, $giaoCa);
        $builder->download();
    }
}