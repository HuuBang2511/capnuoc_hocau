<?php

namespace app\modules\quanly\models\aphu;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "ong_phanphoi".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property float|null $chieudaiho
 * @property string|null $vatlieu
 * @property string|null $hieu
 * @property int|null $coong
 * @property string|null $tinhtrang
 * @property string|null $madma
 * @property string|null $vitrilapda
 * @property string|null $ghichu
 * @property int|null $namlapdat
 * @property float|null $dosau
 * @property string|null $loaicongtr
 * @property string|null $tencongtri
 * @property string|null $donvithiet
 * @property string|null $donvithico
 * @property string|null $tenduong
 * @property string|null $sohem
 * @property string|null $diemdau
 * @property string|null $diemcuoi
 * @property string|null $tuyen
 * @property float|null $cachletrai
 * @property float|null $cachlephai
 * @property string|null $maphuong
 * @property string|null $maquan
 * @property string|null $bvhc
 * @property string|null $danhdau
 * @property float|null $shape_leng
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $status
 * @property string|null $geojson
 */
class OngPhanphoi extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ong_phanphoi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'coong', 'namlapdat', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['objectid', 'coong', 'namlapdat', 'created_by', 'updated_by', 'status'], 'integer'],
            [['chieudaiho', 'dosau', 'cachletrai', 'cachlephai', 'shape_leng'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['vatlieu', 'hieu', 'tinhtrang', 'madma', 'sohem', 'tuyen', 'maphuong', 'maquan', 'danhdau'], 'string', 'max' => 50],
            [['vitrilapda'], 'string', 'max' => 254],
            [['ghichu', 'donvithiet', 'donvithico', 'bvhc'], 'string', 'max' => 200],
            [['loaicongtr'], 'string', 'max' => 10],
            [['tencongtri'], 'string', 'max' => 250],
            [['tenduong', 'diemdau', 'diemcuoi'], 'string', 'max' => 100],
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
            'chieudaiho' => 'Chieudaiho',
            'vatlieu' => 'Vatlieu',
            'hieu' => 'Hieu',
            'coong' => 'Coong',
            'tinhtrang' => 'Tinhtrang',
            'madma' => 'Madma',
            'vitrilapda' => 'Vitrilapda',
            'ghichu' => 'Ghichu',
            'namlapdat' => 'Namlapdat',
            'dosau' => 'Dosau',
            'loaicongtr' => 'Loaicongtr',
            'tencongtri' => 'Tencongtri',
            'donvithiet' => 'Donvithiet',
            'donvithico' => 'Donvithico',
            'tenduong' => 'Tenduong',
            'sohem' => 'Sohem',
            'diemdau' => 'Diemdau',
            'diemcuoi' => 'Diemcuoi',
            'tuyen' => 'Tuyen',
            'cachletrai' => 'Cachletrai',
            'cachlephai' => 'Cachlephai',
            'maphuong' => 'Maphuong',
            'maquan' => 'Maquan',
            'bvhc' => 'Bvhc',
            'danhdau' => 'Danhdau',
            'shape_leng' => 'Shape Leng',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
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
