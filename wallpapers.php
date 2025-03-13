<?PHP
	// Includes
	include("../connect.php");
	
	// URL vars
	isset($_GET['ratio']) ? $ratio = $_GET['ratio'] : NULL;
	isset($_GET['res']) ? $res = $_GET['res'] : NULL;
	(isset($_GET['page']) && $_GET['page'] >= 1) ? $page = $_GET['page'] : $page = 1;
	
	// Find matching images
	$q = "SELECT `filename` FROM `wallpapers` ";
	if(isset($res)) {
		if($res == "Other")
			$q .= "WHERE `res` NOT IN ('1280x1024', '1280x800', '1440x900', 
						 '1680x1050', '1920x1200', '2560x1600', '1920x1080', 
						 '1152x864', '1600x1200', '1024x768')";
		else
			$q .= "WHERE `res`='$res'";
	}
	else if(isset($ratio)) {
		if($ratio == "-1")
			$q .= "WHERE NOT abs(`ratio` - 1.6) < 0.0001 AND 
						 NOT abs(`ratio` - 1.333) < 0.0001 AND
						 NOT abs(`ratio` - 1.25) < 0.0001 AND
						 NOT abs(`ratio` - 1.778) < 0.0001";
		else
			$q .= "WHERE abs(`ratio` - $ratio) < 0.0001";
	}
	$stmnt = $db->prepare($q);
	$stmnt->execute();
	$result = $stmnt->fetchAll();
	//echo $q;
	
	
	// Independent variables
	$counter = 0;
	$firstpage = 1;
	$rowmax = 3;
	$colmax = 5;
	$dir = "Gallery/Wallpapers/";
	$thumbdir = "Gallery/Wallpapers/Thumbnails/";
	$ratios = Array("5:4" => number_format(5/4, 3),
				    "4:3" => number_format(4/3, 3), 
					"16:10" => number_format(16/10, 3), 
					"16:9" => number_format(16/9, 3), 
					"Other" => "-1");
	$resolutions = Array("1280x1024", "1280x800", "1440x900", 
						 "1680x1050", "1920x1200", "2560x1600", 
						 "1920x1080", "1152x864", "1600x1200", 
						 "1024x768", "Other");
	
	// Dependent variables
	$interval = $colmax * $rowmax;
	$lastpage = ceil(sizeof($result)/$interval);
	$prevpage = max(1, $page - 1);
	$nextpage = min($lastpage, $page + 1);
	$start = max($firstpage, $page - 4);
	$end = min($lastpage, $start + 8);
	if($end - $start < 8)
		$start = max($firstpage, $end - 7);
	$wallpapers = array_slice($result, (($page - 1) * $interval), $interval);
		
	// this makes building of the URL and its variables easier
	// it also encodes characters such as & into their respective character references
	function url_vars($page, $ratio, $res)	{
		if(!is_null($ratio))
			return htmlentities("?page=" . $page . "&ratio=" . $ratio);
		else if(!is_null($res))
			return htmlentities("?page=" . $page . "&res=" . $res);
		else
			return htmlentities("?page=" . $page);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Wallpaper Gallery</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.20" />
	<link rel="shortcut icon" href="img/fleur.ico" />
	<link rel="stylesheet" href="../js/fancybox/jquery.fancybox-1.3.1.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../css/wallpapers.css" type="text/css" />
	<script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery.mousewheel.min.js"></script>
	<script type="text/javascript" src="../js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
	<script type="text/javascript" src="../js/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="../js/wallpapers.js"></script>
	<script type="text/javascript">
		<!--
		$(function() {
			// called if the bit.ly link was used to go straight to a specific picture
			<?
				if(isset($_GET['wp']) && file_exists($dir . $_GET['wp']))
				echo "$.fancybox({
						'type'				:	'image',
						'href'				:	'Gallery/Wallpapers/{$_GET['wp']}',
						'title'				:	'{$_GET['wp']}',
						'margin' 			:	100,
						'overlayOpacity' 	:	0.75,
						'titlePosition' 	:	'inside',
						'titleFormat' 		:	dlLink,
						'onComplete'		:	bitlySelect
					});";
			?>
		});
		// -->
	</script>
