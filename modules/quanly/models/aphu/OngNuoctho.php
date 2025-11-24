<?php

namespace app\modules\quanly\models\aphu;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "ong_nuoctho".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property int|null $duongkinh
 * @property string|null $vatlieu
 * @property int|null $namlapdat
 * @property float|null $chieudai
 * @property string|null $ghichu
 * @property float|null $shape_leng
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int $status
 * @property string|null $geojson
 */
class OngNuoctho extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ong_nuoctho';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'duongkinh', 'namlapdat', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['objectid', 'duongkinh', 'namlapdat', 'created_by', 'updated_by', 'status'], 'integer'],
            [['chieudai', 'shape_leng'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['vatlieu'], 'string', 'max' => 50],
            [['ghichu'], 'string', 'max' => 100],
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
            'duongkinh' => 'Duongkinh',
            'vatlieu' => 'Vatlieu',
            'namlapdat' => 'Namlapdat',
            'chieudai' => 'Chieudai',
            'ghichu' => 'Ghichu',
            'shape_leng' => 'Shape Leng',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
            'geojson' => 'Geojson',
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->geojson !== null) {
                // Chuyển GeoJSON thành Geom
                $this->geom = new \yii\db\Expression("ST_Multi(ST_GeomFromGeoJSON(:geojson))", [':geojson' => $this->geojson]);
            }
            return true;
        }
        return false;
    }
}
