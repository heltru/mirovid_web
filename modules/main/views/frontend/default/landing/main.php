<?php echo $this->render('//navbar/navbar') ?>
<div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
        <div class="container">

            <h1 class=" header center    item_h1">Размести рекламу за 15 минут</h1>
            <h3 class="center">планшеты в такси, видео на стекле авто,  led билборд</h3>
            <div class="row ">
                <div class="col s12 m7 ">

                    <div class="cont_utm">
                        <div class=" cont_utm_col_icon">
                            <i class=" utm_icon small material-icons">track_changes</i>
                        </div>
                        <div  class="cont_utm_col_text" >
                            <h5 class="header  light item_utp ">Охват</h5>
                            <p class="item_utp_subtitle">19 машин / 28 800 мин / 100 человек / в день</p>
                        </div>
                    </div>

                    <div class="cont_utm">
                        <div class=" cont_utm_col_icon">
                            <i class=" utm_icon small material-icons">attach_money</i>
                        </div>
                        <div class="cont_utm_col_text">
                            <h5 class="header  light item_utp ">Стоимость</h5>
                            <p class="item_utp_subtitle">СРМ = 100 - 200 руб. <br>1 клик MT= 20 руб.<br>1 клик VK = 7 - 13 руб.<br>
                                1 минута mirovid = 1 руб.
                            </p>
                        </div>

                    </div>

                    <div class="cont_utm">
                        <div class=" cont_utm_col_icon">
                            <i class=" utm_icon small material-icons">assignment_turned_in</i>
                        </div>
                        <div class="cont_utm_col_text">
                            <h5 class="header  light item_utp ">Доступность</h5>
                            <p class="item_utp_subtitle">Личный кабинет <br> Статистика <br> Выбор места показа </p>
                        </div>
                    </div>



                    <div class="cont_utm">
                        <div class=" cont_utm_col_icon">
                            <i class=" utm_icon small material-icons">star</i>
                        </div>

                        <div class="cont_utm_col_text">
                            <h5 class="header  light item_utp ">Новый канал</h5>
                            <p class="item_utp_subtitle">Внимание клиента <br> Платёжеспособная аудитория
                                <br>  15 минут средняя поездка</p>
                        </div>
                    </div>






                </div>

                <div class="col s12 m5 card  push-m2 offer_cont" >
                    <div class="row card-content offer_wrap" >
                        <form  >
                            <div class="row ">
                                <div class="input-field col s12 ">
                                    <p class="offer_promo_text">
                                        Зарегистрируйся сейчас и получи на счет <span style="color: red;">300</span> руб. для своей рекламы
                                    </p>
                                </div>

                                <div class="col s12 center">
                                    <a onclick="window.dataLayer.push({'event': 'load_reclame'});"
                                            href="<?= Yii::$app->user->isGuest ? '/signup' : '/admin' ?>"
                                            style="background-color: #ffd400;color: black;font-weight: bold" class="offer_btn waves-effect waves-light btn-large    ">
                                       Попробовать</a>
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



<div class="container">
    <div class="section">

        <div class="row">
            <div class="col s12 center">
                <h3><i class="mdi-content-send brown-text"></i></h3>
                <h3>Как это работает</h3>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m6">
                <div class="material-placeholder">
                    <img style="width: 99%;" class="img-responsive materialboxed" src="/images/image-473158-galleryV9-nzot-473158.jpg"></div>
            </div>

            <div class="col s12 m6 ">
                <div class="flow-text">
                    <p>19 машин - 24 часа</p>

                    <ul>
                        <li>Что - вы получаете:</li>
                        <li>Внимание каждого пассажира</li>
                        <li>Платежеспособного клиента</li>
                        <li>Средняя поездка 15 минут</li>
                    </ul>
                </div>

            </div>

        </div>

        <div class="row" style="margin-top: 6em;">


            <div class="col s12 m6 ">
                <div class="flow-text">
                    <p>Видео на стекле</p>
                    <p>LED панель на транспортном средстве</p>
                    <ul>
                        <li><strong>Утро</strong> - распродажы  в торговых сетях, детских школ искусств, фитнес центров, парикмахерских, отделов торговых центров </li>
                        <li><strong>День</strong> - инфобизнес, тренинги, банки, кредитные организаций</li>
                        <li><strong>Вечер, выходные</strong> - кальянне, бары, ночные клубы, информирование об акциях в заведениях, горячих скидках</li>
                        <li><strong>Выходные</strong> - Распродажи в ТЦ, бутики, стройматериалы, базы отдыха, бани ,сауны </li>
                    </ul>
                    <p>Ярко / необычно / привлекательно / анимированно</p>
                    <p>Привязка к району времени</p>
                    <p>Показывает только там где нужно</p>
                </div>

            </div>

            <div class="col s12 m6">
                <div class="material-placeholder">
                    <img style="width: 99%;" class="img-responsive materialboxed" src="/images/kkSko7iUwwo.jpg"></div>
            </div>

        </div>

    </div>
</div>



