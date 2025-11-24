<?php

namespace app\modules\quanly\models\aphu;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "dongho_kh".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
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
 * @property string|null $sonha
 * @property string|null $tenduong
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
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $status
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 */
class DonghoKh extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dongho_kh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'codongho', 'hopbaove', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['objectid', 'codongho', 'hopbaove', 'created_by', 'updated_by', 'status'], 'integer'],
            [['dosau', 'docao'], 'number'],
            [['ngaybamchi', 'ngaylapdat', 'ngaycapnha', 'created_at', 'updated_at'], 'safe'],
            [['sothandong', 'sohopdong', 'masochi', 'sohoso', 'dtdd', 'ddtb'], 'string', 'max' => 20],
            [['loaidongho', 'hieudongho', 'vitrilapda', 'tinhtrang', 'madma', 'malotrinh', 'tinhtrangq', 'maphuong', 'maquan', 'email'], 'string', 'max' => 50],
            [['dbdonghonu'], 'string', 'max' => 11],
            [['sonha', 'tenduong', 'tenkhachha', 'diachi', 'lat', 'long'], 'string', 'max' => 100],
            [['ghichu'], 'string', 'max' => 200],
            [['code', 'code_fu', 'giabieu', 'dinhmuc', 'bithuy', 'kiemtra'], 'string', 'max' => 5],
            [['globalid'], 'string', 'max' => 38],
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
            'sothandong' => 'Sothandong',
            'loaidongho' => 'Loaidongho',
            'hieudongho' => 'Hieudongho',
            'dbdonghonu' => 'Dbdonghonu',
            'vitrilapda' => 'Vitrilapda',
            'tinhtrang' => 'Tinhtrang',
            'codongho' => 'Codongho',
            'sohopdong' => 'Sohopdong',
            'masochi' => 'Masochi',
            'sohoso' => 'Sohoso',
            'dosau' => 'Dosau',
            'madma' => 'Madma',
            'malotrinh' => 'Malotrinh',
            'tinhtrangq' => 'Tinhtrangq',
            'hopbaove' => 'Hopbaove',
            'ngaybamchi' => 'Ngaybamchi',
            'ngaylapdat' => 'Ngaylapdat',
            'sonha' => 'Sonha',
            'tenduong' => 'Tenduong',
            'docao' => 'Docao',
            'ghichu' => 'Ghichu',
            'maphuong' => 'Maphuong',
            'maquan' => 'Maquan',
            'tenkhachha' => 'Tenkhachha',
            'dtdd' => 'Dtdd',
            'ddtb' => 'Ddtb',
            'email' => 'Email',
            'code' => 'Code',
            'code_fu' => 'Code Fu',
            'giabieu' => 'Giabieu',
            'dinhmuc' => 'Dinhmuc',
            'bithuy' => 'Bithuy',
            'ngaycapnha' => 'Ngaycapnha',
            'kiemtra' => 'Kiemtra',
            'globalid' => 'Globalid',
            'diachi' => 'Diachi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
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
