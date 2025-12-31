<?php

namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;
use app\modules\quanly\models\danhmuc\DmTinhtrang;
use app\modules\quanly\models\danhmuc\DmSucoNguyennhan;
use app\modules\quanly\models\danhmuc\DmSucoTinhtrang;
use app\modules\quanly\models\danhmuc\DmSucoLoai;
use Yii;

/**
 * This is the model class for table "network_suco".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid_1
 * @property int|null $objectid
 * @property string|null $tinh_trang
 * @property int|null $masuco
 * @property string|null $vitri
 * @property string|null $loai
 * @property string|null $n_phathien
 * @property string|null $d_phathien
 * @property string|null $n_xuly
 * @property string|null $d_xuly
 * @property string|null $n_hoancong
 * @property string|null $nguyennhan
 * @property string|null $cachxuly
 * @property string|null $mataisan
 * @property string|null $ghichu
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $file_dinhkem
 * @property int|null $loaisuco_id
 * @property int|null $nguyennhansuco_id
 * @property int|null $tinhtrangsuco_id
 * @property int|null $tinhtrang_id
 *
 * @property DmSucoLoai $loaisuco
 * @property DmSucoNguyennhan $nguyennhansuco
 * @property DmSucoTinhtrang $tinhtrangsuco
 * @property DmTinhtrang $tinhtrang
 */
class Suco extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network_suco';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'lat', 'long', 'geojson', 'file_dinhkem'], 'string'],
            [['objectid_1', 'objectid', 'masuco', 'status', 'created_by', 'updated_by', 'loaisuco_id', 'nguyennhansuco_id', 'tinhtrangsuco_id', 'tinhtrang_id'], 'default', 'value' => null],
            [['objectid_1', 'objectid', 'masuco', 'status', 'created_by', 'updated_by', 'loaisuco_id', 'nguyennhansuco_id', 'tinhtrangsuco_id', 'tinhtrang_id'], 'integer'],
            [['n_phathien', 'n_xuly', 'n_hoancong', 'created_at', 'updated_at'], 'safe'],
            [['tinh_trang'], 'string', 'max' => 10],
            [['vitri', 'd_phathien', 'd_xuly', 'nguyennhan'], 'string', 'max' => 50],
            [['loai', 'mataisan'], 'string', 'max' => 25],
            [['cachxuly'], 'string', 'max' => 100],
            [['ghichu'], 'string', 'max' => 200],
            [['loaisuco_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmSucoLoai::className(), 'targetAttribute' => ['loaisuco_id' => 'id']],
            [['nguyennhansuco_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmSucoNguyennhan::className(), 'targetAttribute' => ['nguyennhansuco_id' => 'id']],
            [['tinhtrangsuco_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmSucoTinhtrang::className(), 'targetAttribute' => ['tinhtrangsuco_id' => 'id']],
            [['tinhtrang_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmTinhtrang::className(), 'targetAttribute' => ['tinhtrang_id' => 'id']],
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
            'objectid_1' => 'Objectid 1',
            'objectid' => 'Objectid',
            'tinh_trang' => 'Tinh Trang',
            'masuco' => 'Masuco',
            'vitri' => 'Vitri',
            'loai' => 'Loai',
            'n_phathien' => 'N Phathien',
            'd_phathien' => 'D Phathien',
            'n_xuly' => 'N Xuly',
            'd_xuly' => 'D Xuly',
            'n_hoancong' => 'N Hoancong',
            'nguyennhan' => 'Nguyennhan',
            'cachxuly' => 'Cachxuly',
            'mataisan' => 'Mataisan',
            'ghichu' => 'Ghichu',
            'lat' => 'Lat',
            'long' => 'Long',
            'geojson' => 'Geojson',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'file_dinhkem' => 'File Dinhkem',
            'loaisuco_id' => 'Loaisuco ID',
            'nguyennhansuco_id' => 'Nguyennhansuco ID',
            'tinhtrangsuco_id' => 'Tinhtrangsuco ID',
            'tinhtrang_id' => 'Tinhtrang ID',
        ];
    }

    /**
     * Gets query for [[Loaisuco]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoaisuco()
    {
        return $this->hasOne(DmSucoLoai::className(), ['id' => 'loaisuco_id']);
    }

    /**
     * Gets query for [[Nguyennhansuco]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNguyennhansuco()
    {
        return $this->hasOne(DmSucoNguyennhan::className(), ['id' => 'nguyennhansuco_id']);
    }

    /**
     * Gets query for [[Tinhtrangsuco]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTinhtrangsuco()
    {
        return $this->hasOne(DmSucoTinhtrang::className(), ['id' => 'tinhtrangsuco_id']);
    }

    /**
     * Gets query for [[Tinhtrang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTinhtrang()
    {
        return $this->hasOne(DmTinhtrang::className(), ['id' => 'tinhtrang_id']);
    }
}
