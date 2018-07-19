<?php

define("_IN_EXAM_", "TRUE");

require './includes/common.php';
require './includes/constants.php';
require './includes/db_connect.php';
require './includes/user.php';
require './includes/q.php';

$title='Exam';
session_start();
$db = db_connect();

if(!isset($_SESSION['user']))
{
	die("You are not logged in.");
}
if(!get_exam_status($db))
{
	die("The administrator has disabled the starting of exam. <a href=\"javascript:history.back()\">Go back</a>");
}
if(!$_SESSION['user']->get_user_allow())
{
	die("You have already completed the exam or the administrator has banned you from taking the exam. <a href=\"javascript:history.back()\">Go back</a>.");
}

if(isset($_POST["start"])) //If starting exam
{	
	$_SESSION['user'] = new User($_SESSION['user']->get_user_id(), $db);
	if(!$_SESSION['user']->get_user_allow())
	{
		die("You have already completed the exam or the administrator has banned you from taking the exam");
	}
	if(!isset($_SESSION["has_started_exam"]))
	{
		$qs=get_questions($db);
		shuffle($qs); //Shuffle question ids
		if(!insert_q_ids($qs, $_SESSION['user']->get_user_id(), $db))  //Insert question IDs into db
		{
			die("Cannot insert q_ids into database");
		}
		
		$_SESSION["has_started_exam"] = 1;
		$_SESSION["qs"] = $qs;
		$_SESSION["ans"] = array();
		$_SESSION["viewed"] = array();
		$_SESSION["start_time"] =  $_SERVER['REQUEST_TIME'];
		$_SESSION["exam_duration"] = get_exam_time($db);
	}
}
elseif(!isset($_SESSION["qs"]))
{
	die("Please start the exam from your <a href='./index.php'>dashboard</a>.");
}

if(isset($_POST["answer"])) //If question answered in last page
{
	$_SESSION["ans"]["{$_POST['question']}" -1] = (int) $_POST["answer"];
}

$time_left =  $_SESSION["exam_duration"] - ($_SERVER['REQUEST_TIME'] - $_SESSION["start_time"]); //Time left = Duration - (Current time - start time)
if($time_left <= 0)
{
	header("Location: " . _REMOTE_URL_ . "/post-exam.php");
	die();
}

$qno = (!empty($_REQUEST["qno"])) ? $_REQUEST["qno"] : (( $_REQUEST["submit"] == "prev" ) ? ($_POST['question'] - 1) : ($_POST['question'] + 1));

$_SESSION["viewed"][$qno - 1] = TRUE;
$q = new Q($_SESSION["qs"][$qno - 1], $db);

$marks = get_marking_scheme($db);

include './includes/template/exam-header.php';

// var_dump($_SESSION["qs"]);
//var_dump($_SESSION["ans"]);

?>
<div>
	<div id="countdown" class="pull-left" style="width:140px; margin-bottom:10px;"></div>
	<div class="pull-right"><a href="#" id="rulePopover" class="btn" data-toggle="popover"><i class="icon-chevron-down"></i> Toggle rules</a></div>
	<div class="clearfix"></div>
</div>

<h3>Question <?php echo $qno; ?></h3>
<h5><?php echo $q->get_q_body(); ?></h5>

<form method="post" action="./exam.php">
	<label class="radio">
	<input type="radio" name="answer" value="1" <?php if(array_key_exists($qno - 1, $_SESSION["ans"])) echo ($_SESSION["ans"][$qno - 1] == 1) ? 'checked' : '';?> />
	<?php echo $q->get_q_ans1(); ?>
	</label>
	
	<label class="radio">
	<input type="radio" name="answer" value="2" <?php if(array_key_exists($qno - 1, $_SESSION["ans"])) echo ($_SESSION["ans"][$qno - 1] == 2) ? 'checked' : '';?> />
	<?php echo $q->get_q_ans2(); ?>
	</label>
	
	<label class="radio">
	<input type="radio" name="answer" value="3" <?php if(array_key_exists($qno - 1, $_SESSION["ans"])) echo ($_SESSION["ans"][$qno - 1] == 3) ? 'checked' : '';?> />
	<?php echo $q->get_q_ans3(); ?>
	</label>
	
	<label class="radio">
	<input type="radio" name="answer" value="4" <?php if(array_key_exists($qno - 1, $_SESSION["ans"])) echo ($_SESSION["ans"][$qno - 1] == 4) ? 'checked' : '';?> />
	<?php echo $q->get_q_ans4(); ?>
	</label>
	
	<input type="hidden" name="question" value="<?php echo $qno; ?>" />
	
	<div class="form-actions">
		<div id="examButtons">
			<button type="submit" id="leftSubmit" value="prev" name="submit" class="btn btn-primary btn-large" <?php echo ($qno == 1) ? ' disabled' : ''; ?>>&larr; Previous</button>
			<button type="reset" id="resetBtn" value="reset" class="btn btn-large">Reset</button>
			<button type="submit" id="rightSubmit" value="next" name="submit" class="btn btn-primary btn-large" <?php echo ($qno == count($_SESSION["qs"])) ? ' disabled' : ''; ?>>Next &rarr;</button>
		</div>
	</div>
</form>

<div id="endexamModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">End exam</h3>
	</div>
	<div class="modal-body">
		<p>Are you sure you want to end the exam?</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<a class="btn btn-danger" href="./post-exam.php"><i class="icon-remove-sign icon-white"></i> End exam</a>
	</div>
</div>
	
<!-- <ul class="pager">
	<li class="previous<?php echo ($qno == 1) ? ' disabled' : ''; ?>">
		<a href="<?php echo ($qno == 1) ? '#' : "./exam.php?qno=" . ($qno - 1); ?>">&larr; Previous</a>
	</li>
	<li class="next<?php echo ($qno == count($_SESSION["qs"])) ? ' disabled' : ''; ?>">
		<a href="<?php echo ($qno == count($_SESSION["qs"])) ? '#' : "./exam.php?qno=" . ($qno + 1); ?>">Next &rarr;</a>
	</li>
</ul> -->



<?php 

include './includes/template/exam-footer.php'; ?>
