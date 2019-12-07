<?php

namespace app\modules\account;
use app\modules\account\models\Account;
use app\modules\account\models\AccountPay;
use app\modules\app\app\AppAccount;
use app\modules\pay\models\Pay;

/**
 * account module definition class
 */
class AccountModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\account\controllers';

    private $app_account;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->app_account = new AppAccount();
    }

    public function getAccount(){
        return  $this->app_account->getAccount();
    }






}
