<?php

require './includes/common.php';
require './includes/constants.php';
require './includes/db_connect.php';
require './includes/user.php';

$title = "Home";
session_start();

require './includes/template/header.php';
if(isset($_REQUEST['login']))
{
	$alert->addBlue('text', 'You have been successfully logged in.');
	$alert->displayAlert();
}
if(isset($_SESSION['user']))
{
?>

<h3 style="margin-left:30px;">Welcome, <?php echo $_SESSION['user']->get_user_fname() . " " . $_SESSION['user']->get_user_lname(); ?></h3>

<div id="dashboard">
<div class="row-fluid">
	<ul class="thumbnails">
	  <li class="span4">
		<div class="thumbnail">
<?php if($_SESSION['user']->get_user_role() == 'A' || $_SESSION['user']->get_user_role() == 'Q' || $_SESSION['user']->get_user_role() == 'E') { ?>
		  <img src="assets/images/admin.png" alt="">
		  <div class="caption">
			<h3>Administration Panel</h3>
			<p>Administer users, add, edit or remove questions, manage exam settings and results from the adminisration panel.<br /><br /><br /></p>
			<p style="margin-bottom: 20px;"><a href="./admin.php" class="btn btn-primary">Go to Administration Panel &raquo;</a></p>
<?php } else { ?>
		  <img src="assets/images/exam.jpg" alt="">
		  <div class="caption">
<?php if($_SESSION['user']->get_exam_valid()) { ?>
			<h3>Results</h3>
			<p>Includes detailed description of each question and whether you got it right, wrong or skipped it altogether.<br /><br /><br /></p>
				<form method="post" action="./user_res.php">
					<input type="hidden" name="user_id" value="<?php echo $_SESSION['user']->get_user_id(); ?>" />
					<button type="submit" value="start_exam" name="submit" class="btn btn-primary">View results &raquo;</button>
				</form>
<?php } else { ?>				
			<h3>Exam</h3>
			<p>When the administrator has given the go and you, hopefully, have a calm state of mind, click on the button below to start the exam. Make sure that the exam administrator has given the green light for starting the exam.</p>
			<p>
				<form method="post" action="./exam.php?qno=1">
					<input type="hidden" name="start" value="" />
					<button type="submit" value="start_exam" name="submit" class="btn btn-primary">Start exam &raquo;</button>
				</form>
<?php }
} ?>
			</p>
		  </div>
		</div>
	  </li>
	  <li class="span4">
		<div class="thumbnail">
		  <img src="assets/images/profile.jpg" alt="">
		  <div class="caption">
			<h3>Profile</h3>
			<p>Edit and update your personal details, preferences, avatar any time before the start of the examination. Please be accurate in entering your personal details, especially your address and phone number, or we'll not be able to contact you.</p>
			<p style="margin-bottom: 20px;"><a href="./profile.php" class="btn btn-primary">Edit profile</a></p>
		  </div>
		</div>
	  </li>
	  <li class="span4">
		<div class="thumbnail">
		  <img src="assets/images/help.jpg" alt="">
		  <div class="caption">
			<h3>Help and Questions</h3>
			<p>Need help? Stuck on something? Or have some questions? We have prepared a handy list of frequently asked questions (FAQ) to help you get through your issues. Alternately, you can also contact us for a more personalised assistance.</p>
			<p style="margin-bottom: 20px;"><a href="#" class="btn btn-primary">Get help</a></p>
		  </div>
		</div>
	  </li>
	</ul>
</div>
</div>

<?php } else { ?>
<div class="hero-unit">
	<h1>[E]&sup2;valuation</h1>
	<p>Sleek, elegant and intuitive system designed to take care of all your online evaluation needs. Lovingly crafted using PHP, MySQL and the Bootstrap framework.</p>
	<p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p>
</div>

<div class="row-fluid">
<ul class="thumbnails">
  <li class="span4">
	<div class="thumbnail">
	  <!--<img data-src="./assets/images/index.png" alt=""> -->
	  <div class="caption">
		<h3>Register</h3>
		<p>Register for an account that allows you to save your personal information on the system. You can create an account any time, although presumably before the start of the examination. ;)</p>
		<p><a href="./register.php" class="btn btn-inverse">Register</a></p>
	  </div>
	</div>
  </li>
  <li class="span4">
	<div class="thumbnail">
	  <!--<img data-src="./assets/images/index.png" alt=""> -->
	  <div class="caption">
		<h3>Log in</h3>
		<p>Already have an account? What are you waiting for? Login to get more famiiar with the system and edit personal information. Password reset and resending of activation emails can also be done.</p>
		<div style="margin-bottom:10px;">
			<a href="./login.php" class="btn btn-inverse">Login</a> 
			<div class="btn-group">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					Other options
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="./forgotpw.php?mode=passwd">Forgot your password?</a></li>
					<li><a href="./forgotpw.php?mode=activate">Resend activation email</a></li>
				</ul>
			</div>
		</div>
	  </div>
	</div>
  </li>
  <li class="span4">
	<div class="thumbnail">
	  <!--<img data-src="./assets/images/index.png" alt=""> -->
	  <div class="caption">
		<h3>Learn more</h3>
		<p>Want to learn more about [E]<sup>2</sup>valuation? <br />Want to know about the technologies that were used in the creation of this system? <br />Click the button below to get started.</p>
		<p><a href="#" class="btn btn-inverse">Learn more</a></p>
	  </div>
	</div>
  </li>
</ul>
</div>

<?php }
require './includes/template/footer.php';
?>
