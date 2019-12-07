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
use app\modules\helper\models\Helper;
use app\modules\helper\models\Logs;
use app\modules\pay\models\Pay;
use app\modules\pay\models\Trx;
use app\modules\reklamir\models\Reklamir;
use app\modules\show\models\ShowRegister;
use yii\db\Transaction;
use app\modules\helper\HelperModule;

class RegisterShow
{


    public $show_register;
    private $lat;
    private $long;
    private $reklamir_id;

    public $result;

    public function __construct($lat,$long,$reklamir_id)
    {
        $this->show_register = new ShowRegister();

        $this->lat = $lat;
        $this->long = $long;
        $this->reklamir_id = $reklamir_id;



    }

    public function begin(){



        $this->show_register->reklamir_id = $this->reklamir_id;
        $this->show_register->long = $this->long;
        $this->show_register->lat = $this->lat;

        $this->show_register->date_sh = date("Y-m-d H:i:s" ) ;
        $this->show_register->save();



        try{
            $reklama = Reklamir::find()->where(['reklamir.id'=>$this->reklamir_id])->joinWith(['thing_r','thing_r.place_r'])->one();
        } catch (\Exception $e){
            Logs::log('find Reklamir', $e->getMessage());
            return;
        }


        if ($reklama !== null){

            $price_show = $reklama->thing_r->place_r->price_show;
            $reklama->show = (int)$reklama->show + 1;

            $reklama->update(false,['show']);

            $pay_user = Pay::find()->where(['account_id'=>$reklama->account_id])->andWhere([ '>','val',0])->one();
            if ($pay_user !== null){
                $pay_user->val -= $price_show;
                $pay_user->update(false,['val']);


                $pay_yandex = Pay::findOne(['sys_name'=>Pay::YAWOFF_PAY]);
                $pay_yandex->val += $price_show;
                $pay_yandex->update(false,['val']);

                $trx = new Trx();
                $trx->dt = $pay_user->id;
                $trx->kt = $pay_yandex->id;
                $trx->summ = $price_show;
                $trx->type = 1;
                $trx->date =  HelperModule::convertDateToDatetime();
                if (! $trx->save()){
                    Logs::log('$trxNotSave', $trx->getMessage());
                }
            }

        }


        return true;
    }

}