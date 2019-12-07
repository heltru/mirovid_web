<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\ThingCat */

$this->title = 'Create Thing Cat';
$this->params['breadcrumbs'][] = ['label' => 'Thing Cats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thing-cat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
