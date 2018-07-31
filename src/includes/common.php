<?php
/**
* Contains common classes needed for error generation, common methods etc
*/
class Alert
{
	private $red,
			$green,
			$blue,
			$yellow;
			
	function __construct()
	{
		$this->yellow=$this->red=$this->green=$this->blue=array();
	}
	
	function addYellow($key, $value)
	{
		if($key == 'text' && array_key_exists('text',  $this->yellow)) $this->yellow['text'] .= '<br />' . $value;
		else $this->yellow["$key"] = $value;
	}
	
	function addRed($key, $value)
	{
		if($key == 'text' && array_key_exists('text',  $this->red)) $this->red['text'] .= $value;
		else $this->red["$key"] = $value;
	}
	
	function addGreen($key, $value)
	{
		if($key == 'text' && array_key_exists('text',  $this->green)) $this->green['text'] .= $value;
		else $this->green["$key"] = $value;
	}
	
	function addBlue($key, $value)
	{
		if($key == 'text' && array_key_exists('text',  $this->blue)) $this->blue['text'] .= $value;
		else $this->blue["$key"] = $value;
	}
	
	function displayAlert()
	{
		foreach($this as $key => $value)
		{
			if(!empty($this->$key))
			{
				echo '<div class="alert fade in ';
				switch ($key)
				{
					case 'red': echo 'alert-error '; break;
					case 'green': echo 'alert-success '; break;
					case 'blue': echo 'alert-info '; break;
					default:
				}
				if(array_key_exists('classes',  $this->$key)) echo $value['classes'];
				echo '">';
				if(!array_key_exists('close',  $this->$key)) echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
				if(array_key_exists('heading',  $this->$key)) echo '<h4 class="alert-heading">' . $value['heading'] . '</h4>';
				if(array_key_exists('btn',  $this->$key)) echo '<p>';
				echo $value['text'];
				if(array_key_exists('btn',  $this->$key)) echo '</p>';				
				if(array_key_exists('btn',  $this->$key)) echo '<p>' . $value['btn'] . '</p>';
				echo "</div>\n";
			}
		}
	}
}

$alert = new Alert();

function get_exam_status($db)
{
	$qr = $db->query("SELECT exam_allow FROM exams WHERE exam_id = 1;");
	return $qr->fetchColumn(0);
}

function get_questions($db)
{
	$qr = $db->query("SELECT exam_no_of_qs FROM exams WHERE exam_id = 1;");
	
	$qr = $db->query("SELECT q_id FROM questions WHERE q_allow = 1 LIMIT {$qr->fetchColumn(0)};");
	return array_values($qr->fetchAll(PDO::FETCH_COLUMN, 0));
}

function insert_q_ids($qs, $user_id, $db)
{
	$query = "INSERT INTO answers (user_id, q_id) VALUES "; //Prequery
	$qPart = array_fill(0, count($qs), "($user_id, ?)");
	$query .=  implode(",",$qPart);
	$stmt = $db -> prepare($query); 
	$i = 1;
	foreach($qs as &$value) //bind the values one by one
	{
		$stmt -> bindParam($i++, $value, PDO::PARAM_INT);
	}
	unset($value);
	return $stmt -> execute(); //execute
}

function get_exam_time($db)
{
	$qr = $db->query("SELECT exam_time FROM exams WHERE exam_id = 1;");
	return ($qr->fetchColumn(0) * 60);
}

function get_marking_scheme($db)
{
	$qr = $db->query("SELECT pos_marks, neg_marks from exams WHERE exam_id = 1;");
	return $qr->fetch(PDO::FETCH_ASSOC);
}

function is_connected()
{
    $connected = @fsockopen("www.google.com", 80); //website and port
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;
}

?>
