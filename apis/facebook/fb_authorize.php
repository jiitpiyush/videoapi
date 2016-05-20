<!DOCTYPE html>
<html>
<head><meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
	<title></title>
	 
</head>
<body>

<?php
	ob_clean();
	ob_start();
	$base = $_SERVER['DOCUMENT_ROOT'];
	include "$base/apis/facebook/mysrc/user_function.php";
	require_once("$base/apis/facebook/mysrc/config.php");
	
	if(isset($_GET['fbTrue']) AND isset($_GET['code'])){
		include "$base/apis/facebook/mysrc/functions.php";

	    $token_url = "https://graph.facebook.com/oauth/access_token?"
	       . "client_id=".$config['App_ID']."&redirect_uri=" . urlencode($config['callback_url'])
	       . "&client_secret=".$config['App_Secret']."&code=" . $_GET['code']; 

		$response = getHTML($token_url);
		date_default_timezone_set('Asia/Kolkata');
		$time = date('Y-m-d H:i:s');
		$params = null;
		parse_str($response, $params);

		$response = set_token($params['access_token'],$params['expires'],$time);
		echo $response;
		if($response == 1){
			header("Location: /");
		}
		else if ($response == 2) {
			$url = 'https://www.facebook.com/logout.php?next=' . urlencode($config['callback_url']) .
		      '&access_token='.$params['access_token'];
		      echo "<script type='text/javascript'>var y = confirm('This facebook account is already linked.');</script>";
			header( "refresh:0;url=$url" );

		}
		else{
			echo "<script type='text/javascript'> alert('some error occured');</script>";
			header("Location: /");
		}
	}
	else if(valid_token()){
		header("Location: /");
	}
	else {
		$id = $config['App_ID'];
      	$cb = $config['callback_url'];
      	$fb_uri = "https://www.facebook.com/dialog/oauth?client_id=$id&redirect_uri=$cb&scope=email,publish_actions";
      	header("Location: $fb_uri");
	}

?>


</body>
</html>