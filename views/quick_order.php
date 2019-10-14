<?php
$session = \Yii::$app->session;

if ( $session->get('first_url')){
    $url =  \Yii::$app->params['domain'] .
        $session->get('first_url')/*. '*' . $session->get('last_url') */ ;
} else {
    $url =  \Yii::$app->params['domain'] .
        $session->get('last_url');
}

$url = urldecode($url);


$domain = Yii::$app->getModule('domain');
$al = $domain->getName();
$sn = '';
if ($al !==null && ! $domain->isDefDomain()){
    $sn .= $al . '.';
}

$varentity = Yii::$app->getModule('varentity');

?>
<div id="quick-order" data-messages="<?
$url = 'php/message/message.json';
$message = array();
if(file_exists($url)){
    $json = file_get_contents($url);
    if(!empty( $json)){
        $message = json_decode($json,true);
    }
}

if(!empty($message) && !empty($message['error_message']) && $message['error_message']['active']==='Да'){

$mes = str_replace("\n", "", $message['error_message']['message']);

?> <?=htmlspecialchars_decode(trim($mes));}?>">

    <p class="modal-title">Оформление заказа</p>
    <form action="<?= \yii\helpers\Url::to(['/basket/default/fast-order']) ?>" method="post" class="spnForm">
        <input type="hidden" name="id" id="id_type" value=""/>
        <input type="hidden" name="id_s" id="id_s" value=""/>
        <input type="hidden" name="type" id="type" value=""/>
        <input type="hidden" id="cost" value="">
        <input type="hidden" name="quantity" value="1"/>

        <input type="hidden" class="url" name="url"/>
        <input type="hidden" class="gmt" name="gmt"/>
        <label for="name-order">
            <span>ФИО *</span>
            <input id="name-order" name="name" placeholder="" value="" type="text">
        </label>
        <label for="phone-order">
            <span>Телефон *</span>
            <input id="phone-order" data-mask="<?=$varentity->getLayoutVar('phone_mask')?>" name="phone" placeholder="" value="" type="text">
        </label>
        <label for="email">
            <span>Email</span>
            <input id="email" name="email" placeholder="" value="" type="email">
        </label>
        <label for="address">
            <span>Адрес</span>
            <input id="address" name="adress" placeholder="" value="" type="text">
        </label>
        <input id="fastForm" type="submit" value="Оформить заказ" class="button">
        <p>Наш менеджер позвонит Вам в течение 5 минут <br/> и уточнит детали доставки</p>
    </form>
</div>

<div id="call-order">
    <p class="modal-title">Заказ звонка</p>
    <form method="post" class="spnForm">
        <label for="name-call">
            <span>ФИО</span>
            <input id="name-call" name="name" placeholder="" value="" type="text">
        </label>
        <label for="phone-call">
            <span>Телефон</span>
            <input id="phone-call" data-mask="<?=$varentity->getLayoutVar('phone_mask')?>" name="phone" value="" placeholder="" type="text">
        </label>



        <input type="hidden" name="host" id="host" value="https://<?=$sn.Yii::$app->params['domain'] ?>"/>
        <input type="submit" id="submitCall" value="Заказать звонок" class="button">
        <p>Наш менеджер позвонит Вам в течение 5 минут</p>
    </form>
</div>

<div id="message">
    <p class="modal-title"></p>
   <p class="message-text">Наш менеджер позвонит Вам в течение 5 минут</p>
    <div class="button">Продолжить покупки</div>
    <div class="button-link" onclick="window.location.href='/basket'">Перейти в корзину</div>
</div>

<div id="success_fastform">
    <p class="modal-title">Спасибо за заказ!</p>
    <p class="modal-message">Наш менеджер позвонит Вам <br> в течение 5 минут</p>
    <div class="button">Закрыть окно</div>
</div>


<div id="to-cabinet">
    <div id="cabinet-log-in" class="active cab-tab">
        <p class="modal-title">Войти</p>
        <form method="post">
            <label for="email-log">
                <span>Email</span>
                <input id="email-log" name="email" placeholder="" type="email">
            </label>
            <label for="pass-log">
                <span>Пароль</span>
                <input id="pass-log" name="pass" placeholder="" type="password">
            </label>
            <input type="submit" value="Войти" class="button">
        </form>
        <div class="button-container">
            <p class="button-link">Зарегистрироваться</p>
        </div>
    </div>
    <div id="cabinet-reg" class="cab-tab">
        <p class="modal-title">Зарегистрироваться</p>
        <form method="post" class="spnForm">
            <label for="name-reg">
                <span>ФИО</span>
                <input id="name-reg" name="name" placeholder="" type="text">
            </label>
            <label for="phone-reg">
                <span>Телефон</span>
                <input id="phone-reg" data-mask="<?=$varentity->getLayoutVar('phone_mask')?>" name="phone" placeholder="" type="text">
            </label>
            <label for="email-reg">
                <span>Email</span>
                <input id="email-reg" name="email" placeholder="" type="email">
            </label>
            <label for="pass-reg">
                <span>Пароль</span>
                <input id="pass-reg" name="pass" placeholder="" type="password">
            </label>
            <label for="pass-repeat">
                <span>Повторите пароль</span>
                <input id="pass-repeat" name="pass" placeholder="" type="password">
            </label>
            <input type="submit" value="Зарегистрироваться" class="button">
        </form>
        <div class="button-container">
            <p class="button-link">Уже зарегистрирован</p>
        </div>
    </div>
</div>