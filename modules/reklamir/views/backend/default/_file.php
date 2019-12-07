<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 28.11.2019
 * Time: 17:36
 */
use yii\helpers\Html;


if (is_object($model->file_r)){
    $pathinfo = pathinfo($model->file_r->path);
    $ext = $pathinfo['extension'];

    if (in_array($ext,['png','jpg','jpeg','gif','bmp'])){
        echo ($model->file_r->path) ? Html::img('/'.$model->file_r->path,['width'=>'150px']) : $model->file_r->name;
    } else {

        echo  (   $model->file_r->path_preview) ? Html::img('/'.$model->file_r->path_preview,['width'=>'150px']) : $model->file_r->name;
    }
}
 echo $form->field($model, 'uploadFile')->fileInput();