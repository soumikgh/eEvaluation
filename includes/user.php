<?php
/**
* Contains the User class for manipulating user information
*/
class User
{
	private $user_id,
			$user_email,
			$user_role,
			$user_fname,
			$user_lname,
			$user_pass,
			$user_sex,
			$user_addr,
			$user_city,
			$user_state,
			$user_pin,
			$user_pnumber,
			$user_salt,
			$user_status, //I for Inactive, A for active
			$user_allow, //1 for yes, 0 for No
			$q_corr_ans,
			$q_inc_ans,
			$marks;

	function __construct($user_id, $db)
	{
		$qr = $db->prepare("SELECT * FROM `users` WHERE `user_id` = ?;");
		$qr->execute(array($user_id)) or die('Cannot get user details from database..<br />'. implode(" - ", $qr->errorInfo()));
		$temp = $qr->fetch(PDO::FETCH_ASSOC);
		foreach($temp as $key => $value)
		{
			$this->$key = $value;
		}
	}

	static function list_user_ids($db)
	{
		$result = $db->sql_query('SELECT `user_id` FROM `users`;') or die('Cannot retrieve user ids from database..<br />' . implode(" - ", $qr->errorInfo()));
		$array = array();
		while($row = $result->fetch_row())
		{
			$array[] = $row[0];
		}
		$db->sql_freeresult($result);
		return $array;
	}

	static function validate_login($user_email, $db, $user_pass=NULL)
	{
		$qr = $db->prepare("SELECT user_id, user_pass, user_salt, user_status FROM users WHERE user_email = ?;") or die('Cannot retrieve password from database..<br />' . implode(" - ", $qr->errorInfo()));
		$qr->execute(array($user_email));
		switch($qr->rowCount())
		{
			case 0: return 'The user does not exist.';
				break;
			case 1: $temp = $qr->fetch(PDO::FETCH_OBJ);
				if($user_pass == NULL) //Just email validation
				{
					return (int) $temp->user_id;
				}
				elseif($temp->user_status == 'I')
				{
					return 'Account is inactive';
				}
				if(strcmp(crypt($user_pass, $temp->user_salt), $temp->user_pass) == 0)
				{
					return (int) $temp->user_id;
				}
				else return 'Password does not match with our record.';
				break;
			default: return 'The db returned more than 1 matches for the user. Contact admin.';
		}
	}

	function get_user_id()
	{
		return $this->user_id;
	}
		
	function get_user_role()
	{
		return $this->user_role;
	}
	
	function get_user_salt()
	{
		return $this->user_salt;
	}

	function get_user_email()
	{
		return $this->user_email;
	}

	function get_user_allow()
	{
		return $this->user_allow;
	}
	
	function get_user_details()
	{
		return get_object_vars($this);
	}
	
	function get_user_fname()
	{
		return $this->user_fname;
	}
	
	function get_user_lname()
	{
		return $this->user_lname;
	}
	
	function get_exam_valid()
	{
		return (($this->marks !== NULL) && ($this->user_allow == 0));
	}

