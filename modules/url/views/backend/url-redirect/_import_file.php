<?php
/**
 * Created by PhpStorm.
 * User: o.trushkov
 * Date: 01.02.18
 * Time: 11:52
 */
use yii\helpers\Html;
$this->title = 'Импорт';
$this->params['breadcrumbs'][] = ['label' => 'Авто.редирект', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
echo  Html::beginForm('import','post',
     ['enctype' => 'multipart/form-data']
    );
?>

<div class="col-xs-12">
    <?php
    echo Html::fileInput('filedata',null);
    ?>
</div>



<div class="col-xs-12">
    <br>
    <?php
    echo Html::submitButton('Импорт',['class'=>'btn btn-default'])
    ?>
</div>

<?php echo  Html::endForm();?>

