<?php

namespace app\modules\quanly\models\aphu;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\aphu\OngDichvu;

/**
 * SearchOngDichvu represents the model behind the search form about `app\modules\quanly\models\aphu\OngDichvu`.
 */
class SearchOngDichvu extends OngDichvu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'objectid', 'coong', 'namlapdat', 'enabled', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'idduongong', 'vatlieu', 'hieu', 'ngaylapdat', 'tinhtrang', 'dbdonghonu', 'madma', 'diachi', 'ghichu', 'created_us', 'created_da', 'last_edite', 'last_edi_1', 'globalid', 'bvtd', 'created_at', 'updated_at', 'geojson'], 'safe'],
            [['chieudaiho', 'dosau', 'shape_leng'], 'number'],
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
        $query = OngDichvu::find()->where(['status'=>1]);

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
            'ngaylapdat' => $this->ngaylapdat,
            'coong' => $this->coong,
            'namlapdat' => $this->namlapdat,
            'dosau' => $this->dosau,
            'created_da' => $this->created_da,
            'last_edi_1' => $this->last_edi_1,
            'enabled' => $this->enabled,
            'shape_leng' => $this->shape_leng,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(idduongong)', mb_strtoupper($this->idduongong)])
            ->andFilterWhere(['like', 'upper(vatlieu)', mb_strtoupper($this->vatlieu)])
            ->andFilterWhere(['like', 'upper(hieu)', mb_strtoupper($this->hieu)])
            ->andFilterWhere(['like', 'upper(tinhtrang)', mb_strtoupper($this->tinhtrang)])
            ->andFilterWhere(['like', 'upper(dbdonghonu)', mb_strtoupper($this->dbdonghonu)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)])
            ->andFilterWhere(['like', 'upper(diachi)', mb_strtoupper($this->diachi)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(created_us)', mb_strtoupper($this->created_us)])
            ->andFilterWhere(['like', 'upper(last_edite)', mb_strtoupper($this->last_edite)])
            ->andFilterWhere(['like', 'upper(globalid)', mb_strtoupper($this->globalid)])
            ->andFilterWhere(['like', 'upper(bvtd)', mb_strtoupper($this->bvtd)])
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
        'created_us',
        'created_da',
        'last_edite',
        'last_edi_1',
        'globalid',
        'enabled',
        'bvtd',
        'shape_leng',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'status',
        'geojson',        ];
    }
}
