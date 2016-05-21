<?php
		
	function exist_token($uid){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/connect/nect.php";
		include "$base/constants.php";

		$query = "SELECT uid FROM $yt_token_table WHERE uid = ?";
		$stmt = $conn->prepare($query);
		if($stmt->execute(array($uid))){
			if($stmt->rowCount() > 0){
				return 1;
			}
			else{
				return 0;
			}
		}
		else{
			die("connection_aborted");
		}
	}
	function set_gtoken($uid, $token, $expiry, $refresh, $time){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/connect/nect.php";
		include "$base/constants.php";

		if(exist_token($uid)){
			$query = "UPDATE $yt_token_table SET access_token= ? , expire_time =? , token_time = ? , refresh_token = ? WHERE uid = ?";
			$stmt = $conn->prepare($query);		
			if($stmt->execute(array($token, $expiry, $time, $refresh, $uid))){
				return 1;
			}
			else{
				return 0;
			}
		}
		else{
			$query = "INSERT INTO $yt_token_table (access_token, expire_time, token_time, refresh_token , uid) VALUES(?,?,?,?,?)";
			$stmt = $conn->prepare($query);		
			if($stmt->execute(array($token, $expiry, $time, $refresh, $uid))){
				return 1;
			}
			else{
				return 0;
			}
		}
	}

	function set_gvideo_detail($id,$vid,$yt_vid,$time){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/constants.php";
		include "$base/connect/nect.php";

		$q = "UPDATE $user_videos_table SET yt_video_id = ?, yt_upload_time = ? WHERE uid = ? AND video_id = ?";
		$stmt = $conn->prepare($q);
		if($stmt->execute(array($yt_vid, $time, $id, $vid))){
			return 1;
		}
		else{
			return 0;
		}

	}
?>