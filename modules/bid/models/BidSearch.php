<?php

namespace app\modules\bid\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bid\models\Bid;

/**
 * BidSearch represents the model behind the search form of `app\modules\bid\models\Bid`.
 */
class BidSearch extends Bid
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'reklamir_id', 'val', 'minute_id', 'hour_id', 'mount_id', 'year_id', 'day_id', 'time_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Bid::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'reklamir_id' => $this->reklamir_id,
            'val' => $this->val,
            'minute_id' => $this->minute_id,
            'hour_id' => $this->hour_id,
            'mount_id' => $this->mount_id,
            'year_id' => $this->year_id,
            'day_id' => $this->day_id,
            'time_id' => $this->time_id,
        ]);

        return $dataProvider;
    }
}
