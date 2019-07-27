<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.09.2018
 * Time: 23:55
 */

namespace app\modules\app\app;




use app\modules\car\models\Car;
use app\modules\helper\HelperModule;
use app\modules\helper\models\LoginCar;
use app\modules\helper\models\Logs;
use yii\httpclient\Client;

class AppCarManager
{

    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {

            $inst = new AppCarManager();

        }
        return $inst;
    }




    public function carLogin($car_id,$ip){

        Logs::log('loginCar-ip',$ip);
        $car = Car::findOne(['id'=> $car_id]);

        if ($car !== null /*&&  $this->validCarAuthData($data,$car->secret)*/ ){
            $cr = new LoginCar();
            $cr->date_cr = HelperModule::convertDateToDatetime();
            $cr->car_id = (int) $car->id;
            if (! $cr->save()){
                Logs::log('loginCarSaveLoginCar',$cr->getErrors());
            }
            $car->online = Car::ST_ON;
            $car->ip = $ip;

            if (!$car->update(['online','ip'])){
                Logs::log('loginCar-updateCarOnline',$car->getErrors());
            }

        }


    }


    public function carAction($car_id,$action){

        $car = Car::findOne(['id'=> $car_id]);
        if ($car === null) return;

        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport' // only cURL supports the options we need
                ,
            'baseUrl' => $car->ip.':3000/'.$action,
    /*'requestConfig' => [
            'format' => Client::FORMAT_JSON
        ],*/
    'responseConfig' => [
            'format' => Client::FORMAT_JSON
        ],
        ]);

        //$client = new Client();
        $response = null;
        try {
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setData(['secret' => 'abc'])->send();

        } catch (\yii\httpclient\Exception $e) {

        }

        if ($response === null){
            return ['data'=>'error network'];
        }

        //ex($response->data);
        if ($response->isOk) {
            return  $response->data;
            //$newUserId = $response->data['id'];
        }



       /* switch ($action){
            case 'restart':
                break;
            case 'play':
                break;
            case 'stop':
                break;
            case 'info':
                break;
            case 'update':
                break;
        }*/
    }


}