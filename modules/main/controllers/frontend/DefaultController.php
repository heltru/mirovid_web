<?php

namespace app\modules\main\controllers\frontend;

use app\modules\helper\models\Helper;
use app\modules\show\models\ShowRegister;
use app\modules\show\models\TrackAuto;
use app\modules\show\models\TrackPoint;
use yii\helpers\Json;
use yii\web\Controller;

class DefaultController extends Controller
{
 public $layout = '/adminlte/main-login';

   public function actions()
    {
        /*
        return [
             'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
        */
    }

    public function actionRedirectMain(){

        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ". \Yii::$app->request->hostInfo);
        exit();
    }

    public function actionRegisterTrackPoint(){

        $data = ShowRegister::find()->all();


        return $this->render('landing/map_register_track_points',['data'=>$data]);
    }

    public function actionSession(){
        \Yii::$app->session->open();
        ex( \Yii::$app->session->get('tracks'));

    }

    public function actionIndex()
    {

        $data = TrackAuto::find()->joinWith(['trackPoints_r'])->all();

        $tracks = [];
        $colors = [];

        $common_points = [];
        foreach ($data as $num => $item){
            $points = [];
            foreach ($item->trackPoints_r as $point){

                $points[] = [$point->lat,$point->long];
                $common_points[] = [$point->lat,$point->long];
            }

            if (!count($points)){
                continue;
            }

            $colors[$num] = '#' . Helper::genColorCodeFromText( $item->id) ;
            $tracks[$num] = ['tracks_index'=>$num,'points'=>$points];
            $tracks_session[$num] = ['point_index'=>rand(0,count($points)-1),'points','points'=>$points];

        }


        $title = 'Заказать рекламу на авто машинах в Кирове. Пассивный заработок для авто';
        $descr = 'Динамичная и цветная релкама, продукт или услуга дойдет до сознания. Увеличь доход от авто на 2000 руб в месяц';
        $key = 'Объвяление, Реклама, авто, сообщения, новости, led, Киров';
        $this->view->title =$title;
        $this->view->registerMetaTag([ 'name' => 'description', 'content' =>$descr]);
        $this->view->registerMetaTag([ 'name' => 'keywords', 'content' => $key]);
        $this->view->registerMetaTag([ 'property' => 'og:type', 'content' =>  'product.group']);
        $this->view->registerMetaTag([ 'property' => 'og:locale', 'content' =>  'ru_RU']);
        $this->view->registerMetaTag([ 'property' => 'og:description', 'content' => $descr]);
        $this->view->registerMetaTag([ 'property' => 'og:title', 'content' =>  $title]);
        $this->view->registerMetaTag([ 'property' => 'og:url', 'content' => \Yii::$app->request->hostInfo  ]);
        $this->view->registerMetaTag(['property' => 'og:image',
                'content' => \Yii::$app->request->hostInfo .'']);

        $this->layout = 'landing_index';
        return $this->render('landing/index',[
            'tracks'=>Json::encode($tracks),
            'tracks_session'=>Json::encode($tracks_session),
            'colors'=>Json::encode($colors)
        ]);
    }



    public function actionSalePanel()
    {
        //  ex(4);
        $title = 'Купить панель Mirovid LED в Кирове';
        $descr = 'Пассивный заработок от 2000 руб в месяц! Нужен только автомобиль!';
        $key = 'Объвяление, Реклама, авто, сообщения, новости, led, Киров';
        $this->view->title =$title;
        $this->view->registerMetaTag([ 'name' => 'description', 'content' =>$descr]);
        $this->view->registerMetaTag([ 'name' => 'keywords', 'content' => $key]);
        $this->view->registerMetaTag([ 'property' => 'og:type', 'content' =>  'product.group']);
        $this->view->registerMetaTag([ 'property' => 'og:locale', 'content' =>  'ru_RU']);
        $this->view->registerMetaTag([ 'property' => 'og:description', 'content' => $descr]);
        $this->view->registerMetaTag([ 'property' => 'og:title', 'content' =>  $title]);
        $this->view->registerMetaTag([ 'property' => 'og:url', 'content' => \Yii::$app->request->hostInfo  ]);
        $this->view->registerMetaTag(['property' => 'og:image',
            'content' => \Yii::$app->request->hostInfo .'']);

        $this->layout = 'landing_sale';
        return $this->render('landing/sale-panel');
    }


    public function actionOrderReclame()
    {
        //  ex(4);
        $title = 'Заказать рекламу Mirovid LED  в Кирове';
        $descr = 'Живые объявления в Кирове. Динамичный стиль. Заметно тысячам человек. Ночью и Днем светит LED огонь Вашей идеи!';
        $key = 'Объвяление, Реклама, авто, сообщения, новости, led, Киров';
        $this->view->title =$title;
        $this->view->registerMetaTag([ 'name' => 'description', 'content' =>$descr]);
        $this->view->registerMetaTag([ 'name' => 'keywords', 'content' => $key]);
        $this->view->registerMetaTag([ 'property' => 'og:type', 'content' =>  'product.group']);
        $this->view->registerMetaTag([ 'property' => 'og:locale', 'content' =>  'ru_RU']);
        $this->view->registerMetaTag([ 'property' => 'og:description', 'content' => $descr]);
        $this->view->registerMetaTag([ 'property' => 'og:title', 'content' =>  $title]);
        $this->view->registerMetaTag([ 'property' => 'og:url', 'content' => \Yii::$app->request->hostInfo  ]);
        $this->view->registerMetaTag(['property' => 'og:image',
            'content' => \Yii::$app->request->hostInfo .'']);

        $this->layout = 'landing';
        return $this->render('landing/order-reclame');
    }



    public function actionError()
    {

        return $this->render('error');

    }


}
