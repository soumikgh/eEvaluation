<?php

require './includes/common.php';
require './includes/constants.php';
require './includes/db_connect.php';
require './includes/user.php';

$title='Change account details';
session_start();

include './includes/template/header.php';
if(isset($_SESSION["user"]))
{
	$alert->addYellow('text', '<strong>Info:</strong> You cannot change your name and email address. If you need to change them, contact an administrator.');
	$alert->addYellow('close', 'false');

	if(isset($_REQUEST["submit"])) //If form submitted
	{
		$db = db_connect();
		$str = $_SESSION['user']->update_user_details($_REQUEST['email'], $_REQUEST['role'], $_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['pass'], $_REQUEST['sex'], $_REQUEST['addr'], $_REQUEST['city'], $_REQUEST['state'], $_REQUEST['pin'], $_REQUEST['pnumber'], $db);
		if($str !== TRUE) //Details not succesfully updated
		{
			$alert->addRed('heading', 'Error');
			$alert->addRed('text', $str);
		}
		else
		{
			$alert->addBlue('text', 'User details updated successfully');
			$_SESSION['user'] = new User($_SESSION['user']->get_user_id(), $db);
		}
	}
		
	$info = $_SESSION['user']->get_user_details();
	$alert->displayAlert();

?>

<form class="form-register form-horizontal" id="registerForm" method="post" action="./profile.php">
<h3>Edit your profile</h3>

<div class="control-group">
	<label class="control-label" for="fname">First name:</label>
	<div class="controls">
		<input type="text" name="fname" class="uneditable-input" placeholder="First name" value="<?php echo $info['user_fname']; ?>" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="lname">Last name:</label>
	<div class="controls">
		<input type="text" name="lname" class="uneditable-input" placeholder="Last name" value="<?php echo $info['user_lname']; ?>" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="email">Email address:</label>
	<div class="controls">
		<input type="text" name="email" class="uneditable-input" placeholder="Email address" value="<?php echo $info['user_email']; ?>" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pass">Password:</label>
	<div class="controls">
		<input type="password" name="pass" placeholder="Click to change password" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="avatar">Avatar:</label>
	<div class="controls">
		<a href="http://gravatar.com"><img src="<?php echo $_SESSION['user']->get_gravatar_url(80); ?>" class="img-polaroid" /></a>
		<div class="help-block" style="margin-top:5px;">Get your own Gravatar at <a href="http://gravatar.com">Gravatar.com</a></div>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="radio">Gender:</label>
	<div class="controls">
		<label class="radio inline">
		<input type="radio" name="sex" value="M" <?php echo (strcmp($info['user_sex'], 'M') == 0)?'checked':''; ?> /> Male
		</label>
		
		<label class="radio inline">
		<input type="radio" name="sex" value="F" <?php echo (strcmp($info['user_sex'], 'F') == 0)?'checked':''; ?> /> Female
		</label>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="addr">Address:</label>
	<div class="controls">
		<textarea name="addr" placeholder="Address"><?php echo $info['user_addr']; ?></textarea>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="city">City:</label>
	<div class="controls">
		<input type="text" name="city" placeholder="City" value="<?php echo $info['user_city']; ?>" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="state">State:</label>
	<div class="controls">
<select name="state">
<option value=<?php echo '"' . $info['user_state'].'">'. $info['user_state']; ?></option>
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
		<input type="text" name="pin" maxlength="6" placeholder="Pin" value="<?php echo $info['user_pin']; ?>" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pnumber">Phone number:</label>
	<div class="controls">
		<input type="text" name="pnumber" maxlength="10" placeholder="Phone" value="<?php echo $info['user_pnumber']; ?>" />
	</div>
</div>

<input type="hidden" name="role" value="<?php echo $info['user_role']; ?>" />

    <div class="form-actions">
    <button type="submit" value="update" name="submit" class="btn btn-primary">Update</button>
    <button type="reset" value="reset" class="btn">Reset</button>
    </div>

</form> 


<?php
}
else
{
	$alert->addRed('text', '<strong>Error:</strong> You are not logged in. You need to be logged in to change your profile.');
	$alert->displayAlert();
}
include './includes/template/footer.php'; ?>
