<?php

require './includes/common.php';
require './includes/constants.php';
require './includes/db_connect.php';
require './includes/user.php';
require './includes/q.php';

$title = "Exam results";
session_start();
$db = db_connect();

$out='';
if(!isset($_SESSION['user']) || !isset($_SESSION["has_started_exam"])) //Not logged in and not exam given
{
		die("You are not allowed here");
}
else
{
	$_SESSION['user']->update_user_allow(0, $db); //Bar user to further sit in exams
	$qr = $db->prepare("UPDATE answers SET answer = ? WHERE user_id = {$_SESSION['user']->get_user_id()} AND q_id = ?");
	$corr_ans = 0; $inc_ans = 0; $un_ans = count($_SESSION["qs"]) - count($_SESSION["ans"]);
	
	foreach($_SESSION['ans'] as $key => $value)
	{
		$qr->execute(array($value, $_SESSION["qs"][$key]));
		if(Q::verify_ans($_SESSION["qs"][$key], $value, $db))
		{
			$corr_ans++;
		}
		else
		{
			$inc_ans++;
		}
	}
	$scheme = get_marking_scheme($db);
	$marks = ($corr_ans * $scheme["pos_marks"]) - ($inc_ans * $scheme["neg_marks"]);
	User::update_marks($_SESSION['user']->get_user_id(), $corr_ans, $inc_ans, $un_ans, $marks, $db);
	unset($_SESSION["has_started_exam"]);
	$_SESSION['user'] = new User($_SESSION['user']->get_user_id(), $db);
	
	require './includes/template/header.php';
?>

<div class="page-header">
	<h1>Results <small>including details of each question</small></h1>
</div>
<div class="form-register" style="max-width: 800px;">
	<h4 class='text-center'>Congratulations! You have successfully completed the examination.</h4>
	<div style='width: 450px; margin: auto;'>You have scored <?php echo $marks; ?> marks.<br />
	Out of <?php echo count($_SESSION["qs"]); ?> questions, you have attempted <?php echo count($_SESSION["ans"]); ?> questions.<br />
	<?php echo $corr_ans; ?> were correct.<br />
	<?php echo $inc_ans; ?> were incorrect.<br /></div>
	<?php echo '<div id="viewdetrep" style="margin-top:20px;"><div class="text-center"><a data-id="' . $_SESSION['user']->get_user_id() . '" class="btn btn-inverse viewdetrepbtn" href="#"><i class="icon-search icon-white"></i> View detailed performance report</a></div></div>'; ?>
</div>
<?php
}
require './includes/template/footer.php';
?>
