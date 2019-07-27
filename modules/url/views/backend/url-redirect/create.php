<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\url\models\UrlRedirect */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Авто.редирект', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="url-redirect-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
