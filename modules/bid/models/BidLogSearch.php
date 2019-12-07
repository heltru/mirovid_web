<?php

namespace app\modules\bid\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bid\models\BidLog;

/**
 * BidLogSearch represents the model behind the search form of `app\modules\bid\models\BidLog`.
 */
class BidLogSearch extends BidLog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'reklamir_id', 'read', 'time_id'], 'integer'],
            [['msg'], 'safe'],
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
        $query = BidLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['reklamir_r']);
        $query->andWhere(['account_id'=>\Yii::$app->getModule('account')->getAccount()->id]);


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
            'read' => $this->read,
            'time_id' => $this->time_id,
        ]);

        $query->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
