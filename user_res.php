<?php

require './includes/common.php';
require './includes/constants.php';
require './includes/db_connect.php';
require './includes/user.php';
require './includes/q.php';

$title = "Exam results";
session_start();

$out='';
if(!isset($_SESSION['user']))
{
		die("You are not allowed here");
}
if(!$_SESSION['user']->get_exam_valid())
{
		die("You are not allowed here");
}

$db = db_connect();
require './includes/template/header.php';
?>

<div class="page-header">
	<h1>Results <small>including details of each question</small></h1>
</div>
<div class="form-register" style="max-width: 800px;">
<?php

$qr = $db->query("SELECT user_fname, user_lname, q_corr_ans, q_inc_ans, q_un_ans, marks FROM users WHERE user_id = {$_REQUEST['user_id']}");
$result = $qr->fetch(PDO::FETCH_ASSOC);

$qr = $db->query("SELECT pos_marks FROM exams WHERE exam_id = 1");
$marks = $qr->fetchColumn(0);		

echo "<h4 class='text-center'>{$result['user_fname']} {$result['user_lname']} has scored <span class='text-success'>{$result['marks']}</span> marks out of a possible " . (($result['q_corr_ans'] + $result['q_inc_ans'] + $result['q_un_ans']) * $marks) . ".</h4>";
echo "<div style='width: 450px; margin: auto;'>" . ($result['q_corr_ans'] + $result['q_inc_ans']) . " questions were attempted from a total of " . ($result['q_corr_ans'] + $result['q_inc_ans'] + $result['q_un_ans']) . " questions, of which,<br />";
echo "{$result['q_corr_ans']} were correct,<br />{$result['q_inc_ans']} were incorrect, and<br />{$result['q_un_ans']} were left unanswered</div>";

		
echo '<div style="margin-top: 20px;"></div><table class="table table-condensed table-hover table-bordered"><tr><th>Q. No.</th><th>Question</th><th>Correct answer</th><th>Your answer</th></tr>';
$qr = $db->query("SELECT q_id, answer FROM answers WHERE user_id = {$_REQUEST['user_id']}");
$result = $qr->fetchAll(PDO::FETCH_ASSOC);
$qr2 = $db->prepare("SELECT q_body, q_ans1, q_ans2, q_ans3, q_ans4, q_correct_ans FROM questions WHERE q_id = ?"); 

foreach($result as $key => $value)
{
	$qr2->execute(array($value["q_id"]));
	$res = $qr2->fetch(PDO::FETCH_ASSOC);
	if($value["answer"] == NULL)
	{
		echo "<tr class='warning'><td>" . ($key + 1) . "</td><td>{$res['q_body']}</td><td>" . $res['q_correct_ans'] . ". " . $res["q_ans{$res['q_correct_ans']}"] . "</td><td>Not given</td></tr>";
	}
	elseif($value["answer"] == $res["q_correct_ans"])
	{
		echo "<tr class='success'><td>" . ($key + 1) . "</td><td>{$res['q_body']}</td><td>" . $res['q_correct_ans'] . ". " . $res["q_ans{$res['q_correct_ans']}"] . "</td><td>" . $res['q_correct_ans'] . ". " . $res["q_ans{$res['q_correct_ans']}"] . " <i class='icon-ok'></i></td></tr>";
	}
	else
	{
		echo "<tr class='error'><td>" . ($key + 1) . "</td><td>{$res['q_body']}</td><td>" . $res['q_correct_ans'] . ". " . $res["q_ans{$res['q_correct_ans']}"] . "</td><td>" . $value["answer"] . ". " . $res["q_ans{$value["answer"]}"] . " <i class='icon-remove'></i></td></tr>";
	}
}
echo "</table></div>";

echo "</div>";		
		

	
require './includes/template/footer.php';
?>
