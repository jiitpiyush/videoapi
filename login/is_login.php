<?php 
	function islogin(){
	    if(isset($_SESSION['uid']) && isset($_SESSION['LoggedIn'])){
	    	return 1;
	    }
	    else{
	    	return 0;
	    }
	}
?>

