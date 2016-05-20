<?php
function getHTML($url)
	{
	       $ch = curl_init($url); // initialize curl with given url
	       curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
	       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
	       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
	       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); // max. seconds to execute
	       curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
	       return @curl_exec($ch);
	}

	function postVideo($id,$query)
	{
			$url = "https://graph-video.facebook.com/v2.6/".$id."/videos";
	       $ch = curl_init($url); // initialize curl with given url
			/*
	       curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
	       curl_setopt($ch, CURLOPT_REFERER, "http://videoapi.edubrandmedia.com/upload/");
	       */
	       
	       curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($query));
	       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
	       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
	       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); // max. seconds to execute
	       curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
	       curl_setopt($ch, CURLOPT_HTTPHEADER, array('
			Host: videoapi.edubrandmedia.com
			User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0 Iceweasel/38.7.1
			Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
			Accept-Language: en-US,en;q=0.5
			Accept-Encoding: gzip, deflate
			Referer: http://videoapi.edubrandmedia.com/upload/
			Cookie: PHPSESSID=qd915auv0m17m1a2a2ptti55u0;
			Connection: keep-alive
			'));
	       return @curl_exec($ch);
	}

	function uploadVideo($id,$data,$token){
		$graph_url = "https://graph-video.facebook.com/v2.6/".$id."/videos?access_token=".$token;
		
		$query = $data;
		$options = array('http' => array(
										'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
										'method'  => 'POST',
										'content' => http_build_query($query),
										),
						);
		$context  = stream_context_create($options);
		$result = file_get_contents($graph_url, false, $context);
		return $result;
	}
?>