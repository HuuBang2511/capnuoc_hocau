<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\GdDonghoTongGd;

/**
 * GdDonghoTongGdSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\GdDonghoTongGd`.
 */
class GdDonghoTongGdSearch extends GdDonghoTongGd
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'codongho', 'soluongnap', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'iddonghoto', 'mavitri', 'hieudongho', 'loaidongho', 'sothandong', 'ngaylapdat', 'vitrilapda', 'tinhtrang', 'donvithico', 'mshamdht', 'vatlieunap', 'khuvuc', 'ghichu', 'maphuong', 'maquan', 'globalid', 'created_at', 'updated_at', 'lat', 'long', 'geojson'], 'safe'],
            [['objectid', 'dosau', 'toadox', 'toadoy', 'docao'], 'number'],
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
        $query = GdDonghoTongGd::find()->where(['status'=>1]);

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
            'codongho' => $this->codongho,
            'dosau' => $this->dosau,
            'toadox' => $this->toadox,
            'toadoy' => $this->toadoy,
            'soluongnap' => $this->soluongnap,
            'docao' => $this->docao,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(iddonghoto)', mb_strtoupper($this->iddonghoto)])
            ->andFilterWhere(['like', 'upper(mavitri)', mb_strtoupper($this->mavitri)])
            ->andFilterWhere(['like', 'upper(hieudongho)', mb_strtoupper($this->hieudongho)])
            ->andFilterWhere(['like', 'upper(loaidongho)', mb_strtoupper($this->loaidongho)])
            ->andFilterWhere(['like', 'upper(sothandong)', mb_strtoupper($this->sothandong)])
            ->andFilterWhere(['like', 'upper(ngaylapdat)', mb_strtoupper($this->ngaylapdat)])
            ->andFilterWhere(['like', 'upper(vitrilapda)', mb_strtoupper($this->vitrilapda)])
            ->andFilterWhere(['like', 'upper(tinhtrang)', mb_strtoupper($this->tinhtrang)])
            ->andFilterWhere(['like', 'upper(donvithico)', mb_strtoupper($this->donvithico)])
            ->andFilterWhere(['like', 'upper(mshamdht)', mb_strtoupper($this->mshamdht)])
            ->andFilterWhere(['like', 'upper(vatlieunap)', mb_strtoupper($this->vatlieunap)])
            ->andFilterWhere(['like', 'upper(khuvuc)', mb_strtoupper($this->khuvuc)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(maphuong)', mb_strtoupper($this->maphuong)])
            ->andFilterWhere(['like', 'upper(maquan)', mb_strtoupper($this->maquan)])
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
        'iddonghoto',
        'mavitri',
        'hieudongho',
        'loaidongho',
        'sothandong',
        'ngaylapdat',
        'vitrilapda',
        'tinhtrang',
        'donvithico',
        'codongho',
        'dosau',
        'toadox',
        'toadoy',
        'mshamdht',
        'soluongnap',
        'vatlieunap',
        'khuvuc',
        'docao',
        'ghichu',
        'maphuong',
        'maquan',
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
