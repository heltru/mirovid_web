<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 11.11.2018
 * Time: 19:24
 */
namespace app\modules\app\app;

use app\modules\app\app\AppPrice;
use app\modules\block\models\Msg;
use app\modules\show\models\ShowRegister;
use yii\db\Transaction;
use app\modules\helper\HelperModule;

class RegisterShow
{


    public $show_register;
    public $msg;

    public function __construct()
    {
        $this->show_register = new ShowRegister();

    }

    public function register_show($data){

        $this->show_register->setAttributes($data);
        $this->show_register->date_sh = date("Y-m-d H:i:s" ) ;

        /*
        if (! $this->show_register->save()){
            return $this->show_register->getErrors();
        }
        */

        $reg = new ShowRegister();
        $reg->date_sh = HelperModule::convertDateToDatetime();
        $reg->lat = $data['lat'];
        $reg->long = $data['long'];
        $reg->file_id = $data['file_id'];
        $reg->save();


        /*
        $msg = Msg::find()->joinWith(['block_r.account_r'])->where(['msg.id'=> $this->show_register->msg_id])->one();

        if ($msg !== null){
            $app = new AppPrice();


            if (
                true
            //    $msg->count_show < $msg->count_limit && $msg->block_r->account_r->balance > 0
            ){
                $msg->count_show += 1;
                $msg->count_total += 1;

                $msg->block_r->account_r->balance -= $app->getPriceMsg();

                $transaction = \Yii::$app->db->beginTransaction(
                    Transaction::SERIALIZABLE
                );
                try {

                    $msg->block_r->account_r->update(false,['balance']);
                    $msg->update(false,['count_show','count_total']);


                    $reg = new ShowRegister();
                    $reg->date_sh = HelperModule::convertDateToDatetime();
                    $reg->lat = $data['lat'];
                    $reg->long = $data['long'];
                    $reg->msg_id = $msg->id;
                    $reg->save();


                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }


            }


        }
*/
        return true;
    }

    public function actionRegisterShowOffline(){

    }

}