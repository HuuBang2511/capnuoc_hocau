<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "gd_dongho_kh_gd".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $sothandong
 * @property string|null $loaidongho
 * @property string|null $hieudongho
 * @property string|null $dbdonghonu
 * @property string|null $vitrilapda
 * @property string|null $tinhtrang
 * @property int|null $codongho
 * @property string|null $sohopdong
 * @property string|null $masochi
 * @property string|null $sohoso
 * @property float|null $dosau
 * @property string|null $madma
 * @property string|null $malotrinh
 * @property string|null $tinhtrangq
 * @property int|null $hopbaove
 * @property string|null $ngaybamchi
 * @property string|null $ngaylapdat
 * @property float|null $docao
 * @property string|null $ghichu
 * @property string|null $maphuong
 * @property string|null $maquan
 * @property string|null $tenkhachha
 * @property string|null $dtdd
 * @property string|null $ddtb
 * @property string|null $email
 * @property string|null $code
 * @property string|null $code_fu
 * @property string|null $giabieu
 * @property string|null $dinhmuc
 * @property string|null $bithuy
 * @property string|null $ngaycapnha
 * @property string|null $kiemtra
 * @property string|null $globalid
 * @property string|null $diachi
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $status
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 */
class GdDonghoKhGd extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_dongho_kh_gd';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'dosau', 'docao'], 'number'],
            [['codongho', 'hopbaove', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['codongho', 'hopbaove', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['sothandong', 'sohopdong', 'masochi', 'sohoso', 'dtdd', 'ddtb'], 'string', 'max' => 20],
            [['loaidongho', 'hieudongho', 'vitrilapda', 'tinhtrang', 'madma', 'malotrinh', 'tinhtrangq', 'maphuong', 'maquan', 'email'], 'string', 'max' => 50],
            [['dbdonghonu'], 'string', 'max' => 11],
            [['ngaybamchi', 'ngaylapdat', 'ngaycapnha'], 'string', 'max' => 24],
            [['ghichu', 'diachi'], 'string', 'max' => 200],
            [['tenkhachha', 'lat', 'long'], 'string', 'max' => 100],
            [['code', 'code_fu', 'giabieu', 'dinhmuc', 'bithuy', 'kiemtra'], 'string', 'max' => 5],
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
            'sothandong' => 'Số thân đồng',
            'loaidongho' => 'Loại',
            'hieudongho' => 'Hiệu',
            'dbdonghonu' => 'DB đồng hồ nữ',
            'vitrilapda' => 'Vị trí',
            'tinhtrang' => 'Tình trạng',
            'codongho' => 'Cỡ đồng hồ',
            'sohopdong' => 'Số HĐ',
            'masochi' => 'Mã số chi',
            'sohoso' => 'Số hồ sơ',
            'dosau' => 'Độ sâu',
            'madma' => 'Mã DMA',
            'malotrinh' => 'Mã lộ trình',
            'tinhtrangq' => 'Tình trạng',
            'hopbaove' => 'Hộp bảo vệ',
            'ngaybamchi' => 'Ngày bám chi',
            'ngaylapdat' => 'Ngày lắp đặt',
            'docao' => 'Độ cao',
            'ghichu' => 'Ghi chú',
            'maphuong' => 'Mã phường',
            'maquan' => 'Mã quận',
            'tenkhachha' => 'Tên KH',
            'dtdd' => 'ĐTDD',
            'ddtb' => 'DDTB',
            'email' => 'Email',
            'code' => 'Code',
            'code_fu' => 'Code Fu',
            'giabieu' => 'Giá biểu',
            'dinhmuc' => 'Định mức',
            'bithuy' => 'Bithuy',
            'ngaycapnha' => 'Ngày cấp nhật',
            'kiemtra' => 'Kiểm tra',
            'globalid' => 'Globalid',
            'diachi' => 'Địa chỉ',
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
