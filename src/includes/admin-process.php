<?php

require './common.php';
require './constants.php';
require './db_connect.php';
require './user.php';
require './q.php';

$db = db_connect();

switch($_REQUEST['mode'])
{
	case "usersearch":

		$input = array();
		if(!empty($_REQUEST['fname'])) $input["fname"] = $_REQUEST['fname'];
		if(!empty($_REQUEST['lname'])) $input["lname"] = $_REQUEST['lname'];
		if(!empty($_REQUEST['email'])) $input["email"] = $_REQUEST['email'];
		$result=User::find_user($input, $db);
		if(count($result) == 0)
		{
			echo 'Your search criteria did not match any users';
		}
		else
		{
			$qr = $db->prepare("SELECT user_fname, user_lname, user_email, user_pnumber FROM users where user_id = ?;");
			echo <<<'EOF'
			<table id="usertable" class="table table-hover table-bordered table-striped">
			<tr>
				<th>First name</th>
				<th>Last name</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Action</th>
			</tr>
EOF;
			foreach($result as $value)
			{
				$qr->execute(array($value['user_id'])) or die('Cannot get user details from database');
				$temp = $qr->fetch(PDO::FETCH_ASSOC);
				echo "<tr><td>{$temp['user_fname']}</td><td>{$temp['user_lname']}</td><td>{$temp['user_email']}</td><td>{$temp['user_pnumber']}</td><td>" . '    <div class="btn-group dropup"><a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-user icon-white"></i> User actions <span class="caret"></span></a><ul class="dropdown-menu"><li><a data-id="' . $value['user_id'] . '" class="ueditselectbtn" href="#" data-toggle="modal" data-target="#ueditModal"><i class="icon-pencil"></i> Edit</a></li><li><a data-id="' . $value['user_id'] . '" class="udelselectbtn" href="#" data-toggle="modal" data-target="#udelModal"><i class="icon-trash"></i> Delete</a></li><li><a data-id="' . $value['user_id'] . '" class="ubanselectbtn" href="#" data-toggle="modal" data-target="#ubanModal"><i class="icon-ban-circle"></i> Ban</a></li><li class="divider"></li><li><a data-id="' . $value['user_id'] . '" class="umakeadmselectbtn" href="#" data-toggle="modal" data-target="#umakeadmModal"><i class="i"></i> Make admin</a></li></ul></div>';
			}
			echo "</table";
		}
		break;
		
		
	case "userinfo":
	
		$usr = new User($_REQUEST['user_id'], $db);
		echo json_encode((array) $usr->get_user_details());
		break;
		
		
	case "userupdate":
	
		if($_REQUEST['pass'] != '')
		{
			if(!(User::update_password($_REQUEST['pass'], '', $db, $_REQUEST['id'])))
			{
				echo json_encode(array( 'status' => 0, 'message' => 'Error in updating password' ));
				break;
			}
		}
		$qr = $db->prepare("UPDATE users SET user_fname = ?, user_lname = ?, user_email = ?, user_role = ?, user_status = ?, user_allow = ?, user_sex = ?, user_addr = ?, user_city = ?, user_state = ?, user_pin = ?, user_pnumber = ? WHERE user_id = ?");
		if(!($qr->execute(array($_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['email'], $_REQUEST['role'], $_REQUEST['status'], $_REQUEST['allow'], $_REQUEST['sex'], $_REQUEST['addr'], $_REQUEST['city'], $_REQUEST['state'], $_REQUEST['pin'], $_REQUEST['pnumber'], $_REQUEST['id']))))
		{
			echo json_encode(array( 'status' => 0, 'message' => 'Error in updating user details'. implode(" - ", $qr->errorInfo()) ));
			break;
		}
		echo json_encode(array( 'status' => 1, 'message' => 'Successfully updated user details' ));
		break;
		
		
	case "useradd":
	
		$tmp = User::register_user($_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['email'], $_REQUEST['pass'], $_REQUEST['pass'], $_REQUEST['sex'], $_REQUEST['addr'], $_REQUEST['city'], $_REQUEST['state'], $_REQUEST['pin'], $_REQUEST['pnumber'], $db, FALSE, $_REQUEST['status'], $_REQUEST['allow'], $_REQUEST['role'], TRUE);
		if($tmp === TRUE)
		{
			echo json_encode(array( 'status' => 1, 'message' => 'Successfully added user into database' ));
		}
		else
		{
			echo json_encode(array( 'status' => 0, 'message' => $tmp ));
		}
		break;
		
		
	case "qadd":
	
		$tmp = Q::add_question($_REQUEST['q_body'], $_REQUEST['q_ans1'], $_REQUEST['q_ans2'], $_REQUEST['q_ans3'], $_REQUEST['q_ans4'], $_REQUEST['q_correct_ans'], $_REQUEST['q_allow'], '0', $db);
		if($tmp === TRUE)
		{
			echo json_encode(array( 'status' => 1, 'message' => 'Successfully added question into database' ));
		}
		else
		{
			echo json_encode(array( 'status' => 0, 'message' => $tmp ));
		}
		break;
		
		case "qeditsearch":
		
		$tmp = Q::search_question($_REQUEST['q_body'], $db);
		if(!is_array($tmp))
		{
			echo $tmp;
		}
		else
		{
			$qr = $db->prepare("SELECT q_body from `questions` WHERE q_id = ?;");
			echo <<<'EOF'
			<table id="qedittable" class="table table-hover table-bordered table-striped">
			<tr>
				<th>Question ID</th>
				<th>Question</th>
				<th>Action</th>
			</tr>
EOF;
			foreach($tmp as $value)
			{
				$qr->execute(array($value['q_id']));
				$result = $qr->fetch(PDO::FETCH_ASSOC);
				echo "<tr><td>{$value['q_id']}</td><td>";
				echo (strlen($result['q_body']) > 81) ? substr($result['q_body'], 0, 80) . '...' : $result['q_body'];
				echo "</td><td>" . '<a data-id="' . $value['q_id'] . '" class="btn btn-info qeditbtn" href="#" data-toggle="modal" data-target="#qeditModal"><i class="icon-pencil icon-white"></i> Edit</a></td></tr>';
			}
			echo "</table>";
		}
		break;
		
		
		case "qeditinfo":
		
		$ques = new Q($_REQUEST['q_id'], $db);
		echo json_encode((array) $ques->get_q_details());
		break;
		
		
		case "qupdate":
		
		$tmp = Q::update_question($_REQUEST['q_id'], $_REQUEST['q_body'], $_REQUEST['q_ans1'], $_REQUEST['q_ans2'], $_REQUEST['q_ans3'], $_REQUEST['q_ans4'], $_REQUEST['q_correct_ans'], $_REQUEST['q_allow'], '0', $db);
		if($tmp === TRUE)
		{
			echo json_encode(array( 'status' => 1, 'message' => 'Question details edited successfully' ));
		}
		else
		{
			echo json_encode(array( 'status' => 0, 'message' => $tmp ));
		}
		break;
		
		
		case "qdelsearch":
		
		$tmp = Q::search_question($_REQUEST['q_body'], $db);
		if(!is_array($tmp))
		{
			echo $tmp;
		}
		else
		{
			$qr = $db->prepare("SELECT q_body from `questions` WHERE q_id = ?;");
			echo <<<'EOF'
			<table id="qdeltable" class="table table-hover table-bordered table-striped">
			<tr>
				<th>Question ID</th>
				<th>Question</th>
				<th>Action</th>
			</tr>
EOF;
			foreach($tmp as $value)
			{
				$qr->execute(array($value['q_id']));
				$result = $qr->fetch(PDO::FETCH_ASSOC);
				echo "<tr><td>{$value['q_id']}</td><td>";
				echo (strlen($result['q_body']) > 81) ? substr($result['q_body'], 0, 80) . '...' : $result['q_body'];
				echo "</td><td>" . '<a data-id="' . $value['q_id'] . '" class="btn btn-info qdelbtn" href="#" data-toggle="modal" data-target="#qdelModal"><i class="icon-trash icon-white"></i> Delete</a></td></tr>';
			}
			echo "</table>";
		}
		break;
		
		
		case "qdel":
		
		if(!is_numeric($_REQUEST['q_id']))
		{
			echo json_encode(array( 'status' => 0, 'message' => 'Invalid question id: ' . $_REQUEST['q_id'] ));
			break;
		}
		else
		{
			$tmp = Q::delete_question($_REQUEST['q_id'], $db);
			if($tmp === TRUE) echo json_encode(array( 'status' => 1, 'message' => 'Question deleted successfully' ));
			else echo json_encode(array( 'status' => 0, 'message' => $tmp ));
		}
		break;
		
		case "examinfo":
		
		$qr = $db->query("SELECT * FROM exams where exam_id = 1;");
		echo json_encode($qr->fetch(PDO::FETCH_ASSOC));
		break;
		
		case "examupdatedetails":
		
		$qr = $db->prepare("UPDATE exams SET exam_title = ?, exam_no_of_qs = ?, exam_time = ?, pos_marks = ?, neg_marks = ? WHERE exam_id = 1;");
		if($qr->execute(array($_REQUEST['exam_title'], $_REQUEST['exam_no_of_qs'], $_REQUEST['exam_time'], $_REQUEST['pos_marks'], $_REQUEST['neg_marks'])))
		{
			echo json_encode(array( 'status' => 1, 'message' => 'Exam settings updated successfully' ));
		}	
		else echo json_encode(array( 'status' => 0, 'message' => 'Cannot update exam details: ' . implode(" - ", $qr->errorInfo())));
		break;
		
		case "examtoggle":
		
		$val = ($_REQUEST['value'] == 'true') ? 1 : 0;
		if($db->query("UPDATE exams SET exam_allow = $val WHERE exam_id = 1;"))
		{
			echo json_encode(array( 'status' => 1, 'message' => 'Exam permission toggled successfully' ));
		}
		else echo json_encode(array( 'status' => 0, 'message' => 'Cannot update exam toggle: ' . implode(" - ", $qr->errorInfo())));
		break;
		
	case "ressearch":

		$input = array();
		if(!empty($_REQUEST['fname'])) $input["fname"] = $_REQUEST['fname'];
		if(!empty($_REQUEST['lname'])) $input["lname"] = $_REQUEST['lname'];
		if(!empty($_REQUEST['email'])) $input["email"] = $_REQUEST['email'];
		$result=User::find_res_user($input, $db);
		if(count($result) == 0)
		{
			echo 'Your search criteria did not match any users';
		}
		else
		{
			$qr = $db->prepare("SELECT user_fname, user_lname, user_email, marks FROM users where user_id = ?;");
			echo <<<'EOF'
			<table id="restable" class="table table-hover table-bordered table-striped">
			<tr>
				<th>First name</th>
				<th>Last name</th>
				<th>Email</th>
				<th>Marks</th>
				<th>Result</th>
			</tr>
EOF;
			foreach($result as $value)
			{
				$qr->execute(array($value['user_id'])) or die('Cannot get user details from database');
				$temp = $qr->fetch(PDO::FETCH_ASSOC);
				echo "<tr><td>{$temp['user_fname']}</td><td>{$temp['user_lname']}</td><td>{$temp['user_email']}</td><td>{$temp['marks']}</td><td>" . '<div class="btn-group dropup"><a data-id="' . $value['user_id'] . '" class="btn btn-info uresselectbtn" href="#" data-toggle="modal" data-target="#viewResModal"><i class="icon-search icon-white"></i> View</a><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button><ul class="dropdown-menu"><li><a data-id="' . $value['user_id'] . '" class="uresdelselectbtn" href="#" data-toggle="modal" data-target="#uresdelModal"><i class="icon-trash"></i> Delete exam records</a></li></ul></div>';
			}
			echo "</table";
		}
		break;
		
		case "top5res":
		
		$qr = $db->query("SELECT user_id, user_fname, user_lname, user_email, marks FROM users WHERE marks IS NOT NULL ORDER BY marks DESC LIMIT 5;");
		$result = $qr->fetchAll(PDO::FETCH_ASSOC);
		echo <<<'EOF'
		Top 5 scorers
		<table id="restable" class="table table-hover table-bordered table-striped">
		<tr>
			<th>First name</th>
			<th>Last name</th>
			<th>Email</th>
			<th>Marks</th>
			<th>Result</th>
		</tr>
EOF;
		foreach($result as $value)
		{
			echo "<tr><td>{$value['user_fname']}</td><td>{$value['user_lname']}</td><td>{$value['user_email']}</td><td>{$value['marks']}</td><td>" . '<a data-id="' . $value['user_id'] . '" class="btn btn-info uresselectbtn" href="#" data-toggle="modal" data-target="#viewResModal"><i class="icon-search icon-white"></i> View</a>';
		}
		echo "</table";
		break;

		
		case "showdetailres":
		
		echo '<table class="table table-condensed table-hover table-bordered"><tr><th>Q. No.</th><th>Question</th><th>Correct answer</th><th>Your answer</th></tr>';
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
		echo "</table>";			
		break;
		
		case "showmainres":	
		
		$qr = $db->query("SELECT user_fname, user_lname, q_corr_ans, q_inc_ans, q_un_ans, marks FROM users WHERE user_id = {$_REQUEST['user_id']}");
		$result = $qr->fetch(PDO::FETCH_ASSOC);
		
		$qr = $db->query("SELECT pos_marks FROM exams WHERE exam_id = 1");
		$marks = $qr->fetchColumn(0);		
		
		echo "<h4 class='text-center'>{$result['user_fname']} {$result['user_lname']} has scored <span class='text-success'>{$result['marks']}</span> marks out of a possible " . (($result['q_corr_ans'] + $result['q_inc_ans'] + $result['q_un_ans']) * $marks) . ".</h4>";
		echo "<div style='width: 450px; margin: auto;'>" . ($result['q_corr_ans'] + $result['q_inc_ans']) . " questions were attempted from a total of " . ($result['q_corr_ans'] + $result['q_inc_ans'] + $result['q_un_ans']) . " questions, of which,<br />";
		echo "{$result['q_corr_ans']} were correct,<br />{$result['q_inc_ans']} were incorrect, and<br />{$result['q_un_ans']} were left unanswered</div>";
		break;
		
		case "deluser":
		
		if($db->exec("DELETE FROM users where user_id = {$_REQUEST['user_id']}") == 1)
		{
			echo json_encode(array( 'status' => 1, 'message' => 'User deleted successfully' ));
		}
		else echo json_encode(array( 'status' => 0, 'message' => 'Cannot delete user'));
		break;
		
		case "getbanstatus":
		
		$qr = $db->query("SELECT user_allow FROM users WHERE user_id = {$_REQUEST['user_id']}");
		echo json_encode($qr->fetch(PDO::FETCH_ASSOC));
		break;
		
		case "userban":

		if($db->exec("UPDATE users set user_allow = {$_REQUEST['user_allow']} where user_id = {$_REQUEST['user_id']}") !== FALSE)
		{
			echo json_encode(array( 'status' => 1, 'message' => 'User ban status updated successfully' ));
		}
		else echo json_encode(array( 'status' => 0, 'message' => 'Cannot update user ban status'));
		break;
		
		case "getadmstatus":
		
		$qr = $db->query("SELECT user_role FROM users WHERE user_id = {$_REQUEST['user_id']}");
		echo json_encode($qr->fetch(PDO::FETCH_ASSOC));
		break;
		
		case "userrole":

		if($db->exec("UPDATE users set user_role = '{$_REQUEST['user_role']}' where user_id = {$_REQUEST['user_id']}") !== FALSE)
		{
			echo json_encode(array( 'status' => 1, 'message' => 'User role updated successfully' ));
		}
		else echo json_encode(array( 'status' => 0, 'message' => 'Cannot update user role'));
		break;
		
		case "uresdel":
		
		if($db->query("DELETE FROM answers WHERE user_id = {$_REQUEST['user_id']}") && $db->query("UPDATE users SET q_corr_ans = NULL, q_inc_ans = NULL, q_un_ans = NULL, marks = NULL, user_allow = 1 WHERE user_id = {$_REQUEST['user_id']}"))
		{
			echo json_encode(array( 'status' => 1, 'message' => 'User exam records deleted successfully' ));
		}
		else echo json_encode(array( 'status' => 0, 'message' => 'Cannot delete user exam records'));
		break;
		
		
		
}

?>
