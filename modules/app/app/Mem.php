<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 11.11.2018
 * Time: 0:10
 */

namespace app\modules\app\app;


use app\modules\block\models\Msg;

class Mem
{


    public function getMem($id){
        $mem = Msg::find()->where(['id'=>$id])->one();
        return $mem;
    }

}