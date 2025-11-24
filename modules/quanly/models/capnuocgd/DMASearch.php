<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\DMA;

/**
 * DMASearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\DMA`.
 */
class DMASearch extends DMA
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'objectid', 'sodaunoi', 'sometong', 'sovan', 'sotru', 'status'], 'integer'],
            [['geom', 'madma'], 'safe'],
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
        $query = DMA::find()->where(['status'=>1]);

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
            'sodaunoi' => $this->sodaunoi,
            'sometong' => $this->sometong,
            'sovan' => $this->sovan,
            'sotru' => $this->sotru,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)]);

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
        'madma',
        'sodaunoi',
        'sometong',
        'sovan',
        'sotru',        ];
    }
}
