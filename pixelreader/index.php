<?PHP
	// 
	function coldiff($R1,$G1,$B1,$R2,$G2,$B2)
	{
		return max(hexdec($R1),hexdec($R2)) - min(hexdec($R1),hexdec($R2)) +
			   max(hexdec($G1),hexdec($G2)) - min(hexdec($G1),hexdec($G2)) +
			   max(hexdec($B1),hexdec($B2)) - min(hexdec($B1),hexdec($B2));
	}
	
	if(isset($_GET['location'])) $imgloc = base64_decode($_GET['location']);
	$palette = Array();
	$pixelcount = $cellcount = 0;
	
	// If a path to an image was in the URL, determine what kind of image it is
	// and if we can support it.  If we can't or no path was given, go to the default Mario picture.
	if(isset($imgloc) && ($imgattrs = getimagesize($imgloc))) 
	{
		$pathparts = pathinfo($imgloc);
		if($imgattrs[2] == 1 && strcasecmp($pathparts['extension'], "gif") == 0)
			$img = imagecreatefromgif($imgloc);
		else if($imgattrs[2] == 2 && strcasecmp($pathparts['extension'], "jpg") == 0)
			$img = imagecreatefromjpeg($imgloc); 
		else if($imgattrs[2] == 3 && strcasecmp($pathparts['extension'], "png") == 0)
			$img = imagecreatefrompng($imgloc);
		else
		{
			$err = "Sorry, Pixel Reader does not support that image type.\n";
			$imgloc = "img/mario_8.png";
			$imgattrs = getimagesize($imgloc);
			$pathparts = pathinfo($imgloc);
			$img = imagecreatefrompng($imgloc);
		}
		list($w, $h) = $imgattrs;
	}
	else
	{
		$imgloc = "img/mario_8.png";
		$imgattrs = getimagesize($imgloc);
		$pathparts = pathinfo($imgloc);
		list($w, $h) = $imgattrs;
		$img = imagecreatefrompng($imgloc);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Pixel Reader</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="shortcut icon" href="img/fleur.ico" />
	<link rel="stylesheet" href="../../css/pixel.css" type="text/css" />
	<link rel="stylesheet" href="../../js/fancybox/jquery.fancybox-1.3.1.css" type="text/css" media="screen" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>	
	<script type="text/javascript" src="../../js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
	<script type="text/javascript" src="../../js/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript">
	<!-- 
		var factor = 1;
		var colors = false;
		var height, width, maxheight, maxwidth;
				
		// Recalculates the size and position of the picture
		function resize()
		{
			if(factor < 1)
				factor = 1;
			else if(factor * height >= maxheight || factor * width >= maxwidth)
				factor -= 1;
			else
			{
				$('div#container').css('height', factor * height);
				$('div#container').css('width', factor * width);
				$('div#container > div').removeClass().addClass('_' + factor);
				$('div#container').css('margin-top',  ($(window).height() + $('div.botbar').height() - $('div#container').height())/2 + "px");
			}
		}
		
		// A simple Fancybox for newcomers.  Explains site functions.
		function instructions()
		{
			$.fancybox(
				'<h2>Hello and welcome to Pixel Reader</h2><br />' +
				'<p>The purpose of this site is simple - ' + 
				'view your favorite images rendered entirely with markup language.' +
				' Use the buttons on the top and bottom bars ' +
				'to see more pictures and features.</p><br /><p>Also, here are some' +
				'shortcuts you can use...</p><br /><center><table>' +
				'<tr><td>Grow Picture</td><td>Mousewheel Up, +, I</td></tr>' +
				'<tr><td>Shrink Picture</td><td>Mousewheel Down, -, O</td></tr>' +
				'<tr><td>Reset Picture</td><td>0 (zero), R</td></tr>'+
				'<tr><td>This Window</td><td>Spacebar</td></tr></table></center>',
				{
					'autoDimensions'	: false,
					'height'        	: 'auto',
					'scrolling' 		: 'no', 
					'width'         	: 350					
				}
			);
		}
		
		$(function() {				
			$('div.botbar').css('left', ($(window).width() - $('div.botbar').width())/2 + "px");
			$('div.topbar').css('left', ($(window).width() - $('div.topbar').width())/2 + "px");
			$('div#container').css('margin-top',  ($(window).height() + $('div.botbar').height() - $('div#container').height())/2 + "px");
			$("button#inline").fancybox(
				{
					'content' 		: $('#palette').html(), 
					'scrolling' 	: 'no', 
					'titlePosition' : 'inside'
				}
			);
			$('div.botbar').fadeIn('slow');
			$('div.topbar').fadeIn('slow');
			
			height = $('div#container').height();
			width = $('div#container').width();
			maxheight = $(window).height() - $('div.botbar').height() - $('div.topbar').height();
			maxwidth = $(window).width();
			
			// This allows the mousewheel to shrink and grow the picture
			$(document).bind('mousewheel', function(event, delta) {
				delta > 0 ? factor += 1 : factor -= 1;
				resize();
				return false;
			});
			
			// Listen for certain keys
			$(document).bind('keypress', function(event) {
				var key = event.which;
				// This listens for the number 0 and R key
				if(key == '48' || key == '114')
				{
					factor = 1;
					resize();
					return false;
				}
				// This listens for the - key or the O key
				if(key == '45' || key == '111')
				{
					factor -= 1;
					resize();
					return false;
				}
				// This listens for the + key or the I key
				if(key == '61' || key == '105')
				{
					factor += 1;
					resize();
					return false;
				}
				// This listens for the spacebar
				if(key == '32')
				{
					instructions();
					return false;
				}
			});
			
			// This will reposition the bars on the page to the center of the browser window when it gets resized
			// It will also prevent a picture from being too big for a browser window when it gets resized
			$(window).bind('resize', function(event)	{
				var diff1 = $('div#container').height() - $(window).height();
				var diff2 = $('div#container').width() - $(window).width();
				maxheight = $(window).height() - $('div.botbar').height() - $('div.topbar').height();
				maxwidth = $(window).width();
				if(diff1 > 0 || diff2 > 0)
				{
					if(diff1 > diff2)
						factor = Math.floor((maxheight/height));
					else
						factor = Math.floor((maxwidth/width));
				}
				resize();
				$('div.botbar').css('left', ($(window).width() - $('div.botbar').width())/2 + "px");
				$('div.topbar').css('left', ($(window).width() - $('div.topbar').width())/2 + "px");
			});
		});
	// -->
	</script>
	<style>
		<?php
			for($i = 1; $i <= 50; $i++):
				echo "#container > div._{$i} { height:{$i}px; width:{$i}px; }\n";
			endfor;
		?>
	</style>
</head>
<body>
<?php 
	// The bar on the top of the browser window
	echo "<div class='topbar' style='display: none'>\r\n<div>\r\n";
	echo "<button id='intro' style='clear: both' onclick='instructions();'>First Time Here?</button>\r\n</div>\r\n";
	echo "<div>\r\n<button id='grow' class='float'  onclick='factor+=1;resize();'>Grow</button>\r\n";
	echo "<button class='float' id='inline' title='{$pathparts['filename']}&rsquo;s color palette.'>Palette</button>\r\n";
	echo "<button class='float' id='shrink' onclick='factor-=1;resize();'>Shrink</button>\r\n";
	echo "</div>\r\n</div>\r\n";
	
	// The drawing of the picture, line by line
	echo "<div id='container' style='width: {$w}px; height: {$h}px;'>\r\n";
	for($y = 0; $y < $h; $y++) { 
		$prevcolor = $pixelcount = 0;
		for($x = 0; $x < $w; $x++) {
			// Find the color code of the pixel of the given image at coordinates (x, y)
			$rgb = imagecolorat($img, $x, $y);
			$newcolor = ((($rgb & 0x7F000000) >> 24) == 127 ? "transparent" : sprintf('#%02X%02X%02X', (($rgb >> 16) & 0xFF), (($rgb >> 8) & 0xFF), ($rgb & 0xFF)));
			//if((!empty($prevcolor) && ($prevcolor != $newcolor))) {
				echo "<div class='_1' style='background: {$newcolor}; float: left;'></div>\r\n";
				if(!in_array($prevcolor, $palette) && $prevcolor != "transparent")
					$palette[] = $prevcolor;
				//$pixelcount = 0;
			//}
			$prevcolor = $newcolor;
			//$pixelcount++;
		}
	} 
	
	// This prevents a picture with a very large palette from
	// going off-screen or taking up a huge chunk of the browser window
	$rowmax = floor(sqrt(count($palette)));
	if($rowmax > 7)
		$font = (7/$rowmax * 12) . "pt";
	else
		$font = "1em";
		
	$url = "http://www.imagemagick.org/Usage/misc/interpolate_bilinear.jpg";
	
	// The bar on the bottom of the browser window
	echo "</div>\r\n";
	echo "<div class='botbar' style='display: none'>\r\n"; 
	echo "<a style='background: green;'		href='?location=". base64_encode("img/link_8.png")		."'>Link</a>\r\n";
	echo "<a style='background: green;'		href='?location=". base64_encode("img/link_16.png")		."'>Link 16</a>\r\n";
	echo "<a style='background: blue;'		href='?location=". base64_encode("img/megaman_8.png")	."'>Megaman</a>\r\n";
	echo "<a style='background: orange;'	href='?location=". base64_encode("img/samus_8.png")		."'>Samus</a>\r\n";
	echo "<a style='background: red;'		href='?location=". base64_encode("img/mario_8.png")		."'>Mario</a>\r\n";
	echo "<a style='background: #0000CD;'	href='?location=". base64_encode("img/sonic.png")		."'>Sonic</a>\r\n";
	echo "<a style='background: #0000CD;'	href='?location=". base64_encode($url)					."'>URL</a>\r\n";
	echo "<a style='background: purple;'	href='?location=". base64_encode("img/ryu_8.png")		."' class='lastlink'>Ryu</a>\r\n";
	echo "</div>\r\n<div style='display:none'>\r\n<div id='palette'>\r\n";
	
	// This table is hidden until a user clicks the 'First Time Here?' button
	echo "<table class='palette'>\r\n<tr>\r\n";
	foreach($palette as $color)
	{
		echo "<td style='background: {$color}; font-size: {$font};";
		if(coldiff(substr($color, 1, 2), substr($color, 3, 2), substr($color, 5, 2), 0, 0, 0) <= 400)
			echo " color: white;";
		else
			echo " color: black;";
		echo "'>{$color}</td>\r\n";
		
		$cellcount++;
		if($cellcount == $rowmax)
		{
			$cellcount = 0;
			echo "</tr>\r\n<tr>\r\n";
		}
	}
	echo "</tr>\r\n</table>\r\n</div>\r\n</div>\r\n";
?> 
</body>
</html>
