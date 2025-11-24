<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "gd_trambom".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $idtrambom
 * @property int|null $loaitram
 * @property string|null $tentram
 * @property string|null $diadiem
 * @property string|null $namxaydung
 * @property float|null $congsuat
 * @property int|null $soluongbom
 * @property string|null $donviquanl
 * @property string|null $madma
 * @property string|null $ghichu
 * @property string|null $globalid
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $status
 * @property string|null $geojson
 * @property string|null $lat
 * @property string|null $long
 */
class GdTrambom extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_trambom';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'congsuat'], 'number'],
            [['loaitram', 'soluongbom', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['loaitram', 'soluongbom', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['idtrambom', 'donviquanl', 'madma'], 'string', 'max' => 50],
            [['tentram', 'diadiem', 'ghichu'], 'string', 'max' => 200],
            [['namxaydung'], 'string', 'max' => 4],
            [['globalid'], 'string', 'max' => 254],
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
            'idtrambom' => 'ID',
            'loaitram' => 'Loại',
            'tentram' => 'Tên',
            'diadiem' => 'Địa điểm',
            'namxaydung' => 'Năm',
            'congsuat' => 'Công suất',
            'soluongbom' => 'Số lượng bơm',
            'donviquanl' => 'Đơn vị quản lý',
            'madma' => 'Madma',
            'ghichu' => 'Ghi chú',
            'globalid' => 'Globalid',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
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
