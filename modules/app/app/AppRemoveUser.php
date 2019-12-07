<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 23.11.2019
 * Time: 22:53
 */

namespace app\modules\app\app;


use app\modules\account\models\Account;
use app\modules\pay\models\Pay;
use app\modules\pay\models\PayForm;
use app\modules\pay\models\Trx;
use app\modules\reklamir\models\Reklamir;
use app\modules\user\models\User;

class AppRemoveUser
{

    private $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->begin();
    }


    public function begin(){

        $user = User::findOne(['id'=>$this->user_id]);
        if ($user === null){
            return;
        }

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $account = Account::findOne(['user_id'=>$this->user_id]);
            if ($account !== null){
                $account->delete();
            }


            foreach ( Pay::find()->where(['account_id'=>$account->id])->all() as $pay){
                $pay->delete();
                $trix = Trx::find()->orWhere(['dt'=>$pay->id])->orWhere(['kt'=>$pay->id])->all();
                foreach ($trix as $trx){
                    $trx->delete();
                }
            }

            foreach (Reklamir::find()->where(['account_id'=>$account->id])->all() as $reklama) {
                $reklama->delete();
            }

            foreach (PayForm::find()->where(['user_id' => $user->id])->all() as $pay_form) {
                $pay_form->delete();
            }

            $user->delete();

            $manager = \Yii::$app->authManager;
            $item = $manager->getRole('client');
            $manager->revoke($item, $user->id);

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }



    }

}