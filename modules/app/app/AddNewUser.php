<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 19.11.2019
 * Time: 20:13
 */

namespace app\modules\app\app;


use app\modules\account\models\Account;
use app\modules\helper\HelperModule;
use app\modules\helper\models\Logs;
use app\modules\pay\models\Pay;
use app\modules\pay\models\Trx;
use app\modules\user\models\User;

class AddNewUser
{

    private $user;


    public $error;

    public function addNewUser($form){
        $summ = 300;



        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {


            $this->user = new User();
            $this->user->username = $form->username;
            $this->user->email = $form->email;
            $this->user->setPassword($form->password);
            $this->user->status = User::STATUS_ACTIVE;
            $this->user->phone = $form->phone;
            $this->user->generateAuthKey();
            $this->user->generateEmailConfirmToken();
            $this->user->save();

            \Yii::$app->user->login($this->user,  3600*24*30 );


            $acc = new Account();
            $acc->user_id = (int) $this->user->id;
            $acc->status = Account::ST_OK;
            $acc->date_cr = HelperModule::convertDateToDatetime();
            $acc->save();

            $pay_user = new Pay();
            $pay_user->val = $summ;
            $pay_user->account_id = $acc->id;
            $pay_user->save();



            $pay_init = Pay::findOne(['sys_name'=>Pay::INIT_PAY]);

            $trx = new Trx();
            $trx->dt = $pay_init->id;
            $trx->kt = $pay_user->id;
            $trx->summ = $summ;
            $trx->date =  HelperModule::convertDateToDatetime();
            if (! $trx->save()){
                ex($trx->getErrors());
            }

            $pay_init->val += $summ;
            $pay_init->update(false,['val']);


            $auth = \Yii::$app->authManager;
            $client = $auth->getRole('client');
            $auth->assign($client, $this->user->id);

            $transaction->commit();
            return true;
        }catch (\Exception $e) {
            $transaction->rollBack();
            Logs::log('addNewUser',[$e->getMessage()]);
            $this->error = $e->getMessage();
            return null;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Logs::log('addNewUser',[$e]);
            $this->error = $e->getMessage();
            return null;
        }


    }

    public function getUser(){
        return $this->user;
    }
}