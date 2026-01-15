<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\hocau\Donghonhamay;
use app\modules\quanly\models\hocau\Donghotong;
use app\modules\quanly\models\hocau\Nhamaynuoc;
use app\modules\quanly\models\hocau\Ongphanphoi;
use app\modules\quanly\models\hocau\Ongtruyendan;
use app\modules\quanly\models\hocau\Suco;
use app\modules\quanly\models\hocau\Van;
use Yii;
use yii\db\Expression;

class DashboardController extends QuanlyBaseController
{
    public function actionIndex()
    {
        // 1. THỐNG KÊ TỔNG QUAN (CARDS)
        
        // Tổng chiều dài mạng lưới (km) - Giả sử shape_leng đơn vị là mét
        $lenTruyenDan = Ongtruyendan::find()->sum('shape_leng') ?? 0;
        $lenPhanPhoi = Ongphanphoi::find()->sum('shape_leng') ?? 0;
        $totalLengthKm = round(($lenTruyenDan + $lenPhanPhoi) / 1000, 2);

        // Số lượng khách hàng (Đồng hồ nhà máy)
        $totalCustomers = Donghonhamay::find()->count();

        // Sự cố chưa xử lý (Status = 0 hoặc null - tùy quy định logic của bạn)
        $activeIncidents = Suco::find()
            ->where(['or', ['status' => 0], ['status' => null]])
            ->count();

        // Tổng số Van
        $totalValves = Van::find()->count();

        // 2. BIỂU ĐỒ TRÒN: TỈ LỆ CÁC LOẠI SỰ CỐ (Dựa trên bảng Suco và loaisuco_id)
        // Lưu ý: Cần join sang bảng DmSucoLoai để lấy tên, ở đây tôi demo lấy ID và count
        $incidentStats = Suco::find()
            ->select(['loaisuco_id', 'COUNT(*) AS cnt'])
            ->groupBy(['loaisuco_id'])
            ->asArray()
            ->all();
        
        // Chuẩn bị data cho ChartJS
        $incidentLabels = []; 
        $incidentData = [];
        foreach ($incidentStats as $item) {
            // Thực tế bạn nên query lấy tên loại sự cố, ở đây tôi dùng ID tạm
            $incidentLabels[] = "Loại SC " . ($item['loaisuco_id'] ?? 'Khác'); 
            $incidentData[] = $item['cnt'];
        }

        // 3. BIỂU ĐỒ CỘT: TÌNH TRẠNG VAN (Tốt, Hỏng, Cần bảo trì...)
        // Giả sử tinhtrang_id: 1=Tốt, 2=Hỏng...
        $valveStats = Van::find()
            ->select(['tinhtrang_id', 'COUNT(*) AS cnt'])
            ->groupBy(['tinhtrang_id'])
            ->asArray()
            ->all();

        $valveLabels = [];
        $valveData = [];
        foreach ($valveStats as $item) {
            $valveLabels[] = "Tình trạng " . ($item['tinhtrang_id'] ?? 'N/A');
            $valveData[] = $item['cnt'];
        }

        // 4. DANH SÁCH SỰ CỐ MỚI NHẤT (Top 5)
        $recentIncidents = Suco::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('index1', [
            'totalLengthKm' => $totalLengthKm,
            'totalCustomers' => $totalCustomers,
            'activeIncidents' => $activeIncidents,
            'totalValves' => $totalValves,
            'incidentLabels' => json_encode($incidentLabels),
            'incidentData' => json_encode($incidentData),
            'valveLabels' => json_encode($valveLabels),
            'valveData' => json_encode($valveData),
            'recentIncidents' => $recentIncidents
        ]);
    }
}