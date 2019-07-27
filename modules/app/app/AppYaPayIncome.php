<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 21.11.2018
 * Time: 18:22
 */

namespace app\modules\app\app;


use app\modules\account\models\AccountPay;
use app\modules\helper\HelperModule;
use app\modules\helper\models\Logs;
use app\modules\pay\models\Pay;
use app\modules\pay\models\PayForm;
use app\modules\pay\models\Trx;

class AppYaPayIncome
{
    
    public $data;

    public $error;
    public $error_message;

    private $secret = '/EzPeEhPL/nbosuAWbFFuLaw';
    
    private function calcSha($arr)
    {

        $valid = HelperModule::checkIsset('notification_type', $arr)
            && HelperModule::checkIsset('operation_id', $arr)
            && HelperModule::checkIsset('amount', $arr)
            && HelperModule::checkIsset('currency', $arr)
            && HelperModule::checkIsset('sender', $arr)
            && HelperModule::checkIsset('datetime', $arr)
            && HelperModule::checkIsset('codepro', $arr)
            && HelperModule::checkIsset('label', $arr)
            && HelperModule::checkIsset('sha1_hash', $arr);

        if (!$valid) {
            Logs::log(' checkIsset', $this->data);
            return false;
        }

        $notification_secret = $this->secret;

        $strIn = [];
        $strIn[] = HelperModule::getAVal('notification_type', $arr);
        $strIn[] = HelperModule::getAVal('operation_id', $arr);
        $strIn[] = HelperModule::getAVal('amount', $arr);
        $strIn[] = HelperModule::getAVal('currency', $arr);
        $strIn[] = HelperModule::getAVal('datetime', $arr);
        $strIn[] = HelperModule::getAVal('sender', $arr);
        $strIn[] = HelperModule::getAVal('codepro', $arr);
        $strIn[] = $notification_secret;
        $strIn[] = HelperModule::getAVal('label', $arr);

        $str = implode('&',$strIn);
        Logs::log('calcSha',[ sha1($str) ,HelperModule::getAVal('sha1_hash', $arr) ] );
        return sha1($str) == HelperModule::getAVal('sha1_hash', $arr);
        // $sha_1 =  sha1('p2p-incoming&1234567&300.00&643&2011-07-01T09:00:00.000+04:00&41001XXXXXXXX&false&01234567890ABCDEF01234567890&YM.label.12345');
        //return $sha_1;



    }
    
    public function message_income(){

        if (!(is_array($this->data) && $this->calcSha($this->data))){
            Logs::log('message_income invalid_data', $this->data);
            return $this->error_message = 'invalid_data';
        }



        /*
        $order_id = (int)$this->data['codepro'];
        $summpay = (int)$this->data['withdraw_amount'];
        */


        $summpay = (int)$this->data['withdraw_amount'];
        $order_id = (int)$this->data['label'];




        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {


            $pay_form = PayForm::findOne(['label' => $order_id]);
            if ($pay_form === null) {
                Logs::log('actionMessageOrderNotFound', [$order_id,$this->data]);
                $this->error_message = 'actionMessageOrderNotFound';
                return null;
            }

            $pay_form->status = 1;
            $pay_form->update(false,['status']);





            $app_acc = AppAccount::Instance();
            $acc = $app_acc ->getAccount($pay_form->user_id);
            $ac_pay = AccountPay::findOne(['account_id'=>$acc->id]);


            //recal value
            $pay_user = Pay::findOne(['id'=>$ac_pay->pay_id]);
            $pay_user->val += $summpay;
            $pay_user->update(false,['val']);

            $pay_yandex = Pay::findOne(['sys_name'=>Pay::YACLI_PAY]);
            $pay_yandex->val += $summpay;
            $pay_yandex->update(false,['val']);

            $trx = new Trx();
            $trx->dt = $pay_yandex->id;
            $trx->kt = $pay_user->id;
            $trx->summ = $summpay;
            $trx->date =  HelperModule::convertDateToDatetime();
            if (! $trx->save()){
                Logs::log('$trxNotSave', $trx->getErrors());
            }



            /*
            if ($acc !== null){
                $ac_pay = AccountPay::findOne(['account_id'=>$acc->id]);
                if ($ac_pay !== null){
                    $pay_user = Pay::findOne(['id'=>$ac_pay->pay_id]);
                    if ($pay_user !== null){
                        //recal value
                        $pay_user->val += $summpay;
                        $pay_user->update(false,['val']);

                        $pay_yandex = Pay::findOne(['sys_name'=>Pay::YACLI_PAY]);

                        if ($pay_yandex !== null){

                            $trx = new Trx();
                            $trx->dt = $pay_yandex->id;
                            $trx->kt = $pay_user->id;
                            $trx->summ = $summpay;
                            $trx->date =  HelperModule::convertDateToDatetime();
                            if (! $trx->save()){
                                Logs::log('$trxNotSave', $trx->getErrors());
                            }
                        } else {
                            Logs::log('$pay_yandex', $pay_yandex->getErrors());
                        }
                    } else {
                        Logs::log('$pay_user', $pay_user->getErrors());
                    }

                } else {
                    Logs::log('$ac_pay', $ac_pay->getErrors());
                }

            }*/


            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
            //throw $e;
        } catch (\Throwable $e) {

            $transaction->rollBack();
            return false;
            //throw $e;
        }


    }

    public function setData($data){
        $this->data = $data;
    }

}