<?php


namespace app\modules\block\controllers\backend;





use app\modules\app\app\MemCountLimitUpdate;
use app\modules\block\models\CountLimitShowTotalByAccount;
use app\modules\block\models\OrderShowSearchByAccount;
use Yii;


use yii\web\Controller;


/**
 * BadgeController implements the CRUD actions for Badge model.
 */
class BlockUtilsController extends Controller
{

    public function actionProductOfPop()
    {
        $searchModel = new OrderShowSearchByAccount();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $lm =  new CountLimitShowTotalByAccount();
        $count_limit_total = $lm->limit_show_total();



        return $this->render('block-of-pop', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'count_limit_total'=>$count_limit_total
        ]);
    }

    public function actionMsgUpdateCountLimit(){
        $msg_id = (int)Yii::$app->request->post('msg_id');
        $new_value = (int)Yii::$app->request->post('value');

        $app = new MemCountLimitUpdate();
        $app->update_count_limit($msg_id,$new_value);

    }


    public function actionBlockPopSort()
    {
        $sendArr = Yii::$app->request->post()['info'];

        $sql = "UPDATE msg SET account_sort =:account_sort WHERE id=:id";

        $explA = explode('&',$sendArr);
        $res = [];
        if (is_array($explA))
        {
            foreach ($explA as $p =>  $item){
                $explI = explode('=',$item);
                if (count($explI)>1){
                    $id = (int) $explI[1] ;


                    \Yii::$app->db->createCommand($sql)
                        ->bindValue(':account_sort', $p)->bindValue(':id', $id)
                        ->execute();
                    //echo 'UPDATE';

                }
            }
        }

    }

}
