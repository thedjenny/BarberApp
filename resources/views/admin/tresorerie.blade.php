<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Taki eddine seghiri">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/template/admin/assets/images/favicon1.png')}}">

    <title>Pelo-Barber: Tresorerie</title>
    <!-- Custom CSS -->
    <link href="{{asset('/template/admin/assets/libs/flot/css/float-chart.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('/template/admin/dist/css/style.min.css')}}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        function updatePrice(array){
            var i ;
            for(i=1;i<=array.length;i++){

                if(document.getElementById("option"+[i]).selected){
                    $("#prix").attr("value",array[i-1].prix);
                }

            }


        }


        function dateChanged(){
           $("#fin").attr("min",debut.value);
        }

        function radios(el){
           var type = el;

           switch(el){
               case "global":{
                   document.getElementById("checkbox2").checked=false;
                   $("span1").empty();
               }break;
               case "coiffeur":{
                   document.getElementById("checkbox1").checked=false;
                   $("span1").empty();
                   $("span1").html('<div class="form-group row">\n' +
                       '                                            <label class="col-md-3" for="disabledTextInput">Coiffeur</label>\n' +
                       '                                            <div class="col-md-9">\n' +
                       '                                                <select class="form-control" name="coiffeur"  required>\n' +
                       '                                                 <option selected>Coiffeur 1</option>\n' +
                       '                                                </select>\n' +
                       '                                            </div>\n' +
                       '                                        </div>');

               }break;
           }

        }

    </script>

    <style>
        .form-check-inline{
            font-size: initial !important;
            padding-left:0 !important;
            margin-right: auto;
        }
    </style>
</head>

<body>



