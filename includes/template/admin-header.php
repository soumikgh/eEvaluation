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
    <link href="./assets/css/bootstrap-notify.css" rel="stylesheet">
    <link href="./assets/css/bootstrapSwitch.css" rel="stylesheet">
    <style type="text/css">
		body {
			padding-top: 40px;
			padding-bottom: 40px;
		}
		
		#heading {
			padding: 0 20px 20px;
		}
		
		#sidebar .well {
			padding: 4px 4px 0 4px;
		}
		
		#sidenav > li a {
			/* Transition for hovers */
			-webkit-transition: all 0.2s ease;
			-moz-transition: all 0.2s ease;
			-o-transition: all 0.2s ease;
			transition: all 0.2s ease;
		}
		
		#sidenav > li.active a,
		#sidenav > li a:hover {
			margin-left: -10px;
			padding-left: 20px;
		}
		
		#viewResModal {
			width: 900px;
			margin-left: -450px; /* Half of width */
		}
    </style>
    <link href="./assets/css/bootstrap-responsive.min.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="./assets/js/html5shiv.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="./assets/ico/favicon.png">
  </head>

  <body>
    <div class="container">
	<div class="navbar">
    <div class="navbar-inner">
    <a class="brand" href="<?php echo _REMOTE_URL_;?>/">[E]&sup2;valuation</a>
    <ul class="nav">
		<li><a href="<?php echo _REMOTE_URL_;?>/"><i class="icon-home"></i> Home</a></li>
		<?php echo (isset($_SESSION['user'])) ? (($_SESSION['user']->get_user_role() == 'A' || $_SESSION['user']->get_user_role() == 'Q' || $_SESSION['user']->get_user_role() == 'E') ? '<li><a href="' . _REMOTE_URL_ . '/admin.php"><i class="icon-globe"></i> Administration</a></li>' : ''): ''; ?>
	</ul>
	<ul class="nav pull-right">
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> <?php echo (isset($_SESSION['user'])) ? $_SESSION['user']->get_user_fname() : 'Account'; ?>
			<b class="caret"></b>
			</a>
			<ul class="dropdown-menu" role="menu">
				<?php if(!isset($_SESSION['user'])) { ?>
				<li><a href="<?php echo _REMOTE_URL_;?>/login.php">Login</a></li>
				<li><a href="<?php echo _REMOTE_URL_;?>/register.php">Register</a></li>
				<li class="divider"></li>
				<li class="nav-header">Account actions</li>
				<li><a href="<?php echo _REMOTE_URL_;?>/forgotpw.php?mode=passwd">Forgot password</a></li>
				<li><a href="<?php echo _REMOTE_URL_;?>/forgotpw.php?mode=activate">Resend activation email</a></li>
				<?php } else { ?>				
				<li><a href="<?php echo _REMOTE_URL_;?>/profile.php"><i class="icon-pencil"></i> Edit profile</a></li>
				<li class="divider"></li>
				<li><a href="<?php echo _REMOTE_URL_;?>/login.php?logout"><i class="icon-off"></i> Log Out</a></li>
				<?php } ?>
			</ul>
		</li>
	</ul>
    </div>
    </div>
