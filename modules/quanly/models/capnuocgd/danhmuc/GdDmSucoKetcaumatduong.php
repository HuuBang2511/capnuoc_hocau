<?php

namespace app\modules\quanly\models\capnuocgd\danhmuc;



use app\modules\quanly\base\DanhmucBaseModel;
use Yii;

/**
 * This is the model class for table "gd_dm_suco_ketcaumatduong".
 *
 * @property int $id
 * @property string|null $ten
 * @property string|null $ma
 * @property string|null $ghichu
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $status
 */
class GdDmSucoKetcaumatduong extends DanhmucBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gd_dm_suco_ketcaumatduong';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten', 'ghichu'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['created_by', 'updated_by', 'status'], 'integer'],
            [['ma'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ten' => 'Tên',
            'ma' => 'Mã',
            'ghichu' => 'Ghi chú',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }
}
