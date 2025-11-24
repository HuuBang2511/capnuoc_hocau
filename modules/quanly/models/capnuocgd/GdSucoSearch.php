<?php

namespace app\modules\quanly\models\capnuocgd;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\GdSuco;

/**
 * GdSucoSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\GdSuco`.
 */
class GdSucoSearch extends GdSuco
{
    /**
     * @inheritdoc
     */


    public $date_from;
    public $date_to;
    public $field_date;

    public function rules()
    {
        return [
            [['id', 'objectid', 'duongkinho', 'tontai', 'created_by', 'updated_by', 'status'], 'integer'],
            [['geom', 'masuco', 'madanhba', 'sonha', 'duong', 'ngayphathien', 'nguoiphathien', 'ngaysuachua', 'nguoisuachua', 'donvisuachua', 'hinhthucphuchoi', 'vitriphathien', 'nguyennhan', 'bienphapxuly', 'vatlieu_ong', 'tailapmatduong', 'kichthuocp', 'ghichu', 'lat', 'long', 'geojson', 'created_at', 'updated_at', 'xulysuco_id'], 'safe'],
            [['field_date'], 'string'],
            [['date_from', 'date_to'], 'safe'],
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
        $query = GdSuco::find()->where(['status'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->date_from) {
            $query->andFilterWhere(['>=', $this->field_date, $this->date_from]);
        }

        if ($this->date_to) {
            $query->andFilterWhere(['<=', $this->field_date, $this->date_to]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'objectid' => $this->objectid,
            'xulysuco_id' => $this->xulysuco_id,
            'ngayphathien' => $this->ngayphathien,
            'ngaysuachua' => $this->ngaysuachua,
            'duongkinho' => $this->duongkinho,
            'tontai' => $this->tontai,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(masuco)', mb_strtoupper($this->masuco)])
            ->andFilterWhere(['like', 'upper(madanhba)', mb_strtoupper($this->madanhba)])
            ->andFilterWhere(['like', 'upper(sonha)', mb_strtoupper($this->sonha)])
            ->andFilterWhere(['like', 'upper(duong)', mb_strtoupper($this->duong)])
            ->andFilterWhere(['like', 'upper(nguoiphathien)', mb_strtoupper($this->nguoiphathien)])
            ->andFilterWhere(['like', 'upper(nguoisuachua)', mb_strtoupper($this->nguoisuachua)])
            ->andFilterWhere(['like', 'upper(donvisuachua)', mb_strtoupper($this->donvisuachua)])
            ->andFilterWhere(['like', 'upper(hinhthucphuchoi)', mb_strtoupper($this->hinhthucphuchoi)])
            ->andFilterWhere(['like', 'upper(vitriphathien)', mb_strtoupper($this->vitriphathien)])
            ->andFilterWhere(['like', 'upper(nguyennhan)', mb_strtoupper($this->nguyennhan)])
            ->andFilterWhere(['like', 'upper(bienphapxuly)', mb_strtoupper($this->bienphapxuly)])
            ->andFilterWhere(['like', 'upper(vatlieu_ong)', mb_strtoupper($this->vatlieu_ong)])
            ->andFilterWhere(['like', 'upper(tailapmatduong)', mb_strtoupper($this->tailapmatduong)])
            ->andFilterWhere(['like', 'upper(kichthuocp)', mb_strtoupper($this->kichthuocp)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
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
        'masuco',
        'madanhba',
        'sonha',
        'duong',
        'ngayphathien',
        'nguoiphathien',
        'ngaysuachua',
        'nguoisuachua',
        'donvisuachua',
        'hinhthucphuchoi',
        'vitriphathien',
        'nguyennhan',
        'bienphapxuly',
        'duongkinho',
        'vatlieu_ong',
        'tailapmatduong',
        'kichthuocp',
        'ghichu',
        'tontai',
        'lat',
        'long',
        'geojson',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'status',        ];
    }
}
