<?php

namespace app\modules\show\models;

use app\modules\app\app\AppAccount;
use app\modules\show\models\ShowRegister;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * BadgeSearch represents the model behind the search form about `common\models\Badge`.
 */
class ShowRegisterSearch extends ShowRegister
{
    /**
     * @inheritdoc
     */


    public $date_from;
    public $date_to;



    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name','date_from','date_to'], 'safe'],
        ];
    }


  public $time_filter;


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
        $appAc = AppAccount::Instance();

        $query = ShowRegister::find();

        $query->limit(3);



        if ($this->date_from && $this->date_to){
            $query->where(['between', 'date', $this->date_from, $this->date_to]);
        }


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1500,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        return $dataProvider;
    }
}
