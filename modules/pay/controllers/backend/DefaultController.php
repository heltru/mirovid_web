<?php

namespace app\modules\pay\controllers\backend;

use app\modules\app\app\AppYaPayMake;
use app\modules\helper\HelperModule;
use app\modules\helper\models\Helper;
use app\modules\pay\models\PayForm;
use yii\web\Controller;

/**
 * Default controller for the `pay` module
 */
class DefaultController extends Controller
{


    public function actionPayInfo()
    {
        return $this->render('pay-info');
    }

    public function actionMakeFormPay()
    {

        if (\Yii::$app->request->isPost){

            $summ = (int)\Yii::$app->request->post('summ');
            $paymentType = \Yii::$app->request->post('paymentType');

            try{

                $ya_windows_pay_location =  \Yii::$app->getModule('pay')->makeFormPay($summ,$paymentType);  // make_redirect to yandex
                header("Location: $ya_windows_pay_location");

                die();
            } catch (   \Exception $e){
                ex('ошибка при создании формы платежа actionMakeFormPay ' . $e->getMessage());
            }

        }

        return $this->render('pay-info');
    }

}
