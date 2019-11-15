<nav class="white" role="navigation" xmlns="http://www.w3.org/1999/html">
    <div class="nav-wrapper container">
        <a id="logo-container" href="/"  class="brand-logo red-text text-accent-4 item_logo_text"><span class="red-text text-accent-4">☀</span> MIROVID</a>
        <ul class="right hide-on-med-and-down">
            <li><a class="item_phone" style="font-size: 1.5em;color: black" href="#">8(999)-100-2878</a></li>
        </ul>

        <ul id="nav-mobile" style="background-color: #e1e6ff;   " class="sidenav">
            <li><a href="#" style="font-size: 1.5em;">89991002878</a></li>
            <li><a href="/zakazat-reklamu-v-seti-mirovid-led" style="font-size: 1.5em;">Заказать рекламу</a></li>
            <li><a href="/kupit-led-panel-mirovid" style="font-size: 1.5em;">Купить панель</a></li>
        </ul>
        <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i style="color: black;" class="material-icons">menu</i></a>
    </div>
</nav>

<div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot" style="background-image: url(themes/one/image/bg/9.jpg);">
        <div class="container">

            <h1 class=" header center    item_h1">Купить панель MIROVID LED</h1>
            <div class="row ">


                <div class="col s12 m7 ">

                    <div class="cont_utm">
                        <div class=" cont_utm_col_icon">
                            <i class=" utm_icon small material-icons">monetization_on</i>
                        </div>
                        <div  class="cont_utm_col_text" >
                            <h5 class="header  light item_utp ">Пассивный заработок</h5>
                            <p class="item_utp_subtitle">Ездишь по своим делам и получаешь деньги от показа рекламы</p>
                        </div>
                    </div>

                    <div class="cont_utm">
                        <div class=" cont_utm_col_icon">
                            <i class=" utm_icon small material-icons">visibility</i>
                        </div>
                        <div class="cont_utm_col_text">
                            <h5 class="header  light item_utp ">Мир увидит</h5>
                            <p class="item_utp_subtitle">Предложи Mirovid как рекламное место и получи постоянный % к своему доходу от панели </p>
                        </div>

                    </div>

                    <div class="cont_utm">
                        <div class=" cont_utm_col_icon">
                            <i class=" utm_icon small material-icons">ondemand_video</i>
                        </div>
                        <div class="cont_utm_col_text">
                            <h5 class="header  light item_utp ">Твоя доска объявлений</h5>
                            <p class="item_utp_subtitle">Подай своё объявление и люди увидят его</p>
                        </div>
                    </div>



                    <div class="cont_utm">
                        <div class=" cont_utm_col_icon">
                            <i class=" utm_icon small material-icons">where_to_vote</i>
                        </div>

                        <div class="cont_utm_col_text">
                            <h5 class="header  light item_utp ">Водитель месяца</h5>
                            <p class="item_utp_subtitle">Выполняешь план по часам в месяц - получаешь премию</p>
                        </div>
                    </div>



                    <div class="cont_utm">
                        <div class=" cont_utm_col_icon">
                            <i class=" utm_icon small material-icons">group_add</i>
                        </div>
                        <div class="cont_utm_col_text">
                            <h5 class="header  light item_utp ">Репост другу</h5>
                            <p class="item_utp_subtitle">Пригласи друзей и получи приятный денежный бонус</p>
                        </div>
                    </div>

                </div>


                <div class="col s12 m5 card  push-m2">
                    <div class="row card-content">
                        <form  >
                            <div class="row ">
                                <div class="input-field col s12 ">
                                    <input name="name" placeholder="Имя" id="first_name" type="text" class="validate">
                                    <label style=" color: #515751;   font-weight: bold;
    font-size: 2.4em;" for="first_name">Имя</label>
                                </div>
                                <div class="input-field col s12">
                                    <input name="phone"  placeholder="Телефон" id="last_name" type="text" class="validate">
                                    <label style="color: #515751;    font-weight: bold;
    font-size: 2.4em;" for="last_name">Телефон</label>
                                </div>
                                <div class="input-field col s12">
                                    <input name="email"  placeholder="Email" id="Email" type="text" class="validate">
                                    <label style="color: #515751;    font-weight: bold;
    font-size: 2.4em;" for="email">Email</label>
                                </div>
                                <div class="col s12">
                                    <a
                                            onclick="window.dataLayer.push({'event': 'order_panel'});"
                                            style="background-color: #ffd400;color: black;font-weight: bold"
                                       class="waves-effect waves-light btn-large send-order  ">Оставить заявку</a>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>

            </div>

            <br><br>

        </div>
    </div>
    <div class="parallax"><img src="/themes/one/background1.jpg" alt="Unsplashed background img 1"></div>
</div>







<footer style=" padding-top: 0;"  class="page-footer white">

    <div class="footer-copyright">
        <div class="container ">
            <span class="text-gray">  Made by</span> <a class="brown-text text-lighten-3" href="http://materializecss.com">Materialize</a>
        </div>
    </div>
</footer>
<script>
    $(document).ready(function() {
        M.updateTextFields();
    });
    // $(document).ready(function (){
    var heigh = $( window ).width();
    if ( heigh < 420){

        $('#index-banner').height(1500);

        $('nav .brand-logo').css('font-size','2rem');
        $('.item_h1').css('font-size','3.2rem');

        $('.quest').height(800);



    } else{
        //  $('#index-banner').height (heigh);
    }



    $('.send-order').click( function (e) {
        var $this = $(this);



        $.ajax({
            type:"POST",
            url:"/order/default/send-order",

            data:{
                _csrffe:$('meta[name=csrf-token]').attr('content'),
                name:$($this).parent().parent().find("input[name='name']").val(),
                phone:$($this).parent().parent().find("input[name='phone']").val(),
                email:$($this).parent().parent().find("input[name='email']").val(),
                text:$($this).parent().parent().find("textarea[name='text']").val(),
            },
            success:function (data) {
                if (parseInt(data) > 0){
                    alert('Заявка создана!  №' + parseInt(data) );
                }
                console.log(data);
            }
        });
    });

    //});
</script>