<?php
	function valid_token(){
		session_start();
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/constants.php";
		date_default_timezone_set('Asia/Kolkata');
		$id = $_SESSION['uid'];
		include "$base/connect/nect.php";
		$query = "SELECT fb_expire,fb_token_time FROM $fb_token_table WHERE uid = ?";
		$stmt = $conn->prepare($query);
		if($stmt->execute(array($id))){
			$row = $stmt->rowCount();
			if($row > 0){
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$t_time = $row['fb_token_time'];
				$expire_seconds = $row['fb_expire'];
				$time_now = date('Y-m-d H:i:s');
				$calculated_seconds = $time_now - $t_time;
				$conn = null;
				if($calculated_seconds < $expire_seconds){
					return 1;
				}
			}
		}
		return 0;
	}


	function fetch_fb_id($token){
		$graph_url = "https://graph.facebook.com/me?fields=id&access_token=" . $token;
		$user = json_decode(getHTML($graph_url));
		$id = $user->id;
		return $id;
	}

	function get_fb_id(){
		session_start();
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/constants.php";
		include "$base/connect/nect.php";
		$id = $_SESSION['uid'];
		$q = "SELECT fb_id FROM $login_table WHERE uid = ?";
		$stm = $conn->prepare($q);

		if($stm->execute(array($id))){
				$row = $stm->fetch(PDO::FETCH_ASSOC);
				$conn = '';
				return $row['fb_id'];
		}
	}

	function get_fb_token(){
		session_start();
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/constants.php";
		include "$base/connect/nect.php";
		$id = $_SESSION['uid'];
		$q = "SELECT fb_token FROM $fb_token_table WHERE uid = ?";
		$stm = $conn->prepare($q);
		if($stm->execute(array($id))){
				$row = $stm->fetch(PDO::FETCH_ASSOC);
				$conn = '';

				return $row['fb_token'];
		}
	}

	function exist_fb_id($fb_id){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/constants.php";
		include "$base/connect/nect.php";

		$q = "SELECT uid FROM $login_table WHERE fb_id = ?";
		$stm = $conn->prepare($q);
		if($stm->execute(array($fb_id))){
			if($stm->rowCount() > 0){
				$row = $stm->fetch(PDO::FETCH_ASSOC);
				return $row['uid'];
			}
			else{
				return 0;
			}
		}
		else{
			return 1;
		}
	}
	function set_video_detail($id,$vid,$fb_vid,$time){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/constants.php";
		include "$base/connect/nect.php";

		$q = "UPDATE $user_videos_table SET fb_video_id = ?, fb_upload_time = ? WHERE uid = ? AND video_id = ?";
		$stmt = $conn->prepare($q);
		if($stmt->execute(array($fb_vid, $time, $id, $vid))){
			return 1;
		}
		else{
			return 0;
		}

	}
	function set_token($token,$livetime,$time){
		session_start();
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/constants.php";
		$id = $_SESSION['uid'];
		include "$base/connect/nect.php";
		$fb_id = fetch_fb_id($token);
		$r_id = exist_fb_id($fb_id);
		if(!$r_id ){
					$query = "UPDATE $login_table SET fb_id = :fb WHERE uid = :id";
					$stmt = $conn->prepare($query);
					$stmt->bindParam(':fb',$fb_id);
					$stmt->bindParam(':id',$id);
					if(!($stmt->execute())){
						echo $stmt->getMessage();
						return 0;
					}

					$query = "INSERT INTO $fb_token_table VALUES(?,?,?,?,?)";
					$stmt = $conn->prepare($query);

					if($stmt->execute(array($id,$token,'',$livetime,$time))){
						return 1;
					}
					else{
						return 0;
					}
		}
		else if($r_id == $id){
					$query = "UPDATE $fb_token_table SET fb_token = ? , fb_expire = ?, fb_token_time = ? WHERE uid = ?";
					$stmt = $conn->prepare($query);

					if($stmt->execute(array($token,$livetime,$time,$id))){
						return 1;
					}
					else{
						return 0;
					}
		}
		else if($r_id != $id){
			return 2;
		}
		else{
			return 3;
		}
	}
?>