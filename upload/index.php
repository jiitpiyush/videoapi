<?php
    session_start();
    $base = $_SERVER['DOCUMENT_ROOT'];
    include_once "$base/login/is_login.php";
    if(islogin()){
        include_once "model_data.php";
    }
    else{
        header("Location: /login/");
    }
?>