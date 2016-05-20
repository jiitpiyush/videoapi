<?php
		ob_clean();
		ob_start();
		session_start();
		$base = $_SERVER['DOCUMENT_ROOT'];
		include_once "$base/login/is_login.php";	
		if(islogin()){
			include "$base/constants.php";
			date_default_timezone_set('Asia/Kolkata');
			$id = $_SESSION['uid'];
			include "$base/connect/nect.php";
			$query = "SELECT refresh_token FROM $yt_token_table WHERE uid = ?";
			$stmt = $conn->prepare($query);
			if($stmt->execute(array($id))){
				$row = $stmt->rowCount();
				if($row > 0){
					echo 1;
				}
			}
			echo 0;
		}
?>