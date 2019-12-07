<?php

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/images/round-account-button-with-user-inside.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->getModule('user')->getUser()->username ?> </p>

                <a href="#"><i class="fa fa-circle text-success"></i>
                    <?= Yii::$app->formatter->asDecimal(Yii::$app->getModule('balance')->getBalance(),0)  ?>
                </a>
            </div>
        </div>



        <?php
         $all_items = [
             [
                 'label' => 'Моя Реклама',
                 'icon' => ' fa-sitemap',

                 'url' =>  ['/admin/reklamir/default/index'],
             ],
             [
                 'label' => 'Баланс',
                 'icon' => ' fa-rub',
                 'url' =>['/admin/pay/default/pay-info'],
                 //'url' =>  ['/admin/block/index'],
                 'active' => $this->context->route == 'admin/pay/default/pay-info'

             ],

             [
                 'label' => 'Карта показов',
                 'icon' => ' fa-map',
                 'url' =>['/admin/show/default/map-register'],
                 //'url' =>  ['/admin/block/index'],
                 'active' => $this->context->route == 'admin/show/default/map-register'

             ],
             [
                 'label' => ' Таблица показов',
                 'icon' => ' fa-table',
                 'url' =>['/admin/show/show/index'],
                 //'url' =>  ['/admin/block/index'],
                 'active' => $this->context->route == 'admin/show/default/index'

             ],
             [
                 'label' => ' Уведомления',
                 'icon' => ' fa-bell-o',
                 'url' =>['/admin/bid/bid-log/index'],
                 'template'=>'<a href="{url}">{icon} {label}<span class="pull-right-container"><small class="label pull-right bg-yellow">'.$bid_logs_total.'</small></span></a>',
                 'active' => $this->context->route == 'admin/bid/bid-log/index'

             ],


             [
                 'label' => 'Pixel-editor',
                 'icon' => ' fa-gear',
                 'options' => ['id' => 'pixeleditor'],
                 'url' =>['/admin/show/default/pixel-editor'],
                 'active' => $this->context->route == 'admin/show/default/pixel-editor'

             ],
         ];
         $admin_items = [
             ['label' => 'Admin', 'options' => ['class' => 'header']],
             [
                 'label' => 'Вся Реклама',
                 'url' =>  ['/admin/reklamir/default/common'],
             ],

             [
                 'label' => 'Местонахождение',
                 'url' =>  ['/admin/reklamir/place/index'],
             ],
             [
                 'label' => 'Устройства',
                 'url' =>  ['/admin/reklamir/thing/index'],
             ],
             [
                 'label' => 'Категории устройств',
                 'url' =>  ['/admin/reklamir/thing-cat/index'],
             ],

             [
                 'label' => 'Users',
                 'url' =>  ['/admin/user/default/index'],
             ],
             [
                 'label' => 'Аукцион',
                 'url' =>  ['/admin/bid/default/index'],
             ],
             [
                 'label' => 'Files',
                 'icon' => ' fa-film',
                 'url' =>  ['/admin/file/default/index'],
             ],
         ];

         if ( \app\modules\helper\models\Helper::getIsAdmin(Yii::$app->user->id)){
             $all_items = array_merge($all_items,$admin_items);
         }
        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $all_items,
            ]
        ) ?>

    </section>

</aside>