	function update_user_details($email, $role, $fname, $lname, $pass, $sex, $addr, $city, $state, $pin, $pnumber, $db)
	{
		$str=static::validate_user_details('A', $fname, $lname, $email, $pass, $pass, $sex, $addr, $city, $state, $pin, $pnumber, $db);
		if(strcmp($str, '') != 0)
		{
			return $str;
		}
		else
		{
			$qr = $db->prepare("
					UPDATE users
					SET user_email = \"$email\",
					user_role = \"$role\",
					user_fname = \"$fname\",
					user_lname = \"$lname\",
					user_sex = \"$sex\",
					user_addr = \"$addr\",
					user_city = \"$city\",
					user_state = \"$state\",
					user_pin = \"$pin\",
					user_pnumber = \"$pnumber\"
					WHERE user_id = " . $this->user_id);
			$qr->execute(array($email, $role, $fname, $lname, $sex, $addr, $city, $state, $pin, $pnumber)) or die('Cannot update data. <br />' . implode(" - ", $qr->errorInfo()));
			
			if(strcmp($pass, '') != 0) //Change password if password is changed
			{
				$salt=static::generate_salt();
				$pass = crypt($pass, $salt);
				$db->exec("UPDATE users SET user_pass = {$db->quote($pass)}, user_salt = {$db->quote($salt)} WHERE user_id = " . $this->user_id) or die('Cannot update password. <br />' . implode(" - ", $db->errorInfo()));
			}
			return TRUE;
		}
	}



	static function validate_user_details($role, $fname, $lname, $email, $pass1, $pass2, $sex, $addr, $city, $state, $pin, $pnumber, $db)
	{
		$str='';
		if($fname == '') { $str.="Please enter a valid first name.<br />"; }
		if($lname == '') { $str.="Please enter a valid last name.<br />"; }
		if (filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE) { $str.="Please enter a valid email.<br />"; }
		if($role != 'A')
		{
			if($pass1 == '') { $str.="Please enter a valid password.<br />"; }
			if($pass2 == '') { $str.="Please enter a valid password (repeat).<br />"; }
			if(strcmp($pass1, $pass2) != 0) { $str.="Passwords do not match.<br />"; }
		}
		if($sex == '') { $str.="Please select a gender.<br />"; }
		if($addr == '') { $str.="Please enter a valid address.<br />"; }
		if($city == '') { $str.="Please enter a valid city.<br />"; }
		if($state == '') { $str.="Please enter a valid state.<br />"; }
		if (filter_var($pin, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9]{6}$/'))) == FALSE) { $str.="Please enter a valid pin.<br />"; }
		if (filter_var($pnumber, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9]{10}$/'))) == FALSE) { $str.="Please enter a valid phone number.<br />"; }

		//Email address uniqueness

		if($role == 'A') 	return $str; //If called by admin, do not check for uniqueness
		else
		{
			$result = $db->prepare("SELECT user_id FROM users WHERE user_email = ?;");
			$result->execute(array($email)) or die('Cannot retrieve email from database..<br />' . implode(" - ", $result->errorInfo()));
			if($result->rowCount() > 0) { $str.="Email already in use.<br />"; }
			return $str;
		}
	}

	static function register_user($fname, $lname, $email, $pass1, $pass2, $sex, $addr, $city, $state, $pin, $pnumber, $db, $send_mail = TRUE, $status = 'I', $allow = 1, $role = 'U', $sent_by_admin = FALSE)
	{
		$tmp = ($sent_by_admin) ? 'A' : 'U';
		$str=static::validate_user_details($tmp, $fname, $lname, $email, $pass1, $pass2, $sex, $addr, $city, $state, $pin, $pnumber, $db);
		if(strcmp($str, '') != 0) return $str; //If validation error, return error
		else
		{
			$salt=static::generate_salt();
			$temp_pass = crypt($pass1, $salt);
			$qr = $db->prepare("INSERT INTO users (user_email, user_role, user_fname, user_lname, user_pass, user_sex, user_addr, user_city, user_state, user_pin, user_pnumber, user_salt, user_status, user_allow)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
			$qr->execute(array($email, $role, $fname, $lname, $temp_pass, $sex, $addr, $city, $state, $pin, $pnumber, $salt, $status, $allow)) or die('Cannot insert user into database..<br />' . implode(" - ", $qr->errorInfo()));
			
			if($send_mail) return User::send_activation_email($email, $db, $fname, $lname, $salt);
			else return TRUE;
		}
	}
	
	static function send_activation_email($email, $db, $fname=NULL, $lname=NULL, $salt=NULL)
	{
		if($fname == NULL) //Not coming from register page
		{
			$qr = $db->prepare("SELECT user_fname, user_lname, user_salt, user_status FROM `users` WHERE `user_email` = ?;");
			$qr->execute(array($email)) or die('Cannot get user details from database..<br />'. implode(" - ", $qr->errorInfo()));
			$temp = $qr->fetch(PDO::FETCH_OBJ);

			if($temp->user_status == 'A')
			{
				return 'User account is already activated';
			}
			
			$fname=$temp->user_fname;
			$lname=$temp->user_lname;
			$salt=$temp->user_salt;
		}
		
			$uid=urlencode(base64_encode($salt));
			$body="Dear $fname $lname,\n\nYou recent registered an account at Online Examination System.\nYour account has been created, but in order to activate your account you need to verify your email.\n\nTo verify your email, click on this link: \n\n" . _REMOTE_URL_ . "/forgotpw.php?mode=activate&uid=$uid";
			$from = 'From: oes@soumikghosh.com'."\r\n";
			if (mail($email, 'Activate your account', $body, $from))
			{
				return TRUE;
			}
			else
			{
				return "Email problem";
			}
	}
	
	static function activate_user($hash, $db)
	{
		$salt=base64_decode($hash);
		$qr = $db->prepare("SELECT user_status FROM `users` WHERE `user_salt` = ?;");
		$qr->execute(array($salt)) or die('Cannot get user details from database..<br />'. implode(" - ", $qr->errorInfo()));
		
		switch($qr->rowCount())
		{
			case 0: return 'Invalid URL.';
					break;
			case 1: $temp = $qr->fetch(PDO::FETCH_OBJ);
					if($temp->user_status == 'A')
					{
						return 'User account is already activated';
					}
					else
					{					
						if($db->exec("UPDATE `users` set user_status = 'A' WHERE `user_salt` = {$db->quote($salt)};") == 1)
						{
							return TRUE;
						}
						else
						{
							return 'Error in updating user account';
						}
					}
		}
	}
	
	static function generate_salt()
	{
		return openssl_random_pseudo_bytes(20);
	}
	
	static function send_passreset_email($email, $db)
	{
		$qr = $db->prepare("SELECT user_fname, user_lname, user_salt FROM `users` WHERE `user_email` = ?;");
		$qr->execute(array($email)) or die('Cannot get user details from database..<br />'. implode(" - ", $qr->errorInfo()));
		$temp = $qr->fetch(PDO::FETCH_OBJ);
	
		$fname=$temp->user_fname;
		$lname=$temp->user_lname;
		$salt=$temp->user_salt;

	
		$uid=urlencode(base64_encode($salt));
		$body="Dear $fname $lname,\n\nYou (or someone pretending to be you) recently requested that your password be reset with regards to your account at Online Examination System.\n\nTo reset your password, click on this link: \n\n" . _REMOTE_URL_ . "/forgotpw.php?mode=passwd&uid=$uid\n\nIf you did not initiate the password reset request, you can safely ignore this email. Additionally, you can also report this incident to an administrator of the system.";
		$from = 'From: oes@soumikghosh.com'."\r\n";
		if (mail($email, 'Password reset instructions', $body, $from))
		{
			return TRUE;
		}
		else
		{
			return "Email problem";
		}
	}
	
	static function check_user($hash, $db)
	{
		$salt=base64_decode($hash);
		$qr = $db->prepare("SELECT user_status, user_id FROM `users` WHERE `user_salt` = ?;");
		$qr->execute(array($salt)) or die('Cannot get user details from database..<br />'. implode(" - ", $qr->errorInfo()));
		
		switch($qr->rowCount())
		{
			case 0: return 'Invalid URL.';
					break;
			case 1: $temp = $qr->fetch(PDO::FETCH_OBJ);
					if($temp->user_status == 'I')
					{
						return 'User account is inactive';
					}
					else return (int) $temp->user_id;
					break;
		}
	}
	
	static function update_password($pass, $hash, $db, $user_id = FALSE)
	{
		if(empty($user_id))
		{
			$qr = $db->query("SELECT user_id FROM users where user_salt = {$db->quote($hash)};") or die('Cannot get user id from database..<br />'. implode(" - ", $qr->errorInfo()));
			$user_id = $qr->fetch(PDO::FETCH_OBJ)->user_id;
		}
		$salt=static::generate_salt();
		$pass = crypt($pass, $salt);
		$qr = $db->prepare("UPDATE users SET user_pass = ?, user_salt = ? WHERE user_id = ?;");
		$qr->execute(array($pass, $salt, $user_id)) or die('Cannot update password. <br />' . implode(" - ", $qr->errorInfo()));
		return TRUE;
	}
	
	function get_gravatar_url($size)
	{
		return "http://www.gravatar.com/avatar/" . md5(strtolower(trim($this->get_user_email()))) . "?d=mm&amp;s=$size";
	}
	
	static function find_user($in, $db) // Returns associative array when user(s) found, otherwise false
	{
		$query = 'SELECT user_id FROM users where ';
		foreach($in as $key => $value)
		{
			$query .= "user_$key LIKE {$db->quote('%' . $value . '%')} AND ";
		}
		$query = substr($query, 0, -5);
		$res = $db->query($query) or die('Cannot get user id from database..<br />'. implode(" - ", $db->errorInfo()));
		return ($res) ? $res->fetchAll(PDO::FETCH_ASSOC) : FALSE;
	}
	
	static function find_res_user($in, $db) // Returns associative array when user(s) found, otherwise false
	{
		$query = 'SELECT user_id FROM users where ';
		foreach($in as $key => $value)
		{
			$query .= "user_$key LIKE {$db->quote('%' . $value . '%')} AND ";
		}
		$query .= "marks IS NOT NULL ORDER BY marks";
		$res = $db->query($query) or die('Cannot get user id from database..<br />'. implode(" - ", $db->errorInfo()));
		return ($res) ? $res->fetchAll(PDO::FETCH_ASSOC) : FALSE;
	}
	
	function update_user_allow($value, $db)
	{
		if($value !== 0 && value !== 1 )
		{
			die("Invalid value for user_allow");
		}
		else
		return $db->query("UPDATE users SET user_allow = $value where user_id = {$this->user_id}");
	}
		
	function update_marks($user_id, $corr_ans, $inc_ans, $un_ans, $marks, $db)
	{
		$qr = $db->prepare("UPDATE users SET q_corr_ans = ?, q_inc_ans = ?, q_un_ans = ?, marks = ? WHERE user_id = ?");
		return $qr->execute(array($corr_ans, $inc_ans, $un_ans, $marks, $user_id));
	}
}
?>
