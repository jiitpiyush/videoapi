<?php
	$timeFirst  = strtotime('2015-02-27 18:20:20');
	$timeSecond = strtotime('2015-03-01 18:20:20');
	$differenceInSeconds = $timeSecond - $timeFirst;
	echo $differenceInSeconds;
?>