<?php
/**
 * Created by PhpStorm.
 * User: o.trushkov
 * Date: 07.08.17
 * Time: 11:24
 */

namespace app\modules\sitemap\models;


use app\modules\blogcat\models\BlogCat;
use app\modules\brandcat\models\BrandCat;
use app\modules\catalog\models\Cat;
use app\modules\compare\models\CpCat;
use app\modules\compare\models\CpComb;
use app\modules\compare\models\CpCompList;
use app\modules\compare\models\CpEntity;
use app\modules\filter\models\Filter;
use app\modules\promopage\models\Promopage;
use app\modules\set\models\Sets;
use app\modules\textpage\models\Textpage;
use app\modules\blog\models\Blog;
use app\modules\brand\models\Brand;
use app\modules\action\models\Actions;
use app\modules\product\models\Product;
use app\modules\url\models\Url;
use Yii;

class Sitemap
{

    public  $textpages;
    public  $products;
    public  $prodcomm;
    public  $promopage;
    public  $catsproducts;
    public  $parent_cat;
    public  $catsblogs=[];
    public  $blogs=[];
    public  $filters;
    public  $brands=[];
    public  $actions;
    public  $brandscats=[];
    public  $sets;
    public  $geoPage= [];
    public  $linkMapPage= [];

    public  $compare_cards = [];
    public  $compare_cats = [];


    public $cat_prod;

    public function makeData(){

        $varentity = Yii::$app->getModule('varentity');
        $geo = Yii::$app->getModule('geo');
        $domain = Yii::$app->getModule('domain');
        $domain_id = $domain->getDomainId();

        $settings = Yii::$app->getModule('settings');
        $sel_domain = explode(',',$settings->getVar('sellout_domains')) ;

        $date_now = date('Y-m-d');

        //страницы сайта
        $this->textpages = Textpage::find()->
            select('textpage.id,textpage.sitemap,textpage.status,textpage.type_page,textpage.name')->
            joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
            andWhere(['sitemap' => Textpage::SM_OK ])->
            andWhere(['status'=> Textpage::ST_OK ])->
            andWhere(['<=','url.last_mod',$date_now]);



        if ( ! in_array($domain_id,$sel_domain)){
            $this->textpages->andWhere([ '!=' ,'url.action','sellout']);
        }

        $this->textpages =  $this->textpages->all();



        //продукты
        $this->products =  Product::find()->
            //select('product.id,product.status,product.name,product.cat_id')->
            joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
            andWhere(['status'=> Product::ST_OK ])->
            andWhere(['<=','url.last_mod',$date_now])->
            all();

    /*    $this->prodcomm =  Product::find()->
            innerJoin('url','product.id=url.identity and url.action = "comment" and url.type_url="product"')->
            andWhere(['status'=> Product::ST_OK ])->
        andWhere(['<=','url.last_mod',$date_now])->all();*/



        // категории товаров
        $this->catsproducts = Cat::find()->
            select('cat.id,cat.status,cat.parent_id,cat.name')->
            joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
            andWhere(['<=','url.last_mod',$date_now])->
            andWhere(['status'=> Cat::ST_OK ])->
            orderBy('ord')->
            all();


        if (! $geo->isGeo){
            //категории блога
            $allCats = BlogCat::find()->
            joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
            //   innerJoin('blog','blog.blog_cat_id = blog_cat.id and blog.status='.BlogCat::ST_OK)->
            andWhere(['blog_cat.status'=>BlogCat::ST_OK])->
            orderBy('blog_cat.ord')->all();
            /*
                    $idC = [];
                    foreach ($allCats as $cat_c){
                        $idC[] = $cat_c->parent_id;
                    }
                    $allPCat = BlogCat::find()->
                    andWhere(['blog_cat.status'=>BlogCat::ST_OK])->
                    andWhere(['blog_cat.id'=> $idC ])->
                    orderBy('blog_cat.ord')->all();
                    $allCats = array_merge($allPCat,$allCats);*/
            $this->catsblogs = $allCats;
        }

        if (! $geo->isGeo){
            //статьи блога
            $this->blogs = Blog::find()->joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
            andWhere(['status'=> Blog::ST_OK ])->
            andWhere(['<=','date_post',$date_now])->orderBy('ord')->all();
        }




        //фильтры
        $this->filters = Filter::find()->joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
        andWhere(['<=','url.last_mod',$date_now])->andWhere(['status'=> Filter::ST_OK ])
            ->andWhere([ '!=' ,'id_suit' ,0])->all();


        if (! $geo->isGeo){
            //бренды
            $this->brands = Brand::find()->joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
            andWhere(['<=','url.last_mod',$date_now])->andWhere(['status'=> Brand::ST_OK  ])
                ->all();
        }



            //promopage
            $this->promopage = Promopage::find()
                ->joinWith(['url_r'=>function($q){$q->domain();}],true,'INNER JOIN')
                ->
            andWhere(['<=','url.last_mod',$date_now])->andWhere(['status'=> Promopage::ST_OK  ])
                ->all();





        if (! $geo->isGeo){
            //товары производителей
            $this->brandscats = BrandCat::find()->joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
            andWhere(['<=','url.last_mod',$date_now])->andWhere(['status'=> BrandCat::ST_OK ])->all();

        }


        //акции
        $this->actions =  Actions::find()->joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
        andWhere(['<=','url.last_mod',$date_now])->andWhere(['status'=>  Actions::ST_OK ])
            ->andWhere(['OR',
                ['type_slider'=>Actions::T_A],
                ['type_slider'=>Actions::T_AG]
            ]);


        $this->actions= $this->actions->all();
        //наборы дня
        $this->sets =  Sets::find()->joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->
            andWhere(['show'=>  Sets::SHOW_Y ,'set_of_day' => Sets::D_YES])->
        andWhere(['<=','url.last_mod',$date_now])->all();

        $this->makeGeoData();

        $settings = Yii::$app->getModule('settings');
        $cp = (int) $settings->getVar('compare_sitemap');
        if ($cp){

            $all = CpComb::find()->joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN');//->groupBy('cp_comb.id');

            $this->compare_cards = $all->all();


            $this->compare_cats = CpCat::find()->joinWith(['url_rr'=>function($q){$q->domain();}],true,'INNER JOIN')->all();
        }

       /* echo '<pre>';
        var_dump($this->geoPage);exit;*/

    }

    private function makeGeoData(){

        if (  ! \Yii::$app->getModule('geo')->isRegionCenter() ) return;

        foreach (  \Yii::$app->getModule('geo')->getSitemap() as $url=> $lm){

            /*      $name = 'Доставка по области';
              if (strpos($url,'dostavka-v-') !== false){
                  $name = '';
              }*/
            $this->geoPage[$url] = [
                'url'=>$url,
                'last_mod'=>$lm,
                'text'=>''
            ];
        }

    }

    public function setCityTextSputnicList(){

    }



    public function makeCatProd(){

        $catProd = [];
        foreach ($this->catsproducts as $cat){

            if ( $cat->parent_id != 0){
                $catProd[$cat->id] = [
                    'cat'=>$cat,
                    'prod'=>[]
                ];
            } else {
                $this->parent_cat[$cat->id] = [
                    'cat'=>$cat,
                    'prod'=>[]
                ];
            }

        }

        foreach ( $this->products as $prod){
            if (! isset($catProd[  $prod->cat_id ])) continue;
            $catProd[  $prod->cat_id ]['prod'][] = $prod;
        }

       /* echo '<pre>';
        print_r($catProd);
        exit;*/
        $this->cat_prod = $catProd;
    }
}