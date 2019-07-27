
<section class="pb_cover_v3 overflow-hidden cover-bg-indigo cover-bg-opacity text-left pb_gradient_v1 pb_slant-light" id="section-home">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-6">
                <h2 class="heading mb-3">Быстрые сообщения online по городу </h2>
                <div class="sub-heading">
                    <p class="mb-4">Легкий способ поделиться сообщением. Увидят многие 😃 Нужен только смартфон!</p>
                    <p class="mb-5"><a class="btn btn-success btn-lg pb_btn-pill smoothscroll" href="#section-pricing"><span class="pb_font-14 text-uppercase pb_letter-spacing-1">See Pricing</span></a></p>
                </div>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-5 relative align-self-center">
                <div class="bg-white rounded pb_form_v1">
              <!--  <form action="#" class="bg-white rounded pb_form_v1"> -->
                    <h2 class="mb-4 mt-0 text-center">Покажу сейчас!</h2>
                    <div class="form-group">
                        <div class="dropdown">
                            <button style="width: 100%;background-color: #ffffff;
    color: #868e96;" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Номер машины
                            </button>
                            <div class="dropdown-menu " id="testTemp" aria-labelledby="dropdownMenuButton">
                                <a style="color: black" class="dropdown-item"  >н380нх</a>
                                <a style="color: black" class="dropdown-item"  >е247нк</a>

                            </div>
                        </div>
                      <!--  <input type="text" name="phone" class="form-control pb_height-50 reverse" placeholder="н380нх"> -->
                    </div>

                    <div class="form-group">
                        <textarea  class="form-control py-3 reverse" placeholder="Текст сообщения..."></textarea>
                    </div>
                    <div class="form-group">
                        <div>
                            <span style="font-size: 25px;color: #181a1c">
                                &nbsp;<span style="font-weight: bold;" class="countShow">60</span><span style="margin-left: 3px;" class="countViewIcon">⌛ сек</span>
                               =&nbsp;<span class="summ_max">10</span>&#x20bd;</span>
                        </div>
                    </div>
                    <div class="form-group" style=";">



                        <div class="slidecontainer" >

                         <!--    <span style="position:absolute; flex: 0 0 10%;    max-width: 7%;color:red; border:1px solid blue; min-width:100px;">
                                <span id="myValue"></span>
                             </span> -->

                            <input type="range" min="60" step=6 max="180" value="60" class="slider"  >
                        </div>


                    </div>

                  <!--  <div class="form-group">
                        <div class="pb_select-wrap">
                            <select class="form-control pb_height-50 reverse">
                                <option value="" selected>Type</option>
                                <option value="">Basic</option>
                                <option value="">Business</option>
                                <option value="">Free</option>
                            </select>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <input type="text" name="phone" class="form-control pb_height-50 reverse" placeholder="+7 999 1002878">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-lg btn-block pb_btn-pill  btn-shadow-blue" value="Написать">
                    </div>

                    <div class="form-group" style="display: block">
                        <form  id="framediv"  method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">
                            

                            
                            
                          <input type="hidden" name="receiver" value="410015089945946">
                            <input type="hidden" name="quickpay-form" value="donate">

                            <input type="hidden" name="label" value="$order_id">
                            <input type="hidden" name="targets" value="транзакция {order_id}">
                            <input type="hidden" name="sum" value="60.00" data-type="number">

                            <input type="hidden" name="comment" value="Реклама в сервисе">
                            <input type="hidden" name="need-fio" value="false">
                            <input type="hidden" name="need-email" value="false">
                            <input type="hidden" name="need-phone" value="true">
                            <input type="hidden" name="need-address" value="false">
                            <input type="hidden" name="paymentType" value="AC">


                        </form>
                    </div>
              <!--  </form> -->
            </div>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

<script>
    $(document).ready( function (){
        $('input[name="phone"]').mask("+9(999) 9999999");
        $('input[name="car"]').mask("a(999)aa");
        var selNumber = '';
        $('.dropdown-item').click(function (e) {
            selNumber = $(e.target).text();
            $('#dropdownMenuButton').text(  'Номер машины '+ selNumber );
        });




        $('.slidecontainer input[type="range"]').on('input',function (e) {
            //console.log(e.target.value);
            $('.summ_max').html(e.target.value / 6);
            $('.countShow').html(e.target.value );
        });

  /*      var myRange = document.querySelector('.slidecontainer');


        var myValue =  document.querySelector('#myValue');
        var myUnits = 'myUnits';
        var off = myRange.offsetWidth / (parseInt(myRange.max) - parseInt(myRange.min));
        var px =  ((myRange.valueAsNumber - parseInt(myRange.min)) * off) - (myValue.offsetParent.offsetWidth / 2);

        myValue.parentElement.style.left = px + 'px';
        myValue.parentElement.style.top = myRange.offsetHeight + 'px';
        myValue.innerHTML = myRange.value + ' ' + myUnits;

        myRange.oninput =function(){
            var px = ((myRange.valueAsNumber - parseInt(myRange.min)) * off) - (myValue.offsetWidth / 2);
            myValue.innerHTML = myRange.value + ' ' + myUnits;
            myValue.parentElement.style.left = px + 'px';
        };
*/

        $('.slidecontainer input[type="range"]').change(function (e) {
            console.log(e);
        });

        $('.bg-white.rounded.pb_form_v1 input[type="submit"]').click(function (e){
            e.preventDefault();
            var valid = false;
            var a = $('.bg-white.rounded.pb_form_v1 input[name="phone"]').val();
            var b = $('.bg-white.rounded.pb_form_v1 textarea').val();
            var c = $('.countShow').text();
           valid = selNumber && String(a)   && String(b)      ;
           if (! valid) {
               var sN = 'Выберите машину';
               var ae = 'Заполните телефон';
               var be = 'Заполните текст';
               var am = 'Заполните данные';
 ;
               if (!selNumber){
                   am = sN;
               } else if (!b){
                   am = be;
               } else if(!a){
                   am = ae;
               }


               alert(am);

               return false;
           } else {
               $.ajax({
                   type:"POST",
                   url:"<?=\yii\helpers\Url::to(['/api/default/recive-order'])?>",
                   data:{
                       number_car: selNumber,
                       phone:$('.bg-white.rounded.pb_form_v1 input[name="phone"]').val(),
                       text: $('.bg-white.rounded.pb_form_v1 textarea').val(),
                       count_view:$('.countShow').text(),
                       _csrffe:$('meta[name=csrf-token]').attr('content')
                   },
                   success: function (data){
                       if (typeof data == 'object'){
                           if ( data.responce.status == 200){
                               $('#framediv input[name="label"]').val(data.responce.order_id);
                               $('#framediv input[name="sum"]').val(data.responce.sum);
                               $('#framediv input[name="targets"]').val('транзакция ' + data.responce.order_id);

                               //alert('Success');
                               $('#framediv').submit();
                           }

                           console.log(  data);
                       }
                   }
               });
           }


        });
    } );
</script>