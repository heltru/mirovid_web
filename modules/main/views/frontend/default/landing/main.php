<div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
        <div class="container">

            <h1 class=" header center    item_h1">Опубликуй пост на экране</h1>
            <h3 class="center">ТЦ лифты, автобусы, общепит</h3>
            <div class="row ">

                <div class="col s12" >
                    <div class="row card-content" >
                        <form  >
                            <div class="row ">

                                <div class="col s12 center">
                                    <a onclick="window.dataLayer.push({'event': 'load_reclame'});"
                                            href="<?= Yii::$app->user->isGuest ? '/main/default/login' : '/admin' ?>"
                                            style="background-color: rgb(0, 119, 255);
                                            color:#ffffff;font-weight: bold"
                                       class="offer_btn waves-effect waves-light btn-large">
                                        <?php if ( Yii::$app->user->isGuest ){ ?>
                                       Войти через ВК
                                        <?php } else { ?>
                                            Войти
                                       <?php  } ?>
                                    </a>
                                </div>


                            </div>

                        </form>
                    </div>

                </div>

            </div>

            <br><br>

        </div>
    </div>
    <div class="parallax"><img src="/themes/one/image/bg/2.jpg" alt="Unsplashed background img 1"></div>
</div>

