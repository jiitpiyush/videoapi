<?php
ob_clean();
ob_start();
session_start();
date_default_timezone_set('Asia/Kolkata');
// $base = $_SERVER['DOCUMENT_ROOT'];

require_once "$base/apis/google/google-api-php-client/src/Google/autoload.php";
include_once "$base/apis/google/mysrc/functions.php";


$client = new Google_Client();
$client->setAuthConfigFile('apis/google/client_secret.json');
$client->setAccessType("offline");
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/google_authorize.php');
$client->addScope('https://www.googleapis.com/auth/youtube.upload');
$client->setApprovalPrompt('force');

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} 
else
 {
  $client->authenticate($_GET['code']);
  $token = $client->getAccessToken();
  $response = json_decode($token);
  // $access_token = $response->access_token;
  $expiry = $response->expires_in;
  $refresh_token = $response->refresh_token;
  $time = date('Y-m-d H:i:s');

 /* echo $access_token."<br/>";
  echo $expiry."<br/>";
  echo $refresh_token."<br/>";
  echo $time."<br/>";
 */

  if(set_gtoken($_SESSION['uid'], $token, $expiry, $refresh_token, $time)){
  	echo "authorised";
  }
  else{
  	echo "not authorised";
  }

  $_SESSION['g_access_token'] = $token;
  $redirect_uri = '/';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>