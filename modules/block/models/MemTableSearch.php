<?php

namespace app\modules\block\models;



use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class MemTableSearch extends Msg
{



    public function search($params,$block_id)
    {
        $query = Msg::find();
        $query->where(['block_id'=>$block_id]);
        $query->andWhere(['!=','msg.status',Msg::ST_DL]);



        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);


        return $dataProvider;
    }
}
