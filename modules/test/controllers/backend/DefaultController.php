<?php

namespace app\modules\test\controllers\backend;

use app\modules\app\app\AppCreateMem;
use app\modules\app\app\AppMemDelete;
use app\modules\app\app\AppNovaVidShow;
use app\modules\block\models\Block;
use app\modules\block\models\BlockMsg;
use app\modules\block\models\Msg;
use app\modules\block\models\MsgDaytime;
use app\modules\block\models\MsgLocale;
use app\modules\block\models\MsgLocaleCost;
use app\modules\car\models\Car;
use app\modules\helper\models\Helper;
use app\modules\test\app\SiteError;
use yii\web\Controller;
use Yii;
use yii\base\Model;


/**
 * Default controller for the `test` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionDelTestData(){

        $block = Block::findOne(['status'=>Block::ST_TEST]);

        if ($block === null){
            ex('Empty Block');
        }

        foreach ( Msg::findAll(['block_id'=>$block->id]) as $msg){
            $app = new AppMemDelete();
            $app->delete_mem($msg);
        }
    }

    public function actionTestShowReg(){
      ex(
          file_get_contents('http://mirovid/api/car/register-show?file_name=1_5_output-onlinepngtools.png&lat=1&long=2')
      );
    }

    public function actionGenTestData(){

        $block = Block::findOne(['status'=>Block::ST_TEST]);
        if ($block === null){
            ex('Empty Block');
        }


        $app = AppNovaVidShow::Instance();


        foreach ($app->matrix_locales as $num => $value){

            $num_geo = $num+1;

            $time_id = rand(1,336-(336/2));

            $app_cr = new AppCreateMem();
            $msg = new Msg();

            $msg->block_id = $block->id;
            $msg->type = Msg::T_T;
            $msg->content = 'Geo: ' . $num_geo .  ' TF:' . $time_id;
            $msg->date_cr = Helper::mysql_datetime();
            $msg->date_update = Helper::mysql_datetime();
            $msg->status = Msg::ST_OK;


            if ( !  $app_cr->createMem($msg,$block,$msg->getAttributes()) ){
                ex($msg->getErrors());
            }






            $gl = new MsgLocale();
            $gl->msg_id = $msg->id;
            $gl->locale_id = $num_geo;
            $gl->save();



            $glc = new MsgLocaleCost();
            $glc->msg_id = $msg->id;
            $glc->locale_id = $num_geo;
            $glc->cost = 1;
            $glc->save();








        }





    }

    public function actionTestTime(){

        $this->layout = false;
        return $this->render('time');
    }

    public function actionTest(){

        $this->layout = false;
        return $this->render('test');
    }

    public function actionUpdate()
    {
        $settings = Car::find()->indexBy('id')->limit(1)->all();



        if (Model::loadMultiple($settings, Yii::$app->request->post()) && Model::validateMultiple($settings)) {
            foreach ($settings as $setting) {
                $setting->save(false);
            }
            return $this->redirect('index');
        }

        return $this->render('update', ['settings' => $settings]);
    }

    public function actionWp(){

        $data = [
         //   'title'=>'new post 777000' ,
            //'post_views'=>42342342,
        //    '_aioseop_title'=>'nice title 4',
          //  '_aioseop_description'=>'nice 3 '
            'author'=>1,
            'content'=>1,
            'post'=>55,
            'rating'=>5
        ];

        $process = curl_init('http://wordpress/wp-json/wp/v2/comments');
        //curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', $additionalHeaders));
        curl_setopt($process, CURLOPT_HEADER, 1);
        curl_setopt($process, CURLOPT_USERPWD, 'admin' . ":" . 'qwerty');
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($process);
        ex($return);
        curl_close($process);
    }


    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionPay(){
        $data_post = array (
            'notification_type' => 'card-incoming',
            'zip' => '',
            'amount' => '1.96',
            'firstname' => '',
            'codepro' => 'false',
            'withdraw_amount' => '2.00',
            'city' => '',
            'unaccepted' => 'false',
            'label' => '29',
            'building' => '',
            'lastname' => '',
            'datetime' => '2018-03-08T20:17:29Z',
            'suite' => '',
            'sender' => '',
            'phone' => '',
            'sha1_hash' => '17fc8925d280aa5472d3102bf99de5d32c2ad693',
            'street' => '',
            'flat' => '',
            'fathersname' => '',
            'operation_label' => '2233adff-0002-5000-8036-053b8e7edb6c',
            'operation_id' => '573855449753013012',
            'currency' => '643',
            'email' => '',
        );

        $urlTest = 'http://192.168.0.152/payment/yandex/message';
        // Get cURL resource
        $curl = curl_init();
    // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $urlTest,
                CURLOPT_USERAGENT => 'Codular Sample cURL Request',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data_post
            ));
    // Send the request & save response to $resp
            $resp = curl_exec($curl);
        $http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        ex([
            $resp,
            $http_code
        ]);
    // Close request to clear up some resources
            curl_close($curl);
            //$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

    }

    public function actionSitemapNot200(){



        $se = new SiteError();
        $se->actionSitemapNot200();

        $text =  $se->resultTextFormat();
        $settings = \Yii::$app->getModule('settings');

        $lastDateTime = $settings->editVar( 'siteErrorLastDateTime',time());
        $siteErrorLastCheck  = $settings->editVar( 'siteErrorLastCheck',$text);

        return $text;
        /*$this->getLoadSiteMap();
        ex($this->sites);*/
    }

    private function getLoadSiteMap(){
        foreach ( $this->sites as $num => $item){

            $siteMap = $this->url_load( $item['sitemap'] );
            $xml = new \SimpleXMLElement($siteMap['respond']);

            foreach ($xml->url as $url_list) {
                $url = (string)$url_list->loc;
                if ( $url && $this->validUrl($url) ) {
                    $raw = $this->url_load($url);

                    if ($raw['http_code'] == "200" ||
                        $raw['http_code'] == "301" ||
                        $raw['http_code'] == "302"
                    ){ // 200
                        $this->sites[$num]['static']['count200'] += 1;
                    } else { // err
                        $this->sites[$num]['static']['countErr'] += 1;
                    }

                    $rec = [
                        'url'=>$url,
                        'code'=>$raw['http_code']
                    ];
                    $this->sites[$num]['loadLinks'][] = $rec;
                } else {
                    var_dump('no');
                }


            }

        }
    }

    private function validUrl($url){
        return (  ! filter_var($url, FILTER_VALIDATE_URL) === FALSE) ;
    }

    private function url_load($url=''){
        $timeout = 10;
        $ch = curl_init();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
        $http_respond = curl_exec($ch);
        //$http_respond = trim( strip_tags( $http_respond ) );
        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

        /*if ( ( $http_code == "200" ) || ( $http_code == "302" ) ) {
            return true;
        } else {
            // return $http_code;, possible too
            return false;
        }*/
        curl_close( $ch );

        return [
            'respond'=>$http_respond,
            'http_code'=>$http_code
        ];
    }


}
