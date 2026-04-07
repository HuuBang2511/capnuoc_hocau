<?php
namespace app\modules\quanly\controllers;

use Yii;
use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\hocau\NkChatLuongGio;
use app\modules\quanly\models\hocau\NkGiaoCa;
use app\modules\quanly\models\hocau\NkNuocThaiSh;
use app\modules\quanly\models\hocau\NkDongHoKhachHang;
use app\modules\quanly\components\BaoCaoNgayExcel;
use app\modules\quanly\components\SanLuongDongHoExcel;

class NhatKyController extends QuanlyBaseController
{
    // ─────────────────────────────────────────────────────────────
    // BÁO CÁO HÀNG NGÀY
    // ─────────────────────────────────────────────────────────────
    public function actionBaoCao()
    {
        return $this->render('/hocau/bao_cao/index');
    }

    // ─────────────────────────────────────────────────────────────
    // NHẬP CHẤT LƯỢNG NƯỚC THEO GIỜ
    // ─────────────────────────────────────────────────────────────
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
                Yii::$app->session->setFlash('success', 'Đã lưu chất lượng nước ' . date('H:i'));
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

    // ─────────────────────────────────────────────────────────────
    // SỔ GIAO CA
    // ─────────────────────────────────────────────────────────────
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
                Yii::$app->session->setFlash('success', 'Đã lưu sổ giao ca');
                return $this->redirect(['giao-ca', 'ngay' => $ngay, 'ca' => $ca]);
            }
        }

        return $this->render('/hocau/giao_ca/index', [
            'model' => $model,
            'ngay'  => $ngay,
            'ca'    => (int)$ca,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // NƯỚC THẢI SINH HOẠT
    // ─────────────────────────────────────────────────────────────
    public function actionNuocThaiSh($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');

        $model = NkNuocThaiSh::findOne(['ngay' => $ngay]);
        if (!$model) {
            $model = new NkNuocThaiSh();
            $model->ngay       = $ngay;
            $model->nguoi_nhap = Yii::$app->user->identity->username ?? '';
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Đã lưu kết quả nước thải ngày ' . date('d/m/Y', strtotime($ngay)));
                return $this->redirect(['nuoc-thai-sh', 'ngay' => $ngay]);
            }
        }

        // Lịch sử 3 tháng gần nhất
        $lichSu = NkNuocThaiSh::find()
            ->where(['>=', 'ngay', date('Y-m-d', strtotime('-90 days'))])
            ->orderBy(['ngay' => SORT_DESC])
            ->all();

        return $this->render('/hocau/nuoc_thai_sh/index', [
            'model'  => $model,
            'lichSu' => $lichSu,
            'ngay'   => $ngay,
            'QCVN'   => NkNuocThaiSh::QCVN,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // SẢN LƯỢNG ĐỒNG HỒ — BÁO CÁO
    // ─────────────────────────────────────────────────────────────
    public function actionSanLuongDongHo($tu_ngay = null, $den_ngay = null)
    {
        $den_ngay = $den_ngay ?? date('Y-m-d');
        $tu_ngay  = $tu_ngay  ?? date('Y-m-d', strtotime('-7 days'));

        $khachHang = NkDongHoKhachHang::getActive();

        return $this->render('/hocau/san_luong_dong_ho/index', [
            'tu_ngay'   => $tu_ngay,
            'den_ngay'  => $den_ngay,
            'khachHang' => $khachHang,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // SẢN LƯỢNG ĐỒNG HỒ — QUẢN LÝ CẤU HÌNH
    // ─────────────────────────────────────────────────────────────
    public function actionDongHoConfig()
    {
        $danhSach = NkDongHoKhachHang::find()
            ->orderBy(['thu_tu' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        return $this->render('/hocau/san_luong_dong_ho/config', [
            'danhSach' => $danhSach,
        ]);
    }

    public function actionDongHoSave()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->request->isPost) return ['success'=>false,'msg'=>'Invalid'];

        $post = Yii::$app->request->post();
        $id   = $post['id'] ?? null;

        $model = $id ? NkDongHoKhachHang::findOne($id) : new NkDongHoKhachHang();
        if (!$model) return ['success'=>false,'msg'=>'Không tìm thấy bản ghi'];

        // Chuyển mảng channel IDs về JSON string
        $dvao = $post['channel_dau_vao'] ?? [];
        $dra  = $post['channel_dau_ra']  ?? [];
        if (is_string($dvao)) $dvao = array_filter(array_map('trim', explode(',', $dvao)));
        if (is_string($dra))  $dra  = array_filter(array_map('trim', explode(',', $dra)));

        $model->ten_kh          = $post['ten_kh'] ?? '';
        $model->thu_tu          = (int)($post['thu_tu'] ?? 0);
        $model->channel_dau_vao = json_encode(array_values(array_map('intval', $dvao)));
        $model->channel_dau_ra  = json_encode(array_values(array_map('intval', $dra)));
        $model->don_vi          = $post['don_vi'] ?? 'm³';
        $model->ghi_chu         = $post['ghi_chu'] ?? null;
        $model->active          = (bool)($post['active'] ?? true);
        $model->updated_at      = date('Y-m-d H:i:s');

        if ($model->save()) {
            return ['success'=>true,'id'=>$model->id];
        }
        return ['success'=>false,'msg'=>implode(', ', $model->getFirstErrors())];
    }

    public function actionDongHoDelete($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = NkDongHoKhachHang::findOne($id);
        if (!$model) return ['success'=>false,'msg'=>'Không tìm thấy'];
        $model->delete();
        return ['success'=>true];
    }

    // ─────────────────────────────────────────────────────────────
    // API — CLN cho trang báo cáo
    // ─────────────────────────────────────────────────────────────
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
                'ns_ph'   => $r->ns_ph,  'ns_ntu'  => $r->ns_ntu,
                'nt_ph'   => $r->nt_ph,  'nt_ntu'  => $r->nt_ntu,
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

    // ─────────────────────────────────────────────────────────────
    // API — Dữ liệu sản lượng đồng hồ (gọi từ JS)
    // ─────────────────────────────────────────────────────────────
    public function actionApiSanLuong($tu_ngay = null, $den_ngay = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $den_ngay = $den_ngay ?? date('Y-m-d');
        $tu_ngay  = $tu_ngay  ?? date('Y-m-d', strtotime('-7 days'));

        // Validate ngày
        if (!strtotime($tu_ngay) || !strtotime($den_ngay)) {
            return ['success'=>false,'msg'=>'Ngày không hợp lệ'];
        }

        $khachHang = NkDongHoKhachHang::getActive();
        if (empty($khachHang)) return ['success'=>true,'rows'=>[],'ngay_list'=>[]];

        // Thu thập tất cả channelId cần query
        $allChannels = [];
        foreach ($khachHang as $kh) {
            foreach ($kh->getChannelDauVaoArr() as $cid) $allChannels[$cid] = true;
            foreach ($kh->getChannelDauRaArr()  as $cid) $allChannels[$cid] = true;
        }
        $channelIds = array_keys($allChannels);

        // Tạo danh sách ngày trong khoảng
        $ngayList = [];
        $d = strtotime($tu_ngay);
        $dEnd = strtotime($den_ngay);
        while ($d <= $dEnd) {
            $ngayList[] = date('Y-m-d', $d);
            $d = strtotime('+1 day', $d);
        }
        if (empty($ngayList)) return ['success'=>true,'rows'=>[],'ngay_list'=>[]];

        // Query SCADA SQL Server qua iot_api
        // Gọi endpoint lấy sản lượng theo channel + ngày
        $scadaData = $this->fetchScadaSanLuong($channelIds, $tu_ngay, $den_ngay);

        // Tính sản lượng cho từng khách hàng × từng ngày
        $rows = [];
        foreach ($khachHang as $kh) {
            $dvaoIds = $kh->getChannelDauVaoArr();
            $draIds  = $kh->getChannelDauRaArr();

            $rowData = [
                'id'         => $kh->id,
                'ten_kh'     => $kh->ten_kh,
                'dau_vao'    => $kh->getLabelDauVao(),
                'dau_ra'     => $kh->getLabelDauRa(),
                'don_vi'     => $kh->don_vi,
                'ngay_data'  => [],
                'tong'       => null,
                'tb_ngay'    => null,
            ];

            $tongTong = 0;
            $countNgay = 0;

            foreach ($ngayList as $ngay) {
                $vao = 0;
                foreach ($dvaoIds as $cid) {
                    $v = $scadaData[$cid][$ngay] ?? null;
                    if ($v !== null) $vao += $v;
                }
                $ra = 0;
                foreach ($draIds as $cid) {
                    $v = $scadaData[$cid][$ngay] ?? null;
                    if ($v !== null) $ra += $v;
                }
                // Nếu không có dữ liệu đầu vào thì null
                $hasData = false;
                foreach ($dvaoIds as $cid) {
                    if (isset($scadaData[$cid][$ngay])) { $hasData = true; break; }
                }
                $sl = $hasData ? max(0, $vao - $ra) : null;
                $rowData['ngay_data'][$ngay] = $sl;
                if ($sl !== null) { $tongTong += $sl; $countNgay++; }
            }

            $rowData['tong']    = $countNgay > 0 ? round($tongTong) : null;
            $rowData['tb_ngay'] = $countNgay > 0 ? round($tongTong / $countNgay) : null;
            $rows[] = $rowData;
        }

        return [
            'success'    => true,
            'rows'       => $rows,
            'ngay_list'  => $ngayList,
        ];
    }

    /**
     * Fetch sản lượng từ SCADA gateway qua iot_api.php
     * Trả về: [ channelId => [ 'Y-m-d' => value, ... ], ... ]
     */
    private function fetchScadaSanLuong(array $channelIds, string $tu_ngay, string $den_ngay): array
    {
        $params = http_build_query([
            'action'    => 'sanluong_dong_ho',
            'key'       => 'SCADA_HOCAU_2024_SECRET_KEY',
            'channels'  => implode(',', $channelIds),
            'tu_ngay'   => $tu_ngay,
            'den_ngay'  => $den_ngay,
        ]);

        $ctx = stream_context_create(['http'=>['timeout'=>10]]);
        $json = @file_get_contents(
            'http://192.168.31.11/iot_api.php?' . $params, false, $ctx
        );

        if (!$json) return [];

        $data = json_decode($json, true);
        if (!isset($data['data'])) return [];

        // Format trả về: { "data": { "60007": { "2026-03-01": 1234, ... }, ... } }
        $result = [];
        foreach (($data['data'] ?? []) as $channelId => $ngayData) {
            $result[(int)$channelId] = $ngayData;
        }
        return $result;
    }

    // ─────────────────────────────────────────────────────────────
    // XUẤT EXCEL BÁO CÁO NGÀY
    // ─────────────────────────────────────────────────────────────
    public function actionXuatBaoCaoNgay($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');

        $chatLuong = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->orderBy('thoi_gian')
            ->all();

        $giaoCa = NkGiaoCa::findAll(['ngay' => $ngay]);

        $scadaData = [];
        $json = @file_get_contents(
            'http://192.168.31.11/iot_api.php?action=sanluong&loai=thatthoat&key=SCADA_HOCAU_2024_SECRET_KEY'
        );
        if ($json) {
            $data    = json_decode($json, true);
            foreach (($data['days'] ?? []) as $d) {
                // So sánh cả 2 format ngày
                $ngayScada = $d['ngay'] ?? '';
                $ngayISO   = strpos($ngayScada, '/') !== false
                    ? date('Y-m-d', strtotime(str_replace('/', '-', $ngayScada)))
                    : $ngayScada;
                if ($ngayISO === $ngay) { $scadaData = $d; break; }
            }
        }

        $builder = new BaoCaoNgayExcel($ngay, $scadaData, $chatLuong, $giaoCa);
        $builder->download();
    }

    // ─────────────────────────────────────────────────────────────
    // XUẤT EXCEL SẢN LƯỢNG ĐỒNG HỒ
    // ─────────────────────────────────────────────────────────────
    public function actionXuatSanLuong($tu_ngay = null, $den_ngay = null)
    {
        $den_ngay = $den_ngay ?? date('Y-m-d');
        $tu_ngay  = $tu_ngay  ?? date('Y-m-d', strtotime('-7 days'));

        $apiResult = $this->actionApiSanLuong($tu_ngay, $den_ngay);
        // Reset response format cho Excel
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $builder = new SanLuongDongHoExcel($tu_ngay, $den_ngay, $apiResult['rows'] ?? [], $apiResult['ngay_list'] ?? []);
        $builder->download();
    }
}