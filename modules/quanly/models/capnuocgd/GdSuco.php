<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use app\modules\quanly\models\capnuocgd\danhmuc\GdDmSucoNguyennhan;
use app\modules\quanly\models\capnuocgd\danhmuc\GdDmXulysuco;
use Yii;

/**
 * This is the model class for table "gd_suco".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $masuco
 * @property string|null $madanhba
 * @property string|null $sonha
 * @property string|null $duong
 * @property string|null $ngayphathien
 * @property string|null $nguoiphathien
 * @property string|null $ngaysuachua
 * @property string|null $nguoisuachua
 * @property string|null $donvisuachua
 * @property string|null $hinhthucphuchoi
 * @property string|null $vitriphathien
 * @property string|null $nguyennhan
 * @property string|null $bienphapxuly
 * @property int|null $duongkinho
 * @property string|null $vatlieu_ong
 * @property string|null $tailapmatduong
 * @property string|null $kichthuocp
 * @property string|null $ghichu
 * @property int|null $tontai
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int $status
 * @property int $xulysuco_id
 */
class GdSuco extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public $imageFiles;

    public static function tableName()
    {
        return 'gd_suco';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'duongkinho', 'tontai', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['objectid', 'duongkinho', 'tontai', 'created_by', 'updated_by', 'status', 'xulysuco_id'], 'integer'],
            [['ngayphathien', 'ngaysuachua', 'created_at', 'updated_at'], 'safe'],
            [['masuco'], 'string', 'max' => 20],
            [['madanhba'], 'string', 'max' => 11],
            [['file'], 'string'],
            [['sonha', 'duong', 'nguoiphathien', 'nguoisuachua', 'donvisuachua', 'nguyennhan', 'bienphapxuly', 'vatlieu_ong', 'tailapmatduong', 'kichthuocp'], 'string', 'max' => 50],
            [['hinhthucphuchoi', 'vitriphathien'], 'string', 'max' => 10],
            [['ghichu', 'lat', 'long'], 'string', 'max' => 200],
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
            'masuco' => 'Mã sự cố',
            'madanhba' => 'Mã danh bạ',
            'sonha' => 'Số nhà',
            'duong' => 'Đường',
            'ngayphathien' => 'Ngày phát hiện',
            'nguoiphathien' => 'Người phát hiện',
            'ngaysuachua' => 'Ngày sửa chữa',
            'nguoisuachua' => 'Người sửa chữa',
            'donvisuachua' => 'Đơn vị sửa chữa',
            'hinhthucphuchoi' => 'Hình thức phục hồi',
            'vitriphathien' => 'Vị trí phát hiện',
            'nguyennhan' => 'Nguyên nhân',
            'bienphapxuly' => 'Biện pháp xử lý',
            'duongkinho' => 'Đường kính o',
            'vatlieu_ong' => 'Vật liệu ống',
            'tailapmatduong' => 'Tái lập mặt dường',
            'kichthuocp' => 'Kích thước p',
            'ghichu' => 'Ghi chú',
            'tontai' => 'Tồn tại',
            'lat' => 'Lat',
            'long' => 'Long',
            'geojson' => 'Geojson',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
            'xulysuco_id' => 'Xử lý sự cố',
            'file' => 'File',
        ];
    }

    public function getDm_nguyennhan()
    {
        return $this->hasOne(GdDmSucoNguyennhan::className(), ['ma' => 'nguyennhan']);
    }

    public function getDm_xulysuco()
    {
        return $this->hasOne(GdDmXulysuco::className(), ['id' => 'xulysuco_id']);
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
