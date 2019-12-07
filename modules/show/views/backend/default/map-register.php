<?php
use yii\helpers\Html;

use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\modules\reklamir\models\Reklamir;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Зарегестрированные показы';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    #showregistersearch-date_from-kvdate {
        padding-right: 15px;
        padding-left: 15px;
        margin-bottom: 15px;
    }
    #balloon_circle{
        width: 50px;
        height: 50px;
        -webkit-border-radius: 25px;
        -moz-border-radius: 25px;
        border-radius: 25px;
        background: red;
    }
</style>
<div class="badge-index">


    <div class="row">

        <div class="col-xs-12">
            <div class="block-search">
                <?php $form = ActiveForm::begin([
                    'action' => ['map-register'],
                    'method' => 'get',
                ]); ?>

                <div class="col-xs-12">
                    <?= $form->field($searchModel,'reklamir_id')->dropDownList(
                            ArrayHelper::map(Reklamir::find()
                                ->where(['account_id'=>Yii::$app->getModule('account')->getAccount()->id])->all(),
                                'id','name' ),['prompt'=>'---']) ?>
                </div>

                <?php
                echo  DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd'],
                    'options'=>['autocomplete'=>'off']
                ]);
                ?>

                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <?= Html::submitButton('Фильтр', ['class' => 'btn btn-primary']) ?>
                        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-default','onclick'=>"window.location.href = '/admin/show/default/map-register'"]) ?>

                    </div>
                </div>
                <div class="col-xs-12  col-md-6 ">
                    <div class="row">

                        <div class="pull-right">
                            <?= $form->field($searchModel,'count_items',[ 'template' => '{input}{label}{error}{hint}',
                                'options' => ['class' => 'form-group form-inline'],])->dropDownList( [50=>50,100=>100,500=>500,1000=>1000,5000=>5000],[
                                'style' => 'margin-right: 10px;'] ) ?>
                        </div>

                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>

        </div>


        <div class="col-xs-12">

            <?php
            echo $this->render('_map_register',['points'=>$points,'dataProvider' => $dataProvider,]);
            ?>
        </div>

        <div class="col-xs-12">

            <?php echo \yii\widgets\LinkPager::widget([
                'pagination' => $dataProvider->pagination,
            ]) ?>
            <p style="text-align: right">Итого показов: <?=$dataProvider->totalCount?></p>
        </div>


    </div>
</div>





</div>
