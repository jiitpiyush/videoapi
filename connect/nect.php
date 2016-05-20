<?php
	$db_s 	= 'localhost';
	/*
	$db_u 	= 'mysql_piyush';
	$db_p 	= 'welcome2010';
*/
	$db_u 	= 'root';
	$db_p	= 'password';

	$db_n 	= 'videoapi';

	
	try{
		$conn = new PDO("mysql:host=$db_s;dbname=$db_n", $db_u, $db_p);
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	catch(PDOException $e){
		echo "Error:".$e->getMessage();
	}
?>