<?php

namespace app\modules\api\controllers\frontend;

use yii\web\Controller;


class ChromeController extends Controller
{

    public function actionIndex($id=null){
        $this->layout = 'html';
        return $this->render('viewer_main');
    }

}
