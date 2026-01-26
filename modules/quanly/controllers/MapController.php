<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use yii\web\Controller;
use yii\web\Response;

// Import các Models dữ liệu
use app\modules\quanly\models\hocau\Ongtruyendan;
use app\modules\quanly\models\hocau\Ongphanphoi;
use app\modules\quanly\models\hocau\Van;
use app\modules\quanly\models\hocau\Suco;
use app\modules\quanly\models\hocau\Donghonhamay;

// Import các Models danh mục dùng để lọc
use app\modules\quanly\models\danhmuc\DmLoaiong;
use app\modules\quanly\models\danhmuc\DmHieudongho;
use app\modules\quanly\models\danhmuc\DmTinhtrang;
use app\modules\quanly\models\danhmuc\DmSucoNguyennhan;
use app\modules\quanly\models\danhmuc\DmLoaiham;
use app\modules\quanly\models\danhmuc\DmLoaimoinoi;

class MapController extends QuanlyBaseController
{
    /**
     * Hiển thị bản đồ GIS Full-screen
     */
    public function actionHocau()
    {
        $this->layout = false;
        
        // Lấy danh mục để nạp vào bộ lọc JS
        $filterData = [
            'loaiong' => DmLoaiong::find()->select(['id', 'ten'])->asArray()->all(),
            'hieudongho' => DmHieudongho::find()->select(['id', 'ten'])->asArray()->all(),
            'tinhtrang' => DmTinhtrang::find()->select(['id', 'ten'])->asArray()->all(),
            'nguyennhan' => DmSucoNguyennhan::find()->select(['id', 'ten'])->asArray()->all(),
            'loaiham' => DmLoaiham::find()->select(['id', 'ten'])->asArray()->all(),
            'loaimoinoi' => DmLoaimoinoi::find()->select(['id', 'ten'])->asArray()->all(),
        ];

        return $this->render('hocau', [
            'filterData' => $filterData
        ]);
    }

    /**
     * API trả về dữ liệu thống kê (Giữ nguyên như trước)
     */
    public function actionThongkeApi()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        // 1. Tổng quan mạng lưới
        $lenTruyenDan = round((Ongtruyendan::find()->sum('shape_leng') ?? 0) / 1000, 2);
        $lenPhanPhoi = round((Ongphanphoi::find()->sum('shape_leng') ?? 0) / 1000, 2);
        
        // 2. Thống kê số lượng
        $stats = [
            'truyendan' => $lenTruyenDan, // km
            'phanphoi'  => $lenPhanPhoi,  // km
            'van'       => (int)Van::find()->count(),
            'dongho'    => (int)Donghonhamay::find()->count(),
            'suco'      => (int)Suco::find()->where(['<>', 'status', 1])->count(), // Sự cố chưa xử lý
        ];

        // 3. Dữ liệu biểu đồ tròn: Tình trạng Van
        $vanTotal = $stats['van'];
        $vanHong = (int)Van::find()->where(['tinhtrang_id' => 2])->count(); // Giả sử 2 là hỏng
        $vanTot = $vanTotal - $vanHong;

        $chartData = [
            'labels' => ['Hoạt động tốt', 'Hư hỏng/Sự cố'],
            'data' => [$vanTot, $vanHong]
        ];

        return [
            'success' => true,
            'stats' => $stats,
            'chart_van' => $chartData
        ];
    }
}