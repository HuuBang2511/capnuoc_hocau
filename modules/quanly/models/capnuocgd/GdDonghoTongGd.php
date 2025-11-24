<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "gd_dongho_tong_gd".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $iddonghoto
 * @property string|null $mavitri
 * @property string|null $hieudongho
 * @property string|null $loaidongho
 * @property string|null $sothandong
 * @property string|null $ngaylapdat
 * @property string|null $vitrilapda
 * @property string|null $tinhtrang
 * @property string|null $donvithico
 * @property int|null $codongho
 * @property float|null $dosau
 * @property float|null $toadox
 * @property float|null $toadoy
 * @property string|null $mshamdht
 * @property int|null $soluongnap
 * @property string|null $vatlieunap
 * @property string|null $khuvuc
 * @property float|null $docao
 * @property string|null $ghichu
 * @property string|null $maphuong
 * @property string|null $maquan
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
class GdDonghoTongGd extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_dongho_tong_gd';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'dosau', 'toadox', 'toadoy', 'docao'], 'number'],
            [['codongho', 'soluongnap', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['codongho', 'soluongnap', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['iddonghoto', 'hieudongho', 'loaidongho', 'tinhtrang', 'vatlieunap', 'maphuong'], 'string', 'max' => 50],
            [['mavitri', 'sothandong', 'mshamdht'], 'string', 'max' => 20],
            [['ngaylapdat'], 'string', 'max' => 24],
            [['vitrilapda', 'globalid'], 'string', 'max' => 254],
            [['donvithico', 'ghichu'], 'string', 'max' => 200],
            [['khuvuc', 'maquan'], 'string', 'max' => 10],
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
            'iddonghoto' => 'ID',
            'mavitri' => 'Mã vị trí',
            'hieudongho' => 'Hiệu',
            'loaidongho' => 'Loại',
            'sothandong' => 'Số thân đồng',
            'ngaylapdat' => 'Ngày lắp đặt',
            'vitrilapda' => 'Vị trí lắp đặt',
            'tinhtrang' => 'Tình trạng',
            'donvithico' => 'Đơn vị thi công',
            'codongho' => 'Cỡ ĐH',
            'dosau' => 'Độ sâu',
            'toadox' => 'Toadox',
            'toadoy' => 'Toadoy',
            'mshamdht' => 'Mshamdht',
            'soluongnap' => 'Số lượng nạp',
            'vatlieunap' => 'Vật liệu nạp',
            'khuvuc' => 'Khu vực',
            'docao' => 'Độ cao',
            'ghichu' => 'Ghi chú',
            'maphuong' => 'Mã phường',
            'maquan' => 'Mã quận',
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
