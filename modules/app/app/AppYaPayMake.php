<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 21.11.2018
 * Time: 18:21
 */

namespace app\modules\app\app;


use app\modules\helper\HelperModule;
use app\modules\pay\models\PayForm;

class AppYaPayMake
{

    public  $successURL = 'mirovid/admin/pay/default/pay-info';//http://

    public $summ;
    public $paymentType;

    public $error;




    public function make_form(){


        $summ = $this->summ;
        $paymentType = $this->paymentType;



        $guid = GUID();
        $pay_form = new PayForm();
        $pay_form->summ = $summ;
        $pay_form->date = HelperModule::convertDateToDatetime();
        $pay_form->label = $guid;
        $pay_form->user_id = \Yii::$app->user->getId();

        if (! $pay_form->save()){
            ex($pay_form);
        }

        $post = [
            'receiver' => '410015089945946',
            'label' =>  $pay_form->label,
            'paymentType' => $paymentType,
            'quickpay-form'   => 'donate',
            'targets'   => 'платеж номер ' . $pay_form->label,
            'sum'   => $summ,
            'need-fio'   =>false,
            'need-phone'   => false,
            'need-address'   => false,
            'successURL'   =>  $this->successURL,

        ];

        $url = 'https://money.yandex.ru/quickpay/confirm.xml';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        $result = curl_exec($ch);

        if (preg_match('~Location: (.*)~i', $result, $match)) {
            $location = trim($match[1]);
            return $location;
        }

        $this->error = 'error';

    }

}