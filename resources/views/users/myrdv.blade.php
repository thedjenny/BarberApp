<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Crenos</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">


    <!-- Styles -->
    <link href="{{ asset('profile/css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style type="text/css">
        br { clear: both; }
        .cntSeparator {
            font-size: 54px;
            margin: 10px 3px;
            color: #000;
        }
        .desc { margin: 7px 3px; }
        .desc div {
            float: left;
            font-family: Arial;
            width: 70px;
            margin-right: 50px;
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }
    </style>



    <!-- Scripts-->

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script>
       let bool = JSON.parse("{{json_encode($data["bool"])}}");
       if(!bool){
           alert("عفوا ليس لديك موعد حلاقة");
       }
    </script>


</head>
<body>


<div class="container">
    <div class="row">
        <div class="col-lg-10">


            <div class="profile-card-4 text-center">

                <img src="{{$data['user']['profile_picture']}}" class="img-fluid">
                <div class="profile-content">
                    <div class="profile-name">{{$data['user']['username']}}
                    </div>
                    <div class="profile-description">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor.</div>
                    <div class="row">
                        <h3> مرحبا بك عزيزي الزبون</h3>
                    </div><hr>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="profile-overview">
                                <h5>نقاطي</h5>
                                <h4>{{$data['user']['points']}}</h4></div>
                        </div>
                        <div class="col-xs-6">
                            <div class="profile-overview">
                                <h5>تاريخ الاشتراك</h5>
                                <h4>{{$data['user']['created_at']->format("Y-m-d")}}</h4></div>
                        </div>
                    </div><hr>
                    @if($data['bool'])
                    <div class="row">
                        <h4> {{$data['rdv']['time']}} لديك موعد للحلاقة بتاريخ  {{$data['rdv']['date']}} على الساعة </h4>
                    </div><hr>
                    <div class="row">
                        <div class="col-xs-12">
                            <form method="post" action="{{route('cancelrdv')}}" onsubmit="return confirm(' تأكيد الغاء الموعد');">
                                <input type="hidden" name="id" value="{{$data['user']['idClient']}}"/>
                                <input type="hidden" name="date" value="{{$data['rdv']['date']}}"/>
                                <input type="hidden" name="ps" value="{{$data['user']['points']}}"/>
                                <button type="submit" class="btns btn-dgr"><span>الغاء الموعد</span></button>
                            </form>

                        </div>
                    </div>
                    @else
                        <div class="row">
                            <h4> لحجز موعد الرجاء الرجوع الى روبوت المحادثة  </h4>
                        </div><hr>

                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
</body>


</html>
