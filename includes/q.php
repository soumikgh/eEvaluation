<?php

class Q
{
	private $q_id,
			$q_body,
			$q_ans1,
			$q_ans2,
			$q_ans3,
			$q_ans4,
			$q_corr_ans,
			$q_img;
			
	function __construct($q_id, $db)
	{
		$qr = $db->prepare("SELECT * FROM `questions` WHERE `q_id` = ?;");
		$qr->execute(array($q_id)) or die('Cannot get question from database..<br />'. implode(" - ", $qr->errorInfo()));
		$temp = $qr->fetch(PDO::FETCH_ASSOC);
		foreach($temp as $key => $value)
		{
			$this->$key = $value;
		}
	}
	
	static function validate_question($q_body, $q_ans1, $q_ans2, $q_ans3, $q_ans4, $q_corr_ans, $q_allow, $q_img)
	{
		$str = '';
		if($q_body == '') { $str.="Invalid question body, "; }
		if($q_ans1 == '') { $str.="Invalid answer 1, "; }
		if($q_ans2 == '') { $str.="Invalid answer 2, "; }
		if($q_ans3 == '') { $str.="Invalid answer 3, "; }
		if($q_ans4 == '') { $str.="Invalid answer 4, "; }
		if($q_corr_ans != 1 && $q_corr_ans != 2 && $q_corr_ans != 3 && $q_corr_ans != 4) { $str.="Invalid correct answer, "; }
		if($q_allow != '0' && $q_allow != '1') { $str.="Invalid q_allow, "; }
		if($q_img != '0' && $q_img != '1') { $str.="Invalid q_img"; }
		
		if($str == '') return TRUE; // If no error, return TRUE, else return error string
		else return $str;
	}
	
	static function add_question($q_body, $q_ans1, $q_ans2, $q_ans3, $q_ans4, $q_corr_ans, $q_allow, $q_img, $db)
	{
		$valid = static::validate_question($q_body, $q_ans1, $q_ans2, $q_ans3, $q_ans4, $q_corr_ans, $q_allow, $q_img);
		
		if($valid !== TRUE)
		{
			return $valid;
		}
		else
		{
			$qr = $db->prepare("INSERT INTO questions (q_body, q_ans1, q_ans2, q_ans3, q_ans4, q_correct_ans, q_allow, q_img) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
			$qr->execute(array($q_body, $q_ans1, $q_ans2, $q_ans3, $q_ans4, $q_corr_ans, $q_allow, $q_img)) or die('Cannot insert question into database..<br />'. implode(" - ", $qr->errorInfo()));
			return TRUE;
		}
	}
	function get_q_body()
	{
		return $this->q_body;
	}
	
	function get_q_ans1()
	{
		return $this->q_ans1;
	}
	
	function get_q_ans2()
	{
		return $this->q_ans2;
	}
	
	function get_q_ans3()
	{
		return $this->q_ans3;
	}
	
	function get_q_ans4()
	{
		return $this->q_ans4;
	}
	
	function get_q_corr_ans()
	{
		return $this->q_corr_ans;
	}
	
	function get_q_img()
	{
		return ($this->q_img == 'T') ? TRUE : FALSE;
	}
	
	function get_q_details()
	{
		return get_object_vars($this);
	}
	
	static function search_question($q_body, $db)
	{
		$qr = $db->prepare("SELECT * from `questions` WHERE q_body LIKE ?;");
		$qr->execute(array('%' . $q_body . '%'));
		if($qr->rowCount() == 0)
			return 'Your search criteria did not match any questions.';
		else
			return $qr->fetchAll(PDO::FETCH_ASSOC);
	}
	
	static function update_question($q_id, $q_body, $q_ans1, $q_ans2, $q_ans3, $q_ans4, $q_corr_ans, $q_allow, $q_img, $db)
	{
		$valid = static::validate_question($q_body, $q_ans1, $q_ans2, $q_ans3, $q_ans4, $q_corr_ans, $q_allow, $q_img, $db);
		
		if($valid !== TRUE)
		{
			return $valid;
		}
		else
		{
			$qr = $db->prepare("UPDATE questions SET q_body = ?, q_ans1 = ?, q_ans2 = ?, q_ans3 = ?, q_ans4 = ?, q_correct_ans = ?, q_allow = ?, q_img = ? WHERE q_id = ?;");
			$qr->execute(array($q_body, $q_ans1, $q_ans2, $q_ans3, $q_ans4, $q_corr_ans, $q_allow, $q_img, $q_id)) or die('Cannot insert question into database..<br />'. implode(" - ", $qr->errorInfo()));
			return TRUE;
		}
	}
	
	static function delete_question($q_id, $db)
	{
		$qr = $db->prepare("SELECT q_id from `questions` WHERE q_id = ?;");
		$qr->execute(array($q_id));
		if($qr->rowCount() != 1)
		{
			return 'Invalid question id to delete: ' . $q_id;
		}
		else 
		{
			$qr = $db->prepare("DELETE FROM `questions` WHERE q_id = ?;");
			$tmp = $qr->execute(array($q_id));
			if($tmp === TRUE) return $tmp;
			else return 'Cannot delete question' . implode(" - ", $qr->errorInfo());
		}
	}
	
	static function verify_ans($q_id, $value, $db)
	{
		$qr = $db->prepare("SELECT q_correct_ans from `questions` WHERE q_id = ?;");
		$qr->execute(array($q_id));
		return ($value == $qr->fetchColumn(0));
	}
}
