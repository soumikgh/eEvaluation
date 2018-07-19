<?php

require './includes/common.php';
require './includes/constants.php';
require './includes/db_connect.php';
require './includes/user.php';

$title = "Log in";
session_start();
$show_form=TRUE;

$out='';
if(!isset($_SESSION['user']) && isset($_REQUEST['email'])) //Not logged in and form submitted
{
	$db = db_connect();
	$valid = User::validate_login($_REQUEST['email'], $db, $_REQUEST['pass']);
	if(!is_int($valid)) //If login not valid
	{
		$alert->addRed('text', $valid);
	}
	else
	{
		$user = new User($valid, $db);
		$_SESSION['user'] = $user;

		//Redirect to admin page if admin
		if($_SESSION['user']->get_user_role() == 'A' || $_SESSION['user']->get_user_role() == 'Q' || $_SESSION['user']->get_user_role() == 'E')
		{
			header("Location: " . _REMOTE_URL_ . "/admin.php?login");
			die();
		}
		
		//Redirect to index page if user
		header("Location: " . _REMOTE_URL_ . "/?login");
		die();
	}
}

elseif(isset($_SESSION['user'])) //If logged in
{
	if(isset($_REQUEST['logout'])) //If Logout
	{
		if(isset($_SESSION["has_started_exam"])) //If logout during exam
		{
			die("You cannot log out now that you have started the exam. Go to the <a href='./exam.php?qno=1'>exam page</a>.");
		}
		session_unset();
		session_destroy();
		$alert->addYellow('text', 'You have been logged out successfully');
	}
	else
	{
		$show_form=FALSE;
		$alert->addBlue('text', 'You are already logged in. Login<sup>2</sup> cannot be calculated on this system. ;-)');
	}
}

require './includes/template/header.php';
$alert->displayAlert();

if($show_form)
{ ?>

	<form class="form-signin" name="loginForm" method="post" action="./login.php">
		<h2 class="form-signin-heading">Please sign in</h2>
		<input type="email" class="input-block-level" placeholder="Email" name="email" id="email" value="<?php echo (isset($_REQUEST['email']))?$_REQUEST['email']:''; ?>" />
		<input type="password" class="input-block-level" placeholder="Password" name="pass" id="pass" />
		<button class="btn btn-large btn-primary" type="submit">Sign in</button>
		<div class="btn-group">
			<a class="btn dropdown-toggle btn-large" data-toggle="dropdown" href="#">
				Other options
				<span class="caret"></span>
			</a>
				<ul class="dropdown-menu">
				<li><a href="./forgotpw.php?mode=passwd">Forgot your password?</a></li>
				<li><a href="./forgotpw.php?mode=activate">Resend activation email</a></li>
				</ul>
		</div>
	</form>

<?php
}
require './includes/template/footer.php';
?>
