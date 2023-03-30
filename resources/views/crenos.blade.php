<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Barber Vintage -Crenaux</title>
          <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/template/admin/assets/images/favicon1.png')}}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">


        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="{{ asset('buttons/css/base.css') }}" rel="stylesheet">


        <script>
            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/messenger.Extensions.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'Messenger'));

        </script>
        <style>
            html, body {
                background-color: #fff;
                color: #fcfeff;
                font-family: 'Raleway', system-ui;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
            .parallax {
                /* The image used */
                background-image: url({{url('bg-img/imag2.jpg')}});

                /* Set a specific height */
                min-height: 500px;

                /* Create the parallax scrolling effect */
                background-attachment: fixed;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }



            .m-b-md {
                margin-bottom: 30px;
            }

            /*.btn.btn-round {
                border-radius: 40px;
            }
            .btn.btn-secondary {
                color: #fff;
                border-color: #8bbabb;
                background: #8bbabb;
            }
            .btn {
                padding: 12px 16px;
                cursor: pointer;
                border-width: 1px;
                border-radius: 5px;
                font-size: 14px;
                font-weight: 500;
                -webkit-box-shadow: 0px 10px 20px -6px rgb(0 0 0 / 12%);
                -moz-box-shadow: 0px 10px 20px -6px rgba(0, 0, 0, 0.12);
                box-shadow: 0px 10px 20px -6px rgb(0 0 0 12%);
                overflow: hidden;
                position: relative;
                -moz-transition: all 0.3s ease;
                -o-transition: all 0.3s ease;
                -webkit-transition: all 0.3s ease;
                -ms-transition: all 0.3s ease;
                transition: all 0.3s ease;
            }
            .btn-block {
                display: block;
                width: 10%;
            }
            .mb-2, .my-2 {
                margin-bottom: 0.5rem !important;
            }
            *, *::before, *::after {
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
            }
            *, *::before, *::after {
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
            }*/

        </style>

       
    </head>
    <body>
    <div class="parallax">
    <div class="incontent">
        <div>
            @if($data["isOff"])
                <h1 align="center">عفوا لا يوجد مواعيد اليوم لعطلة أو سبب طارئ</h1>
            @elseif(count($data["crns"])==0)
                <h1 align="center">عفوا كل المواعيد محجوزة</h1>
            @elseif($data['exist'])

                    <h1 align="center">عفوا لا يمكنك حجز أكثر من موعد</h1>
                    <a href="{{route('myrdv',['id'=>$data["id"]])}}">اضغط هنا لتصفح موعدك القادم</a>
            @else
                <div class="h1container">
                <h1 align="center"> مرحبا بك  الرجاء اختيار الموعد وتاكيده</h1><br></div><br>
                <ol class="content">
                    @foreach($data["crns"] as $crn)
                        <form method="post" action="{{route('reserver')}}" onsubmit="return confirm(' تأكيد اختيار موعدك يوم {{$data["date"]}} على الساعة {{$crn}}');">
                            <input type="hidden" name="date" value="{{$data["date"]}}"/>
                            <input type="hidden" name="userid" value="{{$data["id"]}}"/>
                            <input type="hidden" name="type" value="{{$data["type"]}}"/>
                            <li class="content__item">
                                <input type="hidden" name="creno" value="{{$crn}}"/>
                                <button type= "submit" class="button button--pan"><span>{{$crn}}</span></button>
                            </li>
                        </form>
                    @endforeach
        </ol>@endif
            </div>
        </div>
    </div>
    </body>
    <script type="text/javascript" src="{{ asset('buttons/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('buttons/js/popper.js') }}"></script>
    <script type="text/javascript" src="{{ asset('buttons/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('buttons/js/main.js') }}"></script>

</html>
