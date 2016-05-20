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
			$query = "SELECT fb_expire,fb_token_time FROM $fb_token_table WHERE uid = ?";
			$stmt = $conn->prepare($query);
			if($stmt->execute(array($id))){
				$row = $stmt->rowCount();
				if($row > 0){
					$conn = null;
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					$t_time = $row['fb_token_time'];
					$expire_seconds = $row['fb_expire'];
					$time_now = date('Y-m-d H:i:s');
					$calculated_seconds = $time_now - $t_time;
					if($calculated_seconds < $expire_seconds){
						echo 1;
					}
				}
			}
			echo 0;
		}
?>