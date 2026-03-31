<?php
namespace app\modules\quanly\models\hocau;
use yii\db\ActiveRecord;

class NkChatLuongGio extends ActiveRecord
{
    const QCVN = [
        'ns_ph'  => ['min'=>6.5,  'max'=>8.5,  'unit'=>''],
        'ns_ntu' => ['min'=>0,    'max'=>2.0,  'unit'=>'NTU'],
        'clo_du' => ['min'=>0.2,  'max'=>1.0,  'unit'=>'mg/L'],
    ];

    public static function tableName() { return 'nk_chat_luong_gio'; }
    // Su dung connection 'db' mac dinh (xem config/db.php)
    // Neu PostgreSQL dung connection khac, sua lai o day
    // public static function getDb() { return \Yii::$app->get('ten_connection'); }

    public function rules()
    {
        return [
            [['thoi_gian','ca'], 'required'],
            ['ca', 'in', 'range'=>[1,2]],
            [['ns_ph','nt_ph','nl1_ph','nl2_ph'], 'number', 'min'=>0, 'max'=>14],
            [['ns_ntu','nt_ntu','nl1_ntu','nl2_ntu'], 'number', 'min'=>0],
            ['clo_du', 'number', 'min'=>0, 'max'=>5],
            [['nguoi_nhap','ghi_chu'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'thoi_gian'=>'Thời gian', 'ca'=>'Ca',
            'ns_ph'=>'NS - pH', 'ns_ntu'=>'NS - NTU',
            'nt_ph'=>'NT - pH', 'nt_ntu'=>'NT - NTU',
            'nl1_ph'=>'Lắng 1 - pH', 'nl1_ntu'=>'Lắng 1 - NTU',
            'nl2_ph'=>'Lắng 2 - pH', 'nl2_ntu'=>'Lắng 2 - NTU',
            'clo_du'=>'Clo dư (mg/L)',
            'nguoi_nhap'=>'Người nhập', 'ghi_chu'=>'Ghi chú',
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