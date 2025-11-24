<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "gd_data_logger".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $objectid
 * @property string|null $iddiemtinh
 * @property float|null $aplucvao
 * @property float|null $aplucra
 * @property string|null $chucnang
 * @property float|null $dosau
 * @property float|null $doduc
 * @property float|null $doph
 * @property string|null $kenh
 * @property string|null $hieu
 * @property float|null $hamluongcl
 * @property float|null $luuluong
 * @property string|null $vitri
 * @property string|null $tinhtrang
 * @property string|null $madma
 * @property float|null $docao
 * @property string|null $ghichu
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
class GdDataLogger extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_data_logger';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['objectid', 'aplucvao', 'aplucra', 'dosau', 'doduc', 'doph', 'hamluongcl', 'luuluong', 'docao'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['created_by', 'updated_by', 'status'], 'integer'],
            [['iddiemtinh', 'chucnang', 'kenh', 'hieu', 'tinhtrang', 'madma'], 'string', 'max' => 50],
            [['vitri', 'globalid'], 'string', 'max' => 254],
            [['ghichu'], 'string', 'max' => 200],
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
            'iddiemtinh' => 'ID Điểm',
            'aplucvao' => 'Áp lực vào',
            'aplucra' => 'Áp lực ra',
            'chucnang' => 'Chức năng',
            'dosau' => 'Độ sâu',
            'doduc' => 'Độ đục',
            'doph' => 'Độ ph',
            'kenh' => 'Kênh',
            'hieu' => 'Hiệu',
            'hamluongcl' => 'Hàm lượng clo',
            'luuluong' => 'Lưu lượng',
            'vitri' => 'Vị trí',
            'tinhtrang' => 'Tình trạng',
            'madma' => 'Mã DMA',
            'docao' => 'Độ cao',
            'ghichu' => 'Ghi chú',
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
