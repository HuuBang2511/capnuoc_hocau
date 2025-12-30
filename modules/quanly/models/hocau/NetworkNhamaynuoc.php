<?php

namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "network_nhamaynuoc".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $loai
 * @property float|null $shape_leng
 * @property float|null $shape_area
 * @property string|null $file_dinhkem
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class NetworkNhamaynuoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network_nhamaynuoc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'file_dinhkem', 'lat', 'long', 'geojson'], 'string'],
            [['objectid', 'shape_leng', 'shape_area'], 'number'],
            [['status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['loai'], 'string', 'max' => 50],
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
            'loai' => 'Loai',
            'shape_leng' => 'Shape Leng',
            'shape_area' => 'Shape Area',
            'file_dinhkem' => 'File Dinhkem',
            'lat' => 'Lat',
            'long' => 'Long',
            'geojson' => 'Geojson',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
