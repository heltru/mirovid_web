<?php

namespace app\modules\api\controllers\backend;

use app\modules\app\app\AppNovaVidShow;
use app\modules\app\app\FormData;
use app\modules\block\models\Block;
use app\modules\block\models\BlockMsg;
use app\modules\car\models\Car;
use app\modules\helper\HelperModule;
use app\modules\helper\models\Helper;
use app\modules\order\models\Order;
use GuzzleHttp\Client;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\validators\EmailValidator;
use yii\web\Controller;
use yii\web\Response;
use Yii;
/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function init()
    {
        define('VIDEO_PATH','mirovid/video');
        parent::init(); // TODO: Change the autogenerated stub
    }




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
                ],
            ],
        ]);
    }
*/

    public function actionDeleteVideo(){

        $name = Yii::$app->request->post('name');
        $path = VIDEO_PATH.  '/' . $name;

        if (is_file($path)){
            unlink($path);
            Yii::$app->getSession()->setFlash('success', 'Video  deleted-!');
            return;
        }


        Yii::$app->getSession()->setFlash('danger', 'Error delete!');

    }


    public function actionUpdateFilesCar(){

        $cmd = 'cd ' . Yii::$app->params['path_env'].'/mirovid;'.'bash git_pull.sh' ;
        Helper::runConsole($cmd);

        Yii::$app->session->setFlash('success', $cmd .' Генерация данных запущена!');

        return $this->redirect( Yii::$app->request->referrer );


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








}
