<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "gd_tramcuuhoa".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $idtruhong
 * @property int|null $kichco
 * @property string|null $kcmiengphu
 * @property string|null $loaitruhon
 * @property string|null $hieu
 * @property string|null $vatlieu
 * @property string|null $tieuchuan
 * @property int|null $dknuocvao
 * @property float|null $dosau
 * @property string|null $ngaylapdat
 * @property int|null $slmiengphu
 * @property string|null $vitri
 * @property string|null $tinhtrang
 * @property string|null $tinhtrangh
 * @property string|null $donviquanl
 * @property string|null $madma
 * @property float|null $docao
 * @property string|null $ghichu
 * @property string|null $namlapdat
 * @property string|null $globalid
 * @property string|null $maphuong
 * @property string|null $maquan
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $status
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 */
class GdTramcuuhoa extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_tramcuuhoa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'dosau', 'docao'], 'number'],
            [['kichco', 'dknuocvao', 'slmiengphu', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['kichco', 'dknuocvao', 'slmiengphu', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['idtruhong', 'loaitruhon', 'hieu', 'vatlieu', 'tieuchuan', 'tinhtrang', 'tinhtrangh', 'donviquanl', 'madma', 'namlapdat'], 'string', 'max' => 50],
            [['kcmiengphu'], 'string', 'max' => 20],
            [['ngaylapdat'], 'string', 'max' => 24],
            [['vitri', 'globalid'], 'string', 'max' => 254],
            [['ghichu'], 'string', 'max' => 200],
            [['maphuong'], 'string', 'max' => 30],
            [['maquan'], 'string', 'max' => 25],
            [['lat', 'long'], 'string', 'max' => 100],
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
            'idtruhong' => 'ID',
            'kichco' => 'Kích cỡ',
            'kcmiengphu' => 'Kcmiengphu',
            'loaitruhon' => 'Loại trụ',
            'hieu' => 'Hiệu',
            'vatlieu' => 'Vật liệu',
            'tieuchuan' => 'Tiêu chuẩn',
            'dknuocvao' => 'ĐK nước vào',
            'dosau' => 'Độ sâu',
            'ngaylapdat' => 'Ngày lắp đặt',
            'slmiengphu' => 'SL miếng phụ',
            'vitri' => 'Vị trí',
            'tinhtrang' => 'Tình trạng',
            'tinhtrangh' => 'Tinhtrangh',
            'donviquanl' => 'Đơn vị quản lý',
            'madma' => 'Madma',
            'docao' => 'Độ cao',
            'ghichu' => 'Ghi chú',
            'namlapdat' => 'Namlapdat',
            'globalid' => 'Globalid',
            'maphuong' => 'Maphuong',
            'maquan' => 'Maquan',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
            'lat' => 'Lat',
            'long' => 'Long',
            'geojson' => 'Geojson',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Kiểm tra xem có lat và long
            if ($this->lat !== null && $this->long !== null) {
                // Tạo geom từ lat và long
                $this->geom = new \yii\db\Expression("ST_GeomFromText('POINT({$this->long} {$this->lat})')");
            }
            return true;
        }
        return false;
    }
}
