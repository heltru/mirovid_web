<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/* @var $app app\modules\app\app\AppNovaVidAdminClient */
//$app =  \app\modules\app\app\AppNovaVidAdminClient::Instance();


$myRkItems[] =  ['label'=>'Новая РК','icon'=>'calendar-plus-o','url'=>Url::to(['/admin/block/default/create']),
    'active' =>$this->context->route == 'admin/block/default/create'];
/*
$block_id = \app\modules\block\models\Block::find()->one();
Yii::$app->params['active_block_id_create_mem_url'] = '';
if ($block_id !== null){
    Yii::$app->params['active_block_id_create_mem_url'] = ['/admin/block/default/view','id'=>$block_id->id];

}
*/
/*
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
*/

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
                <p> </p>

                <a href="#"><i class="fa fa-circle text-success"></i>  </a>
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

                ],
            ]
        ) ?>

    </section>

</aside>
