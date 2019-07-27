<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.03.2018
 * Time: 3:46
 */
namespace app\modules\app\app;

use app\modules\account\models\Account;
use app\modules\block\models\Block;
use app\modules\block\models\Msg;
use app\modules\helper\HelperModule;
use app\modules\user\models\User;

class RkNewForm extends \yii\base\Model{

    public $_block;
    public $_msgs = [];


    public $_newmsg = [];
    private $countnewmsg =0;

    const SCENARIO_NEW = 'newform';
    const SCENARIO_OLD = 'oldform';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_NEW] = ['_newmsg', '_block','Block'];
        $scenarios[self::SCENARIO_OLD] = ['username', 'email', 'password'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['Block'], 'required'],

            [['_newmsg'], function ($attribute, $params) {
                if(! (int)count($this->$attribute )> 0  ){
                    $this->addError($attribute, 'Error count message');
                }
            },
                 'on' => self::SCENARIO_NEW
                ],

        ];
    }

    public function load($data, $formName = null){


        if (isset($data['Block']) && isset($data['Block']['name'])){
        //    $this->_block->name = $data['Block']['name'];

            $this->_block->setAttributes($data['Block']);
            if ($this->getScenario() == self::SCENARIO_OLD){

                $this->_block = Block::findOne(['id'=>$this->_block->id]);

                $this->_block->setAttributes($data['Block']);

            }
        }

        if (isset($data['Msg']) && is_array($data['Msg'])){


            foreach ($data['Msg'] as $num => $item){

                //validate img
            /*    if (  ( ! isset($item['content']) || ! $item['content'] || count( $this->_newmsg ) > 10
                    || count( $this->_msgs ) > 10 ) ){
                    continue;
                }*/

                if ($this->getScenario() == self::SCENARIO_NEW){
                    $msg = new  Msg();
                    $msg->setAttributes($item);
                    $this->setNewMsg($msg);
                }

                if ($this->getScenario() == self::SCENARIO_OLD ){
                    if ( isset($item['id']) && (int)$item['id'] ){
                        $msg = Msg::findOne(['id'=>$item['id']]);

                        if ($msg!== null){
                            $msg->setAttributes($item);

                            $this->setMsg($msg,$num);
                        }
                    } else {
                        $msg = new  Msg();
                        $msg->setAttributes($item);
                        $this->setNewMsg($msg);
                    }

                }



            }

        }
        return   parent::load($data);
     //   ex( $this);
    }

    public function afterValidate()
    {

        $error = false;
        if (!$this->_block->validate()) {
            $error = true;
        }
        if ($error) {
            $this->addErrors($this->_block->getErrors());
          //  $this->addError(null); // add an empty error to prevent saving
        }

        foreach ($this->_msgs as $msg){

            $error = false;
            if (!$msg->validate()) {
                $error = true;
            }

            if ($error) {
                $this->addErrors($msg->getErrors());
                //$this->addError(null); // add an emp1ty error to prevent saving
            }
        }


        parent::afterValidate();
    }

    public function beforeValidate()
    {

        $account = Account::findOne(['user_id'=>\Yii::$app->user->getId()]);
        if ($account !== null) {
            $this->_block->account_id = $account->id;
        }


        $this->_block->type = Block::T_RK;

        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function update(){


        if (!$this->validate()) {


            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();

        foreach ($this->_newmsg as $msg){
            $msg->block_id = $this->_block->id;
            $msg->date_cr = \app\modules\helper\HelperModule::convertDateToDatetime();
            $msg->type = \app\modules\block\models\Msg::T_P;
            $msg->date_update =   $msg->date_cr ;
            if (!$msg->save(false)) {
                $transaction->rollBack();
                return false;
            }


            $block_msg  = new \app\modules\block\models\BlockMsg();

            $block_msg->block_id =  $this->_block->id;
            $block_msg->msg_id =  $msg->id;

            if (! $block_msg->save(false)) {
                $transaction->rollBack();
                return false;
            }

        }

        if (!$this->_block->save() && ! $this->_block->isNewRecord ) {
            $transaction->rollBack();
            return false;
        }
/*
        ex(
            $this->_msgs
        );
*/
        foreach ($this->_msgs as $num => $msg){
            $msg->date_update =  \app\modules\helper\HelperModule::convertDateToDatetime();
            if (!$msg->save() && !$msg->isNewRecord) {
                $transaction->rollBack();

                return false;
            }

        }

        $transaction->commit();
        return true;
    }

    public function save()
    {

        if (!$this->validate()) {
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();

        $this->_block->date_cr = \app\modules\helper\HelperModule::convertDateToDatetime();
        if (!$this->_block->save()) {
            $transaction->rollBack();
            return false;
        }
        foreach ($this->_newmsg as $msg){
            $msg->block_id = $this->_block->id;
            $msg->date_cr = \app\modules\helper\HelperModule::convertDateToDatetime();
            $msg->date_update = $msg->date_cr;
            $msg->type = \app\modules\block\models\Msg::T_P;

            if (!$msg->save(false)) {
                $transaction->rollBack();
                return false;
            }


            $block_msg  = new \app\modules\block\models\BlockMsg();

            $block_msg->block_id =  $this->_block->id;
            $block_msg->msg_id =  $msg->id;

            if (! $block_msg->save(false)) {
                $transaction->rollBack();
                return false;
            }

        }

        $transaction->commit();
        return true;
    }

    public function getBlock()
    {
        return $this->_block;
    }

    public function setBlock($block)
    {
        if ($block instanceof Block) {
            if ($block->isNewRecord){
                $block->name = 'Новая компания';
            }
            $this->_block = $block;
        } else if (is_array($block)) {
            $this->_block->setAttributes($block);
        }
    }

    public function setMsg($msg,$id=null)
    {

        if ($msg instanceof \app\modules\block\models\Msg) {



            if ($id === null){
                $this->_msgs[count( $this->_msgs)+1] = $msg;
            } else {
                $this->_msgs[$id] = $msg;
            }
        } else if (is_array($msg) && $id) {

            $this->_msgs[$id]->setAttributes($msg);
        }

    }

    public function getMsg()
    {
         if (  ! ( is_array($this->_msgs) ||  count($this->_msgs))) return [];
         return $this->_msgs;
    }
    public function getNewMsg()
    {
        if (  ! ( is_array($this->_newmsg) ||  count($this->_newmsg))) return [];
        return $this->_newmsg;
    }

    public function setNewMsg($msg){
        $this->_newmsg['new_'.$this->countnewmsg] = $msg;
        $this->countnewmsg = count( $this->_newmsg );
    }
}