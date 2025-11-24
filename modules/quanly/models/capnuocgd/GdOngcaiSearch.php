<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\GdOngcai;

/**
 * GdOngcaiSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\GdOngcai`.
 */
class GdOngcaiSearch extends GdOngcai
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'coong', 'namlapdat', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'idduongong', 'vatlieu', 'hieu', 'tinhtrang', 'madma', 'vitrilapda', 'ghichu', 'loaicongtr', 'tencongtri', 'donvithiet', 'donvithico', 'tenduong', 'sohem', 'diemdau', 'diemcuoi', 'tuyen', 'maphuong', 'maquan', 'globalid', 'bvhc', 'created_at', 'updated_at', 'geojson'], 'safe'],
            [['objectid', 'chieudaiho', 'dosau', 'cachletrai', 'cachlephai', 'shape_leng'], 'number'],
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
        $query = GdOngcai::find()->where(['status'=>1]);

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
            'cachletrai' => $this->cachletrai,
            'cachlephai' => $this->cachlephai,
            'shape_leng' => $this->shape_leng,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(idduongong)', mb_strtoupper($this->idduongong)])
            ->andFilterWhere(['like', 'upper(vatlieu)', mb_strtoupper($this->vatlieu)])
            ->andFilterWhere(['like', 'upper(hieu)', mb_strtoupper($this->hieu)])
            ->andFilterWhere(['like', 'upper(tinhtrang)', mb_strtoupper($this->tinhtrang)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)])
            ->andFilterWhere(['like', 'upper(vitrilapda)', mb_strtoupper($this->vitrilapda)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(loaicongtr)', mb_strtoupper($this->loaicongtr)])
            ->andFilterWhere(['like', 'upper(tencongtri)', mb_strtoupper($this->tencongtri)])
            ->andFilterWhere(['like', 'upper(donvithiet)', mb_strtoupper($this->donvithiet)])
            ->andFilterWhere(['like', 'upper(donvithico)', mb_strtoupper($this->donvithico)])
            ->andFilterWhere(['like', 'upper(tenduong)', mb_strtoupper($this->tenduong)])
            ->andFilterWhere(['like', 'upper(sohem)', mb_strtoupper($this->sohem)])
            ->andFilterWhere(['like', 'upper(diemdau)', mb_strtoupper($this->diemdau)])
            ->andFilterWhere(['like', 'upper(diemcuoi)', mb_strtoupper($this->diemcuoi)])
            ->andFilterWhere(['like', 'upper(tuyen)', mb_strtoupper($this->tuyen)])
            ->andFilterWhere(['like', 'upper(maphuong)', mb_strtoupper($this->maphuong)])
            ->andFilterWhere(['like', 'upper(maquan)', mb_strtoupper($this->maquan)])
            ->andFilterWhere(['like', 'upper(globalid)', mb_strtoupper($this->globalid)])
            ->andFilterWhere(['like', 'upper(bvhc)', mb_strtoupper($this->bvhc)])
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
        'coong',
        'tinhtrang',
        'madma',
        'vitrilapda',
        'ghichu',
        'namlapdat',
        'dosau',
        'loaicongtr',
        'tencongtri',
        'donvithiet',
        'donvithico',
        'tenduong',
        'sohem',
        'diemdau',
        'diemcuoi',
        'tuyen',
        'cachletrai',
        'cachlephai',
        'maphuong',
        'maquan',
        'globalid',
        'bvhc',
        'shape_leng',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'status',
        'geojson',        ];
    }
}