</head>
<body>
<?PHP		
	// echo "<pre>".print_r($results,TRUE)."</pre>"; // helps for debugging

	// table starts
	echo "<table id='gallery' cellspacing='0'>\n";
	
	// choose an aspect ratio...
	echo "<tr class='nav'>\n<td colspan='{$colmax}'>\n";
	echo "<a".(!isset($ratio) && !isset($res) ? " style='color:black'" : null)." href='".url_vars($firstpage, null, null)."'>All Ratios</a>\n";
	foreach($ratios as $k => $v)
		echo "<a ".(isset($ratio) && $ratio == $v ? " style='color:black'" : null)." href='".url_vars($firstpage, $v, null)."'>$k</a>\n";
	echo "<br />\n<br />\n";
	
	// or choose a resolution
	echo "<a ".(!isset($ratio) && !isset($res) ? " style='color:black'" : null)." href='".url_vars($firstpage, null, null)."'>All Resolutions</a>\n";
	foreach($resolutions as $v)
		echo "<a ".(isset($res) && $res == $v ? " style='color:black'" : null)." href='".url_vars($firstpage, null, $v)."'>$v</a>\n";
	echo "</td>\n</tr>\n";
	
	// navigation on top
	echo "<tr class='nav'>\n<td  colspan='{$colmax}'>\n";
	echo "<a ".(($page == 1) ? " style='color:black'" : null)." href='".url_vars($firstpage, $ratio, $res)."'>First </a>\n";
	echo "<a ".(($page == 1) ? " style='color:black'" : null)." href='".url_vars($prevpage, $ratio, $res)."'>Prev </a>\n";
	for($i = $start; $i <= $end; $i++)
		echo "<a ".(($i == $page) ? " style='color:black'" : null)." href='".url_vars($i, $ratio, $res)."'>".sprintf("%03s",$i)."</a>\n";
	echo "<a ".(($page == $lastpage) ? " style='color:black'" : null)." href='".url_vars($nextpage, $ratio, $res)."'> Next</a>\n";
	echo "<a ".(($page == $lastpage) ? " style='color:black'" : null)." href='".url_vars($lastpage, $ratio, $res)."'> Last</a>\n";
	echo "</td>\n</tr>\n";
		
	// populate table with thumbnails and links
	echo "<tr class='images'>\n";
	foreach($wallpapers as $wp) {
		$dim = getimagesize($thumbdir . substr($wp['filename'], 0, -4) . ".jpg");
		if($counter % $colmax == 0)
			echo "</tr>\n<tr>\n";
		echo "<td valign='middle'>
			<a class='picture' rel='wallpapers' title='" . $wp['filename'] . "' href='" . ($dir . $wp['filename']) . "'>
			<img src='" . ($thumbdir . substr($wp['filename'], 0, -4)) . ".jpg'";
		if($dim[0] > $dim[1])
			echo "width=" . (floor(960/$colmax) - 10);
		else
			echo "height=200";
		echo "/>
			</a>
			</td>";
		$counter++;
	}
	if($counter < $interval) {
		$last = end($wallpapers);
		$dim = getimagesize($thumbdir . substr($wp['filename'], 0, -4) . ".jpg");
		for($i = $counter; $i < $interval; $i++) {
			if($i % $colmax == 0)
				echo "</tr>\n<tr>\n";
			echo "<td width=" . (floor(960/$colmax) - 10) . " height=" . $dim[1] . "\n</td>";
		}
	}
	echo "</tr>\n";	
	
	// navigation on bottom
	echo "<tr class='nav'>\n<td  colspan='{$colmax}'>\n";
	echo "<a ".(($page == 1) ? " style='color:black'" : null)." href='".url_vars($firstpage, $ratio, $res)."'>First </a>\n";
	echo "<a ".(($page == 1) ? " style='color:black'" : null)." href='".url_vars($prevpage, $ratio, $res)."'>Prev </a>\n";
	for($i = $start; $i <= $end; $i++)
		echo "<a ".(($i == $page) ? " style='color:black'" : null)." href='".url_vars($i, $ratio, $res)."'>".sprintf("%03s",$i)."</a>\n";
	echo "<a ".(($page == $lastpage) ? " style='color:black'" : null)." href='".url_vars($nextpage, $ratio, $res)."'> Next</a>\n";
	echo "<a ".(($page == $lastpage) ? " style='color:black'" : null)." href='".url_vars($lastpage, $ratio, $res)."'> Last</a>\n";
	echo "</td>\n</tr>\n";
	
	// table ends	
	echo "</table>\n";
	
	/*	might be useful later
	$r  = new HttpRequest('http://api.bit.ly/v3/shorten', HttpRequest::METH_GET);
	$r->addQueryData(array('login' => $login, 'apikey' => $apikey, 'longUrl' => urlencode($url), 'format' => 'txt'));
	try {
		$r->send();
		if ($r->getResponseCode() == 200)
			$response = $r->getResponseBody();
	} catch (HttpException $ex) {
		echo $ex;
	}
	*/
?>
</body>
</html>
