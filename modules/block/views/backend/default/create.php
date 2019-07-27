<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\block\models\Block */

$this->title = 'Добавить РК';
$this->params['breadcrumbs'][] = ['label' => 'РК', ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
