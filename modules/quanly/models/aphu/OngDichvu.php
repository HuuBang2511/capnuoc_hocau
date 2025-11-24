<?php

namespace app\modules\quanly\models\aphu;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "ong_dichvu".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $idduongong
 * @property float|null $chieudaiho
 * @property string|null $vatlieu
 * @property string|null $hieu
 * @property string|null $ngaylapdat
 * @property string|null $tinhtrang
 * @property int|null $coong
 * @property string|null $dbdonghonu
 * @property string|null $madma
 * @property string|null $diachi
 * @property string|null $ghichu
 * @property int|null $namlapdat
 * @property float|null $dosau
 * @property string|null $created_us
 * @property string|null $created_da
 * @property string|null $last_edite
 * @property string|null $last_edi_1
 * @property string|null $globalid
 * @property int|null $enabled
 * @property string|null $bvtd
 * @property float|null $shape_leng
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status
 * @property string|null $geojson
 */
class OngDichvu extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ong_dichvu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'coong', 'namlapdat', 'enabled', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['objectid', 'coong', 'namlapdat', 'enabled', 'created_by', 'updated_by', 'status'], 'integer'],
            [['chieudaiho', 'dosau', 'shape_leng'], 'number'],
            [['ngaylapdat', 'created_da', 'last_edi_1', 'created_at', 'updated_at'], 'safe'],
            [['idduongong', 'vatlieu', 'hieu', 'tinhtrang', 'madma'], 'string', 'max' => 50],
            [['dbdonghonu'], 'string', 'max' => 11],
            [['diachi', 'created_us', 'last_edite'], 'string', 'max' => 254],
            [['ghichu'], 'string', 'max' => 200],
            [['globalid'], 'string', 'max' => 38],
            [['bvtd'], 'string', 'max' => 250],
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
            'idduongong' => 'Idduongong',
            'chieudaiho' => 'Chieudaiho',
            'vatlieu' => 'Vatlieu',
            'hieu' => 'Hieu',
            'ngaylapdat' => 'Ngaylapdat',
            'tinhtrang' => 'Tinhtrang',
            'coong' => 'Coong',
            'dbdonghonu' => 'Dbdonghonu',
            'madma' => 'Madma',
            'diachi' => 'Diachi',
            'ghichu' => 'Ghichu',
            'namlapdat' => 'Namlapdat',
            'dosau' => 'Dosau',
            'created_us' => 'Created Us',
            'created_da' => 'Created Da',
            'last_edite' => 'Last Edite',
            'last_edi_1' => 'Last Edi 1',
            'globalid' => 'Globalid',
            'enabled' => 'Enabled',
            'bvtd' => 'Bvtd',
            'shape_leng' => 'Shape Leng',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
