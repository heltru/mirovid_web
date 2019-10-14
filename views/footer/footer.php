<?php
use app\modules\varentity\models\Varentity;
use app\modules\varentity\models\Layout;
$page =  ( isset($this->params['curr_page'])) ? $this->params['curr_page'] : '' ;
$geo = Yii::$app->getModule('geo');
$urlDeliv = ( ! $geo->isGeo || ! $geo->isRegionCenter() ) ? '/dostavka' : $geo->getDelivUrl();

$varentity = Yii::$app->getModule('varentity');
$layout = new Layout();
$settings = \Yii::$app->getModule('settings');
$phone =  (int)$settings->getVar('tel_phone');
$phone_f = $phone;

if ($phone !== null){
    $phone_f = \app\modules\helper\models\Helper::formatPhone($phone);
}
$url = Yii::$app->request->pathInfo;
?>
<div class="content-container">

    <div id="footer-columns">
        <div id="links-columns">
            <div class="footer-links">
                <ul>
                    <li class="parent-item"><span>О компании</span>
                        <ul>
                            <li>
                                <?= ( $page == 'about' ) ? '<span >О нас</span>' : '<a href="/o-nas">О нас</a>'?>
                            </li>

                            <li>
                                <?= ( $page == 'contact' ) ? '<span >Контакты</span>' : '<a href="/kontakty">Контакты</a>'?>
                            </li>
                            <li>
                                <?= ( $url == 'rekvizity-kompanii' ) ? '<span >Реквизиты компании</span>' : '<a href="/rekvizity-kompanii">Реквизиты компании</a>'?>
                            </li>
                            <li>
                                <?= ( $page == 'orderinfo' ) ? '<span >Правовая информация</span>' : '<a href="/pravovaya-informaciya">Правовая информация</a>'?>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
           <?= $this->render('_foot_cat') ?>
            <div class="footer-links">
                <ul>
                    <li>
                        <?= ( $page == 'delivery' ) ?
                            '<span id="footer-delivery" >Доставка</span>' : '<a id="footer-delivery" href="'.$urlDeliv.'">Доставка</a>'?>
                    </li>
                    <li>
                        <?= ( $page == 'payment' ) ? '<span id="footer-payment">Оплата</span>' : '<a id="footer-payment" href="/oplata">Оплата</a>'?>
                    </li>
                    <?php if ( ! $geo->isGeo  ) { ?>
                    <li>
                        <?= ( $page ==   \app\modules\blog\BlogModule::$url  ) ? '<span >'.\app\modules\blog\BlogModule::$bctitle.'</span>' : '<a href="/'.\app\modules\blog\BlogModule::$url.'">'.\app\modules\blog\BlogModule::$bctitle.'</a>'?>
                    </li>
                    <?php } ?>
                    <li>
                        <?= ( $page == 'actions' ) ? '<span >Акции</span>' : '<a href="/actions">Акции</a>'?>
                    </li>
                    <?php if (!  $geo->isGeo) {  ?>
                    <li>
                        <?= ( $page == 'compare_cat' ) ? '<span id="footer-compare">Сравнение самогонных аппаратов</span>' : '<a id="footer-compare" href="/sravnenie-samogonnyh-apparatov">Сравнение самогонных аппаратов</a>'?>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="footer-links">
                <ul>
                    <?php if (!  $geo->isGeo) {  ?>
                    <li>
                        <?= ( $page == 'allbrands' ) ? '<span >Бренды</span>' : '<a href="/vse-brendy">Бренды</a>'?>
                    </li>
                    <?php } ?>
                    <li>
                        <?= ( $page == 'faq' ) ? '<span >FAQ</span>' : '<a href="/faq">FAQ</a>'?>
                    </li>
                    <li>
                        <?= ( $page == 'howorder' ) ? '<span >Как заказать</span>' : '<a href="/kak-zakazat">Как заказать</a>'?>
                    </li>
                </ul>
            </div>
        </div>
        <div id="company-info">
            <p class="logo" <?=($page != 'main') ?
                "onclick=\"window.location.href='/'\"" : '' ?>  >
                <img src="<?= Yii::$app->params['logofooter'] ?>" alt="" title=""/>

                <!--<img src="/images/theme/logo-white.png" alt="" title=""/>-->
            </p>
            <?php if (!  $geo->isGeo) {  ?>
                <div id="footer-contacts">
                    <p id="footer-address">Cеть фирменных магазинов</p>
                    <a id="footer-email" href="mailto:sale@gradushaus.ru">sale@gradushaus.ru</a>
                </div>
            <?php } else {

                $varentity->rewriteEnt($layout,Varentity::T_L);
                echo $layout->footer_contact_social;

            }  ?>
        </div>
    </div>

</div><!-- end of content-container -->
<div id="copyright">
    <div class="content-container">
        <p>© 2000 - <span id="footer-year"><?= date('Y') ?></span> <?=Yii::$app->params['domain']?></p>
    </div><!-- end of content-container -->
</div>