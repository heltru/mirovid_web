<?php

namespace app\modules\pay;
use app\modules\app\app\AppYaPayMake;

/**
 * pay module definition class
 */
class PayModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\pay\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function makeFormPay($summ,$paymentType){
        $app_pay_make = new AppYaPayMake();
        $app_pay_make->summ = $summ;
        $app_pay_make->paymentType = $paymentType;

        $location = $app_pay_make->make_form(); // make_redirect to yandex

        if ( empty($app_pay_make->error)){
           return $location;
        } else {
            throw new \Exception('ошибка получения формы платежа от yandex');
        }

    }
}
