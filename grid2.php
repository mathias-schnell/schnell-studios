<?
	$colors = Array("red", "orange", "yellow", "green", "blue", "indigo", 
					"violet", "black");
	$modes = Array("Brush" => "brush",
					"Horizontal Line" => "horline", 
					"Vertical Line" => "verline",
					"Horizontal Tube" => "hortube",
					"Vertical Tube" => "vertube",
					"Square" => "square");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Block Placement Helper</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.21" />
	<link href="../img/fleur.ico" rel="shortcut icon" />
	<link type="text/css" href="../css/main.css" rel="stylesheet" />
	<link type="text/css" href="../js/jquery-ui-1.8.18.custom/css/custom-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js"></script>
	<script type='text/javascript'>
	<!--
	// Independent Variables
	var borderSize = 1;
	var gridHeightPercent = 0.85
	var padding = 5;
	var squaresPerRow = 29;
		
	function drawGrid()	{
		var scaling = ($(window).height())/1200;
		var height = $(window).height() * gridHeightPercent;
		var squareSize = Math.round(height/squaresPerRow);
		var miniSquareSize = squareSize/4;
		var gridSize = squaresPerRow * squareSize;		
		$('#grid2').empty();
		for(i = 0; i < squaresPerRow; i++)	{
			for(j = 0; j < squaresPerRow; j++)	{
				$('<div class="square2" id="' + i + '_' + j + '" ' 
					+ 'style="border: black dotted ' + borderSize + 'px; '
					+ 'px; top:' + (squareSize * i)
					+ 'px; left:' + (squareSize * j)
					+ 'px; width: ' + (squareSize - borderSize)
					+ 'px; height:' + (squareSize - borderSize)
					+ 'px;">'
					+ '<div style="position: absolute; '
					+ 'width: ' + miniSquareSize
					+ 'px; height:' + miniSquareSize
					+ 'px; top:' + (squareSize - miniSquareSize)/2
					+ 'px; left:' + (squareSize - miniSquareSize)/2
					+ 'px;"></div></div>')
					.appendTo('#grid2');
			}
		}
		$('#grid2').css({
			'height'		: gridSize + borderSize,
			'margin-left' 	: Math.round(-(gridSize/2)), 
			'margin-top' 	: Math.round(-(gridSize/2)),
			'width' 		: gridSize + borderSize
		});		
		$("#control_panel").css({
			'height'		: (95 - (100 * gridHeightPercent))/2 + "%",
			'padding'		: padding * scaling
		});		
		$("#debug_panel").css({
			'height'		: (95 - (100 * gridHeightPercent))/2 + "%",
			'padding'		: padding * scaling
		});
		$('div#mode_select').css('font-size', scaling + 'em');
		$('div#color_select').css('font-size', scaling + 'em');
	}
	
	function resizeGrid() {
		var scaling = Math.pow(($(window).height())/1200, 2);
		var height = $(window).height() * gridHeightPercent;
		var squareSize = Math.round(height/squaresPerRow);
		var miniSquareSize = squareSize/4;
		var gridSize = squaresPerRow * squareSize;
		for(i = 0; i < squaresPerRow; i++)	{
			for(j = 0; j < squaresPerRow; j++)	{
				$('div#' + i + '_' + j).css({
					'top'		:	(squareSize * i),
					'left'		:	(squareSize * j),
					'width'		:	(squareSize - borderSize),
					'height'	:	(squareSize - borderSize)
				});
				$('div#' + i + '_' + j + ' div').css({
					'top'		:	miniSquareSize + borderSize,
					'left'		:	miniSquareSize + borderSize,
					'width'		:	(squareSize - miniSquareSize)/2,
					'height'	:	(squareSize - miniSquareSize)/2
				});
			}
		}		
		$('#grid2').css({
			'height'		: gridSize + borderSize,
			'margin-left' 	: Math.round(-(gridSize/2)), 
			'margin-top' 	: Math.round(-(gridSize/2)),
			'width' 		: gridSize + borderSize
		});
		$("#control_panel").css({
			'height'		: (95 - (100 * gridHeightPercent))/2 + "%",
			'padding'		: padding * scaling
		});		
		$("#debug_panel").css({
			'height'		: (95 - (100 * gridHeightPercent))/2 + "%",
			'padding'		: padding * scaling
		});
		$('div#mode_select').css({
			'font-size'		: scaling + 'em',
			'padding'		: "*=" + scaling,
			'margin'		: "*=" + scaling
		});
		$('div#color_select').css({
			'font-size'		: scaling + 'em',
			'padding'		: "*=" + scaling,
			'margin'		: "*=" + scaling
		});
	}
	
	function clearGrid() {
		$('div.square2').css("background-color", "white");
	}
	
	$(function() {
		var modeStr = 'input:radio[name="mode"]';
		var colorStr = 'input:radio[name="color"]';
		
		drawGrid();
		$(modeStr).first().attr('checked', true);
		$(colorStr).first().attr('checked', true);
		$('div#mode_select').buttonset();
		$('div#color_select').buttonset();
		
		$(".square2").on({
			click: function() {
				var id = $(this).attr('id');
				var row = parseInt(id.split("_")[0]);
				var col = parseInt(id.split("_")[1]);
				var color = $(colorStr + ':checked').val();
				if($(modeStr + ':checked').val() == 'brush')
					$('div[id=' + row + '_' + col +']').css("background-color", color);
				if($(modeStr + ':checked').val() == 'horline')
					$('div[id^="' + row +'_"]').css("background-color", color);
				if($(modeStr + ':checked').val() == 'verline')
					$('div[id$="_' + col + '"]').css("background-color", color);
				if($(modeStr + ':checked').val() == 'square') {
					var str = "", radius = 3;
					var y1 = row - radius;
					var y2 = row + radius;
					var x1 = col - radius;
					var x2 = col + radius;
					for(i = y1; i <= y2; i++) {
						if(i >= 0 && x1 >= 0)
							str += ",#" + i + "_" + x1;
						if(i >= 0 && x2 >= 0)
							str += ",#" + i + "_" + x2;
					}
					for(i = x1; i <= x2; i++) {
						if(i >= 0 && y1 >= 0)
							str += ",#" + y1 + "_" + i;
						if(i >= 0 && y2 >= 0)
							str += ",#" + y2 + "_" + i;
					}
					str = str.substring(1);
					$(str).css("background-color", color);
				}
				if($(modeStr + ':checked').val() == 'circle') {
					var str = "", radius = 3;
				}
				if($(modeStr + ':checked').val() == 'x') {
					var str = "";
				}
				if($(modeStr + ':checked').val() == 'vertube') {
					var str = "", radius = 3;
					var x1 = col - radius;
					var x2 = col + radius;
					$('div[id$="_' + x1 + '"]').css("background-color", color);
					$('div[id$="_' + x2 + '"]').css("background-color", color);
				}
				if($(modeStr + ':checked').val() == 'hortube') {
					var str = "", radius = 3;
					var y1 = row - radius;
					var y2 = row + radius;
					$('div[id^="' + y1 +'_"]').css("background-color", color);
					$('div[id^="' + y2 +'_"]').css("background-color", color);
				}
			},
			
			dblclick: function() {
				var id = $(this).attr('id');
				var row = parseInt(id.split("_")[0]);
				var col = parseInt(id.split("_")[1]);
				if($(modeStr + ':checked').val() == 'brush')
					$('div[id=' + row + '_' + col +']').css("background-color", "");
				if($(modeStr + ':checked').val() == 'horline')
					$('div[id^="' + row +'_"]').css("background-color", "");
				if($(modeStr + ':checked').val() == 'verline')
					$('div[id$="_' + col + '"]').css("background-color", "");
				if($(modeStr + ':checked').val() == 'square') {
					var radius = 3;
					var y1 = row - radius;
					var y2 = row + radius;
					var x1 = col - radius;
					var x2 = col + radius;
					var str = "";
					for(i = y1; i <= y2; i++) {
						if(i >= 0 && x1 >= 0)
							str += ",#" + i + "_" + x1;
						if(i >= 0 && x2 >= 0)
							str += ",#" + i + "_" + x2;
					}
					for(i = x1; i <= x2; i++) {
						if(i >= 0 && y1 >= 0)
							str += ",#" + y1 + "_" + i;
						if(i >= 0 && y2 >= 0)
							str += ",#" + y2 + "_" + i;
					}
					str = str.substring(1);
					$(str).css("background-color", "");
				}
				if($(modeStr + ':checked').val() == 'circle') {
					var str = "", radius = 3;
				}
				if($(modeStr + ':checked').val() == 'x') {
					var str = "";
				}
				if($(modeStr + ':checked').val() == 'vertube') {
					var str = "", radius = 3;
					var x1 = col - radius;
					var x2 = col + radius;
					$('div[id$="_' + x1 + '"]').css("background-color", "");
					$('div[id$="_' + x2 + '"]').css("background-color", "");
				}
				if($(modeStr + ':checked').val() == 'hortube') {
					var str = "", radius = 3;
					var y1 = row - radius;
					var y2 = row + radius;
					$('div[id^="' + y1 +'_"]').css("background-color", "");
					$('div[id^="' + y2 +'_"]').css("background-color", "");
				}
			},
			
			mouseenter: function() {
				var id = $(this).attr('id');
				var row = parseInt(id.split("_")[0]);
				var col = parseInt(id.split("_")[1]);
				var color = $(colorStr + ':checked').val();
				if($(modeStr + ':checked').val() == 'brush')
					$('div[id=' + row + '_' + col +'] div').css("background-color", color);
				if($(modeStr + ':checked').val() == 'horline')
					$('div[id^="' + row +'_"] div').css("background-color", color);
				if($(modeStr + ':checked').val() == 'verline')
					$('div[id$="_' + col + '"] div').css("background-color", color);
				if($(modeStr + ':checked').val() == 'square') {
					var radius = 3;
					var y1 = row - radius;
					var y2 = row + radius;
					var x1 = col - radius;
					var x2 = col + radius;
					var str = "";
					for(i = y1; i <= y2; i++) {
						if(i >= 0 && x1 >= 0)
							str += ",#" + i + "_" + x1 + " div";
						if(i >= 0 && x2 >= 0)
							str += ",#" + i + "_" + x2 + " div";
					}
					for(i = x1; i <= x2; i++) {
						if(i >= 0 && y1 >= 0)
							str += ",#" + y1 + "_" + i + " div";
						if(i >= 0 && y2 >= 0)
							str += ",#" + y2 + "_" + i + " div";
					}
					str = str.substring(1);
					$(str).css("background-color", color);
				}				
				if($(modeStr + ':checked').val() == 'circle') {
					var str = "", radius = 3;
				}
				if($(modeStr + ':checked').val() == 'x') {
					var str = "";
				}
				if($(modeStr + ':checked').val() == 'vertube') {
					var str = "", radius = 3;
					var x1 = col - radius;
					var x2 = col + radius;
					$('div[id$="_' + x1 + '"] div').css("background-color", color);
					$('div[id$="_' + x2 + '"] div').css("background-color", color);
				}
				if($(modeStr + ':checked').val() == 'hortube') {
					var str = "", radius = 3;
					var y1 = row - radius;
					var y2 = row + radius;
					$('div[id^="' + y1 +'_"] div').css("background-color", color);
					$('div[id^="' + y2 +'_"] div').css("background-color", color);
				}
			},
			
			mouseleave: function() {
				var id = $(this).attr('id');
				var row = parseInt(id.split("_")[0]);
				var col = parseInt(id.split("_")[1]);
				if($(modeStr + ':checked').val() == 'brush')
					$('div[id=' + row + '_' + col +'] div').css("background-color", "");
				if($(modeStr + ':checked').val() == 'horline')
					$('div[id^="' + row +'_"] div').css("background-color", "");
				if($(modeStr + ':checked').val() == 'verline')
					$('div[id$="_' + col + '"] div').css("background-color", "");
				if($(modeStr + ':checked').val() == 'square') {
					var radius = 3;
					var y1 = row - radius;
					var y2 = row + radius;
					var x1 = col - radius;
					var x2 = col + radius;
					var str = "";
					for(i = y1; i <= y2; i++) {
						if(i >= 0 && x1 >= 0)
							str += ",#" + i + "_" + x1 + " div";
						if(i >= 0 && x2 >= 0)
							str += ",#" + i + "_" + x2 + " div";
					}
					for(i = x1; i <= x2; i++) {
						if(i >= 0 && y1 >= 0)
							str += ",#" + y1 + "_" + i + " div";
						if(i >= 0 && y2 >= 0)
							str += ",#" + y2 + "_" + i + " div";
					}
					str = str.substring(1);
					$(str).css("background-color", "");
				}
				
				if($(modeStr + ':checked').val() == 'circle') {
					var str = "", radius = 3;
				}
				if($(modeStr + ':checked').val() == 'x') {
					var str = "";
				}
				if($(modeStr + ':checked').val() == 'vertube') {
					var str = "", radius = 3;
					var x1 = col - radius;
					var x2 = col + radius;
					$('div[id$="_' + x1 + '"] div').css("background-color", "");
					$('div[id$="_' + x2 + '"] div').css("background-color", "");
				}
				if($(modeStr + ':checked').val() == 'hortube') {
					var str = "", radius = 3;
					var y1 = row - radius;
					var y2 = row + radius;
					$('div[id^="' + y1 +'_"] div').css("background-color", "");
					$('div[id^="' + y2 +'_"] div').css("background-color", "");
				}
			}
		});
	});
	// -->
	</script>
</head>
<body id="gridbg" onResize="resizeGrid();">
	<div id="grid2">
	</div>
	<div id="control_panel">
		<div id='mode_select'>
			<?
				$c = 0;
				foreach($modes as $k=>$v) {
					echo "<input type='radio' id='mode_radio" . ($c + 1) . "' name='mode' value='$v' />
					<label for='mode_radio" . ($c + 1) . "'>" . $k . "</label>";
					$c++;
				}
			?>
		</div>
		<div id='options'>
			<div id='color_select'>
				<? 
					foreach($colors as $k=>$v)
						echo "<input type='radio' id='color_radio" . ($k + 1) . "' name='color' value='$v' />
						<label for='color_radio" . ($k + 1) . "'>" . ucfirst($v) . "</label>";
				?>
			</div>
			<!-- Width: <input type='text' id='radius' name='radius' maxlength='2' size='2' value='3' /> -->
		</div>
	</div>
	<div id="debug_panel">
		<button onClick='clearGrid();'>Clear</button> <br />
		<button>Click on the grid to apply color.  Double click to remove.</button>
	</div>
</body>
</html>
