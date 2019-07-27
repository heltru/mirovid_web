<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.09.2018
 * Time: 1:30
 */

namespace app\modules\app\app;


use app\modules\block\models\Msg;
use Symfony\Component\Console\Helper\Helper;
use yii\db\Transaction;
use yii\helpers\BaseFileHelper;

class AppCreateMem
{

    public $block;
    public $msg;


    public function createMem($model,$block,$data){
        $this->block = $block;
        $this->msg = $model;

        // ex( \Yii::$app->request->post('Msg'));
        if (!$data['content'] ||
        ! in_array($data['type'],[Msg::T_T,Msg::T_I])
        ){
            return false;
        }

        $this->msg->block_id = $block->id;
        $this->msg->status = Msg::ST_OK;
        $this->msg->type = $data['type'];
        $this->msg->date_cr = date('Y-m-d H:i:s');
        $this->msg->date_update = date('Y-m-d H:i:s');


        if ($this->msg->type == Msg::T_I){
            $this->msg->content = $this->saveImageFromBase64($data['content']);
        }


        if ($this->msg->type == Msg::T_T){
            $this->msg->content = $this->saveImageFromText($data['content']);
        }


        $transaction = \Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        try {

            if ( $this->msg->save()){

                $block_msg  = new \app\modules\block\models\BlockMsg();
                $block_msg->block_id =  $this->block->id;
                $block_msg->msg_id =  $this->msg->id;

                $block_msg->save();

                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                ex($this->msg->getErrors());
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }


        return false;
    }


    private function saveImageFromBase64($data){
        $appAc = AppAccount::Instance();

        $path = 'uploads/account/' . $appAc->getAccount()->id  ;

        $fb = new BaseFileHelper();

        $fb->createDirectory($path, 0770);

        $file = $path .'/'.time().rand(1000,9999).'.png';

        \app\modules\helper\models\Helper::base64_to_jpeg($data,$file);
        return $file;
    }

    private function saveImageFromText($data){
        $appAc = AppAccount::Instance();

        $path = 'uploads/account/' . $appAc->getAccount()->id  ;

        $fb = new BaseFileHelper();

        $fb->createDirectory($path, 0770);

        $file = $path .'/'.time().rand(1000,9999).'.txt';



        \app\modules\helper\models\Helper::text_to_file($data,$file);
        return $file;
    }

}