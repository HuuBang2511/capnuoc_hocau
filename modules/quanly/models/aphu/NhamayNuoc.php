<?php

namespace app\modules\quanly\models\aphu;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "nhamay_nuoc".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $congnghexl
 * @property int|null $namxd
 * @property string|null $nguon
 * @property string|null $congsuat_1
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 * @property int $status
 * @property string|null $geojson
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $ghichu
 */
class NhamayNuoc extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nhamay_nuoc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson', 'lat', 'long', 'ghichu'], 'string'],
            [['objectid', 'namxd', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['objectid', 'namxd', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['congnghexl', 'nguon'], 'string', 'max' => 100],
            [['congsuat_1'], 'string', 'max' => 50],
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
            'congnghexl' => 'Congnghexl',
            'namxd' => 'Namxd',
            'nguon' => 'Nguon',
            'congsuat_1' => 'Congsuat 1',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'geojson' => 'Geojson',
            'lat' => 'Lat',
            'long' => 'Long',
            'ghichu' => 'Ghichu',
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Kiểm tra xem có lat và long
            if ($this->lat !== null && $this->long !== null) {
                // Tạo geom từ lat và long
                $this->geom = new \yii\db\Expression("ST_GeomFromText('POINT({$this->long} {$this->lat})')");
            }
            return true;
        }
        return false;
    }
}
