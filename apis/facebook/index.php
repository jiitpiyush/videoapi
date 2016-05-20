<?php
    ob_clean();
    ob_start();
    session_start();

    $base = $_SERVER['DOCUMENT_ROOT'];
    require_once("facebook-sdk-v5/autoload.php");
    require_once("mysrc/config.php");
    require_once("$base/constants.php");
    include_once "mysrc/user_function.php";
    date_default_timezone_set('Asia/Kolkata');


    $name = $_POST['name'] ;
    $f_id = $_POST['id'];
    $token = $_POST['token'];
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $uid = $_POST['uid'];

    if($_SERVER['REMOTE_ADDR'] == $server_ip){
        $fb = new Facebook\Facebook([
            'app_id' => $config['App_ID'],
            'app_secret' => $config['App_Secret'],
            'default_graph_version' => 'v2.6',
        ]);

        $data = [
           'title' => $title,
           'description' => $desc,
            'source' => $fb->videoToUpload("../../uploads/".$name),
        ];

        try{
            $response = $fb->post("/$f_id/videos", $data, $token);
        } 
        catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo "Graph returned an error:' " . $e->getMessage();
            exit;
        }
        catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
             echo "Facebook SDK returned an error:' " . $e->getMessage();
             exit;
        }

        $graphNode = $response->getGraphNode();
        $fb_video_id = $graphNode['id'];
        $time = date('Y-m-d H:i:s');
        $response = set_video_detail($uid,$_POST['vid'],$graphNode['id'],$time);
        if($response){
            echo $graphNode['id'];
        }
        else{
            echo 0;
        }
    }
    else{
      // echo "you are not othorised";
        echo "<h1>Not Found</h1>

        The requested URL ".$_SERVER['REQUEST_URI']." was not found on this server.<br/><br/>

        Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.";
    }
?>
