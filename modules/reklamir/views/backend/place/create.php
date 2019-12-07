<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\Place */

$this->title = 'Добавить место';
$this->params['breadcrumbs'][] = ['label' => 'Места', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
