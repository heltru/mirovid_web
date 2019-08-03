<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\UploadVideoForm;
use yii\web\Controller;
use yii\web\UploadedFile;
use Yii;


class DefaultController extends Controller
{

    private $video_dir= 'mirovid/video';

    public function init()
    {
        define('VIDEO_PATH','mirovid/video');
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function actionUploadVideo(){
        $model = new UploadVideoForm();

        if (Yii::$app->request->isPost) {
            $model->videoFile = UploadedFile::getInstance($model, 'videoFile');
            if ($model->upload()) {
                Yii::$app->session->setFlash('success','Video upload !');

            }
        }

        return $this->render('upload-video',['model'=>$model,'video'=>$this->getListVideo()]);
    }






    private function getListVideo()
    {

        $video = [];

        try{
            foreach (scandir(  $this->video_dir) as $file) {
                $file_path = $this->video_dir . '/' . $file;
                if (!is_file($file_path)) {
                    continue;
                }
                $path_info = pathinfo($file_path);
                $filename = $path_info['filename'] .'.'.$path_info['extension'];
                $video[] = $filename;
            }
        } catch (\Exception $e){

        }

        return $video;
    }



    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionTest(){
        exit;
    }
}
