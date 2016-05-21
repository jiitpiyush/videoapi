<?php 
	function islogin(){
		ob_start();
		session_start();
	    if(isset($_SESSION['uid']) && isset($_SESSION['LoggedIn'])){
	    	return 1;
	    }
	    else{
	    	return 0;
	    }
	}
?>

