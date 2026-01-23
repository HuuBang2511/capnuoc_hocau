<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
// Import tất cả các model quản lý
use app\modules\quanly\models\hocau\Nhamaynuoc;
use app\modules\quanly\models\hocau\Ongtruyendan;
use app\modules\quanly\models\hocau\Ongphanphoi;
use app\modules\quanly\models\hocau\Donghotong;
use app\modules\quanly\models\hocau\Donghonhamay;
use app\modules\quanly\models\hocau\Van;
use app\modules\quanly\models\hocau\Moinoi;
use app\modules\quanly\models\hocau\Hamkythuat;
use app\modules\quanly\models\hocau\Cocmoc;
use app\modules\quanly\models\hocau\Suco;

// Import danh mục cho biểu đồ
use app\modules\quanly\models\danhmuc\DmLoaiong;
use app\modules\quanly\models\danhmuc\DmSucoNguyennhan;
use app\modules\quanly\models\danhmuc\DmHieudongho;
use app\modules\quanly\models\danhmuc\DmTinhtrang;
use app\modules\quanly\models\danhmuc\DmLoaiham;
use app\modules\quanly\models\danhmuc\DmLoaimoinoi;

use Yii;
use yii\helpers\Url;

class DashboardController extends QuanlyBaseController
{
    public function actionIndex()
    {
        // --- PHẦN 1: 10 ĐỐI TƯỢNG QUẢN LÝ (2 HÀNG x 5 CỘT) ---
        
        // 1. Nhà máy nước
        $cntNhaMay = Nhamaynuoc::find()->count();
        $urlNhaMay = Url::to(['hocau/nhamaynuoc/index']);

        // 2. Ống truyền dẫn (Tính theo KM)
        $lenTruyenDan = Ongtruyendan::find()->sum('shape_leng') ?? 0;
        $lenTruyenDan = round($lenTruyenDan / 1000, 2); 
        $urlTruyenDan = Url::to(['hocau/ongtruyendan/index']);

        // 3. Ống phân phối (Tính theo KM)
        $lenPhanPhoi = Ongphanphoi::find()->sum('shape_leng') ?? 0;
        $lenPhanPhoi = round($lenPhanPhoi / 1000, 2);
        $urlPhanPhoi = Url::to(['hocau/ongphanphoi/index']);

        // 4. Đồng hồ tổng
        $cntDHTong = Donghotong::find()->count();
        $urlDHTong = Url::to(['hocau/donghotong/index']);

        // 5. Đồng hồ khách hàng (Nhà máy)
        $cntDHKhuVuc = Donghonhamay::find()->count();
        $urlDHKhuVuc = Url::to(['hocau/donghonhamay/index']);

        // 6. Van mạng lưới
        $cntVan = Van::find()->count();
        $urlVan = Url::to(['hocau/van/index']);

        // 7. Mối nối
        $cntMoiNoi = Moinoi::find()->count();
        $urlMoiNoi = Url::to(['hocau/moinoi/index']);

        // 8. Hầm kỹ thuật
        $cntHam = Hamkythuat::find()->count();
        $urlHam = Url::to(['hocau/hamkythuat/index']);

        // 9. Cọc mốc
        $cntCoc = Cocmoc::find()->count();
        $urlCoc = Url::to(['hocau/cocmoc/index']);

        // 10. Sự cố (Đang hoạt động)
        $cntSuCo = Suco::find()->where(['<>', 'status', 1])->count();
        $urlSuCo = Url::to(['hocau/suco/index', 'SucoSearch[status]' => 2]); // Giả sử status 2 là đang xử lý

        // --- PHẦN 2: DỮ LIỆU BIỂU ĐỒ (ARRAYS PARALLEL CHO CHARTJS) ---

        // CHART 1: VẬT LIỆU ỐNG PHÂN PHỐI
        $dmLoaiOng = DmLoaiong::find()->all();
        $pipeLabels = []; $pipeValues = []; $pipeIds = [];
        foreach ($dmLoaiOng as $loai) {
            $count = Ongphanphoi::find()->where(['loaiong_id' => $loai->id])->count();
            if ($count > 0) {
                $pipeLabels[] = $loai->ten;
                $pipeValues[] = $count;
                $pipeIds[] = $loai->id;
            }
        }

        // CHART 2: THỊ PHẦN ĐỒNG HỒ KHÁCH HÀNG
        $dmDongHo = DmHieudongho::find()->all();
        $meterLabels = []; $meterValues = []; $meterIds = [];
        foreach($dmDongHo as $hieu){
            $cnt = Donghonhamay::find()->where(['hieudongho_id' => $hieu->id])->count();
            if($cnt > 0){
                $meterLabels[] = $hieu->ten;
                $meterValues[] = $cnt;
                $meterIds[] = $hieu->id;
            }
        }

        // CHART 3: NGUYÊN NHÂN SỰ CỐ
        $dmNguyenNhan = DmSucoNguyennhan::find()->all();
        $incidentLabels = []; $incidentValues = []; $incidentIds = [];
        foreach($dmNguyenNhan as $nn){
            $cnt = Suco::find()->where(['nguyennhansuco_id' => $nn->id])->count();
            if($cnt > 0){
                $incidentLabels[] = $nn->ten;
                $incidentValues[] = $cnt;
                $incidentIds[] = $nn->id;
            }
        }

        // CHART 4: TÌNH TRẠNG VAN
        $dmTinhTrang = DmTinhtrang::find()->all();
        $valveLabels = []; $valveValues = []; $valveIds = [];
        foreach($dmTinhTrang as $tt){
            $cnt = Van::find()->where(['tinhtrang_id' => $tt->id])->count();
            if($cnt > 0){
                $valveLabels[] = $tt->ten;
                $valveValues[] = $cnt;
                $valveIds[] = $tt->id;
            }
        }

        // CHART 5: LOẠI HẦM KỸ THUẬT (MỚI)
        $dmLoaiHam = DmLoaiham::find()->all();
        $hamLabels = []; $hamValues = []; $hamIds = [];
        foreach($dmLoaiHam as $lh){
            $cnt = Hamkythuat::find()->where(['loaiham_id' => $lh->id])->count();
            if($cnt > 0){
                $hamLabels[] = $lh->ten;
                $hamValues[] = $cnt;
                $hamIds[] = $lh->id;
            }
        }

        // CHART 6: LOẠI MỐI NỐI (MỚI)
        $dmLoaiMoiNoi = DmLoaimoinoi::find()->all();
        $moinoiLabels = []; $moinoiValues = []; $moinoiIds = [];
        foreach($dmLoaiMoiNoi as $mn){
            $cnt = Moinoi::find()->where(['loaimoinoi_id' => $mn->id])->count();
            if($cnt > 0){
                $moinoiLabels[] = $mn->ten;
                $moinoiValues[] = $cnt;
                $moinoiIds[] = $mn->id;
            }
        }

        // Dữ liệu bảng sự cố mới nhất
        $recentIncidents = Suco::find()
            ->with(['nguyennhansuco', 'loaisuco'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(8) // Tăng lên 8 dòng cho đẹp
            ->all();

        return $this->render('index1', [
            // KPI Data & Links (10 Objects)
            'cntNhaMay' => $cntNhaMay, 'urlNhaMay' => $urlNhaMay,
            'lenTruyenDan' => $lenTruyenDan, 'urlTruyenDan' => $urlTruyenDan,
            'lenPhanPhoi' => $lenPhanPhoi, 'urlPhanPhoi' => $urlPhanPhoi,
            'cntDHTong' => $cntDHTong, 'urlDHTong' => $urlDHTong,
            'cntDHKhuVuc' => $cntDHKhuVuc, 'urlDHKhuVuc' => $urlDHKhuVuc,
            'cntVan' => $cntVan, 'urlVan' => $urlVan,
            'cntMoiNoi' => $cntMoiNoi, 'urlMoiNoi' => $urlMoiNoi,
            'cntHam' => $cntHam, 'urlHam' => $urlHam,
            'cntCoc' => $cntCoc, 'urlCoc' => $urlCoc,
            'cntSuCo' => $cntSuCo, 'urlSuCo' => $urlSuCo,
            
            // Chart Data (JSON)
            'pipeLabels' => json_encode($pipeLabels), 'pipeValues' => json_encode($pipeValues), 'pipeIds' => json_encode($pipeIds),
            'meterLabels' => json_encode($meterLabels), 'meterValues' => json_encode($meterValues), 'meterIds' => json_encode($meterIds),
            'incidentLabels' => json_encode($incidentLabels), 'incidentValues' => json_encode($incidentValues), 'incidentIds' => json_encode($incidentIds),
            'valveLabels' => json_encode($valveLabels), 'valveValues' => json_encode($valveValues), 'valveIds' => json_encode($valveIds),
            'hamLabels' => json_encode($hamLabels), 'hamValues' => json_encode($hamValues), 'hamIds' => json_encode($hamIds),
            'moinoiLabels' => json_encode($moinoiLabels), 'moinoiValues' => json_encode($moinoiValues), 'moinoiIds' => json_encode($moinoiIds),

            'recentIncidents' => $recentIncidents
        ]);
    }
}