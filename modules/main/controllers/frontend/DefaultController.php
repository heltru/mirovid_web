<?php

namespace app\modules\main\controllers\frontend;

use app\modules\app\AppModule;
use app\modules\app\group\Group;
use app\modules\app\service\VkGroupTokens;
use app\modules\helper\models\Helper;
use app\modules\show\models\ShowRegister;
use app\modules\show\models\TrackAuto;
use app\modules\show\models\TrackPoint;
use app\modules\user\models\User;
use app\modules\VkAPI\Exception;
use app\modules\VkAPI\VkAPI;
use app\modules\VkAPI\VkMethod;
use app\modules\VkAPI\VkOauth;
use app\modules\zapros\models\Subscr;
use app\modules\zapros\models\Zapros;
use App\Service\Common;
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

    public $enableCsrfValidation = false;

    public function actionConnectGroup(){
        $groups = [];
        $vk = new VkMethod(AppModule::getVkAppId4auth());
        $vk->setToken(AppModule::getUserVkToken());
        $vk_groups = $vk->getGroups(AppModule::getUserVkId(), true, ['admin'], ['members_count']);
        if (isset($vk_groups['items']) && $vk_groups['items']) {
            foreach ($vk_groups['items'] as $vk_group) {
                $groups[] = [
                    'id' => $vk_group['id'],
                    'members_count' => (isset($vk_group['members_count']) ? $vk_group['members_count'] : 0),
                    'name' => Helper::sanitizeString($vk_group['name']),

                    'photo_200' =>  Helper::sanitizeURL($vk_group['photo_200']),

                    'auth_link' => Group::getAuthLink($vk_group['id']),
                ];
            }
        }

        return $this->render('connect-group',['groups'=>$groups]);

    }

   public function actionTest(){
       $vk = new VkMethod();

     //  $vk->setGroup(30109290);

       $vk->setToken(\app\modules\app\common\Common::getToken());

       try {

           $stories = $vk->getWall('6734534');

       } catch ( Exception $e) {
           ex($e->getMessage());
           if ($vk->error) {
               if (in_array($vk->error['error_code'], array(VkAPI::ACCESS_DENIED, VkAPI::WRONG_TOKEN))) {
                  // $vk->setToken( AppModule::getVkAppTech4user());
                   ex($vk->error['error_code']);
                   //$vk_users = $vk->getUsers($vk_user_ids, self::$fields);
               }
           }
       }


       ex($stories);
   }

    public function actionVerify()
    {
        $app_id = (isset($_GET['app_id']) ? intval($_GET['app_id']) : 0);
        $return = (isset($_GET['return']) && ($_GET['return']) ? $_GET['return'] : '');

        $vk = new  VkOauth(

            AppModule::getVkAppId4auth($app_id),
            AppModule::getVkAppSecret4auth($app_id),
            AppModule::getVkAppRedirect($app_id, $return));

        try {
            if ($vk::$state) {
                $e = explode("_", $vk::$state);
                if (count($e)) {
                    switch ($e[0]) {
                        case 'group': // Подключение группы
                            //Core\AuthWeb::checkAuth();

                            if (isset($_GET['code']) && $_GET['code']) {
                                $vk_group_id = intval($e[1]);

                                try {
                                    $vk->requestGroupToken($vk_group_id);
                                    $vk_group = $vk->getGroupById($vk_group_id, Group::$fields);
                                    $token = $vk->getToken();

                                    if ($token && $vk_group) {
                                        // check access
                                        $group = Group::update2Vk($vk_group, AppModule::getUserId());
                                        if ($group) {

                                            try {
                                                VkGroupTokens::add($group['group_id'], $vk_group_id, $token, $app_id);
                                            } catch (\Exception $e){
                                                ex($e->getMessage());
                                            }

//                                            Service\Bots::StartTokenlessBots($group['group_id']);
//                                            Service\DeliveryCommand::unlockToken($group['group_id']);

                                            try {
                                                Group::setVkSettings($group);
                                                Group::updateGroup(['allow_callback' => 1], $group['group_id']);
                                            } catch (Exception $e) {
                                                ex($e->getMessage());
                                            }

                                            switch ($return) {
                                                case 'connect':
                                                    ex("Location: /cabinet/connect/{$group['group_id']}");
//                                                    header("Location: /cabinet/connect/{$group['group_id']}", TRUE, 301);
                                                    break;
                                                default:
                                                    ex("Location: /cabinet/delivs/{$group['group_id']}");
                                                    //header("Location: /cabinet/delivs/{$group['group_id']}", TRUE, 301);
                                            }
                                        } else {
                                            //header("Location: /cabinet/add", TRUE, 301);
                                            ex("Location: /cabinet/add");
                                        }
                                    } else {
                                        //header("Location: /cabinet/add", TRUE, 301);
                                        ex("Location: /cabinet/add");
                                    }
                                } catch (Exception $e) {
                                    ex("Location: /cabinet/add?vk_group_id={$vk_group_id}&app_id={$app_id}&err=" . (isset($vk->error['error_msg']) ? $vk->error['error_msg'] : $e->getMessage()));
                                    //header("Location: /cabinet/add?vk_group_id={$vk_group_id}&app_id={$app_id}&err=" . (isset($vk->error['error_msg']) ? $vk->error['error_msg'] : $e->getMessage()), TRUE, 301);
                                }
                            } else {
                                ex("Location: /cabinet/add");
                                //header("Location: /cabinet/add", TRUE, 301);
                            }
                            break;
                        case 'user': // Подключение пользователя
                            $vk_user_id = $vk->requestToken();
                            if ($vk_user_id) {
                                $token = $vk->getToken();
                                if ($token) {
                                    $user = User::update2Vk($vk_user_id, $token);
                                    if ($user) {
                                        \Yii::$app->user->login($user,  3600*24*30 );
                                        //Service\VkInvalidSessions::del($user);
                                    }
                                }
                            }
                            \Yii::$app->response->redirect('/admin');
                           // header("Location: /admin", TRUE, 301);
                            break;
                    }
                }
            }
        } catch (\Exception $e) {
//            die($e->getMessage());
            header("Location: /", TRUE, 301);
        }
    }


    public function actionLogin()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $url = "/admin";

        if ( \Yii::$app->user->isGuest) $url = User::getLoginLink();


        header("Location: $url", TRUE, 301);
        exit();
    }

    public function actionIndex()
    {


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

        $this->view->registerCssFile('/themes/one/css/style.css');

        $this->view->registerCssFile('/themes/one/css/index-css.css');

        $this->view->registerJsFile('/js/common.js');

        $this->layout = 'landing';
        return $this->render('landing/main');
    }


    public function actionIndex1()
    {

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

        $this->view->registerCssFile('/themes/one/css/style.css');

        $this->view->registerCssFile('/themes/one/css/index-css.css');

        $this->view->registerJsFile('/js/common.js');

        $this->layout = 'landing';

        return $this->render('landing/main');
    }



    public function actionLedBillboard()
    {

        $title = 'Диджитал билборды в Хлынове';
        $descr = 'Повысите узнаваемость бренда для вашего бизнеса. Рекламодатели, использующие рекламные щиты, ускоряют свое присутствие в социальных сетях. Ты можешь больше!';
        $key = 'биллборд, видеоэкран, наружная реклама, уличный светодиодный экран, led экран, аренда светодиодного экрана, Киров';
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

        $this->view->registerCssFile('/themes/one/css/led-billboard.css');
        $this->view->registerJsFile('/js/common.js');
        $this->layout =  'landing';

        return $this->render('landing/led-billboard');//led-billboard
    }

    public function actionSendMeEx(){
        $email = \Yii::$app->request->post('email');

        $rec = new Subscr();
        $rec->email = $email;
        $rec->save();

        \Yii::$app->mailer->compose()
            ->setFrom('mirovidweb@gmail.com')
            ->setTo($email)
            ->setSubject('Образцы успешной рекламы')
            ->attach('cifrovye-bilbordy.zip', ['fileName' => 'cifrovye-bilbordy.rar'])
            ->send();

        return $email;
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

    public function actionOrderReclame()
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






    public function actionError()
    {

        return $this->render('error');

    }


}
