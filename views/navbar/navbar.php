<nav class="white" role="navigation" xmlns="http://www.w3.org/1999/html">
    <div class="nav-wrapper container">
        <a id="logo-container" href="/"  style="color:#ba3238" class="brand-logo red-text text-accent-4 item_logo_text">
            <span class="red-text text-accent-4">☀</span> MIROVID</a>
            <ul class="right hide-on-med-and-down">

            <li><a class="item_phone" style="font-size: 1.5em;color: black" href="<?= Yii::$app->user->isGuest ? '/signup' : '/admin' ?>">Личный кабинет</a></li>
            <?php if ( Yii::$app->user->isGuest ){ ?>
                <li><a class="item_phone" style="font-size: 1.5em;color: black" href="/signup">Регистрация</a></li>
            <?php } ?>
            <li><a class="item_phone" style="font-size: 1.5em;color: black" href="tel:+79513491487">8(951) 349-14-87</a></li>

        </ul>


    </div>
</nav>