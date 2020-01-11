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


    public function actionPreview($id){

    }


    public function actionPreseachFile(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $link_id = $this->preseachFile();
        if ($link_id){
            return [
                'link'=>Url::to(['preview','id'=>$link_id]),
                'name' => 'Файл #' . $link_id
            ];
        }
    }

    public function actionIndex()
    {




        return $this->render('index');
    }

    private function preseachFile(){

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
