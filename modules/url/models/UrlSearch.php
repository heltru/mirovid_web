<?php

namespace app\modules\url\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * UrlSearch represents the model behind the search form about `common\models\Url`.
 */
class UrlSearch extends Url
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'redirect', 'crs', 'domain_id'], 'integer'],
            [['href', 'real_canonical', 'title', 'h1', 'description_meta', 'type_url', 'last_mod'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
   public function scenarios()
   {
       return parent::scenarios(); // TODO: Change the autogenerated stub
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
        $query = Url::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
          'pageSize' => 60,
      ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'type_url' => $this->type_url
        ]);
        $query->andFilterWhere(['like', 'href', $this->href]);
/*
        $query->andFilterWhere(['like', 'href', $this->href])
            ->andFilterWhere(['like', 'real_canonical', $this->real_canonical])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'h1', $this->h1])
            ->andFilterWhere(['like', 'description_meta', $this->description_meta])
            ->andFilterWhere(['like', 'type_url', $this->type_url]);*/

        return $dataProvider;
    }
}
