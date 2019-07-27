<?php
namespace app\modules\url\components;

//use common\models\Domain;
//use common\models\GeoCity;
use app\modules\url\models\Url;
use yii\db\Expression;
use yii\web\UrlRuleInterface;
use yii\base\Object;
//use common\models\Product;
//use common\models\Url;
/**
 * Created by PhpStorm.
 * User: o.trushkov
 * Date: 24.05.17
 * Time: 13:45
 *
 */

class CardRule extends Object implements UrlRuleInterface
{

    private  $deph=0;


    public function createUrl($manager, $route, $params)
    {

        $this->deph = 0;
        //var_dump($route,$params);exit; //string(12) "catalog/view" array(1) { ["id"]=> int(9) }


        $arr = explode('/',$route); //check count == 2
       // ex($route);
       /* if (count( $arr) == 5 ){
            ex($arr);
        }*/
        if (isset($params['id'])) {

            if ($arr[1] == 'default'){
                array_splice($arr, 1, 1);
            }

            $urlM = Url::find()->where([
                'identity'=>$params['id'],'type_url'=>$arr[0],'action'=>$arr[1] ])
                ->andWhere(['public'=> Url::P_OK])
                ->one();


             if (  $urlM !== null && $urlM->rawHref ) {

                /* if ($params['id'] == 32){
                     echo '<pre>';
                     var_dump($urlM);
                 }*/

              /*   if ($urlM->rawHref == 'kopchenie/dymogeneratory/platon-xl'){

                     var_dump($urlM);
                 }*/


                 $urlM = $this->checkRedirect($urlM);

                 /*if ($urlM->rawHref == 'kopchenie/dymogeneratory/platon-xl-two123'){

                     var_dump($urlM);
                 }*/

                 unset($params['id']);

               /*  if ( \Yii::$app->geoModule->isGeo ){
                     $urlM = \Yii::$app->geoModule->createGeoUrl($urlM);
                 }*/

                 $href = $urlM->rawHref;


                 if ( isset($params['url']) ){
                     unset($params['url']);
                 }

                 if ( isset($params['page']) ){
                     $href .= '/' . $params['page'];
                     unset($params['page']);
                 }

                 $_query = http_build_query($params);



                 $url = (!empty($_query)) ?    $href . '?' . $_query  :   $href;



                 return  $url;
             }  return  false;
        }

        return false;  // данное правило не применимо
    }

    public function parseRequest($manager, $request)
    {

        //   $this->checkActiveDomain();

        /*
         * if ( isset Domain ){
         *      find Domain -> check active Domain -> get entity Domain -> set entity In geoModule
         *
         * }
         * */


        $this->deph = 0;
        //  $this->saveUtm();
        $this->saveUtm( );


        $pathInfo = $request->getPathInfo(); //koptilna-kasseler/2

        $pathInfo = trim($pathInfo, '/');

        $expPath =  explode('/',$pathInfo);
        $page = null;

        if( count($expPath) > 1){
            $lastParam = array_pop($expPath);
            $a = (int) $lastParam;
            $a.='';

            if ( $lastParam == $a  && $a > 0 /*is_integer(  $lastParam  )*/ ){
                $page = $lastParam;
            }
        }

        $result = $this->findUrl($pathInfo,null);



        if(!empty($result)){
            return $result;
        }



        if( $page !== NULL ){
            $result = $this->findUrl(join('/',$expPath),$page);
            if( empty($result) ){
                return false;
            }
            return $result;
        }


        return false;  // данное правило не применимо
    }





    private function checkRedirect($urlM){
        $this->deph ++;
        if($urlM->redirect == 0 || $this->deph > 5){
            return $urlM;
        }
        $original = clone $urlM;
        $urlM = Url::find()->where(['id'=>$urlM->redirect])->one();

        if ($urlM !== null ){
            return $this->checkRedirect($urlM);
        }
        return $original;
    }

    private function checkActiveDomain(){
        //samara.testeav.it-06.aim/
        //samara.koptilka.com/

        $hn = \Yii::$app->request->hostName;
        $domain_name = explode('.',$hn);

        $domDb = Domain::find()->where(['alias'=>$domain_name[0],'active'=>1])->one();

        if ($domDb !== null && $domDb->type == 'geo'){
            \Yii::$app->geoModule->setDomainSity($domDb->id_type);
        }

    }

    private function makeQueryLastMod($query){
        if ( ! \Yii::$app->user->can('admin')) {
            $time = new \DateTime('now');
            $today = $time->format('Y-m-d');

            $query->andWhere([ '<=',   new Expression('DATE(url.last_mod)'), $today]);
        }
        return $query;
    }

    private function findUrl($urlStr = '',$page = null){


        $query = Url::find()->where(['href'=>$urlStr])->andWhere(['public'=> Url::P_OK]);

        $query = $this->makeQueryLastMod($query);
        $url = $query->one();

        /////////
      /*  if ($url === null){
            $query = Url::find()->where(['href'=>$urlStr])->andWhere(['public'=> Url::P_OK]);
            $query = $this->makeQueryLastMod($query);
            $url = $query->one();
        }*/
        /////////
        if ( ($url !== null) && ( $url->type_url ) &&  ( $url->action ) &&  ( $url->identity )){
            if($url->pagination == false && $page !== null){
                return [];
            }



            if ( $url->redirect && is_object($url->redirect_r) ){
                $url = Url::checkRedirect($url);

                \Yii::$app->response->redirect( '/' . $url->rawHref,301);
            }

            $route = $url->type_url.'/default/'.$url->action;

            $params = ['id'=>$url->identity,'url'=>$url ];



            if( $page !== NULL  ){
                $params['page'] = $page;
            }

            if($page == 1){
                $this->redirectPagination($urlStr);
            }
          /*  echo '<pre>';
            var_dump(
                $route,$params
            ); exit;
            echo ' 123 '; exit;*/

            return [ $route,$params];
        }

        return [];
    }


    private function redirectPagination( $urlStr ){

        $querySrt  = \Yii::$app->request->queryString;
        $redirUrl = '/' .$urlStr;

        if ($querySrt){
            \Yii::$app->response->redirect( $redirUrl . '?' . urldecode($querySrt), 301);
        } else {
            \Yii::$app->response->redirect( $redirUrl  , 301);
        }

    }
    private function saveUtm(){

        //echo '<pre>';
        $session = \Yii::$app->session;
        $session->open();
        /* echo '<pre>';
         var_dump($session->get('UTM_PATH'));*/
        $firstUrl = \Yii::$app->request->queryString;
        $secondUrl = \Yii::$app->request->url;




        //  if ($secondUrl == '/') $secondUrl = \Yii::$app->request->hostName;
        /*      echo '<pre>';

              var_dump([
                  'first_url'=>$firstUrl . $secondUrl,
                  'last_url'=>\Yii::$app->request->url
                  ]);
      exit;*/

        if (  ! ($session->get('first_url')) &&  (  ( is_string($firstUrl) && strlen($firstUrl) > 0 )  ||
                (  ( is_string($secondUrl) && strlen($secondUrl) > 0) ))){

            $session->set('first_url',$firstUrl . $secondUrl);
        }



        $session->set('last_url',\Yii::$app->request->url);
        // var_dump($query);


        $session->close();
    }

}

