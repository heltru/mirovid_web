<?php

namespace app\modules\main\controllers\frontend;

use yii\web\Controller;

class DefaultController extends Controller
{
 public $layout = '/adminlte/main-login';

   public function actions()
    {
        /*
        return [
             'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
        */
    }

    public function actionIndex()
    {
        ex(4);
        $this->layout = 'landing';
        return $this->render('landing/view');
    }



    public function actionError()
    {

        return $this->render('error');

    }


}
