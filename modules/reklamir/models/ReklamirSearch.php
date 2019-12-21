<?php

namespace app\modules\reklamir\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\reklamir\models\Reklamir;

/**
 * ReklamirSearch represents the model behind the search form of `app\modules\reklamir\models\Reklamir`.
 */
class ReklamirSearch extends Reklamir
{

    public $place_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'thing_id', 'file_id', 'account_id', 'show', 'status','place_id'], 'integer'],
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

        $query = Reklamir::find()->where(['account_id'=>\Yii::$app->getModule('account')->getAccount()->id]);

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

        $query->innerJoin('thing','thing.id=reklamir.thing_id');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'thing_id' => $this->thing_id,
            'file_id' => $this->file_id,
            'place_id' => $this->place_id,


            'show' => $this->show,
            'status' => $this->status,
        ]);



        return $dataProvider;
    }

}
