<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\Reklamir */

$this->title = 'Добавить рекламу';
$this->params['breadcrumbs'][] = ['label' => 'Реклама', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reklamir-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
