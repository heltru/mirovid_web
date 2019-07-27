<?php

namespace app\modules\block\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\block\models\Block;

/**
 * BlockSearch represents the model behind the search form of `app\modules\block\models\Block`.
 */
class BlockSearch extends Block
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'account_id'], 'integer'],
            [['status', 'date_cr', 'type'], 'safe'],
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
        $query = Block::find();

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
            'account_id' => $this->account_id,
            'date_cr' => $this->date_cr,
        ]);

       // $query->andFilterWhere(['like', 'status', $this->status])
        $query->andFilterWhere(['like', 'type', $this->type]);

        $query->andWhere(['block.status'=>\app\modules\block\models\Block::ST_OK]);
        $query->innerJoin('account' ,'block.account_id = account.id' );
        $query->innerJoin('user' ,'user.id = account.user_id' );
        $query->andWhere(['user.id'=> Yii::$app->user->getId()]);
        return $dataProvider;
    }
}
