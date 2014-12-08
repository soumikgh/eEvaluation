<?php

require './includes/constants.php';
require './includes/user.php';
$show_form1=TRUE; $show_form2=FALSE;
$red=$green=$yellow='';
$title = ($_REQUEST['mode'] == 'activate') ? 'Activate user account' : 'Reset password';

session_start();

if(!isset($_SESSION['user'])) //If not logged in
{
	require './includes/db_connect.php';
	if(isset($_REQUEST['email'])) //If email was sent
	{
		$email=stripslashes($_REQUEST['email']);
		// validate e-mail address
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$red .= "Invalid email address";
		}
		else
		{ 
			$db = db_connect();
			if(!is_int($user_id = User::validate_login($email, $db))) //Email not in database
			{
				$red .= $user_id;
			}
			else
			{
				switch($_REQUEST['mode'])
				{
					case 'activate':	$title = "Re-send account activation email";
										$tmp=User::send_activation_email($email, $db);
										if($tmp === TRUE)
										{
											$yellow = "Activation email has been sent";
										}
										else $yellow = $tmp;
										break;
					case 'passwd': $title="Forgot password";
										$tmp=User::send_passreset_email($email, $db);
										if($tmp === TRUE)
										{
											$yellow = "An email has been sent to you with instructions on how to set a new password";
										}
										else $yellow = $tmp;
										break;
				}
			}
		}
	}
	elseif(isset($_REQUEST['uid']))
	{
		switch($_REQUEST['mode'])
		{
			case 'activate':$title = 'User activation';
							$db = db_connect();
							$tmp=User::activate_user($_REQUEST['uid'], $db);
							if($tmp === TRUE)
							{
								$green = "User account activated";
								$show_form1=FALSE;
							}
							else $yellow = $tmp;
							break;
			case 'passwd':$title = 'Password Reset';
							$show_form2 = TRUE;
							$show_form1 = FALSE;
							$db = db_connect();
							if(!is_int($tmp=User::check_user($_REQUEST['uid'], $db))) //If UID is not valid
							{
								$red .= $tmp;
								$show_form2 = FALSE;
							}
							else
							{
								if(isset($_POST['pass1'])) //If pass has been already given
								{
									if($_POST['pass1'] !== $_POST['pass2'])
									{
										$yellow .= "Passwords do not match";
									}
									else
									{
										if(User::update_password($_POST['pass1'], base64_decode($_REQUEST['uid']), $db) === TRUE)
										{
											$green .= "Password has been updated";
											$show_form2 = FALSE;
										}
									}
								}
							}
		}
	}

	require './includes/template/header.php';

	if(!empty($red))
	{
		echo "<div class='alert alert-error fade in'><a class='close' data-dismiss='alert'>&times;</a><strong>Error: </strong><p>{$red}</p></div>";
	}
	if(!empty($green))
	{
		echo "<div class='alert alert-success fade in'><a class='close' data-dismiss='alert'>&times;</a><strong>Success! </strong><p>{$green}</p><p><a class='btn btn-info' href='./login.php'>Log in</a>  <a class='btn' href='./'>Return to Index</a></p></div>";
	}
	if(!empty($yellow))
	{
		echo "<div class='alert alert-block fade in'><a class='close' data-dismiss='alert'>&times;</a>{$yellow}</div>";
	}
	if($show_form1)
	{
?>
      <form class="form-signin" method="post" action="./forgotpw.php?mode=<?php echo $_REQUEST['mode']; ?>">
        <h4 class="form-signin-heading">Please enter your email address</h2>
        <input type="email" class="input-block-level" placeholder="Email address" name="email" id="email" value="<?php echo (isset($_REQUEST['email']))?$_REQUEST['email']:''; ?>" />
        <button class="btn btn-large btn-primary" type="submit">Submit</button>
      </form>
<?php
	}
	elseif($show_form2)
	{
?>
		<form class="form-signin" method="post" action="./forgotpw.php?mode=<?php echo $_REQUEST['mode']; ?>&amp;uid=<?php echo urlencode($_REQUEST['uid']); ?>">
        <h4 class="form-signin-heading">Please enter your new password</h2>
        <input type="password" class="input-block-level" placeholder="Password" name="pass1" id="pass1" value="<?php echo (isset($_REQUEST['pass1']))?$_REQUEST['pass1']:''; ?>" required />
        <input type="password" class="input-block-level" placeholder="Password" name="pass2" id="pass2" value="<?php echo (isset($_REQUEST['pass2']))?$_REQUEST['pass2']:''; ?>" required />
        <button class="btn btn-large btn-primary" type="submit">Submit</button>
		</form>
<?php
	}
}

else
{
	require './includes/template/header.php';
	echo "<div class='alert alert-info'>You are already logged in. You can change your password from your <a href=\"./profile.php\">profile page</a>.</div>";
}
require './includes/template/footer.php';
?>
