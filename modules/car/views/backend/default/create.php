<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\car\models\Car */

$this->title = 'Create Car';
$this->params['breadcrumbs'][] = ['label' => 'Cars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
