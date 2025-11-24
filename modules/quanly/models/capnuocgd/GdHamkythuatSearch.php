<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\GdHamkythuat;

/**
 * GdHamkythuatSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\GdHamkythuat`.
 */
class GdHamkythuatSearch extends GdHamkythuat
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'soluongnap', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'idhamkythu', 'loaiham', 'tenhamkyth', 'namlapdat', 'kichthuoch', 'tinhtrangh', 'donviquanl', 'vatlieunap', 'madma', 'ghichu', 'donvithiet', 'donvithico', 'globalid', 'created_at', 'updated_at', 'geojson'], 'safe'],
            [['objectid', 'dosau', 'docao', 'shape_leng', 'shape_area'], 'number'],
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
        $query = GdHamkythuat::find()->where(['status'=>1]);

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
            'soluongnap' => $this->soluongnap,
            'dosau' => $this->dosau,
            'docao' => $this->docao,
            'shape_leng' => $this->shape_leng,
            'shape_area' => $this->shape_area,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(idhamkythu)', mb_strtoupper($this->idhamkythu)])
            ->andFilterWhere(['like', 'upper(loaiham)', mb_strtoupper($this->loaiham)])
            ->andFilterWhere(['like', 'upper(tenhamkyth)', mb_strtoupper($this->tenhamkyth)])
            ->andFilterWhere(['like', 'upper(namlapdat)', mb_strtoupper($this->namlapdat)])
            ->andFilterWhere(['like', 'upper(kichthuoch)', mb_strtoupper($this->kichthuoch)])
            ->andFilterWhere(['like', 'upper(tinhtrangh)', mb_strtoupper($this->tinhtrangh)])
            ->andFilterWhere(['like', 'upper(donviquanl)', mb_strtoupper($this->donviquanl)])
            ->andFilterWhere(['like', 'upper(vatlieunap)', mb_strtoupper($this->vatlieunap)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(donvithiet)', mb_strtoupper($this->donvithiet)])
            ->andFilterWhere(['like', 'upper(donvithico)', mb_strtoupper($this->donvithico)])
            ->andFilterWhere(['like', 'upper(globalid)', mb_strtoupper($this->globalid)])
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
        'idhamkythu',
        'loaiham',
        'tenhamkyth',
        'namlapdat',
        'kichthuoch',
        'tinhtrangh',
        'donviquanl',
        'soluongnap',
        'vatlieunap',
        'madma',
        'dosau',
        'docao',
        'ghichu',
        'donvithiet',
        'donvithico',
        'globalid',
        'shape_leng',
        'shape_area',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'status',
        'geojson',        ];
    }
}
