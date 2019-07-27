<?php

namespace app\modules\block\models;



use Yii;


class CountLimitShowTotalByAccount
{


    public $limit_total=0;

    public function limit_show_total(){
        $query = Msg::find();
        //      $query->select('*.block, msg.account_sort as account_sort');
        $query->innerJoin('block_msg','block_msg.msg_id = msg.id');
        $query->innerJoin('block',' block_msg.block_id = block.id');
        $query->andWhere([ 'msg.status' => [Msg::ST_OK ,Msg::ST_MR ]]);
        $query->andWhere(['block.status' => Block::ST_OK ]);
        $query->innerJoin('account' ,'block.account_id = account.id' );
        $query->innerJoin('user' ,'user.id = account.user_id' );
        $query->andWhere(['user.id'=> Yii::$app->user->getId()]);

        return $query->sum('count_limit');

    }





}
