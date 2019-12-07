<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.11.2018
 * Time: 15:08
 */

namespace app\modules\app\app;


use app\modules\account\models\AccountPay;
use app\modules\pay\models\Pay;

class AppBalance
{



    public function getBalanceByCurrAccount(){
        $app_acc = new AppAccount();
        $acc = $app_acc->getAccount();
        if ($acc !== null){


            $pay_user = Pay::findOne(['account_id'=>$acc->id]);

            return $pay_user->val;
        }

        return 0;

    }

    public function debitByAccount($account_id,$summ){
        $pay_user = Pay::findOne(['account_id'=>$account_id]);
    }

}