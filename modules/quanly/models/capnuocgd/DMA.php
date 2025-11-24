<?php

namespace app\modules\quanly\models\capnuocgd;
use app\modules\quanly\base\QuanlyBaseModel;

use Yii;

/**
 * This is the model class for table "v2_4326_DMA".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $madma
 * @property int|null $sodaunoi
 * @property int|null $sometong
 * @property int|null $sovan
 * @property int|null $sotru
 * @property string|null $geojson
 * @property int|null $status
 */
class DMA extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v2_4326_DMA';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'sodaunoi', 'sometong', 'sovan', 'sotru', 'status'], 'default', 'value' => null],
            [['objectid', 'sodaunoi', 'sometong', 'sovan', 'sotru', 'status'], 'integer'],
            [['madma'], 'string', 'max' => 20],
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
            'objectid' => 'Objectid',
            'madma' => 'Madma',
            'sodaunoi' => 'Sodaunoi',
            'sometong' => 'Sometong',
            'sovan' => 'Sovan',
            'sotru' => 'Sotru',
            'geojson' => 'Geojson',
            'status' => 'Status',
        ];
    }
}
