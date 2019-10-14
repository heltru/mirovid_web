<?php
if ( isset($_SERVER['HTTP_HOST']) &&
strpos($_SERVER['HTTP_HOST'] ,Yii::$app->params['domain']) !== false
){

    $domain = Yii::$app->getModule('domain');
    $idd =  $domain->getDomainId();
    $scr = null;

    if ($idd){
        $scr = \app\modules\scripts\models\Scripts::findOne(['domain_id'=>$idd,
            'entity_type'=>\app\modules\scripts\models\Scripts::T_B]);

    } else {
        $scr = \app\modules\scripts\models\Scripts::findOne(['domain_id'=>0,
            'entity_type'=>\app\modules\scripts\models\Scripts::T_B]);
    }


    if ( $scr !== null){
        echo  $scr->content;
    }

    }

?>


