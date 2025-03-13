<?php
	$m = $_GET['m'] ?? 6 ;
	$d = $_GET['d'] ?? 4;
	$y = $_GET['y'] ?? 2016;
		
	(!($y % 4)) ? $l = 1 : $l = 0;
	echo $y . " is a " . ($l ? "leap" : "common") . " year <br>";
		
	$mons = Array(0, 3, 3, 6, 1, 4, 6, 2, 5, 0, 3, 5);
	$days = Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	if($l)	{
		$mons[0] = 6;
		$mons[1] = 2;
	}
		
	echo $m . "/" . $d . "/" . $y ." is a " . $days[((6 - ((($y % 400) / 100) * 2)) + (($y % 100) * 1.25) + $mons[$m - 1] + $d) % 7] . "<br>";
?>
