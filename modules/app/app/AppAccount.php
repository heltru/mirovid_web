<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.09.2018
 * Time: 23:55
 */

namespace app\modules\app\app;


use app\modules\account\models\Account;

class AppAccount
{

    private $account;

    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {

            $inst = new AppAccount();

        }
        return $inst;
    }



    public function getAccount($user_id=null){
        if ($this->account === null){

            if ($user_id !== null){
                $this->account = Account::findOne(['user_id'=>$user_id]);
            } else {
                $this->account = Account::findOne(['user_id'=>\Yii::$app->user->id]);
            }


        }

        return $this->account;
    }


    public function getLimitMsg(){
        return 30;
    }

    public function getLimitCompany(){
        return 10;
    }

}