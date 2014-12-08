<?php //This file contains the global constants that are used throughout the system

define("_BASE_URL_",rtrim(dirname($_SERVER['SCRIPT_FILENAME']), '/\\'));
define("_REMOTE_URL_", "http://{$_SERVER['HTTP_HOST']}" . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'));

?>
