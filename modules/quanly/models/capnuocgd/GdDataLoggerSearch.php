<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\GdDataLogger;

/**
 * GdDataLoggerSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\GdDataLogger`.
 */
class GdDataLoggerSearch extends GdDataLogger
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'iddiemtinh', 'chucnang', 'kenh', 'hieu', 'vitri', 'tinhtrang', 'madma', 'ghichu', 'globalid', 'created_at', 'updated_at', 'lat', 'long', 'geojson'], 'safe'],
            [['objectid', 'aplucvao', 'aplucra', 'dosau', 'doduc', 'doph', 'hamluongcl', 'luuluong', 'docao'], 'number'],
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
        $query = GdDataLogger::find()->where(['status'=>1]);

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
            'aplucvao' => $this->aplucvao,
            'aplucra' => $this->aplucra,
            'dosau' => $this->dosau,
            'doduc' => $this->doduc,
            'doph' => $this->doph,
            'hamluongcl' => $this->hamluongcl,
            'luuluong' => $this->luuluong,
            'docao' => $this->docao,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(iddiemtinh)', mb_strtoupper($this->iddiemtinh)])
            ->andFilterWhere(['like', 'upper(chucnang)', mb_strtoupper($this->chucnang)])
            ->andFilterWhere(['like', 'upper(kenh)', mb_strtoupper($this->kenh)])
            ->andFilterWhere(['like', 'upper(hieu)', mb_strtoupper($this->hieu)])
            ->andFilterWhere(['like', 'upper(vitri)', mb_strtoupper($this->vitri)])
            ->andFilterWhere(['like', 'upper(tinhtrang)', mb_strtoupper($this->tinhtrang)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(globalid)', mb_strtoupper($this->globalid)])
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
        'iddiemtinh',
        'aplucvao',
        'aplucra',
        'chucnang',
        'dosau',
        'doduc',
        'doph',
        'kenh',
        'hieu',
        'hamluongcl',
        'luuluong',
        'vitri',
        'tinhtrang',
        'madma',
        'docao',
        'ghichu',
        'globalid',
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
