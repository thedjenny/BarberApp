<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('template/login/style.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/template/admin/assets/images/favicon1.png')}}">


</head>
<body>
<!-- Main Content -->
<div class="container-fluid">
    <div class="row main-content bg-success text-center">
        <div class="col-md-4 text-center company__info">
            <span><h2>Welcome Barber Vintage</h2></span>
        </div>
        <div class="col-md-8 col-xs-12 col-sm-12 login_form ">
            <div class="container-fluid">
                <div class="row">
                    <img src="{{ URL::to('/template/login/logo1.png') }}">
                </div>
                <div class="row">
                    <form class="form-group" method="POST" action="{{ route('login') }}">
                        <div class="row">
                            <input type="text" name="email" id="username" class="form__input" placeholder="Numero de tel">
                        </div>
                        <div class="row">
                            <!-- <span class="fa fa-lock"></span> -->
                            <input type="password" name="password" id="password" class="form__input" placeholder="Mot de passe">
                        </div>
                        <div class="row">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember_me">Se souvenir de moi!</label>
                        </div>
                        <div class="row">
                            <input type="submit" value="Se connecter" class="btn">
                        </div>
                    </form>
                </div>
                <div class="row">
               <!--     <p>Don't have an account? <a href="#">Register Here</a></p>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->
<div class="container-fluid text-center footer">
    <p>by <a href="https://wwww.facebook.com/taki729">Taki.</a></p>
</div>
</body>
</html>
