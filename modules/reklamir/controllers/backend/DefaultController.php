<?php

namespace app\modules\reklamir\controllers\backend;

use app\modules\app\fileupload\FileUpload;
use app\modules\bid\models\Bid;
use app\modules\helper\models\Logs;
use app\modules\reklamir\models\ReklamirArea;
use app\modules\reklamir\models\ReklamirCommonSearch;
use app\modules\reklamir\models\ReklamirDaytime;
use app\modules\reklamir\models\ReklamirThing;
use app\modules\reklamir\models\Thing;
use Yii;
use app\modules\reklamir\models\Reklamir;
use app\modules\reklamir\models\ReklamirSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Reklamir model.
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }






    /**
     * Lists all Reklamir models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReklamirSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);




        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,


        ]);
    }

    public function actionChangeStatus(){
        $rec = Reklamir::findOne(['id'=>(int) Yii::$app->request->post('id') ]);
        if ($rec !== null){
            $rec->status = (int)Yii::$app->request->post('status');
            $rec->update(false,['status']);
        }
    }


    public function actionCommon()
    {
        $searchModel = new ReklamirCommonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('common', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Reklamir model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Reklamir model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reklamir();


        if ($model->load(Yii::$app->request->post())    ) {
            $app_file = new FileUpload('mirovid/files/' . Yii::$app->getModule('account')->getAccount()->id);

            try {

                $model->save();

                $app_file->begin();
                if ($app_file->is_add){
                    $file = $app_file->getFileModel();
                    //$app_file->create_preview();
                }

                $model->file_id = $file->id;
                $model->update(false,['file_id']);
                $this->preseachReklamirThing($model);
//ex($model->file_id);

            }catch (\Exception $e) {

                Logs::log('Reklamir actionCreate',[$e]);
                ex($e->getMessage());
                return null;
            } catch (\Throwable $e) {
                ex($e->getMessage());
                Logs::log('Reklamir actionCreate',[$e]);
                return null;
            }



            return $this->redirect(['index', 'id' => $model->id]);


        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Reklamir model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->times = implode(',',ArrayHelper::getColumn( $model->daytime_r ,'time_id')) ;
        $model->areas = implode(',',ArrayHelper::getColumn( $model->area_r ,'area_id')) ;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $app_file = new FileUpload('mirovid/files/' . Yii::$app->getModule('account')->getAccount()->id);

            $app_file->setFileModel($model->file_r);

            $app_file->begin();
            if ($app_file->is_add){
                $file = $app_file->getFileModel();
               // $app_file->create_preview();
            }

            $model->file_id = $app_file->getFileModel()->id;
            $model->update(false,['file_id']);

            $this->preseachTimeAndGeo($model);
            $this->preseachReklamirThing($model);
            return $this->redirect(['index', 'id' => $model->id]);
        }




        $day_id = (int) date('d');
        $mount_id = (int) date('m');
        $year_id = (int) date('Y');

        $bid = Bid::find()->where([

            'mount_id'=>$mount_id,
            'year_id'=>$year_id,
            'day_id'=>$day_id
        ])->joinWith(['reklamir_r'])->all();

        $bid_minute = ArrayHelper::index($bid,'minute_id');
        $bid_hour = ArrayHelper::index($bid,'hour_id');

        return $this->render('update', [

            'bid'=>$bid,
            'bid_minute'=>$bid_minute,
            'bid_hour'=>$bid_hour,

            'model' => $model,
        ]);
    }

    private function preseachReklamirThing($model){

        $selected_things = ArrayHelper::getColumn(Thing::find()->where(['cat_id'=>$model->thing_cat])->all(),
            'id') ;




        if ( count($selected_things) ) {


            $old_links = ReklamirThing::findAll(['reklamir_id'=>  $model->id]);
            $old_thing = ArrayHelper::getColumn($old_links,'thing_id');

            $del_thing_id = array_diff($old_thing,$selected_things);



            foreach ($old_links as $link){
                if ( in_array( $link->thing_id,$del_thing_id)){
                    $link->delete();
                }
            }

            $new_thing_ids = array_diff($selected_things,$old_thing);

            foreach ( $new_thing_ids as $new_thing_id ){
                $l = new ReklamirThing();
                $l->reklamir_id = $model->id;
                $l->thing_id = (int) $new_thing_id;
                if ( !$l->save()){
                    ex($l->getErrors());
                }

            }


        }  else {
            ReklamirThing::deleteAll(['reklamir_id'=>  $model->id]);
        }
    }

    private function preseachTimeAndGeo($model){

        $selected_times = $model->areas;
        $arrSets = explode(',', $selected_times);

        if (  is_string($selected_times) && strlen($selected_times) && count($arrSets) != 36  ) {


            $old_links = ReklamirArea::findAll(['reklamir_id'=>  $model->id]);
            $old_domains = ArrayHelper::getColumn($old_links,'area_id');

            $del_domain_id = array_diff($old_domains,$arrSets);

            foreach ($old_links as $link){
                if ( in_array( $link->area_id,$del_domain_id)){
                    $link->delete();
                }
            }

            $new_locales_id = array_diff($arrSets,$old_domains);

            foreach ( $new_locales_id as $new_locale_id ){
                $l = new ReklamirArea();
                $l->area_id = (int) $new_locale_id;
                $l->reklamir_id = (int) $model->id;
                $l->save();
            }


        }  else {
            ReklamirArea::deleteAll(['reklamir_id'=>  $model->id]);
        }




        $selected_times = $model->times;

        $arrSets = explode(',', $selected_times);


        if (  is_string($selected_times) && strlen($selected_times) && count($arrSets)  && count($arrSets) != 168 ) {

            $old_links = ReklamirDaytime::findAll(['reklamir_id'=>  $model->id]);
            $old_domains = ArrayHelper::getColumn($old_links,'time_id');

            $del_domain_id = array_diff($old_domains,$arrSets);


            foreach ($old_links as $link){
                if ( in_array( $link->time_id,$del_domain_id)){
                    $link->delete();
                }
            }

            $new_locales_id = array_diff($arrSets,$old_domains);

            foreach ( $new_locales_id as $new_locale_id ){
                $l = new ReklamirDaytime();
                $l->time_id = (int) $new_locale_id;
                $l->reklamir_id = (int) $model->id;
                $l->save();
            }


        } else {
            ReklamirDaytime::deleteAll(['reklamir_id'=> $model->id]);
        }


    }


    public function actionBidMinuteTable(){

        $hour_num = (int) \Yii::$app->request->post('hour_num');
        $datetime = (int) \Yii::$app->request->post('datetime');
        $thing_id = (int) \Yii::$app->request->post('thing_id');



        $day_id = (int) date('d',$datetime);
        $mount_id = (int) date('m',$datetime);
        $year_id = (int) date('Y',$datetime);


        $bid = Bid::find()->joinWith(['reklamir_r'])->where([
            'reklamir.thing_id'=> $thing_id,
            'hour_id'=>$hour_num,'mount_id'=>$mount_id,
            'year_id'=>$year_id,'day_id'=>$day_id])->all();

        $bid_minute = ArrayHelper::index($bid,'minute_id');

        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;

        $data = $this->renderPartial('_bid_minutes',['bid'=>$bid,'bid_minute'=>$bid_minute] );

        return ['status'=>'success','data'=>  $data];


    }



    public function actionBidRemove(){
        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;

        $bid_id = (int) \Yii::$app->request->post('bid_id');

        $bid = Bid::findOne(['id'=>$bid_id]);
        if ($bid !== null){
            $bid->delete();
            return ['status'=>'success','response'=>  'Бронь удалена!'];
        }

        return ['status'=>'fail','response'=>  'Не найдено'];



    }

    public function actionBidHourTable(){


        $datetime = (int) \Yii::$app->request->post('datetime');

        $day_id = (int) date('d',$datetime);
        $mount_id = (int) date('m',$datetime);
        $year_id = (int) date('Y',$datetime);


        $bid = Bid::find()->where([
            'mount_id'=>$mount_id,
            'year_id'=>$year_id,
            'day_id'=>$day_id])->joinWith(['reklamir_r'])->all();

        $bid_hour = ArrayHelper::index($bid,'hour_id');
        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;

        $data = $this->renderPartial('_bid_hours',['bid'=>$bid,'bid_hour'=>$bid_hour] );

        return ['status'=>'success','data'=>  $data];


    }

    public function actionBidMake(){


        $minute_id = (int) \Yii::$app->request->post('minute_id');
        $hour_id = (int) \Yii::$app->request->post('hour_id');
        $date = (int) \Yii::$app->request->post('date');
        $val = (int) \Yii::$app->request->post('val');
        $reklamir_id = (int) \Yii::$app->request->post('reklamir_id');



        $bid = new Bid();
        $bid->val = $val;
        $bid->year_id = date('Y',$date);
        $bid->mount_id = date('m',$date);
        $bid->day_id = date('d',$date);
        $bid->hour_id = $hour_id;
        $bid->minute_id = $minute_id;
        $bid->reklamir_id = $reklamir_id;

        $bid->save();

        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;


        return ['status'=>'success','response'=>  'Забронировано!','data'=>$bid->id];


    }

    public function actionBidRewrite(){

        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;
        $minute_id = (int) \Yii::$app->request->post('minute_id');
        $hour_id = (int) \Yii::$app->request->post('hour_id');
        $date = (int) \Yii::$app->request->post('date');
        $val = (int) \Yii::$app->request->post('val');
        $reklamir_id = (int) \Yii::$app->request->post('reklamir_id');


        $bid_id = (int) \Yii::$app->request->post('bid_id');

        $old_bid = Bid::findOne(['id'=>$bid_id]);
        if ($old_bid !== null){
            if ($old_bid->val >= ($val+1) ){
                return ['status'=>'fail','response'=>  'Новая цена должна быть больше ставки!'];
            }
            $old_bid->delete();
        }
        if ($val < 11){
            return ['status'=>'fail','response'=>  'Новая цена должна быть больше ставки!'];
        }


        $bid = new Bid();
        $bid->val = $val;
        $bid->year_id = date('Y',$date);
        $bid->mount_id = date('m',$date);
        $bid->day_id = date('d',$date);
        $bid->hour_id = $hour_id;
        $bid->minute_id = $minute_id;
        $bid->reklamir_id = $reklamir_id;
        $bid->save();




        return ['status'=>'success','response'=>  'Цена перебита!'];


    }


    public function actionBidRewriteFull(){

        \Yii::$app->response->format =  \yii\web\Response::FORMAT_JSON;
        $minute_id = (int) \Yii::$app->request->post('minute_id');
        $hour_id = (int) \Yii::$app->request->post('hour_id');
        $date = (int) \Yii::$app->request->post('date');
        $val = (int) \Yii::$app->request->post('val');
        $reklamir_id = (int) \Yii::$app->request->post('reklamir_id');


        $bid_id = (int) \Yii::$app->request->post('bid_id');

        $old_bid = Bid::findOne(['id'=>$bid_id]);
        if ($old_bid !== null){
            if ($old_bid->val >= ($val+1) ){
                return ['status'=>'fail','response'=>  'Новая цена должна быть больше ставки!'];
            }
            $old_bid->delete();
        }
        if ($val < 11){
            return ['status'=>'fail','response'=>  'Новая цена должна быть больше ставки!'];
        }


        $bid = new Bid();
        $bid->val = $val;
        $bid->year_id = date('Y',$date);
        $bid->mount_id = date('m',$date);
        $bid->day_id = date('d',$date);
        $bid->hour_id = $hour_id;
        $bid->minute_id = $minute_id;
        $bid->reklamir_id = $reklamir_id;
        $bid->save();




        return ['status'=>'success','response'=>  'Цена выкуплена!'];


    }


    /**
     * Deletes an existing Reklamir model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Reklamir model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reklamir the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reklamir::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
