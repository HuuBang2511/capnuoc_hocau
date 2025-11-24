<?php

namespace app\modules\quanly\models\aphu;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\aphu\OngNuoctho;

/**
 * SearchOngNuoctho represents the model behind the search form about `app\modules\quanly\models\aphu\OngNuoctho`.
 */
class SearchOngNuoctho extends OngNuoctho
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'objectid', 'duongkinh', 'namlapdat', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'vatlieu', 'ghichu', 'created_at', 'updated_at', 'geojson'], 'safe'],
            [['chieudai', 'shape_leng'], 'number'],
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
        $query = OngNuoctho::find()->where(['status'=>1]);

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
            'duongkinh' => $this->duongkinh,
            'namlapdat' => $this->namlapdat,
            'chieudai' => $this->chieudai,
            'shape_leng' => $this->shape_leng,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(vatlieu)', mb_strtoupper($this->vatlieu)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(geojson)', mb_strtoupper($this->geojson)]);

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
        'duongkinh',
        'vatlieu',
        'namlapdat',
        'chieudai',
        'ghichu',
        'shape_leng',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'status',
        'geojson',        ];
    }
}
