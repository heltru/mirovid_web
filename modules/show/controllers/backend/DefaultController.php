<?php

namespace app\modules\show\controllers\backend;

use app\modules\app\app\AppAccount;
use app\modules\app\app\AppNovaVidShow;
use app\modules\block\models\Block;

use app\modules\helper\models\Helper;
use app\modules\show\models\ShowRegister;
use app\modules\show\models\ShowRegisterSearch;

use app\modules\show\models\ShowRegisterSearchTable;
use app\modules\show\models\TrackAuto;
use app\modules\show\models\TrackPoint;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `show` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */


    public function actionTest()
    {
        return $this->render('test');
    }


    public function actionPixelEditor(){
        $this->layout = false;
        return $this->render('pixel-editor');
    }

    public function actionWhileLenta()
    {
        $app =  AppNovaVidShow::Instance();

        $listShow = $app->getListShow();

        //ex($listShow['broadcast']);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $listShow['broadcast'],
            /*
             *  'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => ['id', 'name'],
    ],
             * */
        ]);

        return $this->render('while-lenta', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCalcPostCar(){
        \Yii::$app->response->format  = Response::FORMAT_JSON;
        \Yii::$app->session->open();
        $tracks = \Yii::$app->session->get('tracks');

        $track_index = (int)\Yii::$app->request->get('track_index');

        if (isset($tracks[$track_index])){

            $save_item = $tracks[$track_index];
            if ((count($save_item['points'])-1) == $save_item['point_index'] ){
                $tracks[$track_index]['point_index'] = 0;
            } else {
                $tracks[$track_index]['point_index'] = (int)$tracks[$track_index]['point_index']  + 1;
            }
            $point = $tracks[$track_index]['points'][ $tracks[$track_index]['point_index'] ];

            \Yii::$app->session->set('tracks',$tracks);
            \Yii::$app->session->close();
            return ['active_index'=>$tracks[$track_index]['point_index'] ,'lat'=>$point[0],'long'=>$point[1]];
        }

        [];

        \Yii::$app->session->close();

    }

    public function actionMapRegister(){
        $searchModel = new ShowRegisterSearch();



        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        
        $points = [];

        foreach ($dataProvider->getModels() as $num => $model){
            $date = strtotime($model->date_sh);

            $pathinfo = pathinfo($model->reklamir_r->file_r->path);
            $ext = $pathinfo['extension'];
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'bmp'])) {
                $src =  $model->reklamir_r->file_r->path;
            } else {
                $src =  $model->file_r->name ;
            }

            $points[] =  [
                'lat'=>$model->lat,
                'long'=>$model->long,
                'id'=>$model->id,
                'num'=>$num,
                'time'=> date( 'H:i:s',$date),
                'date'=> date('d.m.y',$date),
                'src'=>$src
            ];
        }

        return $this->render('map-register', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'points'=> Json::encode( $points)
        ]);
    }


    public function actionAddTrackPoint(){
        $tp = new TrackPoint();
        $tp->lat = \Yii::$app->request->get('lat');
        $tp->long = \Yii::$app->request->get('long');
        $tp->track_id = \Yii::$app->request->get('track_id');
        $tp->save();
        ex($tp->getErrors());
    }
}
