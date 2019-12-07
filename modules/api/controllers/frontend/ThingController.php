<?php

namespace app\modules\api\controllers\frontend;

use app\modules\app\app\RegisterShow;
use app\modules\playlist\app\AppPlaylist;
use app\modules\reklamir\models\Reklamir;

use app\modules\reklamir\models\Thing;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;


class ThingController extends Controller
{


    public function actionPlaylist()
    {

        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;

        $thing_id = (int) \Yii::$app->request->get('thing_id') ;

        /*


        $app = new AppPlaylist($thing_id);

        return $app->getPlaylist();
*/


        $list = [];

        $all = Reklamir::find()->joinWith(['file_r','area_r'])->where(['thing_id'=>$thing_id,'status'=>Reklamir::ST_ON])->all();
        foreach ($all as $item){
            if (! is_object($item->file_r)){
                continue;
            }



            $list[] = [
                'reklamir_id'=>$item->id,
                'file'=>str_replace(    'mirovid/files/','',$item->file_r->path),
                'area'=>ArrayHelper::getColumn($item->area_r,'area_id'),
                'daytime'=>ArrayHelper::getColumn($item->bid_r,'time_id')

            ];
        }

        return $list;

    }


    public function actionRegisterShow(){

        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;


        $reklamir_id = (int) \Yii::$app->request->get('reklamir_id');
        $lat =  \Yii::$app->request->get('lat') ;
        $long =   \Yii::$app->request->get('long');


        if ($lat === null){
            $lat = 0;
        }
        if ($long === null){
            $long = 0;
        }


        $app = new RegisterShow($lat,$long,$reklamir_id);
        $r =  $app->begin();

         return [ $r ];

    }

    public function actionGlobalConfigLocal(){

        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;

        $thing_id = (int)  \Yii::$app->request->get('thing_id');


        $thing = Thing::findOne(['id'=>$thing_id]);

        if ($thing !== null){

            return Json::decode($thing->global_config_local);
        }
        return ['test'=>'ok'];
    }

    public function actionMyIp(){
        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;


        $thing_id = (int)  \Yii::$app->request->get('thing_id');
        $ip =  \Yii::$app->request->get('ip');

        $thing = Thing::findOne(['id'=>$thing_id]);

        if ($thing !== null){
            $thing->my_ip = $ip;
            $thing->update(false,['my_ip']);
            return 0;
        }
        return 1;

    }

}
