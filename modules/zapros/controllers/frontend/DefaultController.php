<?php

namespace app\modules\zapros\controllers\frontend;

use app\modules\helper\models\Helper;
use Yii;
use app\modules\zapros\models\Zapros;
use app\modules\zapros\models\ZaprosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Order model.
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public $enableCsrfValidation = false;
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ZaprosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Zapros();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Order model.
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
     * Deletes an existing Order model.
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
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Zapros the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Zapros::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionIncoming(){
        $name = \Yii::$app->request->post('name');
        $phone = \Yii::$app->request->post('phone');
        $email = \Yii::$app->request->post('email');
        $text = \Yii::$app->request->post('text');
        $type = (int)\Yii::$app->request->post('type');



        $zapros = new Zapros();
        $zapros->name = $name;
        $zapros->phone = $phone;
        $zapros->email = $email;
        $zapros->text_query = $text;
        $zapros->date_cr = Helper::mysql_datetime();
        $zapros->type = $type;
        $zapros->save();

        $this->preseachZapros($zapros);

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return $zapros->id;
    }

    private function preseachZapros($zapros){
        $body =  [$zapros->name , $zapros->phone ,$zapros->email,$zapros->text_query];

        Yii::$app->mailer->compose()
            ->setFrom('mirovidweb@gmail.com')
            ->setTo('mirovidweb@yandex.ru')
            ->setSubject('Новая заявка - ' . Zapros::$arrTxtStatus[$zapros->type] . ' ' . $zapros->name)
           // ->setTextBody($body)
            ->setHtmlBody(implode('<br>',$body))
            ->send();
    }





}
