<?php
	function get_video_details($vid){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/connect/nect.php";
		include "$base/constants.php";

		$q = "SELECT * FROM $video_url_table WHERE video_id = ?";
		$stmt = $conn->prepare($q);
		if($stmt->execute(array($vid))){
			if($stmt->rowCount() > 0){
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				return $row;
			}
			else{
				return 0;
			}
		}
		else{
			return 0;
		}

	}


	function get_upload_details($uid,$vid){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/connect/nect.php";
		include "$base/constants.php";
		

		$q = "SELECT * FROM $user_videos_table WHERE video_id = ? AND uid = ?";
		$stmt = $conn->prepare($q);
		$data = array();
		$i = 0;
		if($stmt->execute(array($vid,$uid))){
			if($stmt->rowCount() > 0){
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if($row['fb_video_id'] == "0"){
					$data[$i] = "fb";
					$i++;
				}
				if($row['yt_video_id'] == "0"){
					$data[$i] = "yt";
					$i++;
				}
				return $data;
			}
			else{
				return;
			}
		}
		else{
			return;
		}
	}

	function get_fb_id($uid){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/constants.php";
		include "$base/connect/nect.php";
		$q = "SELECT fb_id FROM $login_table WHERE uid = ?";
		$stm = $conn->prepare($q);

		if($stm->execute(array($uid))){
				$row = $stm->fetch(PDO::FETCH_ASSOC);
				$conn = '';
				return $row['fb_id'];
		}
	}

	function get_email($uid){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/constants.php";
		include "$base/connect/nect.php";
		$q = "SELECT api_umail FROM $login_table WHERE uid = ?";
		$stm = $conn->prepare($q);

		if($stm->execute(array($uid))){
				$row = $stm->fetch(PDO::FETCH_ASSOC);
				$conn = '';
				return $row['api_umail'];
		}
	}

	function postVideo($uid, $url, $v_data, $token, $refreshToken,$fb_id){
		$name = $v_data['url'];
		$title = $v_data['video_title'];
		$desc = $v_data['video_desc'];
		$vid = $v_data['video_id'];

		$query = array('uid'=>$uid ,'id'=>$fb_id,  'name'=> $name, 'title'=> $title, 'desc'=> $desc, 'vid'=>$vid, 'token'=> $token, 'refresh'=> $refreshToken);
		$options = array('http' => array(
										'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
										'method'  => 'POST',
										'content' => http_build_query($query),
										),
						);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}


	function get_token($uid,$site){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/connect/nect.php";
		include "$base/constants.php";
		
		if($site=="fb"){
			$table = $fb_token_table;
		}
		elseif($site == "yt"){
			$table = $yt_token_table;
		}

		$q = "SELECT * FROM $table WHERE uid = ?";
		$stmt = $conn->prepare($q);
		$stmt->execute(array($uid));
		if($stmt->rowCount() > 0){
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		else{
			return ;
		}
	}

	function set_status($uid,$vid){
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/connect/nect.php";
		include "$base/constants.php";

		$data = get_upload_details($uid,$vid);
		if(empty($data)){
			$q = "DELETE FROM $cron_table WHERE uid= ? AND video_id = ?";
			$stmt = $conn->prepare($q);
			if($stmt->execute(array($uid,$vid))){
				return 1;
			}

		}
	}
?>