<div class="container">
    <div class="section">

        <div class="row">
            <div class="col s12 center">
                <h3><i class="mdi-content-send brown-text"></i></h3>
                <h3>Кому это нужно</h3>
            </div>
        </div>

        <!--   Icon Section   -->
        <div class="row">
            <div class="col s12 m3  ">
                <div class="icon-block">
                    <div class="hoy_need_cont_img">
                        <img  class="hoy_need_img" src="/images/How-to-Become-a-Freelance-Digital-Marketer_blog.jpg">
                    </div>


                    <h5 class="center com_title">Маркетолог</h5>

                    <ul class="ul_comp">
                        <li class="li_item_comp" >Новый дешевый канал</li>
                        <li  class="li_item_comp">Реклама</li>
                        <li  class="li_item_comp">Статистика</li>
                        <li  class="li_item_comp">Таргет по времени и месту</li>
                    </ul>


                </div>
            </div>

            <div class="col s12 m3  ">
                <div class="icon-block">
                    <div class="hoy_need_cont_img">
                        <img class="hoy_need_img" src="/images/business-woman4-730x461.jpg">
                    </div>
                    <h5 class="center com_title">Владелец бизнеса</h5>

                    <ul class="ul_comp">
                        <li  class="li_item_comp">Просто</li>
                        <li  class="li_item_comp">Прозрачно</li>
                        <li  class="li_item_comp">Не требует спец. знаний</li>
                        <li  class="li_item_comp">Клиенты</li>
                    </ul>


                </div>
            </div>

            <div class="col s12 m3  ">
                <div class="icon-block">
                    <div class="hoy_need_cont_img">
                     <img  class="hoy_need_img" src="/images/108c3bddcc5c059c2d0d2e4c74c43c3b.jpg">
                    </div>
                    <h5 class="center com_title">Рекламное агенство</h5>

                    <ul class="ul_comp">
                        <li  class="li_item_comp">Сервис</li>
                        <li  class="li_item_comp">Условия сотрудничества</li>
                    </ul>


                </div>
            </div>

            <div class="col s12 m3 ">
                <div class="icon-block">
                    <div class="hoy_need_cont_img">
                        <img  class="hoy_need_img" src="/images/photo-1490650404312-a2175773bbf5.jpg">
                    </div>

                    <h5 class="center com_title">Такси</h5>

                    <ul class="ul_comp">
                        <li  class="li_item_comp">Заработок</li>
                        <li  class="li_item_comp">до 1600 руб в день</li>
                    </ul>


                </div>
            </div>


        </div>

    </div>
</div>


<div class="container">
    <div class="section">

        <div class="row">
            <div class="col s12 center">
                <h3><i class="mdi-content-send brown-text"></i></h3>
                <h3>Примеры работ</h3>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m4">

                <div class="img-container">
                    <div class="video" >
                        <video controls >
                            <source src="video/IMG_2464.MOV" type="video/mp4">
                        </video>    </div>
                </div>

                <ul  >
                    <li>Ремонт вмятин</li>
                    <li>Охват 1000 мин</li>
                    <li>Бюджет 1000 руб. привлечено 50 лидов.</li>
                </ul>
            </div>

            <div class="col s12 m4">

                <div class="img-container">
                    <div class="video" >
                        <video controls >
                            <source src="video/MVI-8431-YouTube.mp4" type="video/mp4">
                        </video>    </div>
                </div>

                <ul  >
                    <li>Домашний вентеляционный клапан</li>
                    <li>Охват 16000 мин</li>
                    <li>Бюджет 16000 руб. привлечено 80 лидов.</li>
                </ul>
            </div>



        </div>

    </div>
</div>


<div class="container">
    <div class="section">

        <div class="row">
            <div class="col s12 center">
                <h3><i class="mdi-content-send brown-text"></i></h3>
                <h3>Личный кабинет</h3>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m6">
                <div class="material-placeholder">
                    <img style="width: 99%;" class="img-responsive materialboxed" src="/images/Screenshot_21.png"></div>
            </div>

            <div class="col s12 m6 ">
                <ul class="tabs">
                    <li class="tab"><a class="active" href="#tab_rekl">Рекламодатель</a></li>
                    <li class="tab"><a  href="#rab_taxi">Таксист</a></li>

                </ul>
                <div id="tab_rekl" class="col s12">
                    <div class="flow-text">

                        <ul>
                            <li>Таргетинг</li>
                            <li>Ставки</li>
                            <li>Отчётность</li>
                            <li>Выбор вида устройства</li>
                        </ul>

                    </div>
                </div>
                <div id="rab_taxi" class="col s12">
                    <div class="flow-text">

                        <ul>
                            <li>Отчет по показам</li>
                            <li>Начисления за месяц/день</li>

                        </ul>

                    </div>
                </div>
                <div class="center">
                <a href="<?= Yii::$app->user->isGuest ? '/signup' : '/admin' ?>"
                   style="  background-color: #ffd400;color: black;font-weight: bold" class="btn_order_lk waves-effect waves-light btn-large ">
                    Регистрация</a>
                </div>
            </div>

        </div>



    </div>
