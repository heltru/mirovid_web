<?php
namespace app\modules\payment\app;
/**
 * Created by PhpStorm.
 * User: o.trushkov
 * Date: 19.02.18
 * Time: 16:32
 */
class Settings
{

    public $shopPassword = "grAe3g8";
    public $SECURITY_TYPE;
    public $LOG_FILE;
    public $shopId;
    public $scId;
    public $currency;
    public $request_source;
    public $mws_cert;
    public $mws_private_key;
    public $mws_cert_password = "123456";
    public $paymentAction  = 'https://demomoney.yandex.ru/eshop.xml';

    // 'https://money.yandex.ru/eshop.xml'

    function __construct($SECURITY_TYPE = "MD5" /* MD5 | PKCS7 */, $request_source = "php://input") {
        $this->SECURITY_TYPE = $SECURITY_TYPE;
        $this->request_source = $request_source;
        $this->LOG_FILE = dirname(__FILE__)."/log.txt";
        $this->mws_cert = dirname(__FILE__)."/mws/shop.cer";
        $this->mws_private_key = dirname(__FILE__)."/mws/private.key";

        $this->shopId = \Yii::$app->params['shopId'];
        $this->currency = \Yii::$app->params['currency'];
        $this->scId = \Yii::$app->params['scId'];
        $this->shopPassword = \Yii::$app->params['shopPassword'];
        $this->paymentAction = \Yii::$app->params['paymentAction'];


    }

}