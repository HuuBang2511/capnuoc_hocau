<?php
namespace app\modules\quanly\controllers;

use Yii;
use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\hocau\NkChatLuongGio;
use app\modules\quanly\models\hocau\NkGiaoCa;
use app\modules\quanly\models\hocau\NkNuocThaiSh;
use app\modules\quanly\models\hocau\NkDongHoKhachHang;
use app\modules\quanly\models\hocau\NkClnHangNgay;
use app\modules\quanly\models\hocau\NkPhanTichTuan;
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
    // NHẬP CHẤT LƯỢNG NƯỚC THEO GIỜ (ca ngày / ca đêm)
    // ─────────────────────────────────────────────────────────────
    public function actionChatLuongGio($ngay = null, $ca = null)
    {
        $ngay = $ngay ?? date('Y-m-d');
        if ($ca === null) {
            $ca = (date('H') >= 7 && date('H') < 19) ? 1 : 2;
        }
        $ca = (int)$ca;

        $gioList = $ca == 1
            ? [7,8,9,10,11,12,13,14,15,16,17,18]
            : [19,20,21,22,23,0,1,2,3,4,5,6];

        $allFields = [
            'ns_ph','ns_ntu','nt_ph','nt_ntu','nl1_ph','nl1_ntu','nl2_ph','nl2_ntu',
            'clo_du',
            'ns_clo_nong_do','nt_clo_nong_do','nc_clo_cham','pac_cham',
            'nt_do_mau','ns_do_mau',
            'ngoai_ho_ph','ngoai_ho_ntu',
            'muong_pu_thu_hoi','muong_lang_nl1','muong_pu_ns','dau_be_ns',
            'ho_xi_phong_1_ntu','ho_xi_phong_2_ntu',
            'pac_ty_trong',
        ];

        if (Yii::$app->request->isPost) {
            $post    = Yii::$app->request->post();
            $rows    = $post['rows'] ?? [];
            $nguoi_truc = trim($post['nguoi_truc'] ?? '');
            $nguoi_kt   = trim($post['nguoi_kt'] ?? '');
            $username   = Yii::$app->user->identity->username ?? '';

            $saved = 0;
            foreach ($gioList as $gio) {
                $rowData = $rows[(string)$gio] ?? [];
                // Bỏ qua hàng trống hoàn toàn
                $hasVal = false;
                foreach ($allFields as $f) {
                    if (isset($rowData[$f]) && $rowData[$f] !== '') { $hasVal = true; break; }
                }
                if (!$hasVal) continue;

                $thoi_gian = $ngay . ' ' . ($gio === 0 ? '00' : str_pad($gio, 2, '0', STR_PAD_LEFT)) . ':00:00';
                $model = NkChatLuongGio::findOne(['thoi_gian' => $thoi_gian, 'ca' => $ca]);
                if (!$model) {
                    $model = new NkChatLuongGio();
                    $model->thoi_gian  = $thoi_gian;
                    $model->ca         = $ca;
                    $model->nguoi_nhap = $username;
                }
                foreach ($allFields as $f) {
                    $v = $rowData[$f] ?? null;
                    $model->$f = ($v !== '' && $v !== null) ? (float)$v : null;
                }
                $model->nguoi_truc = $nguoi_truc ?: null;
                $model->nguoi_kt   = $nguoi_kt   ?: null;
                $model->save();
                $saved++;
            }

            $this->saveJarTest($ngay, $ca, $post);
            Yii::$app->session->setFlash('success', 'Đã lưu ' . $saved . ' giờ — ' . date('H:i'));
            return $this->redirect(['chat-luong-gio', 'ngay' => $ngay, 'ca' => $ca]);
        }

        $lichSu = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->andWhere(['ca' => $ca])
            ->orderBy(['thoi_gian' => SORT_ASC])
            ->all();

        // Model dummy chỉ để truyền nguoi_truc/kt vào view
        $model = $lichSu[0] ?? new NkChatLuongGio();

        $jarTest = \app\modules\quanly\models\hocau\NkJarTest::findOne([
            'ngay' => $ngay, 'ca' => $ca
        ]);

        return $this->render('/hocau/chat_luong_gio/index', [
            'model'   => $model,
            'lichSu'  => $lichSu,
            'ngay'    => $ngay,
            'ca'      => $ca,
            'QCVN'    => NkChatLuongGio::QCVN,
            'jarTest' => $jarTest,
        ]);
    }

    /**
     * Lưu jar test từ POST data
     */
    private function saveJarTest(string $ngay, int $ca, array $post): void
    {
        $jGio = trim($post['jar_gio'] ?? ($ca == 1 ? '08:00' : '19:00'));
        $pac  = $post['jar_pac'] ?? [];
        $ntu  = $post['jar_ntu'] ?? [];
        $ph   = $post['jar_ph']  ?? [];
        $lieu = $post['jar_lieu_chon'] ?? null;

        // Chỉ lưu nếu có ít nhất 1 giá trị
        $hasData = false;
        for ($i=1; $i<=5; $i++) {
            if (!empty($pac[$i]) || !empty($ntu[$i])) { $hasData = true; break; }
        }
        if (!$hasData) return;

        $jar = \app\modules\quanly\models\hocau\NkJarTest::findOne(['ngay'=>$ngay,'ca'=>$ca]);
        if (!$jar) {
            $jar = new \app\modules\quanly\models\hocau\NkJarTest();
            $jar->ngay = $ngay;
            $jar->ca   = $ca;
        }
        $jar->gio_thu    = $jGio ? $jGio . ':00' : null;
        $jar->lieu_chon  = $lieu ?: null;
        $jar->nguoi_nhap = Yii::$app->user->identity->username ?? '';
        for ($i=1; $i<=5; $i++) {
            $jar->{'pac_'.$i.'_lieu'} = isset($pac[$i]) && $pac[$i] !== '' ? (float)$pac[$i] : null;
            $jar->{'pac_'.$i.'_ntu'}  = isset($ntu[$i]) && $ntu[$i] !== '' ? (float)$ntu[$i] : null;
            $jar->{'pac_'.$i.'_ph'}   = isset($ph[$i])  && $ph[$i]  !== '' ? (float)$ph[$i]  : null;
        }
        $jar->save();
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
    // CLN HÀNG NGÀY (tab mới — bảng đầy đủ 24h)
    // ─────────────────────────────────────────────────────────────
    public function actionClnHangNgay($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');

        $model = NkClnHangNgay::findOne(['ngay' => $ngay]);
        if (!$model) {
            $model = new NkClnHangNgay();
            $model->ngay       = $ngay;
            $model->gio_data   = '{}';
            $model->nguoi_nhap = Yii::$app->user->identity->username ?? '';
        }

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            // Load các field thông thường (nguoi_truc_sang, v.v.)
            $model->load($post);

            // Xử lý gio_data từ POST array gio[7][ns_ph], gio[8][ns_ph], ...
            $gioPost = $post['gio'] ?? [];
            $gioData = json_decode($model->gio_data ?? '{}', true);
            $GIO_ALL = NkClnHangNgay::GIO_ALL;
            foreach ($GIO_ALL as $gio) {
                $row = $gioPost[(string)$gio] ?? [];
                $vals = [];
                foreach ($row as $k => $v) {
                    $vals[$k] = ($v !== '' && $v !== null) ? (float)$v : null;
                }
                // Chỉ lưu nếu có ít nhất 1 giá trị != null
                $hasVal = count(array_filter($vals, fn($x) => $x !== null)) > 0;
                if ($hasVal) {
                    $gioData[(string)$gio] = $vals;
                }
            }
            $model->gio_data = json_encode($gioData);

            // Jar test arrays
            foreach (['s','c'] as $k) {
                $prefix = 'jar_' . $k;
                foreach (['pac','ntu','ph'] as $t) {
                    $arr = $post[$prefix . '_' . $t] ?? [];
                    $model->{'jar_' . $k . '_' . $t} = json_encode(array_map(
                        fn($v) => ($v !== '' && $v !== null) ? (float)$v : null,
                        array_values($arr)
                    ));
                }
            }

            $model->updated_at = date('Y-m-d H:i:s');
            if ($model->save()) {
                Yii::$app->session->setFlash('success_cln', 'Đã lưu CLN hàng ngày ' . date('d/m/Y', strtotime($ngay)));
                return $this->redirect(['cln-hang-ngay', 'ngay' => $ngay]);
            }
        }

        return $this->render('/hocau/cln_hang_ngay/index', [
            'model' => $model,
            'ngay'  => $ngay,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // CL NƯỚC TUẦN (phan_tich_tuan)
    // ─────────────────────────────────────────────────────────────
    public function actionPhanTichTuan($thang = null, $nam = null)
    {
        $thang = (int)($thang ?? date('m'));
        $nam   = (int)($nam   ?? date('Y'));

        $lichTuan = NkPhanTichTuan::find()
            ->where(['thang' => $thang, 'nam' => $nam])
            ->orderBy(['tuan_so' => SORT_ASC])
            ->all();

        if (Yii::$app->request->isPost) {
            $post  = Yii::$app->request->post();
            $rows  = $post['rows']  ?? [];
            $nguoi_pt = trim($post['nguoi_pt'] ?? '');
            $nguoi_kt = trim($post['nguoi_kt'] ?? '');

            foreach ($rows as $tuanSo => $rowData) {
                $ngayPt = $rowData['ngay_pt_1'] ?? null;
                if (!$ngayPt) continue;

                $rec = NkPhanTichTuan::findOne(['ngay_pt' => $ngayPt]);
                if (!$rec) {
                    $rec = new NkPhanTichTuan();
                    $rec->ngay_pt = $ngayPt;
                }
                $rec->tuan_so = (int)$tuanSo;
                $rec->thang   = $thang;
                $rec->nam     = $nam;

                $numFields = [
                    // NT
                    'nt_do_kiem','nt_do_cung','nt_clorua','nt_tss',
                    'nt_al','nt_fe','nt_mn','nt_amoni','nt_nitrat','nt_nitrit',
                    'nt_sulfat','nt_permanganat','nt_cod','nt_florua',
                    // NS
                    'ns_do_kiem','ns_do_cung','ns_clorua','ns_tss',
                    'ns_al','ns_fe','ns_mn','ns_amoni','ns_nitrat','ns_nitrit',
                    'ns_sulfat','ns_permanganat','ns_cod','ns_florua',
                ];
                // coliform là integer
                foreach (['nt_coliform','ns_coliform'] as $cf) {
                    $v = $rowData[$cf] ?? null;
                    $rec->$cf = ($v !== '' && $v !== null) ? (int)$v : null;
                }
                foreach ($numFields as $f) {
                    $v = $rowData[$f] ?? null;
                    $rec->$f = ($v !== '' && $v !== null) ? (float)$v : null;
                }
                $rec->nguoi_pt = $nguoi_pt ?: null;
                $rec->nguoi_kt = $nguoi_kt ?: null;
                $rec->nguoi_nhap = Yii::$app->user->identity->username ?? '';
                $rec->save();
            }

            Yii::$app->session->setFlash('success_tuan', 'Đã lưu CL nước tháng ' . $thang . '/' . $nam);
            return $this->redirect(['phan-tich-tuan', 'thang' => $thang, 'nam' => $nam]);
        }

        // Lấy người TH/KT từ bản ghi đầu tiên trong tháng
        $firstRec  = $lichTuan[0] ?? null;
        $nguoi_pt  = $firstRec?->nguoi_pt ?? '';
        $nguoi_kt  = $firstRec?->nguoi_kt ?? '';

        return $this->render('/hocau/phan_tich_tuan/index', [
            'lichTuan' => $lichTuan,
            'thang'    => $thang,
            'nam'      => $nam,
            'nguoi_pt' => $nguoi_pt,
            'nguoi_kt' => $nguoi_kt,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // SẢN LƯỢNG ĐỒNG HỒ
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
    // CẤU HÌNH ĐỒNG HỒ KHÁCH HÀNG
    // ─────────────────────────────────────────────────────────────
    public function actionDongHoConfig()
    {
        $danhSach = NkDongHoKhachHang::find()
            ->orderBy(['thu_tu' => SORT_ASC, 'id' => SORT_ASC])
            ->all();
        return $this->render('/hocau/san_luong_dong_ho/config', ['danhSach' => $danhSach]);
    }

    public function actionDongHoSave()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->request->isPost) return ['success'=>false,'msg'=>'Invalid'];

        $post = Yii::$app->request->post();
        $id   = $post['id'] ?? null;
        $model = $id ? NkDongHoKhachHang::findOne($id) : new NkDongHoKhachHang();
        if (!$model) return ['success'=>false,'msg'=>'Không tìm thấy bản ghi'];

        $dvao = $post['channel_dau_vao'] ?? [];
        $dra  = $post['channel_dau_ra']  ?? [];
        if (is_string($dvao)) $dvao = array_filter(array_map('trim', explode(',', $dvao)));
        if (is_string($dra))  $dra  = array_filter(array_map('trim', explode(',', $dra)));

        $sanitize = function($ids) {
            return array_values(array_filter(array_map(function($id) {
                $id = trim($id);
                return preg_match('/^[a-zA-Z0-9_]+$/', $id) ? $id : null;
            }, $ids)));
        };
        $model->ten_kh          = $post['ten_kh'] ?? '';
        $model->thu_tu          = (int)($post['thu_tu'] ?? 0);
        $model->channel_dau_vao = json_encode($sanitize($dvao));
        $model->channel_dau_ra  = json_encode($sanitize($dra));
        $model->don_vi          = $post['don_vi'] ?? 'm³';
        $model->ghi_chu         = $post['ghi_chu'] ?? null;
        $model->active          = (bool)($post['active'] ?? true);
        $model->updated_at      = date('Y-m-d H:i:s');

        if ($model->save()) return ['success'=>true,'id'=>$model->id];
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

        // CLN hàng ngày
        $clnNgay = NkClnHangNgay::findOne(['ngay' => $ngay]);

        return [
            'count'      => count($rows),
            'rows'       => $rows,
            'ca_ngay'    => $ca_ngay ? ['sl_cap' => $ca_ngay->getSanLuongCap()] : null,
            'ca_dem'     => $ca_dem  ? ['sl_cap' => $ca_dem->getSanLuongCap()]  : null,
            'cln_ngay'   => $clnNgay ? ['id' => $clnNgay->id] : null,
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // API — Kiểm tra trạng thái nước thải (cho status card bao_cao)
    // ─────────────────────────────────────────────────────────────
    public function actionApiNuocThai($ngay = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ngay = $ngay ?? date('Y-m-d');
        $rec  = NkNuocThaiSh::findOne(['ngay' => $ngay]);
        return [
            'has_data' => $rec !== null,
            'data'     => $rec ? [
                'ph'       => $rec->ph,
                'tss'      => $rec->tss,
                'amoni'    => $rec->amoni,
                'nitrat'   => $rec->nitrat,
                'coliform' => $rec->coliform,
                'nguoi_th' => $rec->nguoi_th,
            ] : null,
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // API — Sản lượng đồng hồ
    // ─────────────────────────────────────────────────────────────
    public function actionApiSanLuong($tu_ngay = null, $den_ngay = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $den_ngay = $den_ngay ?? date('Y-m-d');
        $tu_ngay  = $tu_ngay  ?? date('Y-m-d', strtotime('-7 days'));

        if (!strtotime($tu_ngay) || !strtotime($den_ngay)) {
            return ['success'=>false,'msg'=>'Ngày không hợp lệ'];
        }

        $khachHang = NkDongHoKhachHang::getActive();
        if (empty($khachHang)) return ['success'=>true,'rows'=>[],'ngay_list'=>[]];

        $allChannels = [];
        foreach ($khachHang as $kh) {
            foreach ($kh->getChannelDauVaoArr() as $cid) $allChannels[$cid] = true;
            foreach ($kh->getChannelDauRaArr()  as $cid) $allChannels[$cid] = true;
        }
        $channelIds = array_keys($allChannels);

        $ngayList = [];
        $d = strtotime($tu_ngay);
        $dEnd = strtotime($den_ngay);
        while ($d <= $dEnd) {
            $ngayList[] = date('Y-m-d', $d);
            $d = strtotime('+1 day', $d);
        }
        if (empty($ngayList)) return ['success'=>true,'rows'=>[],'ngay_list'=>[]];

        $scadaData = $this->fetchScadaSanLuong($channelIds, $tu_ngay, $den_ngay);

        $rows = [];
        foreach ($khachHang as $kh) {
            $dvaoIds = $kh->getChannelDauVaoArr();
            $draIds  = $kh->getChannelDauRaArr();

            $rowData = [
                'id'        => $kh->id,
                'ten_kh'    => $kh->ten_kh,
                'dau_vao'   => $kh->getLabelDauVao(),
                'dau_ra'    => $kh->getLabelDauRa(),
                'don_vi'    => $kh->don_vi,
                'ngay_data' => [],
                'tong'      => null,
                'tb_ngay'   => null,
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

        return ['success' => true, 'rows' => $rows, 'ngay_list' => $ngayList];
    }

    private function fetchScadaSanLuong(array $channelIds, string $tu_ngay, string $den_ngay): array
    {
        $params = http_build_query([
            'key'      => 'SCADA_HOCAU_2024_SECRET_KEY',
            'channels' => implode(',', $channelIds),
            'tu_ngay'  => $tu_ngay,
            'den_ngay' => $den_ngay,
        ]);
        $ctx  = stream_context_create(['http' => ['timeout' => 15]]);
        $json = @file_get_contents('http://192.168.31.6:5001/sanluong_dong_ho?' . $params, false, $ctx);
        if (!$json) return [];
        $data = json_decode($json, true);
        if (empty($data['success']) || !isset($data['data'])) return [];
        $result = [];
        foreach (($data['data'] ?? []) as $channelId => $ngayData) {
            $result[(string)$channelId] = $ngayData;
        }
        return $result;
    }

    // ─────────────────────────────────────────────────────────────
    // XUẤT BÁO CÁO — 5 file riêng biệt
    // ─────────────────────────────────────────────────────────────

    /** Xuất Excel hoá nghiệm (CLN theo giờ + CLN hàng ngày) */
    public function actionXuatHoaNghiem($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');
        $chatLuong = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->orderBy('thoi_gian')->all();
        $clnNgay = NkClnHangNgay::findOne(['ngay' => $ngay]);

        $builder = new \app\modules\quanly\components\HoaNghiemExcel($ngay, $chatLuong, $clnNgay);
        $builder->download();
    }

    /** Xuất Excel vận hành (giao ca ngày + đêm) */
    public function actionXuatVanHanh($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');
        $giaoCa = NkGiaoCa::findAll(['ngay' => $ngay]);
        $scadaData = $this->getScadaForNgay($ngay);
        $builder = new \app\modules\quanly\components\VanHanhExcel($ngay, $giaoCa, $scadaData);
        $builder->download();
    }

    /** Xuất Excel nước thải SH */
    public function actionXuatNuocThai($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');
        $nuocThai = NkNuocThaiSh::findOne(['ngay' => $ngay]);
        $builder = new \app\modules\quanly\components\NuocThaiExcel($ngay, $nuocThai);
        $builder->download();
    }

    /** Xuất Excel CL Nước Tuần */
    public function actionXuatClnTuan($thang = null, $nam = null)
    {
        $thang = (int)($thang ?? date('m'));
        $nam   = (int)($nam   ?? date('Y'));
        $lichTuan = NkPhanTichTuan::find()
            ->where(['thang' => $thang, 'nam' => $nam])
            ->orderBy(['tuan_so' => SORT_ASC])->all();
        $builder = new \app\modules\quanly\components\ClnTuanExcel($thang, $nam, $lichTuan);
        $builder->download();
    }

    /** Xuất Excel tổng hợp ngày (cũ — giữ lại) */
    public function actionXuatBaoCaoNgay($ngay = null)
    {
        $ngay = $ngay ?? date('Y-m-d');
        $chatLuong = NkChatLuongGio::find()
            ->where(['>=', 'thoi_gian', $ngay . ' 00:00:00'])
            ->andWhere(['<=', 'thoi_gian', $ngay . ' 23:59:59'])
            ->orderBy('thoi_gian')->all();
        $giaoCa = NkGiaoCa::findAll(['ngay' => $ngay]);
        $scadaData = $this->getScadaForNgay($ngay);
        $builder = new BaoCaoNgayExcel($ngay, $scadaData, $chatLuong, $giaoCa);
        $builder->download();
    }

    /** Xuất Excel sản lượng đồng hồ */
    public function actionXuatSanLuong($tu_ngay = null, $den_ngay = null)
    {
        $den_ngay = $den_ngay ?? date('Y-m-d');
        $tu_ngay  = $tu_ngay  ?? date('Y-m-d', strtotime('-7 days'));
        $apiResult = $this->actionApiSanLuong($tu_ngay, $den_ngay);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $builder = new SanLuongDongHoExcel($tu_ngay, $den_ngay, $apiResult['rows'] ?? [], $apiResult['ngay_list'] ?? []);
        $builder->download();
    }

    /** Helper: lấy scada data cho 1 ngày */
    private function getScadaForNgay(string $ngay): array
    {
        $scadaData = [];
        $json = @file_get_contents(
            'http://192.168.31.11/iot_api.php?action=sanluong&loai=thatthoat&key=SCADA_HOCAU_2024_SECRET_KEY'
        );
        if ($json) {
            $data = json_decode($json, true);
            foreach (($data['days'] ?? []) as $d) {
                $ngayScada = $d['ngay'] ?? '';
                $ngayISO   = strpos($ngayScada, '/') !== false
                    ? date('Y-m-d', strtotime(str_replace('/', '-', $ngayScada)))
                    : $ngayScada;
                if ($ngayISO === $ngay) { $scadaData = $d; break; }
            }
        }
        return $scadaData;
    }
}