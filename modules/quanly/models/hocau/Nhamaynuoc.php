<?php

namespace app\modules\quanly\models\hocau;

use Yii;

/**
 * This is the model class for table "network_nhamaynuoc".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $loai
 * @property float|null $shape_leng
 * @property float|null $shape_area
 * @property string|null $file_dinhkem
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $loainhamay_id
 *
 * @property DmLoainhamay $loainhamay
 */
class Nhamaynuoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network_nhamaynuoc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'file_dinhkem', 'lat', 'long', 'geojson'], 'string'],
            [['objectid', 'shape_leng', 'shape_area'], 'number'],
            [['status', 'created_by', 'updated_by', 'loainhamay_id'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by', 'loainhamay_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['loai'], 'string', 'max' => 50],
            [['loainhamay_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmLoainhamay::className(), 'targetAttribute' => ['loainhamay_id' => 'id']],
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
            'loai' => 'Loai',
            'shape_leng' => 'Shape Leng',
            'shape_area' => 'Shape Area',
            'file_dinhkem' => 'File Dinhkem',
            'lat' => 'Lat',
            'long' => 'Long',
            'geojson' => 'Geojson',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'loainhamay_id' => 'Loainhamay ID',
        ];
    }

    /**
     * Gets query for [[Loainhamay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoainhamay()
    {
        return $this->hasOne(DmLoainhamay::className(), ['id' => 'loainhamay_id']);
    }
}
