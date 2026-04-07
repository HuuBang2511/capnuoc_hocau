<?php
namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;

class NkChatLuongGio extends QuanlyBaseModel
{
    // QCVN 01-1:2018/BYT — nước sạch
    const QCVN = [
        'ns_ph'  => ['min'=>6.5,  'max'=>8.5,  'unit'=>''],
        'ns_ntu' => ['min'=>0,    'max'=>2.0,  'unit'=>'NTU'],
        'clo_du' => ['min'=>0.2,  'max'=>1.0,  'unit'=>'mg/L'],
    ];

    public static function tableName() { return 'nk_chat_luong_gio'; }

    public function rules()
    {
        return [
            [['thoi_gian','ca'], 'required'],
            ['ca', 'in', 'range'=>[1,2]],
            // Cột cơ bản
            [['ns_ph','nt_ph','nl1_ph','nl2_ph','ngoai_ho_ph'], 'number', 'min'=>0, 'max'=>14],
            [['ns_ntu','nt_ntu','nl1_ntu','nl2_ntu',
              'ho_xi_phong_1_ntu','ho_xi_phong_2_ntu','ngoai_ho_ntu'], 'number', 'min'=>0],
            ['clo_du', 'number', 'min'=>0, 'max'=>5],
            // Cột mở rộng — Clor dư các vị trí
            [['muong_pu_thu_hoi','muong_lang_nl1','muong_pu_ns','dau_be_ns'], 'number', 'min'=>0, 'max'=>5],
            ['pac_ty_trong', 'number', 'min'=>0],
            [['nguoi_nhap','ghi_chu'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'thoi_gian'         => 'Thời gian',
            'ca'                => 'Ca',
            'ns_ph'             => 'NS - pH',
            'ns_ntu'            => 'NS - NTU',
            'nt_ph'             => 'NT - pH',
            'nt_ntu'            => 'NT - NTU',
            'nl1_ph'            => 'Lắng 1 - pH',
            'nl1_ntu'           => 'Lắng 1 - NTU',
            'nl2_ph'            => 'Lắng 2 - pH',
            'nl2_ntu'           => 'Lắng 2 - NTU',
            'clo_du'            => 'Clo dư (mg/L)',
            // Mở rộng
            'ngoai_ho_ph'       => 'Ngoài hồ - pH',
            'ngoai_ho_ntu'      => 'Ngoài hồ - NTU',
            'muong_pu_thu_hoi'  => 'Mương PƯ (thu hồi) - Clor dư',
            'muong_lang_nl1'    => 'Mương lắng NL1 - Clor dư',
            'muong_pu_ns'       => 'Mương PƯ NS - Clor dư',
            'dau_be_ns'         => 'Đầu bể NS - Clor dư',
            'ho_xi_phong_1_ntu' => 'Hố xi phông 1 - NTU',
            'ho_xi_phong_2_ntu' => 'Hố xi phông 2 - NTU',
            'pac_ty_trong'      => 'PAC pha - Tỷ trọng',
            'nguoi_nhap'        => 'Người nhập',
            'ghi_chu'           => 'Ghi chú',
        ];
    }

    public function getStatus($field)
    {
        if (!isset(self::QCVN[$field]) || $this->$field === null) return 'ok';
        $q = self::QCVN[$field];
        $v = (float)$this->$field;
        if ($v < $q['min'] || $v > $q['max']) return 'bad';
        $range = $q['max'] - $q['min'];
        if ($range > 0 && ($v < $q['min'] + $range*0.05 || $v > $q['max'] - $range*0.05)) return 'warn';
        return 'ok';
    }
}