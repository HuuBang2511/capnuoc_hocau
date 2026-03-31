<?php
namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;

class NkGiaoCa extends QuanlyBaseModel
{
    public static function tableName() { return 'nk_giao_ca'; }

    public function rules()
    {
        return [
            [['ngay','ca'], 'required'],
            ['ca', 'in', 'range'=>[1,2]],
            ['ngay', 'date', 'format'=>'php:Y-m-d'],
            [['nuoc_cap_dau','nuoc_cap_cuoi','nuoc_tho_dau','nuoc_tho_cuoi',
              'dien_nha_may_dau','dien_nha_may_cuoi',
              'dien_tram_bom_dau','dien_tram_bom_cuoi'], 'number', 'min'=>0],
            [['pac_kg','chlorine_kg','polymer_kg'], 'number', 'min'=>0],
            [['ns_ph_dau','ns_ph_cuoi'], 'number', 'min'=>0, 'max'=>14],
            [['ns_ntu_dau','ns_ntu_cuoi','clo_du_dau','clo_du_cuoi'], 'number', 'min'=>0],
            [['bom_nt_chay','bom_th_chay','bom_khi_chay'], 'string', 'max'=>30],
            [['su_co','bien_phap','ghi_chu'], 'string'],
            [['nhan_vien_giao','nhan_vien_nhan','nguoi_nhap'], 'string', 'max'=>100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ngay'=>'Ngày', 'ca'=>'Ca',
            'nuoc_cap_dau'=>'Nước cấp đầu ca (m³)',
            'nuoc_cap_cuoi'=>'Nước cấp cuối ca (m³)',
            'nuoc_tho_dau'=>'Nước thô đầu ca (m³)',
            'nuoc_tho_cuoi'=>'Nước thô cuối ca (m³)',
            'bom_nt_chay'=>'Bơm NT hoạt động',
            'bom_th_chay'=>'Bơm TH hoạt động',
            'bom_khi_chay'=>'Bơm khí hoạt động',
            'dien_nha_may_dau'=>'Điện NM đầu (KWh)',
            'dien_nha_may_cuoi'=>'Điện NM cuối (KWh)',
            'dien_tram_bom_dau'=>'Điện TB đầu (KWh)',
            'dien_tram_bom_cuoi'=>'Điện TB cuối (KWh)',
            'ns_ph_dau'=>'pH NS đầu ca', 'ns_ntu_dau'=>'NTU NS đầu ca',
            'clo_du_dau'=>'Clo dư đầu ca', 'ns_ph_cuoi'=>'pH NS cuối ca',
            'ns_ntu_cuoi'=>'NTU NS cuối ca', 'clo_du_cuoi'=>'Clo dư cuối ca',
            'pac_kg'=>'PAC (kg)', 'chlorine_kg'=>'Chlorine (kg)', 'polymer_kg'=>'Polymer (kg)',
            'su_co'=>'Sự cố', 'bien_phap'=>'Biện pháp xử lý', 'ghi_chu'=>'Ghi chú',
            'nhan_vien_giao'=>'NV giao ca', 'nhan_vien_nhan'=>'NV nhận ca',
        ];
    }

    public function getSanLuongCap()
    {
        if ($this->nuoc_cap_cuoi && $this->nuoc_cap_dau)
            return $this->nuoc_cap_cuoi - $this->nuoc_cap_dau;
        return null;
    }
}