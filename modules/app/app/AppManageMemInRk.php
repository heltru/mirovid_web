<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.09.2018
 * Time: 1:30
 */

namespace app\modules\app\app;


use app\modules\block\models\BlockMsg;
use app\modules\block\models\Msg;
use Symfony\Component\Console\Helper\Helper;
use yii\helpers\BaseFileHelper;

class AppManageMemInRk
{



    public function memAddToRk($msg_id,$block_id){
        $old = BlockMsg::findOne(['block_id'=>$block_id,'msg_id'=>$msg_id]);

        if ($old === null){
            $link = new BlockMsg();
            $link->block_id = $block_id;
            $link->msg_id = $msg_id;
            return $link->save();

        }
    }

    public function memRemoveToRk($msg_id,$block_id){
        $old = BlockMsg::findOne(['block_id'=>$block_id,'msg_id'=>$msg_id]);

        if ($old !== null){

            return $old->delete();
        }
        return false;
    }



}