<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ LAConfigs::getByKey('site_description') }}">
    <meta name="author" content="">

    <!-- <meta property="og:title" content="{{ LAConfigs::getByKey('sitename') }}" /> -->
    <!-- <meta property="og:type" content="website" /> -->
    <!-- <meta property="og:description" content="{{ LAConfigs::getByKey('site_description') }}" /> -->
    
    <!-- <meta property="og:url" content="http://laraadmin.com/" /> -->
    <!-- <meta property="og:sitename" content="laraAdmin" /> -->
	<!-- <meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" /> -->
    
    <!-- <meta name="twitter:card" content="summary_large_image" /> -->
    <!-- <meta name="twitter:site" content="@laraadmin" /> -->
    <!-- <meta name="twitter:creator" content="@laraadmin" /> -->

    <title>{{ LAConfigs::getByKey('sitename') }}</title>    
    
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/la-assets/css/bootstrap.css') }}" rel="stylesheet">

	<link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Custom styles for this template -->
    <link href="{{ asset('/la-assets/css/main.css') }}" rel="stylesheet">

    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
	<!-- Import Font Roboto -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap" rel="stylesheet">

    <script src="{{ asset('/la-assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('/la-assets/js/smoothscroll.js') }}"></script>


</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation" style="background-color:#34495e">

<!-- Fixed navbar -->
<div id="navigation" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><b>{{ LAConfigs::getByKey('sitename') }}</b></a>
            <!-- <a class="navbar-brand" href="#" style="font-family: 'Roboto', sans-serif;"><b>Public Sector Recruitment Information System</b></a> -->
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/admin') }}">Login</a></li>
                    <!-- <li><a href="{{ url('/register') }}">Register</a></li> -->
                @else
                    <!-- <li><a href="{{ url(config('laraadmin.adminRoute')) }}" style="font-family: 'Roboto', sans-serif; font-weight:300;">{{ Auth::user()->name }}</a></li> -->
                @endif
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<section style="background-color: #34495e;">
<div class="header-image-wrapper" style="display: block;margin:20px 30%  0 30%;background-color: #34495e;">
	<img class="hidden-xs hidden-sm hidden-md" src="{{ asset('/la-assets/img/header.png') }}">
</div>
</section>
<section id="home" name="home"></section>
<div id="headerwrap">
    <div class="container">
        <div class="row centered">
            <div class="col-lg-12">
                <h1>{{ LAConfigs::getByKey('sitename_part1') }} <b><a>{{ LAConfigs::getByKey('sitename_part2') }}</a></b></h1>
                <h3>{{ LAConfigs::getByKey('site_description') }}</h3>
                <h3><a href="{{ url('/admin') }}" class="btn btn-lg btn-success">Get Started!</a></h3><br>
                <!-- <h3 style="font-family: 'Roboto', sans-serif;">Public Sector Recruitment Information System</a></b></h3> -->
                <!-- <p style="text-align:center; font-family: 'Roboto', sans-serif;">This Applicated is designed to collect details of Governemnt positions up to Year 2021 </p> -->
                <!-- <h3><a href="{{ url('/login') }}" style="font-family: 'Roboto', sans-serif;" class="btn btn-lg btn-success">Get Started!</a></h3><br> -->
            </div>

        </div>
    </div> <!--/ .container -->
</div><!--/ #headerwrap -->



<div id="c">
    <div class="container">
        <p>
            <strong>Copyright &copy; <?php echo date('Y');?>  &nbsp;&nbsp;&nbsp;Powered by  <a href="https://www.pubad.gov.lk/web/index.php?lang=en"><b>IT Division - Ministry of Public Services, Provincial Councils and Local Government</b></a>
        </p>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset('/la-assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script>
    $('.carousel').carousel({
        interval: 3500
    })
</script>
</body>
</html>
