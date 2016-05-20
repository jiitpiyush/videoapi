

<?php
ob_clean();
ob_start();
session_start();
$base = $_SERVER['DOCUMENT_ROOT'];
include_once "$base/login/is_login.php";

error_reporting(E_ALL | E_STRICT);

require('UploadHandler.php');

// print_r(islogin());
if(islogin()){
	$upload_handler = new UploadHandler();
}
else{
	die("login");
}
	
?>
