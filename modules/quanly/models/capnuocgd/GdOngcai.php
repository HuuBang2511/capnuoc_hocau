<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "gd_ongcai".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $idduongong
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
 * @property string|null $globalid
 * @property string|null $bvhc
 * @property float|null $shape_leng
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $status
 * @property string|null $geojson
 */
class GdOngcai extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_ongcai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'chieudaiho', 'dosau', 'cachletrai', 'cachlephai', 'shape_leng'], 'number'],
            [['coong', 'namlapdat', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['coong', 'namlapdat', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['idduongong', 'vatlieu', 'hieu', 'tinhtrang', 'madma', 'sohem', 'tuyen', 'maphuong', 'maquan'], 'string', 'max' => 50],
            [['vitrilapda', 'globalid'], 'string', 'max' => 254],
            [['ghichu'], 'string', 'max' => 229],
            [['loaicongtr'], 'string', 'max' => 10],
            [['tencongtri'], 'string', 'max' => 250],
            [['donvithiet', 'donvithico', 'bvhc'], 'string', 'max' => 200],
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
            'idduongong' => 'ID',
            'chieudaiho' => 'Chiều dài',
            'vatlieu' => 'Vật liệu',
            'hieu' => 'HIệu',
            'coong' => 'Cống',
            'tinhtrang' => 'Tình trạng',
            'madma' => 'Mã DMA',
            'vitrilapda' => 'Vị trí',
            'ghichu' => 'Ghi chú',
            'namlapdat' => 'Năm',
            'dosau' => 'Độ sâu',
            'loaicongtr' => 'Loại công trình',
            'tencongtri' => 'Tên',
            'donvithiet' => 'Đơn vị thiết kế',
            'donvithico' => 'Đơn vị thi công',
            'tenduong' => 'Tên đường',
            'sohem' => 'Số hẻm',
            'diemdau' => 'Điểm đầu',
            'diemcuoi' => 'Điểm cuối',
            'tuyen' => 'Tuyến',
            'cachletrai' => 'Cách lề trái',
            'cachlephai' => 'Cách lề phải',
            'maphuong' => 'Mã phường',
            'maquan' => 'Mã quận',
            'globalid' => 'Globalid',
            'bvhc' => 'BVHC',
            'shape_leng' => 'Shape Leng',
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
