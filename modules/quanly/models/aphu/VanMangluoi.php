<?php

namespace app\modules\quanly\models\aphu;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "van_mangluoi".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
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
 * @property string|null $ngaycoi
 * @property string|null $chucnangva
 * @property string|null $created_us
 * @property string|null $created_da
 * @property string|null $last_edite
 * @property string|null $last_edi_1
 * @property string|null $globalid
 * @property int|null $enabled
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $status
 * @property string|null $geojson
 * @property string|null $lat
 * @property string|null $long
 */
class VanMangluoi extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'van_mangluoi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson', 'lat', 'long'], 'string'],
            [['objectid', 'cochiakhoa', 'covan', 'namlapdat', 'enabled', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['objectid', 'cochiakhoa', 'covan', 'namlapdat', 'enabled', 'created_by', 'updated_by', 'status'], 'integer'],
            [['ngaylapdat', 'ngaycoi', 'created_da', 'last_edi_1', 'created_at', 'updated_at'], 'safe'],
            [['dosau', 'svdongvan', 'toadox', 'toadoy', 'docao'], 'number'],
            [['idvan', 'idhamkythu', 'vatlieu', 'hieu', 'nuocsanxua', 'chieudongv', 'tinhtrang', 'loaivan', 'tinhtrangh', 'trangthai', 'madma', 'maphuong', 'maquan'], 'string', 'max' => 50],
            [['vitrivan', 'created_us', 'last_edite'], 'string', 'max' => 254],
            [['ghichu', 'ghichuhamk'], 'string', 'max' => 200],
            [['chucnangva'], 'string', 'max' => 20],
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
            'idvan' => 'Idvan',
            'idhamkythu' => 'Idhamkythu',
            'cochiakhoa' => 'Cochiakhoa',
            'vatlieu' => 'Vatlieu',
            'hieu' => 'Hieu',
            'nuocsanxua' => 'Nuocsanxua',
            'ngaylapdat' => 'Ngaylapdat',
            'dosau' => 'Dosau',
            'chieudongv' => 'Chieudongv',
            'svdongvan' => 'Svdongvan',
            'vitrivan' => 'Vitrivan',
            'tinhtrang' => 'Tinhtrang',
            'covan' => 'Covan',
            'loaivan' => 'Loaivan',
            'tinhtrangh' => 'Tinhtrangh',
            'trangthai' => 'Trangthai',
            'madma' => 'Madma',
            'toadox' => 'Toadox',
            'toadoy' => 'Toadoy',
            'docao' => 'Docao',
            'ghichu' => 'Ghichu',
            'ghichuhamk' => 'Ghichuhamk',
            'namlapdat' => 'Namlapdat',
            'maphuong' => 'Maphuong',
            'maquan' => 'Maquan',
            'ngaycoi' => 'Ngaycoi',
            'chucnangva' => 'Chucnangva',
            'created_us' => 'Created Us',
            'created_da' => 'Created Da',
            'last_edite' => 'Last Edite',
            'last_edi_1' => 'Last Edi 1',
            'globalid' => 'Globalid',
            'enabled' => 'Enabled',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'status' => 'Status',
            'geojson' => 'Geojson',
            'lat' => 'Lat',
            'long' => 'Long',
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
