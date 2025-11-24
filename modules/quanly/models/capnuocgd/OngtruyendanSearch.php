<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\Ongtruyendan;

/**
 * OngtruyendanSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\Ongtruyendan`.
 */
class OngtruyendanSearch extends Ongtruyendan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'coong', 'namlapdat'], 'integer'],
            [['geom', 'vatlieu', 'tencongtri', 'donvithiet', 'donvithico'], 'safe'],
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
        $query = Ongtruyendan::find();

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
            'coong' => $this->coong,
            'namlapdat' => $this->namlapdat,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(vatlieu)', mb_strtoupper($this->vatlieu)])
            ->andFilterWhere(['like', 'upper(tencongtri)', mb_strtoupper($this->tencongtri)])
            ->andFilterWhere(['like', 'upper(donvithiet)', mb_strtoupper($this->donvithiet)])
            ->andFilterWhere(['like', 'upper(donvithico)', mb_strtoupper($this->donvithico)]);

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
        'vatlieu',
        'coong',
        'namlapdat',
        'tencongtri',
        'donvithiet',
        'donvithico',        ];
    }
}
