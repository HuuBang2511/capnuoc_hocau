<?php

namespace app\modules\quanly\models\aphu;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\aphu\NhamayNuoc;

/**
 * SearchNhamayNuoc represents the model behind the search form about `app\modules\quanly\models\aphu\NhamayNuoc`.
 */
class SearchNhamayNuoc extends NhamayNuoc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'objectid', 'namxd', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'congnghexl', 'nguon', 'congsuat_1', 'created_at', 'updated_at', 'geojson', 'lat', 'long', 'ghichu'], 'safe'],
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
        $query = NhamayNuoc::find()->where(['status'=>1]);

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
            'namxd' => $this->namxd,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(congnghexl)', mb_strtoupper($this->congnghexl)])
            ->andFilterWhere(['like', 'upper(nguon)', mb_strtoupper($this->nguon)])
            ->andFilterWhere(['like', 'upper(congsuat_1)', mb_strtoupper($this->congsuat_1)])
            ->andFilterWhere(['like', 'upper(geojson)', mb_strtoupper($this->geojson)])
            ->andFilterWhere(['like', 'upper(lat)', mb_strtoupper($this->lat)])
            ->andFilterWhere(['like', 'upper(long)', mb_strtoupper($this->long)])
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
        'congnghexl',
        'namxd',
        'nguon',
        'congsuat_1',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'status',
        'geojson',
        'lat',
        'long',
        'ghichu',        ];
    }
}
