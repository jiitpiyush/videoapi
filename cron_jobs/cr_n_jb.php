<?php
	$base = $_SERVER['DOCUMENT_ROOT'];
	include_once "$base/constants.php";
	include_once "$base/connect/nect.php";
	require_once "cron_functions.php";

	if($_SERVER['REMOTE_ADDR'] == $server_ip){
		$query = "SELECT * FROM $cron_table ORDER BY video_id";
		$stmt = $conn->prepare($query);
		if($stmt->execute()){
			if($stmt->rowCount() > 0){
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($result as $row) {
					$uid = $row['uid'];
					$vid = $row['video_id'];
					$status = $row['status'];
					$v_data = array();
					if($status == 0){
						$v_data = get_video_details($vid);
						$upload_data = get_upload_details($uid,$vid);
						$data = array();
						foreach ($upload_data as $site) {
							$t = get_token($uid,$site);
							if($site == 'fb'){
								$url = "http://videoapi.edubrandmedia.com/apis/facebook/index.php";
								$token = $t['fb_token'];
								$fb_id = get_fb_id($uid);
								$vid = postVideo($uid, $url, $v_data, $token,'',$fb_id);
								$data['fb'] = $vid;
							}
							else if($site=='yt'){
								$url = "http://videoapi.edubrandmedia.com/apis/google/index.php";
								$token = $t['access_token'];
								$refreshToken = $t['refresh_token'];
								$vid = postVideo($uid, $url, $v_data, $token, $refreshToken,'');
								$data['yt'] = $vid;
							}
						}
						foreach ($data as $site => $video) {
							if($site == 'fb'){
								$fb_video = "https://www.facebook.com/".$video;
							}
							else if($site == 'yt'){
								$yt_video = "https://www.youtube.com/watch?v=".$video;
							}
						}


						// set_status($uid,$vid);

						$mail_data = "Your video have been uploaded";
						if(!empty($data['fb'])){
							$mail_data .= "\r\n <a href=".$fb_video.">Facebook</a>";
						}
						if(!empty($data['yt'])){
							$mail_data .= "\r\n <a href=".$yt_video.">youtube</a>";
						}
						if(!empty($data['fb']) || !empty($data['yt'])){
							$to = strip_tags(get_email($uid));
							$subject = "Video Uploaded";
							$headers = "From: " . "support@edubrandmedia.com" . "\r\n";
							$headers .= "MIME-Version: 1.0\r\n";
							$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

							$message = $mail_data;
							mail($to, $subject, $message,$headers);
						}
					}
				}
			}
		}
		else{
			echo "string";
		}
	}
?>
