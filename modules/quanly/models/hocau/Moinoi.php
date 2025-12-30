<?php

namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "network_moinoi".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid_1
 * @property int|null $objectid
 * @property string|null $tinhtrang
 * @property string|null $loaimoinoi
 * @property string|null $kichthuoc
 * @property float|null $x
 * @property float|null $y
 * @property float|null $z
 * @property string|null $vattu
 * @property string|null $mavitri
 * @property string|null $ghichu
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
class Moinoi extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network_moinoi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'lat', 'long', 'geojson', 'file_dinhkem'], 'string'],
            [['objectid_1', 'objectid', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['objectid_1', 'objectid', 'status', 'created_by', 'updated_by'], 'integer'],
            [['x', 'y', 'z'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['tinhtrang'], 'string', 'max' => 10],
            [['loaimoinoi', 'kichthuoc', 'mavitri'], 'string', 'max' => 50],
            [['vattu'], 'string', 'max' => 20],
            [['ghichu'], 'string', 'max' => 200],
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
            'objectid_1' => 'Objectid 1',
            'objectid' => 'Objectid',
            'tinhtrang' => 'Tinhtrang',
            'loaimoinoi' => 'Loaimoinoi',
            'kichthuoc' => 'Kichthuoc',
            'x' => 'X',
            'y' => 'Y',
            'z' => 'Z',
            'vattu' => 'Vattu',
            'mavitri' => 'Mavitri',
            'ghichu' => 'Ghichu',
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
