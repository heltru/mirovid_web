<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 23.12.2018
 * Time: 2:15
 */

namespace app\modules\app\app;


use app\modules\block\models\BlockMsg;

class AppMemDelete
{

    public function delete_mem($mem){
        $mem->delete();

        foreach ( BlockMsg::findAll(['msg_id'=>$mem->id]) as $item){

            $item->delete();
        }




    }

}