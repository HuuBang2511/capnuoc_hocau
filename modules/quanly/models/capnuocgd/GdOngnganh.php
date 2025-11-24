<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "gd_ongnganh".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
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
 * @property string|null $globalid
 * @property float|null $shape_leng
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property int|null $created_by
 * @property int|null $status
 * @property string|null $geojson
 */
class GdOngnganh extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_ongnganh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'chieudaiho', 'dosau', 'shape_leng'], 'number'],
            [['coong', 'namlapdat', 'updated_by', 'created_by', 'status'], 'default', 'value' => null],
            [['coong', 'namlapdat', 'updated_by', 'created_by', 'status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['idduongong', 'vatlieu', 'hieu', 'tinhtrang', 'madma'], 'string', 'max' => 50],
            [['ngaylapdat'], 'string', 'max' => 24],
            [['dbdonghonu'], 'string', 'max' => 11],
            [['diachi', 'globalid'], 'string', 'max' => 254],
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
            'idduongong' => 'ID',
            'chieudaiho' => 'Chiều dài',
            'vatlieu' => 'Vật liệu',
            'hieu' => 'Hiệu',
            'ngaylapdat' => 'Ngày lắp đặt',
            'tinhtrang' => 'Tình trạng',
            'coong' => 'Cống',
            'dbdonghonu' => 'Dbdonghonu',
            'madma' => 'Madma',
            'diachi' => 'Địa chỉ',
            'ghichu' => 'Ghi chú',
            'namlapdat' => 'Năm',
            'dosau' => 'Độ sâu',
            'globalid' => 'Globalid',
            'shape_leng' => 'Chiều dài',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
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
