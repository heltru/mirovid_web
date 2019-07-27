<?php
/**
 * Created by PhpStorm.
 * User: o.trushkov
 * Date: 18.12.17
 * Time: 11:50
 */

namespace app\modules\url\services;



use app\modules\url\components\Transliteration;
use app\modules\url\models\Url;

class UrlService
{



    public function changePublic(Url $url ,$on = true){
        $url->public =  ($on) ?  Url::P_OK  : Url::P_NO;

    }

    public function getUrl(){

    }

    public function addMainPage($form,$conf = ['type_url'=>'textpage','identity'=>null,'action'=>'view']){

        $url = new Url();
        $url->setScenario('validMainPage');
        $url->href = $form->href;
        $url->real_canonical = $form->real_canonical;
        $url->title = $form->title;
        $url->h1 = $form->h1;
        $url->description_meta = $form->description_meta;
        $url->redirect = $form->redirect;
        $url->type_url = $conf['type_url'];
        $url->crs = $form->crs;
        $url->domain_id = $form->domain_id;
        $url->action = $conf['action'];
        $url->pagination = $form->pagination;
        $url->identity = $conf['identity'];
        $url->keywords = $form->keywords;


    }

    public function add($url,$conf = ['type_url'=>'textpage','identity'=>null,'action'=>'view']){

        $url->setScenario('validMainPage');

        $url->type_url = $conf['type_url'];
        $url->action = $conf['action'];
        $url->identity = $conf['identity'];

        $url->save();
        return $url;

    }


    public function findUrl($conf=['type_url'=>'textpage','identity'=>null,'action'=>'view']) //id action
    {
        if (    (   $prUrl = Url::findOne($conf) ) !== null) {
            return  $prUrl;
        } else {
            return new Url();
        }


        //throw new NotFoundHttpException('The url by product page does not exist.');
    }

    public  function transliterate($txt){
        $replacement = '-';
        $translator = new Transliteration();
        $translator->standard = Transliteration::GOST_779B;

        $txt = $translator->transliterate($txt);

        $string = preg_replace('/[^a-zA-Z0-9=\s—–-]+/u', '', $txt);
        $string = preg_replace('/[=\s—–-]+/u', $replacement, $string);
        $txt = trim($string, $replacement);
        return $txt;
    }




}