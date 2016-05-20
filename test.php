<?php
session_start();
$id = array();
$_SESSION['url'] = $_POST['video'];
if(!empty($_POST['check_list'])) {
    foreach($_POST['check_list'] as $check) {
            if($check === "facebook"){
            	$_SESSION['fb'] = 1;
            }
            elseif($check === "youtube"){
            	$_SESSION['yt']= 1;
            }
    }
}
header("Location: ./google/");
?>