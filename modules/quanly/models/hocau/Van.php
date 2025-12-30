<?php

namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "network_van".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid_1
 * @property int|null $objectid
 * @property string|null $tinhtrang
 * @property string|null $mavan
 * @property string|null $vitri
 * @property int|null $covan
 * @property string|null $loaivan
 * @property int|null $cochiakhoa
 * @property int|null $sovong
 * @property string|null $chieudong
 * @property string|null $dongmo
 * @property string|null $ngaylapdat
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
class Van extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network_van';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'lat', 'long', 'geojson', 'file_dinhkem'], 'string'],
            [['objectid_1', 'objectid', 'covan', 'cochiakhoa', 'sovong', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['objectid_1', 'objectid', 'covan', 'cochiakhoa', 'sovong', 'status', 'created_by', 'updated_by'], 'integer'],
            [['ngaylapdat', 'created_at', 'updated_at'], 'safe'],
            [['tinhtrang'], 'string', 'max' => 10],
            [['mavan'], 'string', 'max' => 25],
            [['vitri', 'loaivan'], 'string', 'max' => 50],
            [['chieudong', 'dongmo'], 'string', 'max' => 1],
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
            'mavan' => 'Mavan',
            'vitri' => 'Vitri',
            'covan' => 'Covan',
            'loaivan' => 'Loaivan',
            'cochiakhoa' => 'Cochiakhoa',
            'sovong' => 'Sovong',
            'chieudong' => 'Chieudong',
            'dongmo' => 'Dongmo',
            'ngaylapdat' => 'Ngaylapdat',
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
