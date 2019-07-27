<?php

namespace app\modules\block\models;



use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class BlockMsgSearch extends Msg
{



    public function search($params,$block_id)
    {
        $query = Msg::find();
        $query->where(['block_id'=>$block_id]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false
        ]);


        return $dataProvider;
    }
}
