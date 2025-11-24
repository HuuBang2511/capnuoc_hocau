<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\GdTrambom;

/**
 * GdTrambomSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\GdTrambom`.
 */
class GdTrambomSearch extends GdTrambom
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'loaitram', 'soluongbom', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'idtrambom', 'tentram', 'diadiem', 'namxaydung', 'donviquanl', 'madma', 'ghichu', 'globalid', 'created_at', 'updated_at', 'geojson', 'lat', 'long'], 'safe'],
            [['objectid', 'congsuat'], 'number'],
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
        $query = GdTrambom::find()->where(['status'=>1]);

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
            'loaitram' => $this->loaitram,
            'congsuat' => $this->congsuat,
            'soluongbom' => $this->soluongbom,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(idtrambom)', mb_strtoupper($this->idtrambom)])
            ->andFilterWhere(['like', 'upper(tentram)', mb_strtoupper($this->tentram)])
            ->andFilterWhere(['like', 'upper(diadiem)', mb_strtoupper($this->diadiem)])
            ->andFilterWhere(['like', 'upper(namxaydung)', mb_strtoupper($this->namxaydung)])
            ->andFilterWhere(['like', 'upper(donviquanl)', mb_strtoupper($this->donviquanl)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(globalid)', mb_strtoupper($this->globalid)])
            ->andFilterWhere(['like', 'upper(geojson)', mb_strtoupper($this->geojson)])
            ->andFilterWhere(['like', 'upper(lat)', mb_strtoupper($this->lat)])
            ->andFilterWhere(['like', 'upper(long)', mb_strtoupper($this->long)]);

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
        'idtrambom',
        'loaitram',
        'tentram',
        'diadiem',
        'namxaydung',
        'congsuat',
        'soluongbom',
        'donviquanl',
        'madma',
        'ghichu',
        'globalid',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'status',
        'geojson',
        'lat',
        'long',        ];
    }
}
