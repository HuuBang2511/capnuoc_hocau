<?php

namespace app\modules\quanly\models\aphu;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\aphu\HoThuyloi;

/**
 * SearchHoThuyloi represents the model behind the search form about `app\modules\quanly\models\aphu\HoThuyloi`.
 */
class SearchHoThuyloi extends HoThuyloi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'objectid', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'tenho', 'dungtich', 'dientichma', 'created_at', 'updated_at', 'geojson', 'ghichu'], 'safe'],
            [['dosautrung', 'shape_leng', 'shape_area'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = HoThuyloi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'objectid' => $this->objectid,
            'dosautrung' => $this->dosautrung,
            'shape_leng' => $this->shape_leng,
            'shape_area' => $this->shape_area,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(tenho)', mb_strtoupper($this->tenho)])
            ->andFilterWhere(['like', 'upper(dungtich)', mb_strtoupper($this->dungtich)])
            ->andFilterWhere(['like', 'upper(dientichma)', mb_strtoupper($this->dientichma)])
            ->andFilterWhere(['like', 'upper(geojson)', mb_strtoupper($this->geojson)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'geom',
        'objectid',
        'tenho',
        'dungtich',
        'dientichma',
        'dosautrung',
        'shape_leng',
        'shape_area',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'status',
        'geojson',
        'ghichu',        ];
    }
}
