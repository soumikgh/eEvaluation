<?php

function db_connect()
{
	// Change the second and the third parameter to the username and password respectively of the user having access to the database
	// You can also change the first parameter to edit database name and database host
	$db = new PDO('mysql:host=localhost;dbname=oes', 'root', '');
	return($db);
}
?>
