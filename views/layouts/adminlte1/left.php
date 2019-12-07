<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->getModule('user')->getUser()->username ?> </p>

                <a href="#"><i class="fa fa-circle text-success"></i>
                    <?= Yii::$app->formatter->asDecimal(Yii::$app->getModule('balance')->getBalance(),0)  ?>
                </a>
            </div>
        </div>

        <?php


        ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    [
                        'label' => 'Files',
                        'icon' => ' fa-film',
                        'url' =>  ['/admin/file/default/index'],
                    ],
                    [
                        'label' => 'Моя Реклама',
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
                        'label' => 'Карта показов',
                        'icon' => ' fa-map',
                        'url' =>['/admin/show/default/map-register'],
                        //'url' =>  ['/admin/block/index'],
                        'active' => $this->context->route == 'admin/show/default/map-register'

                    ],
                    [
                        'label' => 'Pixel-editor',
                        'icon' => ' fa-gear',
                        'options' => ['id' => 'pixeleditor'],
                        'url' =>['/admin/show/default/pixel-editor'],
                        'active' => $this->context->route == 'admin/show/default/pixel-editor'

                    ],
                    [
                        'label' => 'Вся Реклама',
                        'url' =>  ['/admin/reklamir/default/common'],
                    ],

                ],
            ]
        ) ?>

    </section>

</aside>
<script>
    $(document).ready(function (){
        $('#pixeleditor').find('a').attr('target','_blank');

    });
</script>