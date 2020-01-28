<?php

namespace app\modules\payment\controllers\frontend;

use app\modules\account\models\AccountPay;
use app\modules\app\app\AppAccount;
use app\modules\app\app\AppNovaVidShow;
use app\modules\app\app\AppYaPayIncome;
use app\modules\app\app\FormData;
use app\modules\helper\HelperModule;
use app\modules\helper\models\Helper;
use app\modules\helper\models\Logs;
use app\modules\zapros\models\Zapros;

use app\modules\pay\models\Pay;
use app\modules\pay\models\PayForm;
use app\modules\pay\models\Trx;
use app\modules\payment\app\Settings;

use app\modules\payment\app\YaMoneyCommonHttpProtocol;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `payment` module
 */
class YandexController extends Controller
{

    public $enableCsrfValidation = false;




    public function behaviors()
    {
        return [

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'message' => ['POST'],
                    'check' => ['POST'],
                    'aviso' => ['POST'],
                    'fail' => ['POST'],
                    'success' => ['POST'],
                ],
            ],
        ];
    }

    public function actionSuccessUrl()
    {
        $this->layout = 'landing';
        return $this->render('/success-url');
    }

    public function actionTest()
    {
            return $this->calcSha([]);
    }

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
        if (!$valid) return false;

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


    public function actionMessage()
    {

        Logs::log('paymentAviso', $_REQUEST);

        $app_ya_pay_income = new AppYaPayIncome();
        $app_ya_pay_income->setData($_REQUEST);
        $app_ya_pay_income->message_income();

        exit;
    }

    public function actionCheck()
    {
        Logs::log('check', $_REQUEST);
        $settings = new Settings();
        $yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol("checkOrder", $settings);
        $yaMoneyCommonHttpProtocol->processRequest($_REQUEST);
        Logs::log('end-check', $_REQUEST);
        \Yii::$app->end();

    }

    public function actionAviso()
    {
        Logs::log('paymentAviso', $_REQUEST);
        $settings = new Settings();
        $yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol("paymentAviso", $settings); //$_REQUEST['action']
        $yaMoneyCommonHttpProtocol->processRequest($_REQUEST);
        \Yii::$app->end();


    }

    public function actionSuccess()
    {
        $request = \Yii::$app->request->post();
        Logs::log('success', $request);
        //   ex($request);
    }

    public function actionFail()
    {
        $request = \Yii::$app->request->post();
        Logs::log('fail', $request);
        //   ex($request);
    }


}
