<?php

namespace app\modules\show\controllers\backend;

use app\modules\app\app\AppAccount;
use app\modules\app\app\AppNovaVidShow;
use app\modules\block\models\Block;

use app\modules\show\models\ShowRegister;
use app\modules\show\models\ShowRegisterSearch;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Default controller for the `show` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {
        return $this->render('test');
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

    public function actionMapRegister(){
        $searchModel = new ShowRegisterSearch();



        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        
        $points = [];
        foreach ($dataProvider->getModels()  as $model){
            $date = explode(' ',$model->date_sh);
            $points[] =  [
                'lat'=>$model->lat,
                'long'=>$model->long,
                'id'=>$model->msg_id,
                'time'=> $date[1],
                'date'=> $date[0]
            ];
        }



        return $this->render('map-register', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'points'=> Json::encode( $points)
        ]);
    }
}
