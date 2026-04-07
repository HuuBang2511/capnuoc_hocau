<?php
namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;

class NkNuocThaiSh extends QuanlyBaseModel
{
    // QCVN 14:2008/BTNMT (nước thải sinh hoạt) cột B
    const QCVN = [
        'ph'       => ['min'=>5.0,   'max'=>9.0,    'unit'=>''],
        'tss'      => ['min'=>0,     'max'=>50.0,   'unit'=>'mg/L'],
        'amoni'    => ['min'=>0,     'max'=>5.0,    'unit'=>'mg/L'],
        'nitrat'   => ['min'=>0,     'max'=>30.0,   'unit'=>'mg/L'],
        'coliform' => ['min'=>0,     'max'=>3000,   'unit'=>'MPN/100mL'],
    ];

    public static function tableName() { return 'nk_nuoc_thai_sh'; }

    public function rules()
    {
        return [
            [['ngay'], 'required'],
            ['ngay', 'date', 'format'=>'php:Y-m-d'],
            ['ph',       'number', 'min'=>0,   'max'=>14],
            ['tss',      'number', 'min'=>0,   'max'=>9999],
            ['amoni',    'number', 'min'=>0,   'max'=>9999],
            ['nitrat',   'number', 'min'=>0,   'max'=>9999],
            ['coliform', 'number', 'min'=>0,   'max'=>9999999],
            [['ghi_chu','nguoi_nhap'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ngay'     => 'Ngày',
            'ph'       => 'pH (5–9)',
            'tss'      => 'TSS (≤50 mg/L)',
            'amoni'    => 'Amoni (≤5 mg/L)',
            'nitrat'   => 'Nitrat (≤30 mg/L)',
            'coliform' => 'Coliform (≤3.000 MPN/100mL)',
            'ghi_chu'  => 'Ghi chú',
        ];
    }

    public function getStatus($field)
    {
        if (!isset(self::QCVN[$field]) || $this->$field === null) return 'ok';
        $q = self::QCVN[$field];
        $v = (float)$this->$field;
        if ($v < $q['min'] || $v > $q['max']) return 'bad';
        $range = $q['max'] - $q['min'];
        if ($range > 0 && $v > $q['max'] - $range * 0.1) return 'warn';
        return 'ok';
    }
}