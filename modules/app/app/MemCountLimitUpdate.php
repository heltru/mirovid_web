<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.11.2018
 * Time: 17:08
 */

namespace app\modules\app\app;


use app\modules\block\models\Msg;

class MemCountLimitUpdate
{

    public $msg='';

    public function update_count_limit($msg_id,$new_value){

        $mem = Msg::find()->where(['id'=>$msg_id])->one();

        if ($mem === null){
            return;
        }

        if ( $mem->count_show <= $new_value){
            $mem->count_limit = $new_value;
        }


        $mem->update(false,['count_limit']);

    }

}