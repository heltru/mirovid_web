<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">☀</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

<?php

?>
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning"><?=$bid_logs_total?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Перекупленные ставки</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php


                                 foreach ($bid_logs as $item){ ?>
                                     <li>
                                         <a style="white-space: normal;" href="<?=\yii\helpers\Url::to(['/admin/reklamir/default/update','id'=>$item->reklamir_id]) ?>">

                                             <i class="fa fa-warning text-yellow"></i>
                                             <?='Реклама: ' . $item->reklamir_r->name .': '. $item->msg ?>

                                             Вашу цену на <i class="fa fa-calendar" style="width: 15px;"></i> <?= date('d.m.Y',$item->time_id)?>
                                             <i class="fa fa-clock-o" style="width: 9px;"></i> <?= date('H:i',$item->time_id) . ' перекупили.'?>



                                         </a>
                                     </li>
                                 <?php }
                                ?>

                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>


                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/images/user-profile.png" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->getModule('user')->getUser()->username ?> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/images/user-profile.png" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?= Yii::$app->getModule('user')->getUser()->username ?>

                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/admin/user/profile" class="btn btn-default btn-flat">Профиль</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Выйти',
                                    ['/user/default/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <?php
                if ( \app\modules\helper\models\Helper::getIsAdmin(Yii::$app->user->id)){ ?>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                <?php }
                ?>

            </ul>
        </div>
    </nav>
</header>
