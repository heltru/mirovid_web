<?php
$page =  ( isset($this->params['curr_page'])) ? $this->params['curr_page'] : '' ;
$geo = Yii::$app->getModule('geo');
$urlDeliv = (! $geo->isGeo) ? '/dostavka' : $geo->getDelivUrl();
?>
<div class="content-container">
    <div id="menu-mobile">&nbsp;</div>
    <ul id="main-items">
        <div id="main-items-inner">
            <?php  echo $this->render('_catitem'); ?>

            <?php if ( ! $geo->isGeo  ) { ?>
                <li>
                    <?= ( $page == \app\modules\blog\BlogModule::$url ) ? '<span >Рецепты</span>' :
                        '<a href="/'.\app\modules\blog\BlogModule::$url.'">Рецепты</a>'?>
                </li>
            <?php } ?>

            <li>
                <?= ( $page == 'contact' ) ? '<span >Контакты</span>' : '<a href="/kontakty">Контакты</a>'?>
            </li>
        </div>
    </ul>
</div> <!-- end of content-container -->