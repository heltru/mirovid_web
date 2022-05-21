<?php

namespace app\modules\api\controllers\frontend;

use Yii;
use yii\web\Controller;


class ChromeController extends Controller
{

    public function actionIndex($id=null){
        $this->layout = 'html';
        Yii::$app->log->targets['debug'] = null;
        return $this->render('viewer_main');
    }

}
