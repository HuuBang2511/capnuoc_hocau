<?php

namespace app\modules\quanly\models\capnuocgd;
use app\modules\quanly\base\QuanlyBaseModel;

use Yii;

/**
 * This is the model class for table "v2_4326_ONGTRUYENDAN".
 *
 * @property int $id
 * @property string|null $geom
 * @property string|null $vatlieu
 * @property int|null $coong
 * @property int|null $namlapdat
 * @property string|null $tencongtri
 * @property string|null $donvithiet
 * @property string|null $donvithico
 * @property string|null $geojson
 * @property int|null $status
 */
class Ongtruyendan extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v2_4326_ONGTRUYENDAN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['coong', 'namlapdat', 'status'], 'default', 'value' => null],
            [['coong', 'namlapdat', 'status'], 'integer'],
            [['vatlieu'], 'string', 'max' => 50],
            [['tencongtri'], 'string', 'max' => 250],
            [['donvithiet', 'donvithico'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'geom' => 'Geom',
            'vatlieu' => 'Vật liệu',
            'coong' => 'Cỡ ống',
            'namlapdat' => 'Năm lắp đặt',
            'tencongtri' => 'Tên công trình',
            'donvithiet' => 'Đơn vị thiết kế',
            'donvithico' => 'Đơn vị thi công',
            'geojson' => 'Geojson',
            'status' => 'Status',
        ];
    }
}
