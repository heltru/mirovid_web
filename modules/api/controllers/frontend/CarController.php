<?php

namespace app\modules\api\controllers\frontend;

use app\modules\app\app\AppCarManager;
use app\modules\app\app\AppNovaVidShow;
use app\modules\app\app\FormData;
use app\modules\block\models\Block;
use app\modules\block\models\BlockMsg;
use app\modules\car\models\Car;
use app\modules\helper\HelperModule;
use app\modules\helper\models\Helper;
use app\modules\order\models\Order;
use app\modules\show\app\RegisterShow;
use GuzzleHttp\Client;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\validators\EmailValidator;
use yii\web\Controller;
use yii\web\Response;
use app\modules\helper\models\Logs;

/**
 * Default controller for the `api` module
 */
class CarController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

/*
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),   [

            [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [ 'need-update' ],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    //  'delete' => ['POST'],
                    'need-update' => ['GET'],
                    'recive-order' => ['POST'],
                ],
            ],
        ]);
    }
*/
    public function beforeAction($action)
    {
        if ($action->id == 'car-online' ||
            $action->id == 'register-show'
        ) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }


    public function actionListFiles()
    {
        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;

        $dir_video = 'mirovid\video';
        $dir_img = 'mirovid\images';

        $dirs = [$dir_video,$dir_img];

        $list_path = [];

        foreach ($dirs as $dir){

            foreach (scandir($dir) as $file) {
                $fn = basename($file);
                $path = $dir . '/' . $fn;
                $list_path[] = $path;
            }
        }



        return $list_path;


    }


    public function actionNeedUpdate($id){

        $car = Car::findOne(['id'=>$id]);
        // check car

        if ($car === null){
            return [];
        }

        $app =  AppNovaVidShow::Instance();

        $listShow = $app->getListShow();



        // return $this->render('index',['data'=>$showJson]);
        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;

        return [
            'status'=>'success',
            'responce' => $listShow,
        ];

    }


    public function actionRegisterShow(){
        $msg_id =  (int) \Yii::$app->request->get('msg_id');
        $date_sh =  (int) \Yii::$app->request->get('date_sh');
        $lat = 0;
        $long = 0;
        $car_id = (int) \Yii::$app->request->get('car_id');
        $time = Helper::mysql_datetime(); // (int) \Yii::$app->request->get('time');

/*
        $lat = (int) str_replace('.','',\Yii::$app->request->get('lat')) ;
        $long = (int) st_replace('.','',\Yii::$app->request->get('long')) ;
*/
        $lat =  \Yii::$app->request->get('lat') ;
        $long =   \Yii::$app->request->get('long');


        Logs::log('actionRegisterShow',['msg_id'=>$msg_id,'date_sh'=>$date_sh,'car_id'=>$car_id,'time'=>$time,'lat'=>$lat,'long'=>$long] );


           \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;

        $app = new \app\modules\app\app\RegisterShow();

       // return ['msg_id'=>$msg_id,'date_sh'=>$date_sh,'car_id'=>$car_id];



        $r =  $app->register_show(['msg_id'=>$msg_id,'date_sh'=>$date_sh,'car_id'=>$car_id,'time'=>$time,'lat'=>$lat,'long'=>$long]);

     //   return [  'msg_id'=>$msg_id,'date_sh'=>date("Y-m-d H:i:s", $date_sh), 'car_id'=>$car_id ] ;


        return [ $r ];
      //
    }

    public function actionReciveOrder(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $app = AppNovaVidShow::Instance();

        $formData = new FormData();

          if ($formData->load(\Yii::$app->request->post())){
ex($formData);

              $order = new Order();
              $order->ip = \Yii::$app->request->getUserIP() . '^' . \Yii::$app->request->getRemoteIP();
              $order->datetime = HelperModule::convertDateToDatetime();
              $order->type = Order::T_Q;
              $order->phone = $formData->phone;
              $order->car_id = $formData->number_car;

              if ( $order->save()){

              return [
                        'responce'=>
                                      [
                                          'status'=>'200',
                                          'sum'=> HelperModule::formatPrice( /*$formData->getSumm()*/1 ),
                                          'order_id' =>$order->id,

                                      ]
              ];

              } else {
                  return [ 'responce'=>['status'=>'411',

                      'dump'=> json_encode($formData->getErrors(),true)] ];
              }


          } else {
              return [ 'responce'=>['status'=>'410',

                  'dump'=> json_encode($formData->getErrors(),true)] ];
          }

        return [ 'responce'=>['status'=>'400','dump'=> ($app->redirectPayUrlContent)] ];

    }

    public function actionCarOnline(){

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $postData = file_get_contents('php://input');
        $data = json_decode($postData, true);
        $car_id = (int) $data['car_id'];
        $ip = \Yii::$app->request->getUserIP();
        if ($car_id){
            $app =  AppCarManager::Instance();
            $app->carLogin($car_id,$ip);
        }
        return [

            'car_id'=>$data['car_id'] ,
            'user_ip'=> $ip
        ];
    }






}
