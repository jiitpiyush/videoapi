<?php
	$base = $_SERVER['DOCUMENT_ROOT'];
	require_once("$base/login/is_login.php");
	if(islogin()){
		header('Location: upload/');
		//include_once "$base/upload/index.php";

	}
	else{
		header('Location: /login/');
	}
?>
