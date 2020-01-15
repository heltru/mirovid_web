<?php

namespace app\modules\preview\controllers\frontend;

use app\modules\file\models\FilePreview;
use app\modules\preview\models\Preview;
use Yii;

use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Reklamir model.
 */
class DefaultController extends Controller
{

    public $dir = 'preview';

    public $enableCsrfValidation = false;

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




    public function actionListNeedPreview(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Preview::find()->where(['status'=>Preview::ST_NEED_PREVIEW])->asArray()->all();
    }


    public function actionUploadFile(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $link_id = $this->uploadFile();
        if ($link_id){
            return [
                'link'=> '/preview/default/index/?id='.$link_id,
                'name' => 'Файл #' . $link_id
            ];
        }
    }


    public function actionIndex()
    {
        $id = (int) Yii::$app->request->get('id');

        $preview = Preview::find()->where(['id'=>$id,'status'=>Preview::ST_READY])->one();
        $link = '';
        if ($preview !== null){
            $link = $preview->link;
        }

        return $this->render('index',['id'=>$id,'link'=>$link]);
    }


    public function actionReceivePreview(){
        $id = (int)Yii::$app->request->post('id');
        $preview = Preview::find()->where(['status'=>Preview::ST_NEED_PREVIEW,'id'=>$id])->one();
        if ($preview !== null){
            foreach ($_FILES as $file){
                $pathinfo = pathinfo($file['name']);
                $filename = time() . '_' . rand(1000,9999) .'.'. $pathinfo['extension'];
                if (! is_dir($this->dir)){
                    FileHelper::createDirectory($this->dir);
                }
                $new_file_path = $this->dir . '/' . $filename;

                file_put_contents($new_file_path,file_get_contents($file['tmp_name']));
                chmod($new_file_path, 0660);
                $preview->link = $new_file_path;
                $preview->status = Preview::ST_READY;
                $preview->update(false,['link']);
            }
        }


    }

    private function uploadFile(){

        if (isset($_FILES['file']) && isset($_FILES['file']['tmp_name'])  == ''){
            return -1;
        }
        if (! count($_FILES)){
            return -1;
        }

        $pathinfo = pathinfo($_FILES['file']['name']);
        $filename = time() . '_' . rand(1000,9999) .'.'. $pathinfo['extension'];



        if (! is_dir($this->dir)){
            FileHelper::createDirectory($this->dir);
        }

        $new_file_path = $this->dir . '/' . $filename;

        file_put_contents($new_file_path,file_get_contents($_FILES['file']['tmp_name']));
        chmod($new_file_path, 0660);

        $rec = new Preview();
        $rec->file = $new_file_path;
        $rec->save();

        return  $rec->id;

    }



}
