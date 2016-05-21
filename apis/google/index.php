<?php
ob_clean();
ob_start();
session_start();
$base = $_SERVER['DOCUMENT_ROOT'];
$path = "$base/apis/google";
// set_include_path(get_include_path() . PATH_SEPARATOR . "$path/google/google-api-php-client/src");

require_once "$path/google-api-php-client/src/Google/autoload.php";
include "$path/google-api-php-client/src/Google/Client.php";
include "$path/google-api-php-client/src/Google/Service/YouTube.php";
include_once "mysrc/functions.php";
include "$base/constants.php";
date_default_timezone_set('Asia/Kolkata');

$client = new Google_Client();
$client->setAuthConfigFile('client_secret.json');
$client->addScope('https://www.googleapis.com/auth/youtube.upload');

	$name = $_POST['name'] ;
    $token = $_POST['token'];
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $uid = $_POST['uid'];
    $refreshToken = $_POST['refresh'];

if($_SERVER['REMOTE_ADDR'] == $server_ip){
  	$client->setAccessToken($token);
  	
  	if($client->isAccessTokenExpired()) {
	    $client->refreshToken($refreshToken);
        $newtoken=$client->getAccessToken();

        $response = json_decode($newtoken);
		$expiry = $response->expires_in;
		$refresh_token = $response->refresh_token;
		$time = date('Y-m-d H:i:s');
        set_gtoken($uid, $newtoken, $expiry, $refresh_token, $time);
        $client->setAccessToken($newtoken);
	}
	$youtube = new Google_Service_YouTube($client);

  	// Check to ensure that the access token was successfully acquired.
	if ($client->getAccessToken()) {
	  try{
	    // REPLACE this value with the path to the file you are uploading.
	    $videoPath = "../../uploads/".$name;

	    // Create a snippet with title, description, tags and category ID
	    // Create an asset resource and set its snippet metadata and type.
	    // This example sets the video's title, description, keyword tags, and
	    // video category.
	    $snippet = new Google_Service_YouTube_VideoSnippet();
	    $snippet->setTitle($title);
	    $snippet->setDescription($desc);
	    // $snippet->setTags(array("Videoapi", "EDUBRANDMEDIA"));

	    // Numeric video category. See
	    // https://developers.google.com/youtube/v3/docs/videoCategories/list 
	    $snippet->setCategoryId("22");

	    // Set the video's status to "public". Valid statuses are "public",
	    // "private" and "unlisted".
	    $status = new Google_Service_YouTube_VideoStatus();
	    $status->privacyStatus = "public";

	    // Associate the snippet and status objects with a new video resource.
	    $video = new Google_Service_YouTube_Video();
	    $video->setSnippet($snippet);
	    $video->setStatus($status);

	    // Specify the size of each chunk of data, in bytes. Set a higher value for
	    // reliable connection as fewer chunks lead to faster uploads. Set a lower
	    // value for better recovery on less reliable connections.
	    $chunkSizeBytes = 1 * 1024 * 1024;

	    // Setting the defer flag to true tells the client to return a request which can be called
	    // with ->execute(); instead of making the API call immediately.
	    $client->setDefer(true);

	    // Create a request for the API's videos.insert method to create and upload the video.
	    $insertRequest = $youtube->videos->insert("status,snippet", $video);

	    // Create a MediaFileUpload object for resumable uploads.
	    $media = new Google_Http_MediaFileUpload(
	        $client,
	        $insertRequest,
	        'video/*',
	        null,
	        true,
	        $chunkSizeBytes
	    );
	    $media->setFileSize(filesize($videoPath));


	    // Read the media file and upload it chunk by chunk.
	    $status = false;
	    $handle = fopen($videoPath, "rb");
	    while (!$status && !feof($handle)) {
	      $chunk = fread($handle, $chunkSizeBytes);
	      $status = $media->nextChunk($chunk);
	    }
	    fclose($handle);

	    // If you want to make other calls after the file upload, set setDefer back to false
	    $client->setDefer(false);


	    $htmlBody .= "<h3>Video Uploaded</h3><ul>";
	    $htmlBody .= sprintf('<li>%s (%s)</li>',
	        $status['snippet']['title'],
	        $status['id']);
	    $htmlBody .= sprintf('<li><a href=http://www.youtube.com/watch?v=%s> See Video On google</a></li>',$status['id']);

	    $htmlBody .= '</ul>';
	    $_SESSION['youtube_id'] = $status['id'];

	    $time = date('Y-m-d H:i:s');
	    $response = set_gvideo_detail($uid,$_POST['vid'],$status['id'],$time);
	    if($response){
            echo $status['id'];
        }
        else{
            echo 0;
        }

	  } catch (Google_Service_Exception $e) {
	    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
	        htmlspecialchars($e->getMessage()));
	  } catch (Google_Exception $e) {
	    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
	        htmlspecialchars($e->getMessage()));
	  }

	  $_SESSION['token'] = $client->getAccessToken();
	}
	else {
  	  	// If the user hasn't authorized the app, initiate the OAuth flow
		$state = mt_rand();
		$client->setState($state);
		$_SESSION['state'] = $state;

		$authUrl = $client->createAuthUrl();
		$htmlBody = <<<END
		<h3>Authorization Required</h3>
		<p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
	}

}
else {
  echo "you are not authorised";
}
?>

