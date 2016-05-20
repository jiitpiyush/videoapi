<?php
session_start();
	try{
		$base = $_SERVER['DOCUMENT_ROOT'];
		include "$base/connect/nect.php";
		include "$base/constants.php";
		include "$base/login/is_login.php";

		if(islogin()){
			$id = $_SESSION['uid'];
			$name = $_POST['name'];
			$title = $_POST['title'];
			$desc = $_POST['description'];
			$fb = $_POST['fid'];
			$yt = $_POST['yid'];

			$query = "INSERT INTO $video_url_table (url,video_title, video_desc) VALUES(?,?,?)";
			$stmt = $conn->prepare($query);
			if($stmt->execute(array($name,$title,$desc))){
				 $last_id = $conn->lastInsertId();
				 $query = "INSERT INTO $user_videos_table (uid,video_id,fb_video_id,yt_video_id) VALUES(?,?,?,?)";
				 $stm = $conn->prepare($query);
				 if($stm->execute(array($id,$last_id,$fb,$yt))){
				 	$query = "INSERT INTO $cron_table (uid,video_id) VALUES(?,?)";
				 	$stm = $conn->prepare($query);
				 	if($stm->execute(array($id,$last_id))){
				 		echo "uploaded successfully";
				 	}
				 	else
				 		echo "failed";
				 }
				 else{
				 	echo "failed";
				 }
			}
			else{
				echo "failed";
			}
		}
		else{
			echo "login";
		}
	}
	catch(PDOException $e){
	    echo "<br>" . $e->getMessage();
    }
?>