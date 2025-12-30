<?php

namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "network_ghichu".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $ten
 * @property string|null $vitri
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $file_dinhkem
 */
class Ghichu extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network_ghichu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'lat', 'long', 'geojson', 'file_dinhkem'], 'string'],
            [['objectid', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['objectid', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ten', 'vitri'], 'string', 'max' => 50],
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
            'ten' => 'Ten',
            'vitri' => 'Vitri',
            'lat' => 'Lat',
            'long' => 'Long',
            'geojson' => 'Geojson',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'file_dinhkem' => 'File Dinhkem',
        ];
    }
}
