<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\show\models\ShowRegister */

$this->title = 'Create Show Register';
$this->params['breadcrumbs'][] = ['label' => 'Show Registers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="show-register-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
