<?php

if (

    isset($_SERVER['HTTP_HOST']) &&
    strpos($_SERVER['HTTP_HOST'] ,Yii::$app->params['domain']) !== false
   ){

    $domain = Yii::$app->getModule('domain');
    $idd =  $domain->getDomainId();
    $scr = null;

    if ( $idd !== null){
        $scr = \app\modules\scripts\models\Scripts::findOne(['domain_id'=>$idd,
            'entity_type'=>\app\modules\scripts\models\Scripts::T_H]);

    } else {

        $scr = \app\modules\scripts\models\Scripts::findOne(['domain_id'=>0,
            'entity_type'=>\app\modules\scripts\models\Scripts::T_H]);

    }


    if ( $scr !== null){

        $pos1 = strpos($scr->content,'<script>')+8;
        $pos2 = strpos($scr->content,'</script>');

        if ($pos1 !== false && $pos2 !== false){



            $scr->content = substr($scr->content, 0, $pos1) . ' setTimeout(function(){ ' . PHP_EOL . substr($scr->content, $pos1);

            $pos2 = strpos($scr->content,'</script>');
            $scr->content = substr($scr->content, 0, $pos2) . ' },4000); ' . PHP_EOL . PHP_EOL . substr($scr->content, $pos2);



        }

        echo  $scr->content;
    }

  }