<nav class="white" role="navigation" xmlns="http://www.w3.org/1999/html">
    <div class="nav-wrapper container">
        <a id="logo-container" href="/"  class="brand-logo red-text text-accent-4 item_logo_text">
            <span class="red-text text-accent-4">☀</span> MIROVID </a>

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

<div class="section no-pad-bot" id="index-banner">
    <div class="container">

        <h1 class="header center avg_h1">пассивный доход и реклама Mirovid</h1>


    </div>
</div>


<div class="section"  >
    <div class="container">
        <br><br>

        <div class="row ">
            <div class="col s12 m7 ">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/b3S7QJK_fZU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <div class="col s12 m5   push-m2">
                <div class="row ">
                    <form>
                        <div class="row ">

                            <div class="col s12">

                                <p class="avg_reclame_title">Показы
                                <span class="avg_reclame_title">и рекламный ролик</span>
                                </p>
                            </div>
                            <div class="col s12">
                                <p class="avg_reclame_price">Цена от <span class="avg_reclame_val">10 000</span> руб.</p>
                            </div>

                            <div class="col s12">
                                <a style="background-color: #ffd400;color: black;font-weight: bold"
                                   href="/zakazat-reklamu-v-seti-mirovid-led"
                                   class="waves-effect waves-light btn-large send-order  ">Подробности</a>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>

    </div>
</div>


<div class="section"  >
    <div class="container">
        <br><br>

        <div class="row ">

            <div class="col s12 m5  ">
                <div class="row ">
                    <form>
                        <div class="row ">

                            <div class="col s12">

                                <p class="avg_reclame_title">Пассивный доход
                                    <span class="avg_reclame_title">на панелях</span>
                                </p>
                            </div>
                            <div class="col s12">
                                <p class="avg_reclame_price">От <span class="avg_reclame_val">3 000</span> руб. ежемесячно</p>
                            </div>

                            <div class="col s12">
                                <a style="background-color: #2d6dbc;color: white;font-weight: bold"
                                   href="/kupit-led-panel-mirovid"
                                   class="waves-effect waves-light btn-large send-order  ">Подробности</a>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

            <div class="col s12 m7  push-m2">
               <img class="avg_sale_img img-responsive hide-on-med-and-down" src="/themes/one/image/scrooge-coloring10.gif">
                <!-- https://qph.fs.quoracdn.net/main-qimg-5369be2d38953cfd039f8e2f371ba88b.webp -->
            </div>



        </div>

    </div>
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

        $('iframe').width(320);

       // $('#index-banner').height(1500);

        $('nav .brand-logo').css('font-size','2rem');
        $('.item_h1').css('font-size','3.2rem');
        $('.avg_h1').css('font-size','1.8rem');


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