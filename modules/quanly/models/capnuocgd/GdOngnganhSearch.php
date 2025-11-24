<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\GdOngnganh;

/**
 * GdOngnganhSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\GdOngnganh`.
 */
class GdOngnganhSearch extends GdOngnganh
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'coong', 'namlapdat', 'updated_by', 'created_by', 'status'], 'integer'],
            [['geom', 'idduongong', 'vatlieu', 'hieu', 'ngaylapdat', 'tinhtrang', 'dbdonghonu', 'madma', 'diachi', 'ghichu', 'globalid', 'updated_at', 'created_at', 'geojson'], 'safe'],
            [['objectid', 'chieudaiho', 'dosau', 'shape_leng'], 'number'],
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
        $query = GdOngnganh::find()->where(['status'=>1]);

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
            'chieudaiho' => $this->chieudaiho,
            'coong' => $this->coong,
            'namlapdat' => $this->namlapdat,
            'dosau' => $this->dosau,
            'shape_leng' => $this->shape_leng,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(idduongong)', mb_strtoupper($this->idduongong)])
            ->andFilterWhere(['like', 'upper(vatlieu)', mb_strtoupper($this->vatlieu)])
            ->andFilterWhere(['like', 'upper(hieu)', mb_strtoupper($this->hieu)])
            ->andFilterWhere(['like', 'upper(ngaylapdat)', mb_strtoupper($this->ngaylapdat)])
            ->andFilterWhere(['like', 'upper(tinhtrang)', mb_strtoupper($this->tinhtrang)])
            ->andFilterWhere(['like', 'upper(dbdonghonu)', mb_strtoupper($this->dbdonghonu)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)])
            ->andFilterWhere(['like', 'upper(diachi)', mb_strtoupper($this->diachi)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
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
        'idduongong',
        'chieudaiho',
        'vatlieu',
        'hieu',
        'ngaylapdat',
        'tinhtrang',
        'coong',
        'dbdonghonu',
        'madma',
        'diachi',
        'ghichu',
        'namlapdat',
        'dosau',
        'globalid',
        'shape_leng',
        'updated_at',
        'updated_by',
        'created_at',
        'created_by',
        'status',
        'geojson',        ];
    }
}
