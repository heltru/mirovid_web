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
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPayInfo()
    {
        return $this->render('pay-info');
    }

    public function actionMakeFormPay()
    {

        if (\Yii::$app->request->isPost){

            $summ = (int)\Yii::$app->request->post('summ');
            $paymentType = \Yii::$app->request->post('paymentType');

            $app_pay_make = new AppYaPayMake();
            $app_pay_make->summ = $summ;
            $app_pay_make->paymentType = $paymentType;
            $location = $app_pay_make->make_form(); // make_redirect to yandex

            if ( empty($app_pay_make->error)){
                header("Location: $location");
                die();

            }

        }

        return $this->render('pay-info');
    }

}
