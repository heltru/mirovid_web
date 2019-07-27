<?php

namespace app\modules\api\controllers\backend;

use app\modules\app\app\AppNovaVidShow;
use app\modules\app\app\FormData;
use app\modules\block\models\Block;
use app\modules\block\models\BlockMsg;
use app\modules\car\models\Car;
use app\modules\helper\HelperModule;
use app\modules\order\models\Order;
use GuzzleHttp\Client;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\validators\EmailValidator;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
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
                ],
            ],
        ]);
    }
*/



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
