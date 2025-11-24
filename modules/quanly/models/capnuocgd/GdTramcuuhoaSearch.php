<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\GdTramcuuhoa;

/**
 * GdTramcuuhoaSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\GdTramcuuhoa`.
 */
class GdTramcuuhoaSearch extends GdTramcuuhoa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'kichco', 'dknuocvao', 'slmiengphu', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'idtruhong', 'kcmiengphu', 'loaitruhon', 'hieu', 'vatlieu', 'tieuchuan', 'ngaylapdat', 'vitri', 'tinhtrang', 'tinhtrangh', 'donviquanl', 'madma', 'ghichu', 'namlapdat', 'globalid', 'maphuong', 'maquan', 'created_at', 'updated_at', 'lat', 'long', 'geojson'], 'safe'],
            [['objectid', 'dosau', 'docao'], 'number'],
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
        $query = GdTramcuuhoa::find()->where(['status'=>1]);

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
            'kichco' => $this->kichco,
            'dknuocvao' => $this->dknuocvao,
            'dosau' => $this->dosau,
            'slmiengphu' => $this->slmiengphu,
            'docao' => $this->docao,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(idtruhong)', mb_strtoupper($this->idtruhong)])
            ->andFilterWhere(['like', 'upper(kcmiengphu)', mb_strtoupper($this->kcmiengphu)])
            ->andFilterWhere(['like', 'upper(loaitruhon)', mb_strtoupper($this->loaitruhon)])
            ->andFilterWhere(['like', 'upper(hieu)', mb_strtoupper($this->hieu)])
            ->andFilterWhere(['like', 'upper(vatlieu)', mb_strtoupper($this->vatlieu)])
            ->andFilterWhere(['like', 'upper(tieuchuan)', mb_strtoupper($this->tieuchuan)])
            ->andFilterWhere(['like', 'upper(ngaylapdat)', mb_strtoupper($this->ngaylapdat)])
            ->andFilterWhere(['like', 'upper(vitri)', mb_strtoupper($this->vitri)])
            ->andFilterWhere(['like', 'upper(tinhtrang)', mb_strtoupper($this->tinhtrang)])
            ->andFilterWhere(['like', 'upper(tinhtrangh)', mb_strtoupper($this->tinhtrangh)])
            ->andFilterWhere(['like', 'upper(donviquanl)', mb_strtoupper($this->donviquanl)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(namlapdat)', mb_strtoupper($this->namlapdat)])
            ->andFilterWhere(['like', 'upper(globalid)', mb_strtoupper($this->globalid)])
            ->andFilterWhere(['like', 'upper(maphuong)', mb_strtoupper($this->maphuong)])
            ->andFilterWhere(['like', 'upper(maquan)', mb_strtoupper($this->maquan)])
            ->andFilterWhere(['like', 'upper(lat)', mb_strtoupper($this->lat)])
            ->andFilterWhere(['like', 'upper(long)', mb_strtoupper($this->long)])
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
        'idtruhong',
        'kichco',
        'kcmiengphu',
        'loaitruhon',
        'hieu',
        'vatlieu',
        'tieuchuan',
        'dknuocvao',
        'dosau',
        'ngaylapdat',
        'slmiengphu',
        'vitri',
        'tinhtrang',
        'tinhtrangh',
        'donviquanl',
        'madma',
        'docao',
        'ghichu',
        'namlapdat',
        'globalid',
        'maphuong',
        'maquan',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'status',
        'lat',
        'long',
        'geojson',        ];
    }
}
