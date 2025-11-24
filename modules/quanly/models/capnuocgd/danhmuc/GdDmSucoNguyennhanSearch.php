<?php

namespace app\modules\quanly\models\capnuocgd\danhmuc;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\capnuocgd\danhmuc\GdDmSucoNguyennhan;

/**
 * GdDmSucoNguyennhanSearch represents the model behind the search form about `app\modules\quanly\models\capnuocgd\danhmuc\GdDmSucoNguyennhan`.
 */
class GdDmSucoNguyennhanSearch extends GdDmSucoNguyennhan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'status'], 'integer'],
            [['ten', 'ma', 'ghichu', 'created_at', 'updated_at'], 'safe'],
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
        $query = GdDmSucoNguyennhan::find()->where(['status'=>1]);

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(ten)', mb_strtoupper($this->ten)])
            ->andFilterWhere(['like', 'upper(ma)', mb_strtoupper($this->ma)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'ten',
        'ma',
        'ghichu',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status',        ];
    }
}
