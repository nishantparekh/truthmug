<?php
$id = 2;
$eachtime = 233;
$u = "nishant";
$e = "nishantparekh01@gmail.com";
		$idstrs = (string)$id;
		$eachtimestr = (string)$eachtime;
		echo $eachtime;
		echo "<br />";
		$implement = "{$idstrs}{$eachtimestr}{$u}{$e}";
		echo $implement;
		echo "<br />";
		$hashimplement = md5($implement);
		$s = md5( $implement);
		$t = md5($eachtime);
		echo $s;
		echo "<br />";
		echo $t;
		echo "<br />";
		echo $s.$t;
		$l = strlen($s);
		echo $l
	?>	