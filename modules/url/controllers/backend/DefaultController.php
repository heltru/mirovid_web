<?php

namespace app\modules\url\controllers\backend;


use yii\web\Controller;
use app\modules\url\models\UrlSearch;
use Yii;

/**
 * Default controller for the `url` module
 */
class DefaultController extends Controller
{
    /**
     * Lists all Url models.
     * @return mixed
     */
    public function actionIndex()
    {


        $searchModel = new UrlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Url model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


}
