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
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="email"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

      .form-register {
        max-width: 600px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      
      .form-register .form-register-heading,
      .form-register .checkbox {
        margin-bottom: 10px;
      }
      .form-register input,
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
      
      .dashboard {
        max-width: 900px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }

    </style>
    <link href="./assets/css/bootstrap-responsive.css" rel="stylesheet">

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
		<?php echo (isset($_SESSION['user'])) ? (($_SESSION['user']->get_user_role() != 'U') ? '<li><a href="' . _REMOTE_URL_ . '/admin.php"><i class="icon-globe"></i> Administration</a></li>' : ''): ''; ?>
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
