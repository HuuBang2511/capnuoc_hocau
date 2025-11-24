<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "gd_hamkythuat".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $idhamkythu
 * @property string|null $loaiham
 * @property string|null $tenhamkyth
 * @property string|null $namlapdat
 * @property string|null $kichthuoch
 * @property string|null $tinhtrangh
 * @property string|null $donviquanl
 * @property int|null $soluongnap
 * @property string|null $vatlieunap
 * @property string|null $madma
 * @property float|null $dosau
 * @property float|null $docao
 * @property string|null $ghichu
 * @property string|null $donvithiet
 * @property string|null $donvithico
 * @property string|null $globalid
 * @property float|null $shape_leng
 * @property float|null $shape_area
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $status
 * @property string|null $geojson
 */
class GdHamkythuat extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_hamkythuat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'dosau', 'docao', 'shape_leng', 'shape_area'], 'number'],
            [['soluongnap', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['soluongnap', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['idhamkythu', 'loaiham', 'tinhtrangh', 'donviquanl', 'vatlieunap', 'madma'], 'string', 'max' => 50],
            [['tenhamkyth'], 'string', 'max' => 67],
            [['namlapdat'], 'string', 'max' => 4],
            [['kichthuoch'], 'string', 'max' => 20],
            [['ghichu'], 'string', 'max' => 200],
            [['donvithiet', 'donvithico'], 'string', 'max' => 100],
            [['globalid'], 'string', 'max' => 254],
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
            'idhamkythu' => 'ID hầm',
            'loaiham' => 'Loại',
            'tenhamkyth' => 'Tên',
            'namlapdat' => 'Năm',
            'kichthuoch' => 'Kích thước',
            'tinhtrangh' => 'Tình trạng',
            'donviquanl' => 'Đơn vị quản lý',
            'soluongnap' => 'Số lượng nắp',
            'vatlieunap' => 'Vật liệu nắp',
            'madma' => 'Mã DMA',
            'dosau' => 'Độ sâu',
            'docao' => 'Độ cao',
            'ghichu' => 'Ghi chú',
            'donvithiet' => 'Đơn vị thiết kế',
            'donvithico' => 'Đơn vị thi công',
            'globalid' => 'Globalid',
            'shape_leng' => 'Chu vi',
            'shape_area' => 'Diện tích',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
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
