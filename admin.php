<?php

require './includes/common.php';
require './includes/constants.php';
require './includes/db_connect.php';
require './includes/user.php';
require './includes/q.php';

$title = "Administration";
session_start();

if(!isset($_SESSION['user'])) // If not logged in
{
	die('You are not allowed here');
}
elseif(($_SESSION['user']->get_user_role() != 'U')) //If admin
{
	require './includes/template/admin-header.php';


?>
<div class="row">
	<div class="span12">
		<h2 id="heading">Administration Panel <small>to manage all components of the system</small></h2>
		<div id="test"></div>
    </div>
</div>
<div class="row">
	<div id="sidebar" class="span2">
		<div class="well">
			<ul id="sidenav" class="nav nav-pills nav-stacked">
				<li class="nav-header">Navigation</li>
				<?php if($_SESSION['user']->get_user_role() == 'A') echo '<li class="active"><a href="#usermin" data-toggle="tab"><i class="icon-user"></i> Manage users</a></li>';
					if($_SESSION['user']->get_user_role() == 'A' || $_SESSION['user']->get_user_role() == 'Q') echo '<li><a href="#quesmin" data-toggle="tab"><i class="icon-question-sign"></i> Manage questions</a></li>';
					if($_SESSION['user']->get_user_role() == 'A' || $_SESSION['user']->get_user_role() == 'E') echo '<li><a href="#exammin" data-toggle="tab" id="manageExamNav"><i class="icon-book"></i> Manage exam</a></li>';
					if($_SESSION['user']->get_user_role() == 'A') echo '<li><a href="#resmin" data-toggle="tab"><i class="icon-th-list"></i> Manage results</a></li>'; ?>
			</ul>
		</div>
	</div>
	<div class="span10">
		<div class="tab-content">
			<div class="tab-pane fade in active" id="usermin">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#useredit" data-toggle="tab"><i class="icon-pencil"></i> Edit user</a></li>
						<li><a href="#useradd" data-toggle="tab"><i class="icon-plus-sign"></i> Add a new user</a></li>
					</ul>					
			
					<div class="tab-content">
						<div class="tab-pane fade in active" id="useredit">
							<form id="usersearchform" class="form-horizontal" method="post" action="">
								<div class="control-group">
									<label class="control-label" for="fname">First name</label>
									<div class="controls">
										<input type="text" name="fname" id="fname" placeholder="First name">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="lname">Last name</label>
									<div class="controls">
										<input type="text" name="lname" id="lname" placeholder="Last name">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="email">Email</label>
									<div class="controls">
										<input type="text" name="email" id="email" placeholder="Email">
									</div>
								</div>
								<input type="hidden" name="mode" value="usersearch" />
								<div class="control-group">
									<div class="controls">
										<button type="submit" name="submit" id="usersearchbtn" class="btn btn-primary"><i class="icon-search icon-white"></i> Search</button>
									</div>
								</div>
							</form>
							<div id="usersearchresults"></div>
							<div id="ueditModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit user details</h3>
								</div>
								<div class="modal-body">
<form class="form-horizontal" id="ueditForm" method="post" action="">

<div class="control-group">
	<label class="control-label" for="fname">First name:</label>
	<div class="controls">
		<input type="text" name="fname" placeholder="First name" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="lname">Last name:</label>
	<div class="controls">
		<input type="text" name="lname" placeholder="Last name" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="email">Email address:</label>
	<div class="controls">
		<input type="email" name="email" placeholder="Email address" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pass">Password:</label>
	<div class="controls">
		<input type="password" name="pass" placeholder="Password" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="state">Role:</label>
	<div class="controls">
	<select name="role">
		<option value="A">Super administrator</option>
		<option value="Q">Question administrator</option>
		<option value="E">Exam invigilator</option>
		<option value="U">Examinee</option>
	</select>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="radio">Account activated:</label>
	<div class="controls">
		<label class="radio inline">
		<input type="radio" name="status" value="A" /> Yes
		</label>
		
		<label class="radio inline">
		<input type="radio" name="status" value="I" /> No
		</label>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="state">Has completed exam:</label>
	<div class="controls">
	<select name="allow">
		<option value="1">No</option>
		<option value="0">Yes</option>
	</select>
	<span class="help-block">This value is automatically set by the system to prevent users from re-taking the exam.<br />You can, however, manually set this value to "Yes" if you want to ban a user from taking the exam.</span>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="radio">Gender:</label>
	<div class="controls">
		<label class="radio inline">
		<input type="radio" name="sex" value="M" /> Male
		</label>
		
		<label class="radio inline">
		<input type="radio" name="sex" value="F" /> Female
		</label>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="addr">Address:</label>
	<div class="controls">
		<textarea name="addr" placeholder="Address"></textarea>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="city">City:</label>
	<div class="controls">
		<input type="text" name="city" placeholder="City" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="state">State:</label>
	<div class="controls">
<select name="state">
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
		<input type="text" name="pin" maxlength="6" placeholder="Pin" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pnumber">Phone number:</label>
	<div class="controls">
		<input type="text" name="pnumber" maxlength="10" placeholder="Phone" />
	</div>
</div>
<input type="hidden" name="id" />
<input type="hidden" name="mode" value="userupdate" />
								</div>
								<div class="modal-footer">
									<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
									<button class="btn btn-primary" id="ueditBtn" type="submit" value="edit" name="submit">Save changes</button>
								</div>
								</form>
							</div>
							<div id="udelModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Delete user</h3>
								</div>
								<div class="modal-body">
									<p>Are you sure you want to delete the user?</p>
								</div>
								<div class="modal-footer">
									<form id="udelform" method="post" action="">
										<input type="hidden" name="user_id" />
										<input type="hidden" name="mode" value="deluser" />
										<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<button class="btn btn-danger" id="udelBtn" type="submit" value="delete" name="submit"><i class="icon-trash icon-white"></i> Delete</button>
									</form>
								</div>
							</div>
							<div id="ubanModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Ban user</h3>
								</div>
								<div class="modal-body">
									<form class="form-horizontal" id="ubanform" method="post" action="">
										<div class="control-group">
											<label class="control-label" for="allow">Ban status:</label>
											<div class="controls">
											<select name="user_allow">
												<option value="1">Not banned</option>
												<option value="0">Banned</option>
											</select>
											<span class="help-block">The user is automatically banned from giving the exam after he has finished giving the exam once.</span>
											</div>
										</div>
										<input type="hidden" name="mode" value="userban" />
										<input type="hidden" name="user_id" />
									</form>
								</div>
								<div class="modal-footer">
									<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
									<button class="btn btn-primary" id="ubanBtn" type="submit" value="delete" name="submit">Submit</button>									
								</div>
							</div>
							<div id="umakeadmModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">View results</h3>
								</div>
								<div class="modal-body">
									<form class="form-horizontal" id="umakeadmform" method="post" action="">
										<div class="control-group">
											<label class="control-label" for="state">Role:</label>
											<div class="controls">
											<select name="user_role">
												<option value="A">Super administrator</option>
												<option value="Q">Question administrator</option>
												<option value="E">Exam invigilator</option>
												<option value="U">Examinee</option>
											</select>
											</div>
										</div>
										<input type="hidden" name="mode" value="userrole" />
										<input type="hidden" name="user_id" />
									</form>
								</div>
								<div class="modal-footer">
									<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
									<button class="btn btn-primary" id="umakeadmBtn" type="submit" value="delete" name="submit">Submit</button>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="useradd">
							<h4>Add a new user</h4>
<form class="form-horizontal" id="uaddForm" method="post" action="">

<div class="control-group">
	<label class="control-label" for="fname">First name:</label>
	<div class="controls">
		<input type="text" name="fname" placeholder="First name" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="lname">Last name:</label>
	<div class="controls">
		<input type="text" name="lname" placeholder="Last name" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="email">Email address:</label>
	<div class="controls">
		<input type="email" name="email" placeholder="Email address" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pass">Password:</label>
	<div class="controls">
		<input type="password" name="pass" placeholder="Password" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="state">Role:</label>
	<div class="controls">
	<select name="role" >
		<option value="U">Examinee</option>
		<option value="A">Super administrator</option>
		<option value="Q">Question administrator</option>
		<option value="E">Exam invigilator</option>
	</select>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="radio">Account activated:</label>
	<div class="controls">
		<label class="radio inline">
		<input type="radio" name="status" value="A" /> Yes
		</label>
		
		<label class="radio inline">
		<input type="radio" name="status" value="I" checked /> No
		</label>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="state">Has completed exam:</label>
	<div class="controls">
	<select name="allow">
		<option value="1">No</option>
		<option value="0">Yes</option>
	</select>
	<span class="help-block">This value is automatically set by the system to prevent users from re-taking the exam.<br />You can, however, manually set this value to "Yes" if you want to ban a user from taking the exam.</span>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="radio">Gender:</label>
	<div class="controls">
		<label class="radio inline">
		<input type="radio" name="sex" value="M" checked /> Male
		</label>
		
		<label class="radio inline">
		<input type="radio" name="sex" value="F" /> Female
		</label>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="addr">Address:</label>
	<div class="controls">
		<textarea name="addr" placeholder="Address"></textarea>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="city">City:</label>
	<div class="controls">
		<input type="text" name="city" placeholder="City" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="state">State:</label>
	<div class="controls">
<select name="state">
<option value="">Select a state...</option>
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
		<input type="text" name="pin" maxlength="6" placeholder="Pin" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="pnumber">Phone number:</label>
	<div class="controls">
		<input type="text" name="pnumber" maxlength="10" placeholder="Phone" />
	</div>
</div>

<input type="hidden" name="mode" value="useradd" />

<div class="form-actions">
	<button type="submit" value="add" name="submit" class="btn btn-primary">Add</button>
	<button type="reset" value="reset" class="btn">Reset</button>
</div>						
</form>						
						</div>	
					</div>
				</div>		
			</div>

			<div class="tab-pane fade" id="quesmin">
				<div class="tabbable">				
					<ul id="quesTabs" class="nav nav-tabs">
						<li class="active"><a href="#quesadd" id="quesAddTab" data-toggle="tab"><i class="icon-plus-sign"></i> Add a question</a></li>
						<li><a href="#quesedit" data-toggle="tab"><i class="icon-pencil"></i> Edit a question</a></li>
						<li><a href="#quesdel" data-toggle="tab"><i class="icon-trash"></i> Delete a question</a></li>
					</ul>		
									
					<div class="tab-content">
						<div class="tab-pane fade in active" id="quesadd">
							<h4>Add a question</h4>
<form class="form-horizontal" id="qaddForm" method="post" action="">
	<div class="control-group">
		<label class="control-label" for="q_body">Question body:</label>
		<div class="controls">
			<textarea name="q_body" rows="5" class="span3" placeholder="Question body"></textarea>
		</div>
	</div>							

	<div class="control-group">
		<label class="control-label" for="q_ans1">Answer 1:</label>
		<div class="controls">
			<input type="text" name="q_ans1" class="span3" placeholder="First answer" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="q_ans2">Answer 2:</label>
		<div class="controls">
			<input type="text" name="q_ans2" class="span3" placeholder="Second answer" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="q_ans3">Answer 3:</label>
		<div class="controls">
			<input type="text" name="q_ans3" class="span3" placeholder="Third answer" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="q_ans4">Answer 4:</label>
		<div class="controls">
			<input type="text" name="q_ans4" class="span3" placeholder="Fourth answer" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="q_correct_ans">Correct answer:</label>
		<div class="controls">
			<select name="q_correct_ans" class="span3">
				<option value="1">Option 1</option>
				<option value="2">Option 2</option>
				<option value="3">Option 3</option>
				<option value="4">Option 4</option>
			</select>
		</div>
	</div>			
	
	<div class="control-group">
		<label class="control-label" for="radio">Allowed in exam:</label>
		<div class="controls">
			<label class="radio inline">
				<input type="radio" name="q_allow" value="1" checked /> Yes
			</label>
		
			<label class="radio inline">
				<input type="radio" name="q_allow" value="0" /> No
			</label>
		</div>
	</div>
	
	<input type="hidden" name="mode" value="qadd" />
	
	<div class="form-actions">
		<button type="submit" value="add" name="submit" class="btn btn-primary">Add</button>
		<button type="reset" value="reset" class="btn">Reset</button>
	</div>
</form>
						</div>
						<div class="tab-pane fade" id="quesedit">
							<h4>Edit a question</h4>
							<form id="qeditForm" class="form-horizontal" method="post" action="">
								<div class="control-group">
									<label class="control-label" for="q_body">Question</label>
									<div class="controls">
										<input type="text" name="q_body" class="span3" placeholder="Enter full or a part of the question...">
									</div>
								</div>
								<input type="hidden" name="mode" value="qeditsearch" />
								<div class="control-group">
									<div class="controls">
										<button type="submit" name="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> Search</button>
									</div>
								</div>
							</form>
							<div id="qeditresults"></div>
<div id="qeditModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Edit question</h3>
</div>
<div class="modal-body">
<form class="form-horizontal" id="qeditdisplayForm" method="post" action="">
	<div class="control-group">
		<label class="control-label" for="q_body">Question body:</label>
		<div class="controls">
			<textarea name="q_body" rows="5" class="span3" placeholder="Question body"></textarea>
		</div>
	</div>							

	<div class="control-group">
		<label class="control-label" for="q_ans1">Answer 1:</label>
		<div class="controls">
			<input type="text" name="q_ans1" class="span3" placeholder="First answer" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="q_ans2">Answer 2:</label>
		<div class="controls">
			<input type="text" name="q_ans2" class="span3" placeholder="Second answer" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="q_ans3">Answer 3:</label>
		<div class="controls">
			<input type="text" name="q_ans3" class="span3" placeholder="Third answer" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="q_ans4">Answer 4:</label>
		<div class="controls">
			<input type="text" name="q_ans4" class="span3" placeholder="Fourth answer" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="q_correct_ans">Correct answer:</label>
		<div class="controls">
			<select name="q_correct_ans" class="span3">
				<option value="1">Option 1</option>
				<option value="2">Option 2</option>
				<option value="3">Option 3</option>
				<option value="4">Option 4</option>
			</select>
		</div>
	</div>			
	
	<div class="control-group">
		<label class="control-label" for="radio">Allowed in exam:</label>
		<div class="controls">
			<label class="radio inline">
				<input type="radio" name="q_allow" value="1" /> Yes
			</label>
		
			<label class="radio inline">
				<input type="radio" name="q_allow" value="0" /> No
			</label>
		</div>
	</div>
	
	<input type="hidden" name="mode" value="qupdate" />
	<input type="hidden" name="q_id" />

</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<button type="submit" value="edit" name="submit" class="btn btn-primary">Save changes</button>
</form>
</div>
</div>
						</div>
						<div class="tab-pane fade" id="quesdel">
							<h4>Delete a question</h4>
							<form id="qdelForm" class="form-horizontal" method="post" action="">
								<div class="control-group">
									<label class="control-label" for="q_body">Question</label>
									<div class="controls">
										<input type="text" name="q_body" class="span3" placeholder="Enter full or a part of the question...">
									</div>
								</div>
								<input type="hidden" name="mode" value="qdelsearch" />
								<div class="control-group">
									<div class="controls">
										<button type="submit" name="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> Search</button>
									</div>
								</div>
							</form>
							<div id="qdelresults"></div>
<div id="qdelModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Delete a question</h3>
</div>
<div class="modal-body">
<p>Are you sure you want to delete this question?</p>
</div>
<div class="modal-footer">
	<form id="qdeldisplayForm" method="post" action="">
		<input type="hidden" name="q_id" />
		<input type="hidden" name="mode" value="qdel" />
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
		<button type="submit" name="submit" class="btn btn-primary"><i class="icon-trash icon-white"></i> Delete</button>
	</form>
</div>
</div>
						</div>
					</div>
				</div>
			</div>

			<div class="tab-pane fade" id="exammin">
				<div class="tabbable">					
					<ul id="examTabs" class="nav nav-tabs">
						<li class="active"><a href="#examedit" data-toggle="tab" id="editExamTab"><i class="icon-cog"></i> Exam settings</a></li>
						<li><a href="#examstart" data-toggle="tab"><i class="icon-play"></i> Start exam</a></li>
					</ul>			
									
					<div class="tab-content">	
						<div class="tab-pane fade in active" id="examedit">
							<h4>Edit exam details</h4>
<form id="examdetailsform" class="form-horizontal" method="post" action="">
	<div class="control-group">
		<label class="control-label" for="exam_title">Exam title</label>
		<div class="controls">
			<input type="text" name="exam_title" placeholder="Exam title">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="exam_no_of_qs">Number of questions</label>
		<div class="controls">
			<input type="number" name="exam_no_of_qs" placeholder="Number of questions">
			<span class="help-block">Number of questions shown to the examinee.<br />Questions shown will be a random subset of questions available in the database.</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="exam_time">Duration of exam</label>
		<div class="controls">
			<div class="input-append">
				<input type="number" name="exam_time" class="input-medium" placeholder="Duration of exam">
				<span class="add-on">minutes</span>
			</div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="pos_marks">Marks for correct answer</label>
		<div class="controls">
			<input type="number" name="pos_marks" placeholder="Positive marks">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="neg_marks">Marks deducted for incorrect answer</label>
		<div class="controls">
			<input type="number" name="neg_marks" placeholder="Negative marks">
			<span class="help-block">Marks deducted for a wrong answer. Enter a positive integer.<br />Put in '0' to disable negative marking</span>
		</div>
	</div>
	<input type="hidden" name="mode" value="examupdatedetails" />
	<div class="control-group">
		<div class="controls">
			<button type="submit" name="submit" id="exameditbtn" class="btn btn-primary"><i class="icon-check icon-white"></i> Submit</button>
			<button type="reset" value="reset" class="btn">Reset</button>
		</div>
	</div>
</form>							
						</div>
						<div class="tab-pane fade" id="examstart">
							<h4>Enable starting of exam</h4>
							<p>Allow users to start exam -</p>
							<div class="switch" data-on-label="YES" data-off-label="NO" id="examSwitch">
								<input type="checkbox" checked />
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="resmin">
				<div class="tabbable">					
					<ul class="nav nav-tabs">
						<li class="active"><a href="#viewres" data-toggle="tab"><i class="icon-search"></i> View results</a></li>
					</ul>			
									
					<div class="tab-content">	
						<div class="tab-pane fade in active" id="viewres">
							<h3>View results</h3>
							<form id="ressearchform" class="form-horizontal" method="post" action="">
								<div class="control-group">
									<label class="control-label" for="fname">First name</label>
									<div class="controls">
										<input type="text" name="fname" id="fname" placeholder="First name">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="lname">Last name</label>
									<div class="controls">
										<input type="text" name="lname" id="lname" placeholder="Last name">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="email">Email</label>
									<div class="controls">
										<input type="text" name="email" id="email" placeholder="Email">
									</div>
								</div>
								<input type="hidden" name="mode" value="ressearch" />
								<div class="control-group">
									<div class="controls">
										<button type="submit" name="submit" id="ressearchbtn" class="btn btn-primary"><i class="icon-search icon-white"></i> Search</button>
									</div>
								</div>
							</form>
							<div id="userresresults"></div>
							<div id="viewResModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">View results</h3>
								</div>
								<div class="modal-body">
									<div id="mainres" style="margin-bottom: 20px;"></div>
									<div id="detailres"></div>
								</div>
								<div class="modal-footer">
									<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
								</div>
							</div>
							<div id="uresdelModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Delete examinatin records</h3>
								</div>
								<div class="modal-body">
									<p>Are you sure you want to delete the examination records for the user?</p>
								</div>
								<div class="modal-footer">
									<form id="uresdelform" method="post" action="">
										<input type="hidden" name="user_id" />
										<input type="hidden" name="mode" value="uresdel" />
										<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<button class="btn btn-danger" id="uresdelBtn" type="submit" value="delete" name="submit"><i class="icon-trash icon-white"></i> Delete</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>										
		</div> <!-- Outer tab content -->				
	</div> <!-- Span9 -->
</div> <!-- Row -->
<div class='notifications top-right'></div>
<div class='notifications bottom-right'></div>
<div class='notifications top-left'></div>
<div class='notifications bottom-left'></div>
<div class='notifications center'></div>
		
<?php
}

require './includes/template/admin-footer.php';
?>
