<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/template/admin/assets/images/favicon1.ico')}}">
    <title>Barber Vintage - TODAY'S PLAN</title>
    <!-- Custom CSS -->
    <link href="{{asset('/template/admin/assets/libs/magnific-popup/dist/magnific-popup.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('/template/admin/assets/extra-libs/multicheck/multicheck.css')}}">
    <link href="{{asset('/template/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('/template/admin/dist/css/style.min.css')}}" rel="stylesheet">
    <style>
        img{
            max-width: 100%;
            max-height: 100%;

            display: block; /* remove extra space below image */
        }
        .sad {
            width: 50%;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            display: block;
            margin: auto;
        }
        .titles {
            text-align: center;
            font-size: 36px;
            margin-top: revert;
        }
        .imgContainer{
            width: 100px;
            border: 5px solid black;
           border-radius: 10px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #30e506;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #48f321;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
        div.el-card-item {
            padding: 0 !important;
        }

        div.el-card-avatar.el-overlay-1 {
            margin: 0 !important;
        }





    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
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
                            <a class="dropdown-item" href="{{route('myProfile')}}"><i class="ti-user m-r-5 m-l-5"></i> Mon profile</a>
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

    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="page-title">Barber Vintage : Rendez-vous du <b>{{"\n".$date}}</b></h4>
                    <div class="ml-auto text-right">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Library</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <button class="btn btn-success" data-toggle="modal" data-target="#AjoutModal">Ajouter nouveau rendez-vous</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="AjoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajouter rendez-vous Offline</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Annuler">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{route('addRdvOffline')}}" enctype="multipart/form-data">

                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" name="idCoiffeur" value="{{$idCoiffeur}}">
                                <div class="form-group row">
                                    <label class="col-md-3" for="disabledTextInput">Client</label>
                                    <select name="client" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                                        <option value="null" selected>Selectionnez un client ...</option>
                                        @foreach($clients as $c)
                                            <option value="{{$c->idClient}}" >{{$c->username}} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3" for="points">Type coupe</label>
                                    <select name="coupe" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                                        <option value="null" selected>Selectionnez une coupe ...</option>
                                        @foreach($coiffure as $cf)
                                            <option value="{{$cf->nom}}" >{{$cf->nom}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3" for="points">Jour rendez-vous</label>
                                    <select name="day" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                                        <option  value="0" selected>Aujourd'hui</option>
                                        <option value="1">Demain</option>
                                        <option value="2">Apres-demain</option>
                                    </select>
                                </div>


                            </div>
                            <div class="border-top">
                                <div class="card-body">
                                    <input type="submit" class="btn btn-primary"/>
                                </div>
                            </div>
                        </div>
                    </form> </div>
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
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                @if($vide)
                    <div class="col-12">
                                <h1 class="titles"> عفوا لا يوجد زبائن في هذا اليوم</h1> <br>
                        <img src="{{ URL::to('/bg-img/sad.png') }}" class="sad" />

                    </div>

                @else
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="zero_config" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Photo facebook</th>
                                        <th>Nom d'utilisateur</th>
                                        <th>Type coiffure</th>
                                        <th>Points</th>
                                        <th>Heure rendez-vous</th>
                                        <th>Présent</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($data as $c)
                                        <tr><form id="present{{$loop->iteration}}" action="{{route('isPresent')}}" method="post" onsubmit="confirm('Client {{$c->username}} est present?')">
                                            <td>
                                                <div class="row el-element-overlay">
                                                    <div class="card">
                                                        <div class="el-card-item">
                                                            <div class="el-card-avatar el-overlay-1">  <img src="{{$c->profile_picture}} " />
                                                                <div class="el-overlay">
                                                                    <ul class="list-style-none el-info">
                                                                        <li class="el-item"><a class="btn default btn-outline image-popup-vertical-fit el-link" href=" {{$c->profile_picture}} "><i class="mdi mdi-magnify-plus"></i></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            <td>{{$c->username}}</td><input type="hidden" name="id" value="{{$c->idClient}}"/>
                                             <td>{{$c->coiffure}}</td><input type="hidden" name="type" value="{{$c->coiffure}}"/>
                                            <td>{{$c->points}}</td><input type="hidden" name="points" value="{{$c->points}}">
                                            <td>{{$c->time}}</td>
                                            <td>
                                                @if($c->isPresent != 1)
                                                    <label class="switch">
                                                        <input type="checkbox" name="present" value="true"  onchange="document.getElementById('present'+{{$loop->iteration}}).submit() " >

                                                        <span class="slider"></span>
                                                    </label>
                                                @else
                                                    <button type="button" class="btn btn-success" disabled>
                                                        <i class="fas fa-check-circle font-24"></i>
                                                    </button>
                                                @endif

                                            </td></form>
                                            </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                    @endif
            </div>

            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right sidebar -->
            <!-- ============================================================== -->
            <!-- .right-sidebar -->
            <!-- ============================================================== -->
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
            Developpé par <a href="https://facebook.com/taki729">Taki eddine Seghiri</a>.
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>




<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->

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
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('template/admin/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
<script src="{{asset('template/admin/assets/extra-libs/sparkline/sparkline.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('template/admin/dist/js/waves.js')}}"></script>
<!--Menu sidebar -->
<script src="{{asset('template/admin/dist/js/sidebarmenu.js')}}"></script>
<!--Custom JavaScript -->
<script src="{{asset('template/admin/dist/js/custom.min.js')}}"></script>
<!-- this page js -->
<script src="{{asset('template/admin/assets/extra-libs/multicheck/datatable-checkbox-init.js')}}"></script>
<script src="{{asset('template/admin/assets/extra-libs/multicheck/jquery.multicheck.js')}}"></script>
<script src="{{asset('template/admin/assets/extra-libs/DataTables/datatables.min.js')}}"></script>

<!-- this popup js -->
<script src="{{asset('template/admin/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('template/admin/assets/libs/magnific-popup/meg.init.js')}}"></script>

<script>
    /****************************************
     *       Basic Table                   *
     ****************************************/
    $('#zero_config').DataTable();
</script>

</body>

</html>