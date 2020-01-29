<?php

namespace app\modules\test\controllers\frontend;

use app\modules\car\models\Car;
use app\modules\helper\models\Helper;
use app\modules\test\app\SiteError;
use app\modules\test\models\CityTransport;
use app\modules\test\models\CityTransportCheck;
use app\modules\test\models\CityTransportStat;
use app\modules\user\models\User;
use yii\helpers\Json;
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

    public $email = 'laneo2007@yandex.ru';

    public function actionTestEmail(){

        Yii::$app->mailer->compose()
            ->setFrom('info@mirovid.ru')
            ->setTo('mirovidweb@yandex.ru')
            ->setSubject('test')
            ->setTextBody('test')
            ->setHtmlBody('test a')
            ->send();
    }

    private function checkCarTimeLimit($car,$area){
        $date = Helper::mysql_datetime(strtotime("+25 minutes"));
        $old =  CityTransportCheck::find()->where(['<','date',$date])
            ->andWhere(['gn'=>$car['gn']])
            ->andWhere(['area'=>$area])
            ->andWhere(['number'=>$car['number']])
            ->one();

        return $old === null;

    }

    public function actionTestTimeF(){
        return $this->render('t');
    }


    public function actionTestTime()
    {
        set_time_limit(0);

        $sqrs  = [ [ [58.587693, 49.621885],[58.593872, 49.636380] ] , [ [58.621161, 49.638791],[58.627197, 49.651354] ] ];

        $routes = [1090,1054,1033,1037,1017,1051,1046,1053,1074,1061,1001,1023,1022,1010,1016,1044,1002,1070,1012,1039,1088,1014,1087,1021,1084,5007,5005,5008,5014,5001,5004,5003];



        foreach ($routes as $route){
            $d = file_get_contents('https://cdsvyatka.com/api/kirov/map/route/'.$route.'/transport');

            $d = Json::decode($d);
            foreach ($d as $car) {

                foreach ($sqrs as $num_area => $sqr){

                    $lat = $car['lat'] >= $sqr[0][0] && $car['lat'] <= $sqr[1][0];
                    $long = $car['lng'] >= $sqr[0][1] && $car['lng'] <= $sqr[1][1];

                    if ($lat && $long && $this->checkCarTimeLimit($car,$num_area)){
                        $rec = new CityTransportCheck();
                        $rec->long = $car['lng'];
                        $rec->lat = $car['lat'];
                        $rec->gn = $car['gn'];
                        $rec->number = $car['number'];
                        $rec->date = $car['date'];
                        $rec->route_id = $route;
                        $rec->area = $num_area;
                        $rec->save();
                        if ($rec->getErrors()){
                            ex($rec->getErrors());
                        }

                    }
                }


            }
            sleep(rand(1,3));
        }



    }

    public function actionTestTime1(){
        set_time_limit(0);

        $sqrs  = [   [ [58.621161, 49.638791],[58.627197, 49.651354] ] ];

        $routes = [1090,1054,1033,1037,1017,1051,1046,1053,1074,1061,1001,1023,1022,1010,1016,1044,1002,1070,1012,1039,1088,1014,1087,1021,1084,5007,5005,5008,5014,5001,5004,5003];



        foreach ($routes as $route){
            $d = file_get_contents('https://cdsvyatka.com/api/kirov/map/route/'.$route.'/transport');

            $d = Json::decode($d);
            foreach ($d as $car) {

                foreach ($sqrs as $num_area => $sqr){

                    $lat = $car['lat'] >= $sqr[0][0] && $car['lat'] <= $sqr[1][0];
                    $long = $car['lng'] >= $sqr[0][1] && $car['lng'] <= $sqr[1][1];

                    if ($lat && $long && $this->checkCarTimeLimit($car,1)){
                        $rec = new CityTransportCheck();
                        $rec->long = $car['lng'];
                        $rec->lat = $car['lat'];
                        $rec->gn = $car['gn'];
                        $rec->number = $car['number'];
                        $rec->date = $car['date'];
                        $rec->route_id = $route;
                        $rec->area = 1;
                        $rec->save();
                        if ($rec->getErrors()){
                            ex($rec->getErrors());
                        }

                    }
                }


            }
            sleep(rand(1,3));
        }

    }

    public function actionTest(){

        $this->layout = false;
        return $this->render('a');

        /*
        Yii::$app->mailer->compose(['text' => '@app/modules/user/mails/test'])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Email confirmation for ' . Yii::$app->name)
            ->send();
        mail('test@test.com','Email confirmation for ' . Yii::$app->name,'test');
        echo '123';
        */
        /*
        Yii::$app->mailer->compose()
            ->setFrom('89991002878@mail.ru')
            // ->setTo('757537s@mail.ru')
            ->setTo('laneo2007@yandex.ru')
            ->setSubject('Заказ звонка с сайта novaferm.ru')
            //->setTextBody('Ваша заявка №'.$model->id.' принята. В течении недели мы свяжимся с вами, по телефону или по почте.')
            ->setHtmlBody(' ФИО ')
            ->send();
        */
        /*
        $user = new User();
        $user->username ='123';
        $user->email = 'test@test.test';
        $user->setPassword(3423);
        $user->status = User::STATUS_WAIT;

        mail($this->email,
            'Email confirmation for ' . Yii::$app->name,
            Yii::$app->getView()->renderFile('@app/modules/user/mails/emailConfirm.php',['user' => $user])
        );*/
    }




}
