<?php

namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "network_cocmoc".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $loai
 * @property string|null $vitri
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $update_by
 * @property string|null $geojson
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $file_dinhkem
 */
class Cocmoc extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network_cocmoc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson', 'lat', 'long', 'file_dinhkem'], 'string'],
            [['objectid', 'status', 'created_by', 'update_by'], 'default', 'value' => null],
            [['objectid', 'status', 'created_by', 'update_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['loai', 'vitri'], 'string', 'max' => 50],
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
            'vitri' => 'Vitri',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'update_by' => 'Update By',
            'geojson' => 'Geojson',
            'lat' => 'Lat',
            'long' => 'Long',
            'file_dinhkem' => 'File Dinhkem',
        ];
    }
}