<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="app">
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="{{route('home')}}">
                        <!-- Logo icon -->
                        <b class="logo-icon p-l-10">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{ URL::to('/template/admin/assets/images/logo-icon.png') }}" alt="homepage" class="light-logo" />

                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text">
                             <!-- dark Logo text -->
                             <img src="{{ URL::to('/template/admin/assets/images/logo-text.png') }}" alt="homepage" class="light-logo" />

                        </span>
                        <!-- Logo icon -->
                        <!-- <b class="logo-icon"> -->
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->
                        <!-- <img src="assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->

                        <!-- </b> -->
                        <!--End Logo icon -->
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                        <!-- ============================================================== -->
                        <!-- create new -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search position-absolute">
                                <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>
                            </form>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ URL::to('/template/admin/assets/images/1.jpg') }}" alt="user" class="rounded-circle" width="31"></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="{{route('myProfile')}}"><i class="ti-user m-r-5 m-l-5"></i> Mon profile: {{Auth::user()->nom}} {{Auth::user()->prenom}}</a>
                                <!--   <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i> Estimation des profits</a> -->

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{route('adminSetting')}}"><i class="ti-settings m-r-5 m-l-5"></i> Parametres</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"  onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();"  href="{{route('logout')}}"><i class="fa fa-power-off m-r-5 m-l-5"></i> Se déconnecter</a>
                                <div class="dropdown-divider"></div>

                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="p-t-30">
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('home')}}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Menu principal</span></a></li>


                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('clients')}}" aria-expanded="false"><i class="mdi mdi-account-card-details"></i><span class="hide-menu">Clients</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-calendar-today"></i><span class="hide-menu">Rendez-vous</span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item"><a href="{{route('schedule',['id'=>0])}}" class="sidebar-link"><i class="mdi mdi-calendar-today"></i><span class="hide-menu"> Aujourd'hui </span></a></li>
                                <li class="sidebar-item"><a href="{{route('schedule',['id'=>1])}}" class="sidebar-link"><i class="mdi mdi-calendar-check"></i><span class="hide-menu"> Demain </span></a></li>
                                <li class="sidebar-item"><a href="{{route('schedule',['id'=>2])}}" class="sidebar-link"><i class="mdi mdi-calendar-range "></i><span class="hide-menu"> Après-demain </span></a></li>
                            </ul>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('planning')}}" aria-expanded="false"><i class="mdi mdi-calendar-clock"></i><span class="hide-menu">Calendrier</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('shopSettings')}}" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu">Parametres</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('adminSetting')}}" aria-expanded="false"><i class="mdi mdi-pause-circle"></i><span class="hide-menu">Weekends</span></a></li>

                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('editworkday')}}" aria-expanded="false"><i class="fas fa-stopwatch"></i><span class="hide-menu">Modifier journee libre</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('getCoiffures')}}" aria-expanded="false"><i class="fas fa-cut"></i><span class="hide-menu">Liste des coiffures</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-account-key"></i><span class="hide-menu">Administrateur </span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item"><a href="{{route('registerAdmin')}}" class="sidebar-link"><i class="mdi mdi-account-settings-variant"></i><span class="hide-menu"> Ajouter admin </span></a></li>
                                <li class="sidebar-item"><a href="{{route('adminlist')}}" class="sidebar-link"><i class="mdi mdi-account-remove"></i><span class="hide-menu"> Liste des admins </span></a></li>

                            </ul>
                        </li>

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>

    </div>
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="page-title">Tresorerie</h4>
                    <div class="ml-auto text-right">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>

                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Sales Cards  -->
            <!-- ============================================================== -->
            <div class="row">
                <!-- Column -->
                <div class="col-md-12 col-lg-12 col-xlg-12"><a data-toggle="modal" data-target="#EncaissModal" >

                    <div class="card card-hover">
                            <div class="box bg-success text-center">
                                <h1 class="font-light text-white"><i class="mdi mdi-cash-multiple"></i></h1>
                                <h6 class="text-white">Encaissement</h6>
                            </div>
                        </div></a>
                </div>

                <div class="col-md-12 col-lg-12 col-xlg-12"><a data-toggle="modal" data-target="#DecaissModal" >

                        <div class="card card-hover">
                            <div class="box bg-danger text-center">
                                <h1 class="font-light text-white"><i class="fas fa-sign-out-alt"></i></h1>
                                <h6 class="text-white">Decaissement</h6>
                            </div>
                        </div></a>
                </div>

                <div class="col-md-12 col-lg-12 col-xlg-12"><a data-toggle="modal" data-target="#ChargeModal" >

                        <div class="card card-hover">
                            <div class="box bg-cyan text-center">
                                <h1 class="font-light text-white"><i class="mdi mdi-cart-off"></i></h1>
                                <h6 class="text-white">Charge</h6>
                            </div>
                        </div></a>
                </div>

                <div class="col-md-12 col-lg-12 col-xlg-12"><a data-toggle="modal" data-target="#RapportModal" >

                        <div class="card card-hover">
                            <div class="box bg-primary text-center">
                                <h1 class="font-light text-white"><i class="mdi mdi-file-document"></i></h1>
                                <h6 class="text-white">Rapport</h6>
                            </div>
                        </div></a>
                </div>


                <div class="modal fade" id="EncaissModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Ajouter encaissement</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Annuler">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post" action="{{route('validerEncaissement')}}" enctype="multipart/form-data">

                                <div class="card">
                                    <div class="card-body">

                                        <div class="form-group row">
                                            <label class="col-md-3" for="disabledTextInput">Client</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="client">
                                                    <option selected>Selectionner...</option>
                                                    @foreach($clients as $c)
                                                        <option>{{$c->username}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3" for="disabledTextInput">Coiffeur</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="coiffeur"  required>
                                                    <option selected>Selectionner...</option>
                                                    @foreach($coiffeurs as $c)
                                                        <option value="{{$c->idCoiffeur}}">{{$c->prenom}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3" for="disabledTextInput">Coupe</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="coiffure" onchange="updatePrice({{json_encode($coupes)}})" required>
                                                    <option selected>Selectionner...</option>
                                                    @foreach($coupes as $c)
                                                        <option id="option{{$loop->iteration}}" value="{{$c->nom}}">{{$c->nom}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3" for="disabledTextInput">Prix</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-group" id="prix" name="prix" >
                                            </div>


                                        </div>

                                    </div>
                                    <div class="border-top">
                                        <div class="card-body">
                                            <input type="submit" class="btn btn-primary"/>
                                        </div>
                                    </div>
                                </div></form>
                           </div>
                    </div>
                </div>

                <div class="modal fade" id="DecaissModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Ajouter Retrait</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Annuler">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form method="post" action="{{route('validerDecaissement')}}">
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-md-3" for="disabledTextInput">Coiffeur</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="coiffeur"  required>
                                                <option selected>Selectionner...</option>
                                                @foreach($coiffeurs as $c)
                                                    <option value="{{$c->idCoiffeur}}">{{$c->nom." ".$c->prenom}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3" for="disabledTextInput">Motif</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-group" id="motif" name="motif" placeholder="Ex: Paie magasinier" required>
                                        </div>

                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-3" for="disabledTextInput">Montant</label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-group" id="montant" name="montant" value="0" required>
                                        </div>

                                    </div>

                                </div>
                                <div class="border-top">
                                    <div class="card-body">
                                        <input type="submit" class="btn btn-primary"/>
                                    </div>
                                </div>
                            </div></form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ChargeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Ajouter Charge</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Annuler">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form method="post" action="{{route('validerCharge')}}">
                                <div class="card">
                                    <div class="card-body">



                                        <div class="form-group row">
                                            <label class="col-md-3" for="disabledTextInput">Motif</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-group" id="motif" name="motif" placeholder="Ex: Produit de nettoyage" required>
                                            </div>

                                        </div>


                                        <div class="form-group row">
                                            <label class="col-md-3" for="disabledTextInput">Montant</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-group" id="montant" name="montant" value="0" required>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="border-top">
                                        <div class="card-body">
                                            <input type="submit" class="btn btn-primary"/>
                                        </div>
                                    </div>
                                </div></form>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="RapportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Rapport</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Annuler">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-md-3" for="disabledTextInput">Type</label>

                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" id="checkbox1" name="ch1" onchange="radios('global')">
                                            <label class="form-check-label" for="inlineCheckbox1">Global</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" id="checkbox2" name="ch2" onchange="radios('coiffeur')">
                                            <label class="form-check-label" for="inlineCheckbox1">Coiffeur</label>
                                        </div>

                                    </div>
                                    <span1></span1>
                                    <div class="form-group row">
                                        <label class="col-md-3" for="disabledTextInput">Du</label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-group" id="debut" onchange="dateChanged()" name="debut" required>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3" for="disabledTextInput">Au</label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-group" id="fin" name="fin" required>
                                        </div>
                                    </div>


                                </div>
                                <div class="border-top">
                                    <div class="card-body">
                                        <input type="submit" class="btn btn-primary"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session()->has('message'))
                    <div class="alert alert-success" >
                        {{ session()->get('message') }}
                    </div>
            @endif
                <!-- Column -->
            </div>
            <!-- ============================================================== -->
            <!-- Sales chart -->
            <!-- ============================================================== -->

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
            Developpé par <a href="https://www.facebook.com/taki729">Taki eddine seghiri</a>.
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{asset('template/admin/assets/libs/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('template/admin/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset('template/admin/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('template/admin/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
<script src="{{asset('template/admin/assets/extra-libs/sparkline/sparkline.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('template/admin/dist/js/waves.js')}}"></script>
<!--Menu sidebar -->
<script src="{{asset('template/admin/dist/js/sidebarmenu.js')}}"></script>
<!--Custom JavaScript -->
<script src="{{asset('template/admin/dist/js/custom.min.js')}}"></script>
<!--This page JavaScript -->
<!-- <script src="dist/js/pages/dashboards/dashboard1.js"></script> -->
<!-- Charts js Files -->


</body>

</html>