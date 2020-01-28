<?php
namespace app\modules\app\app;
use app\modules\account\models\Account;
use app\modules\account\models\AccountPay;
use app\modules\block\models\Block;
use app\modules\block\models\BlockMsg;
use app\modules\block\models\Msg;
use app\modules\car\models\Car;
use app\modules\helper\HelperModule;
use app\modules\helper\models\LoginCar;
use app\modules\helper\models\Logs;
use app\modules\zapros\models\Zapros;
use app\modules\pay\models\Pay;
use app\modules\pay\models\PayForm;
use app\modules\pay\models\PayUp;
use app\modules\pay\models\Trx;
use app\modules\show\models\ShowRegister;
use app\modules\user\models\User;
use yii\debug\models\search\Log;
use yii\httpclient\Client;


/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 04.03.2018
 * Time: 6:03
 */
final class AppNovaVidAdminClient
{

    public $account;
    public $user;


    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new AppNovaVidAdminClient();
        }
        return $inst;
    }

    private function __construct()
    {
        if ( \Yii::$app->user->isGuest == false){

            $uId = \Yii::$app->user->getId();

            $this->user = User::findOne(['id'=>$uId]);

            if (is_object($this->user)){
                $this->account = Account::findOne(['user_id'=>$this->user->id ]);
            }


        }

    }


    public function getRkList(){
        if ( ! $this->account){return [];}

        $blocks = Block::find()->where(['account_id'=>$this->account->id,'block.status'=>Block::ST_OK])->all();

        return $blocks;

    }

    public function deleteRkCompany($id){
        $block = Block::findOne(['id'=>$id]);
        if ($block === null) return;

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $links = BlockMsg::findAll(['block_id'=>$id]);
            foreach ($links as $link){
                $msg = Msg::findOne(['id'=>$link->msg_id]);
                if ($msg !== null) {
                    $msg->delete();
                  //  $msg->status = Msg::ST_DL;
                  //  $msg->update(false,['status']);

                }


            }
        //    $block->status = Block::ST_DL;
            //$block->update(false,['status']);
            $block->delete();
            $transaction->commit();
            return true;
        } catch (\Exception $e) {

            $transaction->rollBack();

            return false;
            //throw $e;
        } catch (\Throwable $e) {

            $transaction->rollBack();
            return false;
            //throw $e;
        }

        return $block;

    }


    public function viewRk($id){
        $block = Block::findOne(['id'=>$id]);
        if ($block === null) return null;
        $links = BlockMsg::findAll(['block_id'=>$block->id]);
        $form = new RkNewForm();
        $form->setScenario(RkNewForm::SCENARIO_OLD);

        foreach ( $links as $link){
            $form->setMsg($link->msg_r);
        }


        $form->setBlock($block);



        return \Yii::$app->controller->renderAjax('_view',['form'=>$form]);
    }

    public function deleteMsgFromBlock($id_m,$id_b){
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $link = BlockMsg::findOne(['block_id'=>$id_b,'msg_id'=>$id_m]);
            if ($link !== null){

                $msg = Msg::findOne(['id'=>$link->msg_id]);

                if (  ! ( $msg !== null  )){
                    $msg->delete();
                    $transaction->rollBack();
                    return false;
                }

                $block = Block::findOne(['id'=>$link->block_id]);

                if ( ! ( $block!== null )){
                    $block->delete();
                    $transaction->rollBack();

                    return false;
                }

               $link->delete();
               $transaction->commit();
               return true;


            }

        } catch (\Exception $e) {

            $transaction->rollBack();

            return false;
            //throw $e;
        } catch (\Throwable $e) {

            $transaction->rollBack();
            return false;
            //throw $e;
        }

        return false;
    }

    public function addNewUser($user){
        $summ = 500;

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $acc = new Account();
            $acc->user_id = (int) $user->id;
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

            $transaction->commit();
            return true;
        }catch (\Exception $e) {
            $transaction->rollBack();
            Logs::log('addNewUser',[$e]);
            return null;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Logs::log('addNewUser',[$e]);
            return null;
        }




    }

    public function removeUser($user){

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $account =  Account::find()->where(['user_id'=>$user->id])->one();
            if ($account !== null){
                $account->delete();
                $account_pay = AccountPay::find()->where(['account_id'=>$account->id])->one();

                if ($account_pay !== null){
                    $account_pay->delete();

                    $pay = Pay::find()->where(['id'=>$account_pay->pay_id])->one();
                    if ($pay !== null){
                        $pay->status = Pay::ST_DELETE;
                        $pay->update(false,['status']);
                    }

                }


                foreach (Block::find()->where(['account_id'=>$account->id])->all() as $block){
                    $block->delete();

                    foreach (BlockMsg::find()->where(['block_id'=>$block->id])->all() as $block_msg){
                        $block_msg->delete();
                    }

                    $msg = Msg::findOne(['block_id'=>$block->id]);
                    if ($msg !== null){
                        $msg->delete();
                        foreach (ShowRegister::find()->where(['msg_id'=>$msg->id])->all() as $show_register){
                            $show_register->delete();
                        }
                    }

                }
            }

            foreach (PayForm::find()->where(['user_id'=>$user->id])->all() as $pay_form){
                $pay_form->delete();
            }

            $user->delete();

            $manager = \Yii::$app->authManager;
            $item = $manager->getRole('client');
            $manager->revoke($item,$user->id);

            $transaction->commit();
            return true;


        }catch (\Exception $e) {
            $transaction->rollBack();
            Logs::log('removeUser',[$e]);
            return null;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Logs::log('removeUser',[$e]);
            return null;
        }


    }

    public function getLogoName(){
        if ($this->user){
            if ($this->user->phone){
                return $this->user->phone;
            }
            if ($this->user->username){
                return $this->user->username;
            }
        }
        return 'noname';
    }

    public function getMyRkList(){
        $acc = $this->getMyAccount();
        if ($acc === null){
            return [];
        }
        $all = Block::find()->where(['account_id'=>$acc->id])->all();
        return $all;
    }

    public function getMyAccount(){

        return Account::findOne(['user_id'=>\Yii::$app->user->id]);
    }


}