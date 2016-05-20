
<?php
if(!empty($_FILES)) {
if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
$sourcePath = $_FILES['userImage']['tmp_name'];
$targetPath = "../uploads/".$_FILES['userImage']['name'];

/*
$name = $_FILES["userImage"]["name"];
$title = $_POST['title'];
$desc = $_POST['desc'];

$graph_url = "http://videoapi.edubrandmedia.com/upload/video.php";
		
		$query = array('name'=>$name ,'title'=>$title,'description'=>$desc);
		$options = array('http' => array(
										'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
										'method'  => 'POST',
										'content' => http_build_query($query),
										),
						);
		$context  = stream_context_create($options);
		$result = file_get_contents($graph_url, false, $context);
		echo "<script type='text/javascript'>alert('".$result."');</script>";
*/

if(move_uploaded_file($sourcePath,$targetPath)) {
?>
<video src="<?php echo $targetPath; ?>" width="250px" height="250px" controls=''></video>

<?php
}
}
}
?>