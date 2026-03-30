<?php
namespace app\modules\quanly\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\quanly\models\hocau\NkChatLuongGio;
use app\modules\quanly\models\hocau\NkGiaoCa;

class NhatKyController extends Controller
{
    // ── CHẤT LƯỢNG NƯỚC THEO GIỜ ─────────────────────────────

    public function actionChatLuongGio($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');
        $ca   = (date('H') >= 7 && date('H') < 19) ? 1 : 2;

        // Lấy bản ghi hiện tại hoặc tạo mới
        $model = NkChatLuongGio::findOne([
            'thoi_gian' => $ngay . ' ' . date('H') . ':00:00',
            'ca'        => $ca,
        ]) ?? new NkChatLuongGio([
            'thoi_gian'  => $ngay . ' ' . date('H') . ':00:00',
            'ca'         => $ca,
            'nguoi_nhap' => Yii::$app->user->identity->username ?? 'unknown',
        ]);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Đã lưu chất lượng nước ' . date('H:i'));
                return $this->redirect(['chat-luong-gio', 'ngay' => $ngay]);
            }
        }

        // Lấy lịch sử ngày hôm nay
        $lichSu = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->orderBy(['thoi_gian' => SORT_ASC])
            ->all();

        return $this->render('chat_luong_gio/index', [
            'model'   => $model,
            'lichSu'  => $lichSu,
            'ngay'    => $ngay,
            'QCVN'    => NkChatLuongGio::QCVN,
        ]);
    }

    // ── SỔ GIAO CA ───────────────────────────────────────────

    public function actionGiaoCa($ngay = null, $ca = null)
    {
        $ngay = $ngay ?? date('Y-m-d');
        $ca   = $ca ?? ((date('H') >= 7 && date('H') < 19) ? 1 : 2);

        $model = NkGiaoCa::findOne(['ngay' => $ngay, 'ca' => $ca])
               ?? new NkGiaoCa([
                    'ngay' => $ngay, 'ca' => $ca,
                    'nguoi_nhap' => Yii::$app->user->identity->username ?? 'unknown',
                ]);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Đã lưu sổ giao ca ' . ($ca==1?'Ngày':'Đêm') . ' ngày ' . $ngay);
                return $this->redirect(['giao-ca', 'ngay' => $ngay, 'ca' => $ca]);
            }
        }

        return $this->render('giao_ca/index', [
            'model' => $model,
            'ngay'  => $ngay,
            'ca'    => $ca,
        ]);
    }


    // ── TRANG BÁO CÁO (view chọn ngày + preview) ────────────

    public function actionBaoCao()
    {
        return $this->render('@app/modules/quanly/views/hocau/bao_cao/index');
    }

    // ── XUẤT BÁO CÁO EXCEL NGÀY ─────────────────────────────

    public function actionXuatBaoCaoNgay($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');

        // Lấy dữ liệu từ SCADA (qua gateway)
        $scadaData = $this->getScadaDataForDate($ngay);

        // Lấy dữ liệu nhập tay từ PostgreSQL
        $chatLuong = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->orderBy('thoi_gian')
            ->all();

        $giaoCa = NkGiaoCa::findAll(['ngay' => $ngay]);

        // Build Excel
        $excelBuilder = new BaoCaoNgayExcel($ngay, $scadaData, $chatLuong, $giaoCa);
        return $excelBuilder->download();
    }

    private function getScadaDataForDate($ngay)
    {
        // Gọi iot_api.php để lấy data SCADA
        $url = Yii::$app->params['iot_api_url']
             . '?action=sanluong&loai=thatthoat&key=' . Yii::$app->params['scada_key'];
        $json = @file_get_contents($url);
        if (!$json) return [];
        $data = json_decode($json, true);
        // Tìm ngày cần trong mảng days
        $ngay_vn = date('d/m/Y', strtotime($ngay));
        foreach (($data['days'] ?? []) as $d) {
            if ($d['ngay'] === $ngay_vn) return $d;
        }
        return [];
    }

    // ── API: trả JSON cho trang báo cáo ──────────────────────

    public function actionApiCln($ngay = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ngay = $ngay ?? date('Y-m-d');

        $cln = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->orderBy('thoi_gian')
            ->all();

        $rows = array_map(fn($r) => [
            'gio'     => date('H:i', strtotime($r->thoi_gian)),
            'ca'      => $r->ca,
            'ns_ph'   => $r->ns_ph,  'ns_ntu' => $r->ns_ntu,
            'nt_ph'   => $r->nt_ph,  'nt_ntu' => $r->nt_ntu,
            'nl1_ph'  => $r->nl1_ph, 'nl1_ntu'=> $r->nl1_ntu,
            'nl2_ph'  => $r->nl2_ph, 'nl2_ntu'=> $r->nl2_ntu,
            'clo_du'  => $r->clo_du,
        ], $cln);

        $ca_ngay = NkGiaoCa::findOne(['ngay' => $ngay, 'ca' => 1]);
        $ca_dem  = NkGiaoCa::findOne(['ngay' => $ngay, 'ca' => 2]);

        return [
            'count'   => count($rows),
            'rows'    => $rows,
            'ca_ngay' => $ca_ngay ? ['sl_cap' => $ca_ngay->getSanLuongCap()] : null,
            'ca_dem'  => $ca_dem  ? ['sl_cap' => $ca_dem->getSanLuongCap()]  : null,
        ];
    }

}