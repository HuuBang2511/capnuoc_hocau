<?php

namespace app\modules\quanly\models\aphu;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\aphu\VanMangluoi;

/**
 * SearchVanMangluoi represents the model behind the search form about `app\modules\quanly\models\aphu\VanMangluoi`.
 */
class SearchVanMangluoi extends VanMangluoi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'objectid', 'cochiakhoa', 'covan', 'namlapdat', 'enabled', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'idvan', 'idhamkythu', 'vatlieu', 'hieu', 'nuocsanxua', 'ngaylapdat', 'chieudongv', 'vitrivan', 'tinhtrang', 'loaivan', 'tinhtrangh', 'trangthai', 'madma', 'ghichu', 'ghichuhamk', 'maphuong', 'maquan', 'ngaycoi', 'chucnangva', 'created_us', 'created_da', 'last_edite', 'last_edi_1', 'globalid', 'created_at', 'updated_at', 'geojson', 'lat', 'long'], 'safe'],
            [['dosau', 'svdongvan', 'toadox', 'toadoy', 'docao'], 'number'],
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
        $query = VanMangluoi::find()->where(['status'=>1]);

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
            'cochiakhoa' => $this->cochiakhoa,
            'ngaylapdat' => $this->ngaylapdat,
            'dosau' => $this->dosau,
            'svdongvan' => $this->svdongvan,
            'covan' => $this->covan,
            'toadox' => $this->toadox,
            'toadoy' => $this->toadoy,
            'docao' => $this->docao,
            'namlapdat' => $this->namlapdat,
            'ngaycoi' => $this->ngaycoi,
            'created_da' => $this->created_da,
            'last_edi_1' => $this->last_edi_1,
            'enabled' => $this->enabled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(idvan)', mb_strtoupper($this->idvan)])
            ->andFilterWhere(['like', 'upper(idhamkythu)', mb_strtoupper($this->idhamkythu)])
            ->andFilterWhere(['like', 'upper(vatlieu)', mb_strtoupper($this->vatlieu)])
            ->andFilterWhere(['like', 'upper(hieu)', mb_strtoupper($this->hieu)])
            ->andFilterWhere(['like', 'upper(nuocsanxua)', mb_strtoupper($this->nuocsanxua)])
            ->andFilterWhere(['like', 'upper(chieudongv)', mb_strtoupper($this->chieudongv)])
            ->andFilterWhere(['like', 'upper(vitrivan)', mb_strtoupper($this->vitrivan)])
            ->andFilterWhere(['like', 'upper(tinhtrang)', mb_strtoupper($this->tinhtrang)])
            ->andFilterWhere(['like', 'upper(loaivan)', mb_strtoupper($this->loaivan)])
            ->andFilterWhere(['like', 'upper(tinhtrangh)', mb_strtoupper($this->tinhtrangh)])
            ->andFilterWhere(['like', 'upper(trangthai)', mb_strtoupper($this->trangthai)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(ghichuhamk)', mb_strtoupper($this->ghichuhamk)])
            ->andFilterWhere(['like', 'upper(maphuong)', mb_strtoupper($this->maphuong)])
            ->andFilterWhere(['like', 'upper(maquan)', mb_strtoupper($this->maquan)])
            ->andFilterWhere(['like', 'upper(chucnangva)', mb_strtoupper($this->chucnangva)])
            ->andFilterWhere(['like', 'upper(created_us)', mb_strtoupper($this->created_us)])
            ->andFilterWhere(['like', 'upper(last_edite)', mb_strtoupper($this->last_edite)])
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
        'idvan',
        'idhamkythu',
        'cochiakhoa',
        'vatlieu',
        'hieu',
        'nuocsanxua',
        'ngaylapdat',
        'dosau',
        'chieudongv',
        'svdongvan',
        'vitrivan',
        'tinhtrang',
        'covan',
        'loaivan',
        'tinhtrangh',
        'trangthai',
        'madma',
        'toadox',
        'toadoy',
        'docao',
        'ghichu',
        'ghichuhamk',
        'namlapdat',
        'maphuong',
        'maquan',
        'ngaycoi',
        'chucnangva',
        'created_us',
        'created_da',
        'last_edite',
        'last_edi_1',
        'globalid',
        'enabled',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status',
        'geojson',
        'lat',
        'long',        ];
    }
}
