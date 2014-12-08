<?php

require './includes/common.php';
require './includes/constants.php';
require './includes/db_connect.php';
require './includes/user.php';

$title='Register';
session_start();

include './includes/template/header.php';

$str=''; $show_form=TRUE;

if(isset($_SESSION['user'])) //If logged in
{
	$show_form = FALSE;
	$alert->addBlue('text', 'You are already logged in. Why are you trying to register again?');
}
	
elseif(isset($_POST['submit'])) //If form submitted
{
	$db = db_connect();
	$str=User::register_user($_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['email'], $_REQUEST['pass1'], $_REQUEST['pass2'], $_REQUEST['sex'], $_REQUEST['addr'], $_REQUEST['city'], $_REQUEST['state'], $_REQUEST['pin'], $_REQUEST['pnumber'], $db);
	if($str === TRUE) //Succesfully registered
	{
		$alert->addBlue('classes', 'alert-block');
		$alert->addBlue('heading', 'Account created');
		$alert->addBlue('text', 'Your account has been created. Please check your email for a confirmation link to activate your account.');
		$alert->addBlue('btn', '<a class="btn" href="./">Return to Index</a>');

		$show_form=FALSE;
	}
	else
	{
		$alert->addRed('classes', 'alert-block');		
		$alert->addRed('heading', 'Error');
		$alert->addRed('text', $str);
	}
}

$alert->displayAlert();

if($show_form)
{
?>

<form class="form-register form-horizontal" id="registerForm" method="post" action="./register.php">
<h2 class="form-register-heading">Please register</h2>

<div class="control-group">
	<label class="control-label" for="fname">First name:</label>
	<div class="controls">
		<input type="text" name="fname" placeholder="First name" value="<?php echo (isset($_POST['submit']))?$_REQUEST['fname']:''; ?>" required />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="lname">Last name:</label>
	<div class="controls">
		<input type="text" name="lname" placeholder="Last name" value="<?php echo (isset($_POST['submit']))?$_REQUEST['lname']:''; ?>" required />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="email">Email address:</label>
	<div class="controls">
		<input type="email" name="email" placeholder="Email address" value="<?php echo (isset($_POST['submit']))?$_REQUEST['email']:''; ?>" required />
		<p class="help-block">A confirmation mail will be sent to the email you specify</p>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pass1">Desired password:</label>
	<div class="controls">
		<input type="password" name="pass1" placeholder="Password" value="<?php echo (isset($_POST['submit']))?$_REQUEST['pass1']:''; ?>" required />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pass2">Repeat password:</label>
	<div class="controls">
		<input type="password" name="pass2" placeholder="Password" value="<?php echo (isset($_POST['submit']))?$_REQUEST['pass2']:''; ?>" required />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="radio">Gender:</label>
	<div class="controls">
		<label class="radio inline">
		<input type="radio" name="sex" value="M" <?php if(isset($_POST['submit'])) echo (strcmp($_REQUEST['sex'], 'M') == 0)?'checked':''; else echo 'checked'; ?> /> Male
		</label>
		
		<label class="radio inline">
		<input type="radio" name="sex" value="F" <?php if(isset($_POST['submit'])) echo (strcmp($_REQUEST['sex'], 'F') == 0)?'checked':''; ?> /> Female
		</label>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="addr">Address:</label>
	<div class="controls">
		<textarea name="addr" placeholder="Address"><?php echo (isset($_POST['submit']))?$_REQUEST['addr']:''; ?></textarea>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="city">City:</label>
	<div class="controls">
		<input type="text" name="city" placeholder="City" value="<?php echo (isset($_POST['submit']))?$_REQUEST['city']:''; ?>" required />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="state">State:</label>
	<div class="controls">
<select name="state">
<option <?php echo (isset($_POST['submit']))?'value="'.$_REQUEST['state'].'">'.$_REQUEST['state']:'value="">Select a state...'; ?></option>
<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
<option value="Andhra Pradesh">Andhra Pradesh</option>
<option value="Arunachal Pradesh">Arunachal Pradesh</option>
<option value="Assam">Assam</option>
<option value="Bihar">Bihar</option>
<option value="Chandigarh">Chandigarh</option>
<option value="Chhattisgarh">Chhattisgarh</option>
<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
<option value="Daman and Diu">Daman and Diu</option>
<option value="Delhi">Delhi</option>
<option value="Goa">Goa</option>
<option value="Gujarat">Gujarat</option>
<option value="Haryana">Haryana</option>
<option value="Himachal Pradesh">Himachal Pradesh</option>
<option value="Jammu and Kashmir">Jammu and Kashmir</option>
<option value="Jharkhand">Jharkhand</option>
<option value="Karnataka">Karnataka</option>
<option value="Kerala">Kerala</option>
<option value="Lakshadweep">Lakshadweep</option>
<option value="Madhya Pradesh">Madhya Pradesh</option>
<option value="Maharashtra">Maharashtra</option>
<option value="Manipur">Manipur</option>
<option value="Meghalaya">Meghalaya</option>
<option value="Mizoram">Mizoram</option>
<option value="Nagaland">Nagaland</option>
<option value="Orissa">Orissa</option>
<option value="Pondicherry">Pondicherry</option>
<option value="Punjab">Punjab</option>
<option value="Rajasthan">Rajasthan</option>
<option value="Sikkim">Sikkim</option>
<option value="Tamil Nadu">Tamil Nadu</option>
<option value="Tripura">Tripura</option>
<option value="Uttaranchal">Uttaranchal</option>
<option value="Uttar Pradesh">Uttar Pradesh</option>
<option value="West Bengal">West Bengal</option>
</select>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pin">Pin:</label>
	<div class="controls">
		<input type="text" name="pin" maxlength="6" placeholder="Pin" value="<?php echo (isset($_POST['submit']))?$_REQUEST['pin']:''; ?>" required />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pnumber">Phone number:</label>
	<div class="controls">
		<input type="text" name="pnumber" maxlength="10" placeholder="Phone" value="<?php echo (isset($_POST['submit']))?$_REQUEST['pnumber']:''; ?>" required />
	</div>
</div>

    <div class="form-actions">
    <button type="submit" value="register" name="submit" class="btn btn-primary">Register</button>
    <button type="reset" value="reset" class="btn">Reset</button>
    </div>

</form> 
</div>

<?php } 
include './includes/template/footer.php'; ?>
