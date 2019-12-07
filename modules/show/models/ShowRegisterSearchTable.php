<?php

namespace app\modules\show\models;

use app\modules\helper\models\Helper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\show\models\ShowRegister;

/**
 * ShowRegisterSearchTable represents the model behind the search form of `app\modules\show\models\ShowRegister`.
 */
class ShowRegisterSearchTable extends ShowRegister
{
    /**
     * {@inheritdoc}
     */

    public $date_from;
    public $date_to;

    public function rules()
    {
        return [
            [['id', 'file_id', 'time', 'reklamir_id'], 'integer'],
            [['date_sh','reklamir_id'], 'safe'],
            [['lat', 'long'], 'number'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
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
        $query = ShowRegister::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->innerJoin('reklamir','reklamir.id = show_register.reklamir_id AND reklamir.account_id = :acc_id',['acc_id'=>
        \Yii::$app->getModule('account')->getAccount()->id
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
            'file_id' => $this->file_id,
            'date_sh' => $this->date_sh,
            'lat' => $this->lat,
            'long' => $this->long,
            'time' => $this->time,
            'reklamir_id' => $this->reklamir_id,
        ]);



        $query->andFilterWhere(['>=', 'date_sh', $this->date_from ?  date( 'Y-m-d H:i:s',strtotime($this->date_from . ' 00:00:00') ) : null])
            ->andFilterWhere(['<=', 'date_sh', $this->date_to ? date( 'Y-m-d H:i:s',  strtotime($this->date_to . ' 23:59:59')) : null]);



        return $dataProvider;
    }
}
