<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.12.2018
 * Time: 10:51
 */

namespace app\modules\app\app;


use app\modules\block\models\Msg;
use app\modules\block\models\MsgDaytime;
use app\modules\block\models\MsgLocale;
use app\modules\block\models\MsgLocaleCost;
use app\modules\helper\models\Helper;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;
use yii\helpers\Json;

class AppUpdateMem
{


    public $msg;


    public function updateMem($model){

        $this->msg = $model;


        if (
            $this->msg->content_update &&
              $this->msg->raw_data &&
            in_array($this->msg->type,[Msg::T_T,Msg::T_I])
        ){

            if ($this->msg->type == Msg::T_I){
                $this->updateImageFromBase64();
            }


            if ($this->msg->type == Msg::T_T){
                $this->updateImageFromText();
            }
        }





        $this->msg->date_update = Helper::mysql_datetime();

        $transaction = \Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        try {

            if ( $this->msg->save()){


                $this->preseachTimeAndGeo();

                $this->preseachLocaleCost();

                $this->preseachCopyMem();


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


    private function preseachCopyMem(){
        if ( !$this->msg->copy_mem_id ){
            return;
        }
        $source = Msg::findOne(['id'=>$this->msg->copy_mem_id]);
        if ($source === null){
            return;
        }

        MsgLocaleCost::deleteAll(['msg_id'=>$this->msg->id]);
        foreach ($source->locale_cost_r as $item){
            /*
            $old = MsgLocaleCost::findOne(['locale_id'=>$item->locale_id,'msg_id'=>$this->msg->id]);
            if ($old !== null){
                continue;
            }*/
            $l = new MsgLocaleCost();
            $l->locale_id = (int) $item->locale_id;
            $l->msg_id = (int) $this->msg->id;
            $l->cost =  $item->cost;
            $l->save();
        }

        MsgLocale::deleteAll(['msg_id'=>$this->msg->id]);
        foreach (  $source->locale_r as $item ){
            /*
            $old = MsgLocale::findOne(['locale_id'=>$item->locale_id,'msg_id'=>$this->msg->id]);
            if ($old !== null){
                continue;
            }
            */
            $l = new MsgLocale();
            $l->locale_id = (int) $item->locale_id;
            $l->msg_id = (int) $this->msg->id;
            $l->save();
        }

        MsgDaytime::deleteAll(['msg_id'=>$this->msg->id]);
        foreach ( $source->daytime_r as $item ){
            /*
            $old = MsgDaytime::findOne(['time_id'=>$item->time_id,'msg_id'=>$this->msg->id]);
            if ($old !== null){
                continue;
            }
            */
            $l = new MsgDaytime();
            $l->time_id = (int) $item->time_id;
            $l->msg_id = (int) $this->msg->id;
            $l->save();

        }




    }

    private function preseachLocaleCost(){
        $items = [];
        try {
            $items = Json::decode($this->msg->locales_cost);
        } catch ( \Exception $e){
            return;
        }

        if (   count($items) ) {

            $arrSets = []; $items_cost = [];
            foreach ($items as $item){
                $arrSets[] = (int)$item[0];
                $items_cost[(int)$item[0]]  = (int) $item[1];
            }



            $old_links = MsgLocaleCost::findAll(['msg_id'=>  $this->msg->id]);
            $old_domains = ArrayHelper::getColumn($old_links,'locale_id');

            $del_domain_id = array_diff($old_domains,$arrSets);

            foreach ($old_links as $link){
                if ( in_array( $link->locale_id,$del_domain_id)){
                    $link->delete();
                }
            }

            $new_locales_id = array_diff($arrSets,$old_domains);

            foreach ( $new_locales_id as $new_locale_id ){
                $l = new MsgLocaleCost();
                $l->locale_id = (int) $new_locale_id;
                $l->msg_id = (int) $this->msg->id;
                $l->cost =  $items_cost[$new_locale_id];
                $l->save();
            }


        }  else {
            MsgLocaleCost::deleteAll(['msg_id'=>  $this->msg->id]);
        }



    }

    private function preseachTimeAndGeo(){

        $selected_times = $this->msg->locales;


        $arrSets = explode(',', $selected_times);


        if (  is_string($selected_times) && strlen($selected_times) && count($arrSets) ) {


            $old_links = MsgLocale::findAll(['msg_id'=>  $this->msg->id]);
            $old_domains = ArrayHelper::getColumn($old_links,'locale_id');

            $del_domain_id = array_diff($old_domains,$arrSets);

            foreach ($old_links as $link){
                if ( in_array( $link->locale_id,$del_domain_id)){
                    $link->delete();
                }
            }

            $new_locales_id = array_diff($arrSets,$old_domains);

            foreach ( $new_locales_id as $new_locale_id ){
                $l = new MsgLocale();
                $l->locale_id = (int) $new_locale_id;
                $l->msg_id = (int) $this->msg->id;
                $l->save();
            }


        }  else {
            MsgLocale::deleteAll(['msg_id'=>  $this->msg->id]);
        }




        $selected_times = $this->msg->times;

        $arrSets = explode(',', $selected_times);


        if (  is_string($selected_times) && strlen($selected_times) && count($arrSets) ) {




            $old_links = MsgDaytime::findAll(['msg_id'=>  $this->msg->id]);
            $old_domains = ArrayHelper::getColumn($old_links,'time_id');

            $del_domain_id = array_diff($old_domains,$arrSets);


            foreach ($old_links as $link){
                if ( in_array( $link->time_id,$del_domain_id)){
                    $link->delete();
                }
            }

            $new_locales_id = array_diff($arrSets,$old_domains);

            foreach ( $new_locales_id as $new_locale_id ){
                $l = new MsgDaytime();
                $l->time_id = (int) $new_locale_id;
                $l->msg_id = (int) $this->msg->id;
                $l->save();
            }


        }  else {
           // MsgDaytime::deleteAll(['msg_id'=>  $this->msg->id]);
        }



    }



    private function saveImageFromBase64(){
        $appAc = AppAccount::Instance();

        $path = 'uploads/account/' . $appAc->getAccount()->id  ;

        $fb = new BaseFileHelper();

        $fb->createDirectory($path, 0770);

        $file = $path .'/'.time().rand(1000,9999).'.png';

        \app\modules\helper\models\Helper::base64_to_jpeg($this->msg->raw_data,$file);
        return $file;
    }

    private function updateImageFromBase64(){
        $file = $this->msg->content;
        if (file_exists($file)){
            \app\modules\helper\models\Helper::base64_to_jpeg($this->msg->raw_data,$file);
        } else {
            $file = $this->saveImageFromBase64();
        }
        return $file;
    }

    private function saveImageFromText(){
        $appAc = AppAccount::Instance();

        $path = 'uploads/account/' . $appAc->getAccount()->id  ;



        if (! is_dir($path)){
            $fb = new BaseFileHelper();
            $fb->createDirectory($path, 0770);
        }

        $file = $path .'/'.time().rand(1000,9999).'.txt';

        \app\modules\helper\models\Helper::text_to_file($this->msg->raw_data,$file);
        $this->msg->content = $file;
        return $file;
    }

    private function updateImageFromText(){
        $file = $this->msg->content;
        if (file_exists($file)){
            \app\modules\helper\models\Helper::text_to_file($this->msg->raw_data,$file);
        } else {
            $file = $this->saveImageFromText();
        }
        return $file;

    }

}