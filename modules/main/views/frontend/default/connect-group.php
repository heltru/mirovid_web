<?php
foreach ($groups as $group){
    echo \yii\helpers\Html::a($group['name'],$group['auth_link']);
    echo '</br>';
}