</div>


<div class="container">
        <div class="section">


            <div class="row">
                <div class="col s12 center">
                    <h3><i class="mdi-content-send brown-text"></i></h3>
                    <h3>Почему мы</h3>
                </div>
            </div>


        <!--   Icon Section   -->
        <div class="row">
            <div class="col s12 m6">
                <div class="icon-block">

                    <h5 class="center com_title">Наклейка</h5>

                    <ul class="ul_comp">
                        <li class="li_item_comp" >Не видно в темноте</li>
                        <li  class="li_item_comp">Мелкий и неразборчивый текст при отдалении</li>
                        <li  class="li_item_comp">За 3 сек не поменять на другое сообщение</li>
                    </ul>


                </div>
            </div>

            <div class="col s12 m6">
                <div class="icon-block">

                    <h5 class="center com_title">Mirovid LED</h5>

                    <ul class="ul_comp">
                        <li  class="li_item_comp"> В любое время суток заметно. В темное время еще лучше</li>
                        <li  class="li_item_comp">За счет яркости модулей рекламу видно издалека</li>
                        <li  class="li_item_comp"> Обновление в любой момент, через интернет. Online 24/7</li>
                    </ul>


                </div>
            </div>


        </div>

    </div>
</div>


<div class="parallax-container valign-wrapper " style="min-height: 470px;">
    <div class="section no-pad-bot">
        <div class="container">
            <div class="row center">
                <h5 class="header col s12 light item_title_block">Полная свобода в тестировании гипотез, коммерческих бонусов, промо кодов, акций, новинок, товаров и услуг</h5>
            </div>
        </div>
    </div>
    <div class="parallax"><img src="/themes/one/background2.jpg" alt="Unsplashed background img 2"></div>
</div>







<div class="container">
    <div class="section">


        <div class="row">
            <div class="col s12 center">
                <h3><i class="mdi-content-send brown-text"></i></h3>
                <h3>Начать работать</h3>
            </div>
        </div>


        <div class="row">
            <div class="col s12 m6 center-align">

                <a href="email:mirovidweb@yandex.ru"
                   style="   background-color: #7000ff;color: #e1e6ff;font-weight: bold" class="waves-effect waves-light btn-large ">
                    mirovidweb@yandex.ru</a>
            </div>





            <div class="col s12 m6 center-align">

                <?php

                if ( true /* Yii::$app->user->isGuest */){ ?>

                        <a href="<?= Yii::$app->user->isGuest ? '/signup' : '/admin' ?>" style="    background-color: #ffd400;color: black;font-weight: bold" class="waves-effect waves-light btn-large ">
                            Регистрация</a>


                <?php } ?>

            </div>


        </div>

    </div>
</div>




<footer style=" padding-top: 0;"  class="page-footer white">
    <div class="footer-copyright">
        <div class="container ">

        </div>
    </div>
    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <div class="modal-content">
            <div   >
                <video style="width:100%" controls >
                    <source src="/video/mirovid_wiki.webm" type="video/webm">
                </video>    </div>
        </div>
        <div class="modal-footer">

            <a  href="#!" class="  modal-close waves-effect waves-green btn-flat">Закрыть</a>

        </div>
    </div>

</footer>
<script>
    $(document).ready(function() {

        $('.modal').modal();
        $('.tabs').tabs();
        M.updateTextFields();
        $('.materialboxed').materialbox();
    });

    var heigh = $( window ).width();
    if ( heigh < 420){

        $('#index-banner').height(1400);

        $('nav .brand-logo').css('font-size','2rem');
        $('.item_h1').css('font-size','3.2rem');

        $('.quest').height(472);

        $('.review').height(1500);
        $('.review').removeClass('section');

    }

    $('.send-order').click( function (e) {
            var $this = $(this);

            var name = $($this).parent().parent().find("input[name='name']").val();
            var phone = $($this).parent().parent().find("input[name='phone']").val();
            var email = $($this).parent().parent().find("input[name='email']").val();
            var text = $($this).parent().parent().find("textarea[name='text']").val();
            if (! name){
                alert('Заполните имя'); return;
            }
            if (! phone){
                alert('Заполните телефон'); return;
            }
            if (! email){
                alert('Заполните email'); return;
            }
            if (! text){
                alert('Запишите вопроc'); return;
            }

            $.ajax({
                type:"POST",
                url:"/order/default/send-order",

                data:{
                    _csrffe:$('meta[name=csrf-token]').attr('content'),
                    name:name,
                    phone:phone,
                    email:email,
                    text:text,
                },
                success:function (data) {
                    if (parseInt(data) > 0){
                        alert('Спасибо!');
                        $($this).parent().parent().find("input[name='name']").val('');
                        $($this).parent().parent().find("input[name='phone']").val('');
                        $($this).parent().parent().find("input[name='email']").val('');
                        $($this).parent().parent().find("textarea[name='text']").val('');
                    }
                    console.log(data);
                }
            });
        });

</script>