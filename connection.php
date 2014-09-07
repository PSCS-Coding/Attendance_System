<?php
// Sets a new MySQLi instance
	$db_hostname = 'localhost';
	$db_username = 'pscs';
	$db_password = 'Integrity!';
	$db_database = 'attendance';
	$db_server = new mysqli($db_hostname, $db_username, $db_password, $db_database);
	if ($db_server->connect_error) { die('Connect Error (' . $db_server->connect_errno . ') '  . $db_server->connect_error); }

?>