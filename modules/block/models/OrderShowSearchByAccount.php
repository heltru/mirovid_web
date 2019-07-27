<?php

namespace app\modules\block\models;



use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * ProductIndexSearch represents the model behind the search form about `common\models\Product`.
 */
class OrderShowSearchByAccount extends Msg
{


    public $limit_total=0;

    public static function tableName()
    {
        return 'msg';
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
        $query = Msg::find();
  //      $query->select('*.block, msg.account_sort as account_sort');
        $query->innerJoin('block_msg','block_msg.msg_id = msg.id');
        $query->innerJoin('block',' block_msg.block_id = block.id');
        $query->andWhere([ 'msg.status' => [Msg::ST_OK ,Msg::ST_MR ]]);
        $query->andWhere(['block.status' => Block::ST_OK ]);
        $query->innerJoin('account' ,'block.account_id = account.id' );
        $query->innerJoin('user' ,'user.id = account.user_id' );
        $query->andWhere(['user.id'=> Yii::$app->user->getId()]);


        $query->orderBy('msg.account_sort');

       //  ex( $query->createCommand()->rawSql );

        // add conditions that should always apply here


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
           'pagination' => false,
        ]);


        // grid filtering conditions
        /*$query->andFilterWhere([
            'id' => $this->id,
            'account_id' => $this->account_id,
            'date_cr' => $this->date_cr,
        ]);*/

        // $query->andFilterWhere(['like', 'status', $this->status])
   //     $query->andFilterWhere(['like', 'type', $this->type]);

      //  $query->andWhere(['block.status'=>\app\modules\block\models\Block::ST_OK]);



        return $dataProvider;
    }




}
