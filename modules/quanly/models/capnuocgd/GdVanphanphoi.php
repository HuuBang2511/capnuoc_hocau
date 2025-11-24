<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "gd_vanphanphoi".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $idvan
 * @property string|null $idhamkythu
 * @property int|null $cochiakhoa
 * @property string|null $vatlieu
 * @property string|null $hieu
 * @property string|null $nuocsanxua
 * @property string|null $ngaylapdat
 * @property float|null $dosau
 * @property string|null $chieudongv
 * @property float|null $svdongvan
 * @property string|null $vitrivan
 * @property string|null $tinhtrang
 * @property int|null $covan
 * @property string|null $loaivan
 * @property string|null $tinhtrangh
 * @property string|null $trangthai
 * @property string|null $madma
 * @property float|null $toadox
 * @property float|null $toadoy
 * @property float|null $docao
 * @property string|null $ghichu
 * @property string|null $ghichuhamk
 * @property int|null $namlapdat
 * @property string|null $maphuong
 * @property string|null $maquan
 * @property string|null $chucnangva
 * @property string|null $globalid
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $status
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 */
class GdVanphanphoi extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_vanphanphoi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['objectid', 'dosau', 'svdongvan', 'toadox', 'toadoy', 'docao'], 'number'],
            [['cochiakhoa', 'covan', 'namlapdat', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['cochiakhoa', 'covan', 'namlapdat', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['idvan', 'idhamkythu', 'vatlieu', 'hieu', 'nuocsanxua', 'chieudongv', 'tinhtrang', 'loaivan', 'tinhtrangh', 'trangthai', 'madma', 'maphuong', 'maquan'], 'string', 'max' => 50],
            [['ngaylapdat'], 'string', 'max' => 24],
            [['vitrivan', 'globalid'], 'string', 'max' => 254],
            [['ghichu', 'ghichuhamk'], 'string', 'max' => 200],
            [['chucnangva'], 'string', 'max' => 20],
            [['lat', 'long', 'geojson'], 'string', 'max' => 100],
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
            'idvan' => 'ID van',
            'idhamkythu' => 'ID hầm',
            'cochiakhoa' => 'Cochiakhoa',
            'vatlieu' => 'Vật liệu',
            'hieu' => 'Hiệu',
            'nuocsanxua' => 'Nước sx',
            'ngaylapdat' => 'Ngày lắp đặt',
            'dosau' => 'Độ sâu',
            'chieudongv' => 'Chiều đóng van',
            'svdongvan' => 'Svdongvan',
            'vitrivan' => 'Vị trí',
            'tinhtrang' => 'Tình trạng',
            'covan' => 'Cỡ van',
            'loaivan' => 'Loại',
            'tinhtrangh' => 'Tinhtrangh',
            'trangthai' => 'Trạng thái',
            'madma' => 'Madma',
            'toadox' => 'Toadox',
            'toadoy' => 'Toadoy',
            'docao' => 'Độ cao',
            'ghichu' => 'Ghichu',
            'ghichuhamk' => 'Ghichuhamk',
            'namlapdat' => 'Namlapdat',
            'maphuong' => 'Maphuong',
            'maquan' => 'Maquan',
            'chucnangva' => 'Chức năng',
            'globalid' => 'Globalid',
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
