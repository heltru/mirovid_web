<?php

namespace app\modules\show\models;

use app\modules\app\app\AppAccount;
use app\modules\reklamir\models\Reklamir;
use app\modules\show\models\ShowRegister;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


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

    public $count_items=100;



    public function rules()
    {
        return [
            [['reklamir_id','count_items'], 'integer'],
            [['name','date_from','date_to'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return parent::attributeLabels() + [
                'count_items' => 'Меток на странице',
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


        $query = ShowRegister::find()->joinWith(['reklamir_r','reklamir_r.file_r']);
        $query->andWhere(['>','lat',0]);
        $query->andWhere(['>','long',0]);
        $query->limit(3);

        $this->load($params);


        if ($this->date_from && $this->date_to){
            $query->where(['between', 'date_sh', $this->date_from, $this->date_to]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->count_items,
            ],
        ]);


        if ($this->reklamir_id){
            $query->andWhere(['reklamir_id'=>$this->reklamir_id]);
        } else {
            $query->andWhere(['reklamir_id'=> ArrayHelper::getColumn(
                Reklamir::find()->where(['account_id'=>Yii::$app->getModule('account')->getAccount()->id])->all(),'id') ]);
        }



        if (!$this->validate()) {
            return $dataProvider;
        }
      // ex($query->createCommand()->rawSql);

        return $dataProvider;
    }
}
