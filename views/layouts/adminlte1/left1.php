<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/* @var $app app\modules\app\app\AppNovaVidAdminClient */
$app =  \app\modules\app\app\AppNovaVidAdminClient::Instance();


$myRkItems[] =  ['label'=>'Новая РК','icon'=>'calendar-plus-o','url'=>Url::to(['/admin/block/default/create']),
    'active' =>$this->context->route == 'admin/block/default/create'];
$block_id = \app\modules\block\models\Block::find()->one();
Yii::$app->params['active_block_id_create_mem_url'] = '';
if ($block_id !== null){
    Yii::$app->params['active_block_id_create_mem_url'] = ['/admin/block/default/view','id'=>$block_id->id];

}


foreach ($app->getMyRkList() as $item){
    //  ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
    $sitems = [];
    foreach ( $item->msg_r as $subitem){
        $sitems[] = [
            'label'=> 'ID ' . $subitem->id,
            'icon'=>'circle-thin',
            'active' =>( ($this->context->route == 'admin/block/default/msg-update' )
                &&   $subitem->id == $this->params['curr_msg_id'] ),

            'url'=>Url::to(['/admin/block/default/msg-update','id'=>$subitem->id])
            ,
        ];
    }

    $myRkItems[] = [
            'label'=>$item->name,
            'icon'=>'circle-thin',
            'active' =>


                ( ($this->context->route == 'admin/block/default/view' || $this->context->route == 'admin/block/default/msg-update')
                           &&   $item->id == $this->params['curr_block_id'] )

        ,
            'items' => $sitems,
        'url'=>Url::to(['/admin/block/default/view','id'=>$item->id])
        ,
         ];


}


$app_b = new \app\modules\app\app\AppBalance();
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=$app->getLogoName()?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> <?=$app_b->getBalanceByCurrAccount()?></a>
            </div>
        </div>

        <!-- search form -->
      <!--  <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form> -->
        <!-- /.search form -->
        <?php


        ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    [
                        'label' => 'Список показов',
                        'icon' => ' fa-film',
                        'url' =>  ['/admin/block/block-utils/product-of-pop'],

                    ],
                    [
                        'label' => 'Мои компании',
                        'icon' => ' fa-cubes',
                        'url' =>'#',
                        //'url' =>  ['/admin/block/index'],
                        'active' =>
                            $this->context->route == 'admin/block/default/index' ||
                            $this->context->route == 'admin/block/default/create' ||
                        $this->context->route == 'admin/block/default/msg-update' ||
                            $this->context->route == 'admin/block/default/view'
                        ,

                        'items' =>
                            $myRkItems,
                           /*    'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                   ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],

                             ],*/

                    ],
                    [
                        'label' => 'Карта показов',
                        'icon' => ' fa-map',
                        'url' =>['/admin/show/default/map-register'],
                        //'url' =>  ['/admin/block/index'],
                        'active' => $this->context->route == 'admin/show/default/map-register'

                    ],

                    [
                        'label' => 'Баланс',
                        'icon' => ' fa-rub',
                        'url' =>['/admin/pay/default/pay-info'],
                        //'url' =>  ['/admin/block/index'],
                        'active' => $this->context->route == 'admin/pay/default/pay-info'

                    ],

                    [
                        'label' => 'Добавить Мем',
                        'icon' => ' fa-bomb',
                        'url' =>  Yii::$app->params['active_block_id_create_mem_url']


                    ],

                    ['label' => 'ADMIN', 'options' => ['class' => 'header']],
                    ['label' => 'Car', 'url' => ['/admin/car/index'] , ],
                //    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    ['label' => 'Accounts',   'url' => ['/admin/account/index']],
                    ['label' => 'User', 'url' => ['/admin/user/default/index'] ],
                    ['label' => 'Main Show', 'url' => ['/admin/show/default/while-lenta'] ],
                   // ['label' => 'Test', 'url' => ['/admin/test/default/index'] ],



         /*           ['label' => 'Группы сообщений', 'icon' => 'file-code-o',
                        'url' => ['/admin/block/index']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],*/
                ],
            ]
        ) ?>

    </section>

</aside>
