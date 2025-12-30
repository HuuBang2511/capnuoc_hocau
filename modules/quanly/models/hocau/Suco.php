<?php

namespace app\modules\quanly\models\hocau;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "network_suco".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid_1
 * @property int|null $objectid
 * @property string|null $tinhtrang
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
            [['objectid_1', 'objectid', 'masuco', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['objectid_1', 'objectid', 'masuco', 'status', 'created_by', 'updated_by'], 'integer'],
            [['n_phathien', 'n_xuly', 'n_hoancong', 'created_at', 'updated_at'], 'safe'],
            [['tinhtrang'], 'string', 'max' => 10],
            [['vitri', 'd_phathien', 'd_xuly', 'nguyennhan'], 'string', 'max' => 50],
            [['loai', 'mataisan'], 'string', 'max' => 25],
            [['cachxuly'], 'string', 'max' => 100],
            [['ghichu'], 'string', 'max' => 200],
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
            'tinhtrang' => 'Tinhtrang',
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
        ];
    }
}
