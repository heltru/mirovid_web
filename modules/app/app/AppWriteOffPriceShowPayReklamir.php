<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.11.2019
 * Time: 18:11
 */

namespace app\modules\app\app;


use app\modules\helper\HelperModule;
use app\modules\helper\models\Logs;
use app\modules\pay\models\Pay;
use app\modules\pay\models\Trx;
use app\modules\reklamir\models\Reklamir;

class AppWriteOffPriceShowPayReklamir
{

    public function __construct($reklamir_id)
    {
        $reklama = Reklamir::find()->where(['id'=>$reklamir_id])->joinWith(['thing_r'])->one();

        if ($reklama !== null){
            $app_price = new AppPrice($reklama->thing_r);

            $reklama->show  += 1;
            $reklama->update(false,['show']);

            $pay_user = Pay::find()->where(['account_id'=>$reklama->account_id])->andWhere([ '>','val',0])->one();
            if ($pay_user !== null){
                $pay_user->val -= $app_price->getPriceShow();
                $pay_user->update(false,['val']);


                $pay_yandex = Pay::findOne(['sys_name'=>Pay::YAWOFF_PAY]);
                $pay_yandex->val += $app_price->getPriceShow();
                $pay_yandex->update(false,['val']);

                $trx = new Trx();
                $trx->dt = $pay_user->id;
                $trx->kt = $pay_yandex->id;
                $trx->summ = $app_price->getPriceShow();
                $trx->type = 1;
                $trx->date =  HelperModule::convertDateToDatetime();
                if (! $trx->save()){
                    Logs::log('$trxNotSave', $trx->getErrors());
                }
            }

        }
    }
}