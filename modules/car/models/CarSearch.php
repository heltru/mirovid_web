<?php

namespace app\modules\car\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\car\models\Car;

/**
 * CarSearch represents the model behind the search form of `app\modules\car\models\Car`.
 */
class CarSearch extends Car
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'online', 'status', 'num_tableled'], 'integer'],
            [['name', 'number', 'date_cr'], 'safe'],
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
        $query = Car::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
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
            'online' => $this->online,
            'status' => $this->status,
            'num_tableled' => $this->num_tableled,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'number', $this->number]);


        return $dataProvider;
    }
}
