<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\GdDonghoKhGd;

/**
 * GdDonghoKhGdSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\GdDonghoKhGd`.
 */
class GdDonghoKhGdSearch extends GdDonghoKhGd
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'codongho', 'hopbaove', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'sothandong', 'loaidongho', 'hieudongho', 'dbdonghonu', 'vitrilapda', 'tinhtrang', 'sohopdong', 'masochi', 'sohoso', 'madma', 'malotrinh', 'tinhtrangq', 'ngaybamchi', 'ngaylapdat', 'ghichu', 'maphuong', 'maquan', 'tenkhachha', 'dtdd', 'ddtb', 'email', 'code', 'code_fu', 'giabieu', 'dinhmuc', 'bithuy', 'ngaycapnha', 'kiemtra', 'globalid', 'diachi', 'created_at', 'updated_at', 'lat', 'long', 'geojson'], 'safe'],
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
        $query = GdDonghoKhGd::find()->where(['status'=>1]);

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
            'hopbaove' => $this->hopbaove,
            'docao' => $this->docao,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(sothandong)', mb_strtoupper($this->sothandong)])
            ->andFilterWhere(['like', 'upper(loaidongho)', mb_strtoupper($this->loaidongho)])
            ->andFilterWhere(['like', 'upper(hieudongho)', mb_strtoupper($this->hieudongho)])
            ->andFilterWhere(['like', 'upper(dbdonghonu)', mb_strtoupper($this->dbdonghonu)])
            ->andFilterWhere(['like', 'upper(vitrilapda)', mb_strtoupper($this->vitrilapda)])
            ->andFilterWhere(['like', 'upper(tinhtrang)', mb_strtoupper($this->tinhtrang)])
            ->andFilterWhere(['like', 'upper(sohopdong)', mb_strtoupper($this->sohopdong)])
            ->andFilterWhere(['like', 'upper(masochi)', mb_strtoupper($this->masochi)])
            ->andFilterWhere(['like', 'upper(sohoso)', mb_strtoupper($this->sohoso)])
            ->andFilterWhere(['like', 'upper(madma)', mb_strtoupper($this->madma)])
            ->andFilterWhere(['like', 'upper(malotrinh)', mb_strtoupper($this->malotrinh)])
            ->andFilterWhere(['like', 'upper(tinhtrangq)', mb_strtoupper($this->tinhtrangq)])
            ->andFilterWhere(['like', 'upper(ngaybamchi)', mb_strtoupper($this->ngaybamchi)])
            ->andFilterWhere(['like', 'upper(ngaylapdat)', mb_strtoupper($this->ngaylapdat)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(maphuong)', mb_strtoupper($this->maphuong)])
            ->andFilterWhere(['like', 'upper(maquan)', mb_strtoupper($this->maquan)])
            ->andFilterWhere(['like', 'upper(tenkhachha)', mb_strtoupper($this->tenkhachha)])
            ->andFilterWhere(['like', 'upper(dtdd)', mb_strtoupper($this->dtdd)])
            ->andFilterWhere(['like', 'upper(ddtb)', mb_strtoupper($this->ddtb)])
            ->andFilterWhere(['like', 'upper(email)', mb_strtoupper($this->email)])
            ->andFilterWhere(['like', 'upper(code)', mb_strtoupper($this->code)])
            ->andFilterWhere(['like', 'upper(code_fu)', mb_strtoupper($this->code_fu)])
            ->andFilterWhere(['like', 'upper(giabieu)', mb_strtoupper($this->giabieu)])
            ->andFilterWhere(['like', 'upper(dinhmuc)', mb_strtoupper($this->dinhmuc)])
            ->andFilterWhere(['like', 'upper(bithuy)', mb_strtoupper($this->bithuy)])
            ->andFilterWhere(['like', 'upper(ngaycapnha)', mb_strtoupper($this->ngaycapnha)])
            ->andFilterWhere(['like', 'upper(kiemtra)', mb_strtoupper($this->kiemtra)])
            ->andFilterWhere(['like', 'upper(globalid)', mb_strtoupper($this->globalid)])
            ->andFilterWhere(['like', 'upper(diachi)', mb_strtoupper($this->diachi)])
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
        'sothandong',
        'loaidongho',
        'hieudongho',
        'dbdonghonu',
        'vitrilapda',
        'tinhtrang',
        'codongho',
        'sohopdong',
        'masochi',
        'sohoso',
        'dosau',
        'madma',
        'malotrinh',
        'tinhtrangq',
        'hopbaove',
        'ngaybamchi',
        'ngaylapdat',
        'docao',
        'ghichu',
        'maphuong',
        'maquan',
        'tenkhachha',
        'dtdd',
        'ddtb',
        'email',
        'code',
        'code_fu',
        'giabieu',
        'dinhmuc',
        'bithuy',
        'ngaycapnha',
        'kiemtra',
        'globalid',
        'diachi',
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
