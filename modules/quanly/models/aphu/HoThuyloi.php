<?php

namespace app\modules\quanly\models\aphu;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "ho_thuyloi".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $tenho
 * @property string|null $dungtich
 * @property string|null $dientichma
 * @property float|null $dosautrung
 * @property float|null $shape_leng
 * @property float|null $shape_area
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status
 * @property string|null $geojson
 * @property string|null $ghichu
 */
class HoThuyloi extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ho_thuyloi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson', 'ghichu'], 'string'],
            [['objectid', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['objectid', 'created_by', 'updated_by', 'status'], 'integer'],
            [['dosautrung', 'shape_leng', 'shape_area'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['tenho', 'dungtich', 'dientichma'], 'string', 'max' => 50],
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
            'tenho' => 'Tenho',
            'dungtich' => 'Dungtich',
            'dientichma' => 'Dientichma',
            'dosautrung' => 'Dosautrung',
            'shape_leng' => 'Shape Leng',
            'shape_area' => 'Shape Area',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'geojson' => 'Geojson',
            'ghichu' => 'Ghichu',
        ];
    }
}
