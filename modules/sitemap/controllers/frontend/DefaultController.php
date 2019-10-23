<?php

namespace app\modules\sitemap\controllers\frontend;

use app\modules\product\models\Product;
use app\modules\sitemap\models\Sitemap;
use yii\web\Controller;

/**
 * Default controller for the `textpage` module
 */


use Yii;


class DefaultController extends Controller
{



    //public  $layout = 'sitemap';

    //mine page
    public function actionSitemap()
    {

       $this->layout = 'sitemap';


       Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
       Yii::$app->response->headers->add('Content-Type', 'text/xml');

       return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">  

    <url>
        <loc>'. Yii::$app->request->hostInfo .'</loc>
        <lastmod>2019-10-15T19:06:11+03:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
 


</urlset>
';exit;

       $geo = Yii::$app->getModule('geo');


        $sitemap = new Sitemap();
        $sitemap->makeData();
        $sitemap->makeCatProd();


        return $this->renderPartial('sitemap',
            [
                'sitemap' => $sitemap,
            ]
        );

     /*  return $this->render('sitemap',
            [
                'sitemap' => $sitemap,
            ]
        );*/

    }

//sitemap/default/sitemap-array?secret_key=fdsf34fn34rfafds32
    public function actionSitemapArray(){
        $host = Yii::$app->request->hostInfo  . DIRECTORY_SEPARATOR;
        $arr = [];$c = 0;

        $sitemap = new Sitemap();

        $sitemap->makeData();
        $sitemap->makeCatProd();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        if (
            ! (
            Yii::$app->request->get('secret_key') &&
            Yii::$app->request->get('secret_key') == 'fdsf34fn34rfafds32')){
            return [];

        }


        foreach ($sitemap->textpages as $textpage) {

            if (  is_object($textpage->url_rr)) {

                $url = $textpage->url_rr;

                if ($url->rawHref){
                    $arr[] = $host . $url->rawHref; $c++;
                } else {
                    $arr[] =     Yii::$app->request->hostInfo;  $c++;
                }

            }
        }

        foreach ($sitemap->geoPage as $page) {  $c++;
            $arr[] = $page['url'];
        }

        foreach ($sitemap->products as $product) {

            if (  is_object($product->url_rr)) {

                $url = $product->url_rr;
                $arr[] =  $host . $url->rawHref;  $c++;
            }

            if (  is_object($product->urlComment_r)) {  $c++;
                $url = $product->urlComment_rr;
                $arr[] =  $host . $url->rawHref  ;

            }


        }


        foreach ( $sitemap->cat_prod as $item ){
            if (count($item['prod'])) {

                $url = $item['cat']->url_rr;
                $arr[] =    $host . $url->rawHref;
            }
        }


        foreach ( $sitemap->parent_cat as $item ){
            $url = $item['cat']->url_rr;
            $arr[] =  $host . $url->rawHref;  $c++;


        }

        foreach ($sitemap->catsblogs as $catsblog) {
            if (  is_object($catsblog->url_r)) {
                $url = $catsblog->url_rr;
                $arr[] = $host . $url->rawHref;
            }
        }

        foreach ($sitemap->blogs as $blog) {

            if (  is_object($blog->url_r)) {
                $url = $blog->url_rr;
                $arr[] = $host . $url->rawHref;
            }
        }

        foreach ($sitemap->filters as $filter) {

            if (  is_object($filter->url_r)) {
                $url = $filter->url_rr;
                $arr[] =   $host . $url->rawHref ;
            }
        }


        foreach ($sitemap->actions as $action) {

            if (  is_object($action->url_r)) {
                $url = $action->url_rr;
                $arr[] =   $host . $url->rawHref;   }
        }

        foreach ($sitemap->brands as $brand) {

            if (  is_object($brand->url_r)) {
                $url = $brand->url_rr;
                $arr[] =    $host . $url->rawHref;
            }  }

        foreach ($sitemap->brandscats as $brandscat) {

            if (  is_object($brandscat->url_r)) {
                $url = $brandscat->url_rr;
                $arr[] =  $host . $brandscat->url_r->rawHref;
            }
        }

        return $arr;

    }

}
