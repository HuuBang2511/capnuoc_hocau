<?php

namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "network_ongtruyendan".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $tinhtrang
 * @property string|null $vatlieu
 * @property int|null $coong
 * @property string|null $mavattu
 * @property string|null $ngaylapdat
 * @property string|null $congtrinh
 * @property string|null $dvtk
 * @property string|null $dvtc
 * @property string|null $bvhc
 * @property string|null $ghichu
 * @property float|null $shape_leng
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
class Ongtruyendan extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network_ongtruyendan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'lat', 'long', 'geojson', 'file_dinhkem'], 'string'],
            [['objectid', 'coong', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['objectid', 'coong', 'status', 'created_by', 'updated_by'], 'integer'],
            [['ngaylapdat', 'created_at', 'updated_at'], 'safe'],
            [['shape_leng'], 'number'],
            [['tinhtrang', 'vatlieu'], 'string', 'max' => 10],
            [['mavattu'], 'string', 'max' => 25],
            [['congtrinh', 'dvtk', 'dvtc', 'bvhc'], 'string', 'max' => 50],
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
            'objectid' => 'Objectid',
            'tinhtrang' => 'Tinhtrang',
            'vatlieu' => 'Vatlieu',
            'coong' => 'Coong',
            'mavattu' => 'Mavattu',
            'ngaylapdat' => 'Ngaylapdat',
            'congtrinh' => 'Congtrinh',
            'dvtk' => 'Dvtk',
            'dvtc' => 'Dvtc',
            'bvhc' => 'Bvhc',
            'ghichu' => 'Ghichu',
            'shape_leng' => 'Shape Leng',
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
