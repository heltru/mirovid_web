<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 19.11.2018
 * Time: 22:25
 */

namespace app\modules\app\app;


use app\modules\helper\models\Logs;
use app\modules\reklamir\models\Thing;

class AppPrice
{


    private $price_show=1;

    public function __construct(Thing $thing )
    {
        try{
            $this->price_show  = $thing->place_r->price_show;
        }  catch (\Exception $e){
            Logs::log('AppPrice __construct',$e);
        }

    }

    public  function getPriceShow(){
        return $this->price_show;
    }

}