<?php

namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "network_hamkythuat".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid_1
 * @property int|null $objectid
 * @property string|null $tinhtrang
 * @property string|null $maham
 * @property string|null $loaiham
 * @property string|null $kichthuoc
 * @property string|null $vatlieu
 * @property string|null $sonap
 * @property string|null $vitri
 * @property string|null $ngaylapdat
 * @property string|null $dvtk
 * @property string|null $dvtc
 * @property string|null $bvhc
 * @property string|null $ghichu
 * @property float|null $shape_leng
 * @property float|null $shape_area
 * @property string|null $geojson
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $file_dinhkem
 */
class Hamkythuat extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network_hamkythuat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson', 'file_dinhkem'], 'string'],
            [['objectid_1', 'objectid', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['objectid_1', 'objectid', 'status', 'created_by', 'updated_by'], 'integer'],
            [['ngaylapdat', 'created_at', 'updated_at'], 'safe'],
            [['shape_leng', 'shape_area'], 'number'],
            [['tinhtrang', 'kichthuoc', 'vatlieu', 'sonap', 'vitri', 'dvtk', 'dvtc', 'bvhc'], 'string', 'max' => 50],
            [['maham'], 'string', 'max' => 25],
            [['loaiham'], 'string', 'max' => 10],
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
            'maham' => 'Maham',
            'loaiham' => 'Loaiham',
            'kichthuoc' => 'Kichthuoc',
            'vatlieu' => 'Vatlieu',
            'sonap' => 'Sonap',
            'vitri' => 'Vitri',
            'ngaylapdat' => 'Ngaylapdat',
            'dvtk' => 'Dvtk',
            'dvtc' => 'Dvtc',
            'bvhc' => 'Bvhc',
            'ghichu' => 'Ghichu',
            'shape_leng' => 'Shape Leng',
            'shape_area' => 'Shape Area',
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
