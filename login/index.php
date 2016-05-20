<?php
        ob_start();
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>
<?php 
        session_set_cookie_params(60*60*24*10, '/', 'videoapi.edubrandmedia.com/');
        session_start();
?>
<?php $base = $_SERVER['DOCUMENT_ROOT']; ?>
<?php include_once "$base/connect/nect.php"; ?>
<?php include_once "$base/login/is_login.php";?>


<?php
        if(!isset($_SESSION['rdir'])){
            if(empty($_SERVER['HTTP_REFERER'])) {
                $_SESSION['rdir'] = "/";
            }
            else{
                $_SESSION['rdir'] = $_SERVER['HTTP_REFERER'];
            }
        }
?>


<?php
    if(islogin()){
        $rdir = $_SESSION['rdir'];
        unset($_SESSION['rdir']);
       header("Location: $rdir ");
    }
    else if(!empty($_POST['username']) && !empty($_POST['password'])){
        $username = strval($_POST['username']);
        $stmt = $conn->prepare("SELECT u_salt FROM api_login WHERE api_user= :user");
        $stmt->bindParam(':user', $username, PDO::PARAM_STR,5);
        $stmt->execute();
        $row_count = $stmt->rowCount();
        
        if($row_count == 1)
        {
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            $salt =strval($rows['u_salt']);
            $options = [
                'cost' => 11,
                'salt' => $salt
            ];
            $pass = $_POST['password'];
            $pass = password_hash(strval($pass), PASSWORD_BCRYPT, $options);

            $stmt = $conn->prepare("SELECT * FROM api_login WHERE api_user = :user AND api_upass = :pass ");
            $stmt->bindParam(':user', $username, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            if($row_count == 1)
            {
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $user = $rows['api_ufname']." ".$rows['api_ulname'];
                if(!empty($user))
                    $_SESSION['Username'] = $user;
                else
                    $_SESSION['Username'] = $rows['api_user'];
                
                $_SESSION['uid'] = $rows['uid'];
                $_SESSION['LoggedIn'] = 1;
                
                setcookie("xlazx","");
                sleep(2);
                $rdir = $_SESSION['rdir'];
                unset($_SESSION['rdir']);
                header("Location: $rdir ");
            }
            else
            {
                $login_attempt = (!empty($_COOKIE['xlazx']) ? $_COOKIE['xlazx'] : 0 );
                $login_attempt += 1;
                setcookie("xlazx",""); 
                setcookie("xlazx",$login_attempt);
                header("Location: /login/");
            }
        }
        else
        {
            $login_attempt = (!empty($_COOKIE['xlazx']) ? $_COOKIE['xlazx'] : 0 );
            $login_attempt += 1;
            setcookie("xlazx",""); 
            setcookie("xlazx",$login_attempt); 
            header("Location: /login/");
        }
    }
    else
    {
        include "login_form.php";
    }
?>