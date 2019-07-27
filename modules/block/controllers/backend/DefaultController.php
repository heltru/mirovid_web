<?php

namespace app\modules\block\controllers\backend;

use app\modules\app\app\AppAccount;
use app\modules\app\app\AppCreateMem;
use app\modules\app\app\AppCreateRk;
use app\modules\app\app\AppManageMemInRk;
use app\modules\app\app\AppMemDelete;
use app\modules\app\app\AppNovaVidAdminClient;
use app\modules\app\app\AppUpdateMem;
use app\modules\app\app\RkNewForm;
use app\modules\block\models\BlockMsg;
use app\modules\block\models\BlockMsgSearch;
use app\modules\block\models\MemTableSearch;
use app\modules\block\models\Msg;
use dmstr\widgets\Alert;
use Yii;
use app\modules\block\models\Block;
use app\modules\block\models\BlockSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * BlockController implements the CRUD actions for Block model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Block models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $form = new RkNewForm();
        $form->setScenario(RkNewForm::SCENARIO_NEW);
        $form->setBlock(new Block());
        $form->setNewMsg(new Msg());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'form'=>$form
        ]);
    }


    public function actionBlockOrderCommon()
    {
        $searchModel = new BlockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionAddNewMsgForm(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $num = (int) Yii::$app->request->post('num');

        $form = ActiveForm::begin();


        return ['status'=>'success','response'=>
         $this->renderPartial('_msg',['model'=>new Msg(),'form'=>$form,'num'=>$num])
        ];
    }

    public function actionRmvMsg(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id_m = Yii::$app->request->post('id_m');
        $id_b = Yii::$app->request->post('id_b');

        if (! ($id_b && $id_m)) return null;

        $app = AppNovaVidAdminClient::Instance();
        if ( $app->deleteMsgFromBlock($id_m,$id_b)){
            Yii::$app->getSession()->setFlash('success', 'Сообщение удалено!');
            return ['status'=>'success','response'=>  Alert::widget()];
        }

    }

    public function actionAddNewRkAjax(){

        Yii::$app->response->format = Response::FORMAT_JSON;

        $productForm = new RkNewForm();
        $productForm->setScenario(RkNewForm::SCENARIO_NEW);
        $productForm->block = new Block( );

        parse_str( Yii::$app->request->post('form' ),$output );

       // ex($output);

        $productForm->load($output);

        //ex($productForm);


        if ( $productForm->save() ) {
            Yii::$app->getSession()->setFlash('success', 'Новая компания "'.$productForm->block->name.'" добавлена!');

            $form = new RkNewForm();
            $form->setScenario(RkNewForm::SCENARIO_NEW);
            $form->setBlock(new Block());
            $form->setNewMsg(new Msg());

            return ['status'=>'success','response'=> ['alert'=>Alert::widget(),
                'newform' => $this->renderPartial('_rk_form_new',['model'=>$form])]  ];
        } else {
            foreach ($productForm->getErrors() as $attr => $error){
                Yii::$app->session->setFlash('danger', $error[0]);
            }
            return ['status'=>'error','response'=>
            Alert::widget()
               // print_r($productForm->getErrors())
            ];
        }


    }

    public function actionDelRkCompany($id){

        //Yii::$app->response->format = Response::FORMAT_JSON;

        //$id = (int) Yii::$app->request->post()['id'];

        $app = AppNovaVidAdminClient::Instance();

        $res = $app->deleteRkCompany($id);

        if ($res === true ){

            Yii::$app->getSession()->setFlash('success', 'Компания  удалена!');

            return $this->redirect('/admin/block/default/index');
            //return ['status'=>'success','response'=>  Alert::widget()];

        } else {

            Yii::$app->session->setFlash('danger','Ошибка удаления');
            return $this->redirect(['/admin/block/default/view','id'=>$id]);
           /* return ['status'=>'error','response'=>
                Alert::widget()
            ];
           */
        }


    }

    public function actionViewRk(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = (int) Yii::$app->request->post()['id'];
        $app = AppNovaVidAdminClient::Instance();
        $res = $app->viewRk($id);
        if ($res !== null){
            return ['status'=>'success','response'=>$res];
        } else {
            return ['status'=>'error','response'=>$res];
        }

    }

    public function actionUpdateRkAjax(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $productForm = new RkNewForm();
        $productForm->setScenario(RkNewForm::SCENARIO_OLD);

        $productForm->block = new Block( );

        parse_str( Yii::$app->request->post('form' ),$output );


        $productForm->load($output);

    //    ex($output);

        $update = $productForm->update();
    //    ex($update);
        if ( $update  ) {

            Yii::$app->getSession()->setFlash('info', 'Компания "'.$productForm->block->name.'" обновлена!');
            return ['status'=>'success','response'=>  Alert::widget()];

        } else {


            foreach ($productForm->getErrors() as $attr => $error){
                Yii::$app->session->setFlash('danger', $error[0]);
            }
            return ['status'=>'error','response'=>
                Alert::widget()
                // print_r($productForm->getErrors())
            ];
        }



    }


    /**
     * Displays a single Block model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $r = false;

        $model = $this->findModel($id);
        $mem = new Msg();


        if ($mem->load(Yii::$app->request->post()) ) {
            $app = new AppCreateMem();

            if ( $app->createMem($mem,$model, Yii::$app->request->post('Msg')) ){
                Yii::$app->session->setFlash('success', 'Mem '.$mem->id.' Создан!');
                $r = true;

            }
        }


        //rename Company

        if ($model->load(Yii::$app->request->post()) ) {

            if ( $model->update() ){
                Yii::$app->session->setFlash('success', 'Компания '.$model->id.' Обновлена!');
                $r = true;
            }
        }


        if ($r){
            return $this->redirect(Url::to(['view','id'=>$model->id]));
        }


        $memTableSearch = new MemTableSearch();
        $memTableSearchDp = $memTableSearch->search(Yii::$app->request->post(),$model->id);

        $this->view->params['curr_block_id'] = $model->id;

        return $this->render('view', [
            'mem' => $mem,
            'memTableSearchDp'=>$memTableSearchDp,
            'memTableSearch'=>$memTableSearch,
            'model' => $model,
        ]);

    }

    public function actionMsgUpdate($id){

        $model= Msg::findOne(['id'=>$id]);

        $model->locales = implode(',',ArrayHelper::getColumn(  $model->locale_r ,'locale_id'));
        $model->times = implode(',',ArrayHelper::getColumn( $model->daytime_r ,'time_id')) ;
//        $model->locales_cost = implode(',',ArrayHelper::getColumn( $model->locale_cost_r ,'locale_id')) ;

        $items_cost = [];
        foreach ($model->locale_cost_r as $item){
            $items_cost[] = [$item->locale_id,$item->cost];
        }


        $model->locales_cost = Json::encode( $items_cost );


     //
        if ($model->load(Yii::$app->request->post()) ) {

            $app = new AppUpdateMem();



            if ( $app->updateMem($model) ){
                Yii::$app->session->setFlash('success', 'Mem '.$model->id.' Обновлен!');
                return $this->redirect(['msg-update','id'=>$model->id]);
                $r = true;
            }

        }

        $this->view->params['curr_block_id'] = $model->block_id;
        $this->view->params['curr_msg_id'] = $model->id;

        return $this->render('_mem_update_' .$model->type, [
            'mem' => $model,
        ]);
    }

    public function actionMsgAddToRk(){
        $block_id  = (int) Yii::$app->request->get('id');
        $msg_id = (int) Yii::$app->request->get('id_msg');


        $app = new AppManageMemInRk();
        if ( $app->memAddToRk($msg_id,$block_id)){
            Yii::$app->session->setFlash('success','Мем добавлен к списку показа!');
        }

        return $this->redirect(  Yii::$app->request->referrer);
    }

    public function actionMsgRemoveToRk(){
        $block_id  = (int) Yii::$app->request->get('id');
        $msg_id = (int) Yii::$app->request->get('id_msg');


        $app = new AppManageMemInRk();
        if ( $app->memRemoveToRk($msg_id,$block_id) !== false){
            Yii::$app->session->setFlash('success','Мем удален из списка показа!');
        }

        return $this->redirect(  Yii::$app->request->referrer);


    }

    public function actionMemDelete($id){

        $mem = Msg::findOne(['id'=>$id]);

        if ($mem!==null){
            $block_id = $mem->block_id;
            $app = new AppMemDelete();
            $app->delete_mem($mem);

            return $this->redirect(['/admin/block/default/view','id'=>$block_id ]);
        }

        return $this->redirect(  Yii::$app->request->referrer);


    }

    public function actionTest(){

        return $this->render('test');
    }





    public function actionList($id)
    {

        $rk = $this->findModel($id);

        $searchModel = new BlockMsgSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$rk->id);


        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rk'=>$rk
        ]);
    }

    /**
     * Creates a new Block model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $appCreate = new AppCreateRk();

        if ( $appCreate->company->load(Yii::$app->request->post()) ) {


            if ( $appCreate->createRk() ){

                Yii::$app->session->setFlash('success', 'Рк '.$appCreate->company->name.' Создана!');
                return $this->redirect(['view', 'id' =>$appCreate->company->id]);
            }


        }

        return $this->render('create', [
            'model' => $appCreate->company,
        ]);
    }

    public function actionDeleteMsgFromRk($id_msg){
        $msg = Msg::findOne(['id'=>$id_msg]);
        if ($msg !== null){
            $app = new AppMemDelete();
            $app->delete_mem($msg);
            Yii::$app->session->setFlash('success', 'Мем удален!');

        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Updates an existing Block model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Block model.
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
     * Finds the Block model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Block the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Block::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
