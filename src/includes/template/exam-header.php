<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title; ?> - [E]&sup2;valuation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="./assets/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="./assets/css/jquery.countdown.css" rel="stylesheet">
	<style type="text/css">
		#examButtons {     margin-left: auto; margin-right: auto; width:500px; }
		#examButtons #leftsubmit { float: left; }
		#examButtons #rightSubmit { float: right; }
		#examButtons #resetBtn { margin: 0 80px; }
		.highlight { color: red; }
	</style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="./assets/js/html5shiv.js"></script>
    <![endif]-->

   <link rel="shortcut icon" href="./assets/ico/favicon.png">
  </head>
  
  <body>
<div class="navbar navbar-inverse" style="position: static;">
              <div class="navbar-inner">
                <div class="container">
                  <a class="brand" href="#">[E]&sup2;valuation</a>
                    <ul class="nav pull-right">
                      <li><a href="#"><i class="icon-user icon-white"></i>  <?php echo $_SESSION['user']->get_user_fname(); ?></a></li>
                    </ul>
                </div>
              </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
			
  	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span10">
