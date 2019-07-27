<?php

namespace app\modules\url\controllers\backend;

use app\modules\url\models\Url;
use app\modules\url\services\UrlService;


use Yii;

use yii\filters\AccessControl;
use yii\helpers\BaseInflector;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;


/**
 * UrlController implements the CRUD actions for Url model.
 */
class UrlController extends Controller
{
    private $urlService;

    public function __construct($id, $module, UrlService $urlService, $config = [])
    {
        $this->urlService = $urlService;

        parent::__construct($id, $module, $config);
    }

    public $module;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }





    public function actionMakeHref(){

        Yii::$app->response->format = Response::FORMAT_JSON;
        $txt = Yii::$app->request->post()['txt'];

        $oldHref = ( Yii::$app->request->post('oldHref')) ?
            Yii::$app->request->post('oldHref') : null;

        $clr = explode('/',$txt);
        if (count($clr)>1){
            $txt = end($clr);
        }


        $smth = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode( $txt));
        $txt = html_entity_decode( $smth,null,'UTF-8');

        $txt = $this->urlService->transliterate(mb_strtolower ($txt));


        $m = Url::findOne(['href'=>$txt]);

        if ( ($txt == $oldHref) || ( $m === null )){
            return ['status'=>'200','data'=> $txt ];
        }  else {
            return ['status'=>'500','message'=>'error',
                'for'=>$txt,
                'data'=> $m->type_url . '_' . $m->action  .'_' . $m->identity . '_' .  $m->rawHref ];
        }
    }

    public function actionValidateUrlRedirect()
    {
        $url = new Url();
        $url->setScenario('newRedirect');
        $request = \Yii::$app->getRequest();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isPost &&  $url->load( $request->post() ,'Url' ) ) {

            return ActiveForm::validate($url);// return ActiveForm::validate($model,$url); return ActiveForm::validateMultiple($models);
        }
    }





    /**
     * Creates a new Url model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateRedirect()
    {
        $model = new Url();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('redirect-form', [
                'url' => $model,
            ]);
        }
    }

    public function actionCreateRedirectUrl(){

        $oldHref = Yii::$app->request->post('oldUrl');
        $newHref = Yii::$app->request->post('newUrl');
        $valid = false;


        if ( $oldHref && $newHref ) {
            $modelOld = Url::find()->where(['href'=>$oldHref])->one();
            if ($modelOld !== null){
                Yii::$app->session->setFlash('danger',
                    'Старый url уже есть, воспользуйтесь операцией копирования url');
            }

            $model = Url::find()->where(['href'=>$newHref])->one();

            if ($model !== null  && $modelOld === null){

                $modelNew = clone $model;
                $modelNew->isNewRecord = true;
                $modelNew->attributes = $model->attributes;
                $modelNew->id = null;
                $modelNew->href = $oldHref;
                $modelNew->setScenario('validHref');
                $modelNew->redirect = $model->id;
                if ($modelNew->save()){
                    $valid = true;
                }
            } else {
                Yii::$app->session->setFlash('danger',
                    'Новый url не найден');
            }
        }

        if ( $valid ){
            return $this->redirect(\yii\helpers\Url::to(['/url/index']));
        } else {
            return $this->render('redirect-form',['oldHref'=>$oldHref,'newHref'=>$newHref]);
        }

    }


    public function actionDoubleUrl(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $href = Yii::$app->request->get('href');
        $find = Url::find()->where(['href'=>$href])->one();

        if ($find !== null){
            return ['status'=>500];
        }
        return ['status'=>200];

    }





}
