<?php
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$host = Yii::$app->request->hostInfo  . DIRECTORY_SEPARATOR;

$settings = \Yii::$app->getModule('settings');
$domain = \Yii::$app->getModule('domain');
$geo = \Yii::$app->getModule('geo');

$c = 0;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">


    <?php

    foreach ($sitemap->compare_cats as $compare_cat) {
        if (  is_object($compare_cat->url_rr)) { ?>
            <url>
                <loc>
                    <?php
                    $url = $compare_cat->url_rr;

                    if ($geo->isGeo){
                        $domain->rewriteEnt('url',$url);
                    }

                    if ($url->rawHref){
                        echo $host . $url->rawHref; $c++;
                    } else {
                        echo    Yii::$app->request->hostInfo;  $c++;
                    }
                    ?>
                </loc>


                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    weekly
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>

            <?php
        }
    }


foreach ($sitemap->compare_cards as $compare_card) {
    if (  is_object($compare_card->url_rr)) { ?>
        <url>
            <loc>
                <?php
                $url = $compare_card->url_rr;

                if ($geo->isGeo){
                    $domain->rewriteEnt('url',$url);
                }

                if ($url->rawHref){
                    echo $host . $url->rawHref; $c++;
                } else {
                    echo    Yii::$app->request->hostInfo;  $c++;
                }
                ?>
            </loc>


            <lastmod>
                <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
            </lastmod>

            <changefreq>
                weekly
            </changefreq>

            <priority>
                0.5
            </priority>

        </url>

        <?php
    }
}





    foreach ($sitemap->textpages as $textpage) {

         if (  is_object($textpage->url_rr)) {

             ?>
             <url>
                 <loc>
                     <?php
                     $url = $textpage->url_rr;

                     if ($geo->isGeo){
                         $domain->rewriteEnt('url',$url);
                     }

                     if ($url->rawHref){
                         echo $host . $url->rawHref; $c++;
                     } else {
                        echo    Yii::$app->request->hostInfo;  $c++;
                     }
                ?>
                 </loc>


                 <lastmod>
                     <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                 </lastmod>

                 <changefreq>
                     weekly
                 </changefreq>

                 <priority>
                     0.5
                 </priority>

             </url>
         <?php } ?>
    <?php } ?>

    <?php foreach ($sitemap->geoPage as $page) {  $c++; ?>
        <url>
            <loc><?=$page['url']?></loc>
            <lastmod>
                <?=date('Y-m-d\TH:i:sP',strtotime($page['last_mod']))?>
            </lastmod>
            <changefreq>
                weekly
            </changefreq>
            <priority>
                0.5
            </priority>
        </url>
        <?php } ?>

    <?php foreach ($sitemap->products as $product) {

        if (  is_object($product->url_rr)) {

            ?>
            <url>
                <loc><?php
                    $url = $product->url_rr;
                    if ($geo->isGeo){
                        $domain->rewriteEnt('url',$url);
                    }
                    echo $host . $url->rawHref;  $c++;  ?>
                </loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s',$url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    daily
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>
        <?php }
    if (  is_object($product->urlComment_r)) {  $c++; ?>
        <url>
            <loc><?php
                $url = $product->urlComment_rr;
                if ($geo->isGeo){
                    $domain->rewriteEnt('url',$url);
                }
                echo $host . $url->rawHref  ?>
            </loc>


            <lastmod>
                <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
            </lastmod>

            <changefreq>
                daily
            </changefreq>

            <priority>
                0.5
            </priority>

        </url>
    <?php }
        ?>


    <?php } ?>

    <?php
    foreach ( $sitemap->cat_prod as $item ){
        if (count($item['prod'])) {
            $url = $item['cat']->url_rr;
            if ($geo->isGeo){
                $domain->rewriteEnt('url',$url);
            }
    ?>
            <url>
                <loc><?=  $host . $url->rawHref;  $c++; ?></loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s',
                        $url->last_mod
                    )->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    weekly
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>
        <?php } ?>
    <?php } ?>

    <?php
    foreach ( $sitemap->parent_cat as $item ){
        $url = $item['cat']->url_rr;
        if ($geo->isGeo){
            $domain->rewriteEnt('url',$url);
        }
          ?>
            <url>
                <loc><?=  $host . $url->rawHref;  $c++; ?></loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s',$url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    weekly
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>

    <?php } ?>


    <?php foreach ($sitemap->catsblogs as $catsblog) {
        if (  is_object($catsblog->url_r)) {
            $url = $catsblog->url_rr;
            if ($geo->isGeo){
                $domain->rewriteEnt('url',$url);
            }
            ?>
            <url>
                <loc><?=  $host . $url->rawHref;  $c++; ?></loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    weekly
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>
        <?php } ?>
    <?php } ?>

    <?php foreach ($sitemap->blogs as $blog) {

        if (  is_object($blog->url_r)) {
            $url = $blog->url_rr;
            if ($geo->isGeo){
                $domain->rewriteEnt('url',$url);
            }
            ?>
            <url>
                <loc><?=  $host . $url->rawHref;  $c++; ?></loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    weekly
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>
        <?php } ?>
    <?php } ?>

    <?php

    foreach ($sitemap->promopage as $promopage) {

        if (  is_object($promopage->url_r)) {
            $url = $promopage->url_r;

            if ($geo->isGeo){
                $domain->rewriteEnt('url',$url);
            }
            ?>
            <url>
                <loc><?=  $host . $url->rawHref;  $c++; ?></loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    weekly
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>
        <?php } ?>
    <?php } ?>

    <?php foreach ($sitemap->filters as $filter) {

        if (  is_object($filter->url_r)) {
            $url = $filter->url_rr;
            if ($geo->isGeo){
                $domain->rewriteEnt('url',$url);
            }
            ?>
            <url>
                <loc><?=  $host . $url->rawHref ;  $c++;?></loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    weekly
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>
        <?php } ?>
    <?php } ?>

    <?php foreach ($sitemap->actions as $action) {

        if (  is_object($action->url_r)) {
            $url = $action->url_rr;
            if ($geo->isGeo){
                $domain->rewriteEnt('url',$url);
            }
            ?>
            <url>
                <loc><?=  $host . $url->rawHref;  $c++; ?></loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    daily
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>
        <?php } ?>
    <?php } ?>

    <?php foreach ($sitemap->brands as $brand) {

        if (  is_object($brand->url_r)) {
            $url = $brand->url_rr;
            if ($geo->isGeo){
                $domain->rewriteEnt('url',$url);
            }
            ?>
            <url>
                <loc><?=  $host . $url->rawHref;  $c++; ?></loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s',$url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    weekly
                </changefreq>

                <priority>
                    0.5
                </priority>

            </url>
        <?php } ?>
    <?php } ?>

    <?php foreach ($sitemap->brandscats as $brandscat) {

        if (  is_object($brandscat->url_r)) {
            $url = $brandscat->url_rr;
            if ($geo->isGeo){
                $domain->rewriteEnt('url',$url);
            }
            ?>
            <url>
                <loc><?=  $host . $url->rawHref;  $c++; ?></loc>

                <lastmod>
                    <?=  \DateTime::createFromFormat('Y-m-d H:i:s', $url->last_mod)->format('Y-m-d\TH:i:sP') ?>
                </lastmod>

                <changefreq>
                    weekly
                </changefreq>

                <priority>
                    0.5
                </priority>
            </url>
        <?php } ?>
    <?php } ?>

</urlset>

<?php
$settings->editVar('countLinksSitemap',$c);