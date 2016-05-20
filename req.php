
<?php
$ip = $_SERVER['REMOTE_ADDR'];
//$ip = $_SERVER['SCRIPT_URI'];
echo $ip;
$file = fopen("req.php",'a');
fwrite($file, $ip."\r\n<br/>");
fclose($file);
// foreach (getallheaders() as $name => $value) {
//     echo "$name: $value\n";
// }

?>
<form action="" method="post">
	<button type="submit">Submit form</button>
</form>