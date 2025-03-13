<?php
	$tables = array("male_names" => array("label" => "Male Names", "func" => false, "rolls" => 2),
					"female_names" => array("label" => "Female Names", "func" => false, "rolls" => 2),
					"gemstones" => array("label" => "Gemstones", "func" => true, "rolls" => 1),
					"wizard_items" => array("label" => "Items in a Wizard's Chamber", "func" => true, "rolls" => 1),
					"alchemist_items" => array("label" => "Items in an Alchemist's Lab", "func" => true, "rolls" => 1),
					);
?>
<!DOCTYPE html>
<html>
<head>
	<title>More Random Generators</title>
	<style type="text/css">
		body {
			padding: 0;
			margin: 0;
		}
		
		button {
			margin: 0;
			margin-right: 0.5rem;
		}
		
		.half_block {
			background-color: #f8f8f8;
			border: 5px ridge #666666;
			border-radius: 5px;
			float: left;
			margin: 0.1%;
			padding: 0.3%;
			width: 48%;
		}
		
		.load {
			background-image: url('img/ajax-loader2.gif');
			background-repeat: no-repeat;
			background-position: bottom right; 
		}
		
		.print_to_console {
			float: right;
		}	
		
		#roll_container {
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			justify-content: flex-start;
			align-items: stretch;
    
			clear: both;
			width: 100%;
			margin-top: 2.5rem;
		}		
		
		#roll_container .roll_block {
			clear: both;
			display: block;
		}
		
		#roll_container .roll_block .roll_number {			
			clear: both;
			color: black;
			display: none;
			margin-right: 10px;
			
			height: auto;
			width: auto;
		}
		
		#roll_container .indent_block {
			clear: both;
			display: block;
			padding-left: 1%;
			padding-right: 1%;
			padding-top: 1%;
		}
		
		#roll_container .indent_block label {			
			display: inline-block;
			margin-bottom: 1rem;
			text-decoration: underline;
			vertical-align: top;
			width: 34%;
		}
		
		#roll_container .indent_block label:last-of-type,
		#roll_container .indent_block .textarea:last-of-type {
			margin-bottom: 0;
		}
		
		#roll_container .indent_block .textarea {			
			display: inline-block;
			margin-bottom: 1rem;
			text-align: right;
			width: 64%;
		}
		
		button#s_all_roll {
			position: fixed;
			left: 1%;
			top: 1%;
		}
		
		@media only screen and (max-width: 600px) {
			.half_block {
				float: none;
				margin: 0.5% 0;
				padding: 1%;
				width: 100%;
			}
		}
	</style>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script type="text/javascript">
		function getRandomInt(max) {
			return Math.floor(Math.random() * max);
		}
		
		function randomNumberRange(min, max) { 
			min = Math.ceil(min);
			max = Math.floor(max);
			return Math.floor(Math.random() * (max - min + 1)) + min;
		}
		
		function generateRandomColor() {
		  var letters = '0123456789ABCDEF';
		  var color = '';
		  for (var i = 0; i < 6; i++) {
			color += letters[Math.floor(Math.random() * 16)];
		  }
		  return color;
		}	
		
		function getTableResult(rollID, data) {
			var randMax = Object.keys(data).length;
			var rand = getRandomInt(randMax) + 1;
			jQuery(rollID).html(rand);
			jQuery(rollID).show();
			
			var result;
			jQuery.each(data, function(key, val) {
				range = key.split("-");
				if(range[1] === undefined) { range[1] = range[0]; }
				if(range[0] <= rand && rand <= range[1]) {
					result = val;
					return false;
				}
			});
			return result;
		}
		
		function printToConsole(tablename) {
			var filename = "json/" + tablename + ".json?nocache=" + Math.random();
			jQuery.getJSON(filename, function (data) {
				console.log(data);
			});
		}
		
		<?php
			foreach($tables as $name => $props):
				if(!$props["func"]):
					continue;					
				else:
					?>
					function get_<?=$name;?>() {
						var tablename = "<?=$name;?>";
						var filename = "json/" + tablename + ".json?nocache=" + Math.random();
						jQuery.getJSON(filename, function (data) {
							item = getTableResult('#<?=$name;?>_num1', data);
						});
						jQuery('#<?=$name;?>').html(item['result']);
					}
					<?php
				endif;
			endforeach;
		?>
		
		function get_male_names() {
			var first, last;
			var tablename = "male_names";
			var filename = "json/" + tablename + ".json?nocache=" + Math.random();
			jQuery.getJSON(filename, function (data) {				
				first = getTableResult("#" + tablename + "_num1", data);
			});
			jQuery.getJSON(filename, function (data) {
				last = getTableResult("#" + tablename + "_num2", data);
			});
			jQuery("#" + tablename).html(first["result"] + " " + last["result"]);
		}
		
		function get_female_names() {
			var first, last;
			var tablename = "female_names";
			var filename = "json/" + tablename + ".json?nocache=" + Math.random();
			jQuery.getJSON(filename, function (data) {				
				first = getTableResult("#" + tablename + "_num1", data);
			});
			jQuery.getJSON(filename, function (data) {
				last = getTableResult("#" + tablename + "_num2", data);
			});
			jQuery("#" + tablename).html(first["result"] + " " + last["result"]);
		}		
		
		jQuery(function() {
			jQuery.ajaxSetup({
				async: false
			});		
			
			jQuery("button#all_roll").on("click", function() {
				<?php
					foreach($tables as $name => $props):
						echo "get_{$name}();\n";
					endforeach;
				?>
			});
			<?php
				foreach($tables as $name => $props):
					echo "jQuery('button#{$name}_roll').on('click', get_{$name});\n";
				endforeach;
			?>
		});
	</script>
</head>
<body>
	<button id='all_roll'>Roll Everything!</button>
	<div id="roll_container">
		<?php
			foreach($tables as $name => $props):
			?>
				<div class='half_block'>
					<span class='roll_block'>
						<button id='<?=$name;?>_roll'>Roll!</button>
						<?php for($i = 1; $i <= $props["rolls"]; $i++): ?>
							<button id='<?=$name;?>_num<?=$i;?>' class='roll_number' /></button>
						<?php endfor; ?>
						<button id='<?=$name;?>_debug' class='print_to_console' onclick='printToConsole("<?=$name;?>")'>Print to Console</button>
					</span>
					<span class='indent_block'>
						<label><?=$props["label"];?></label>
						<div id='<?=$name;?>' class='textarea'/></div>
					</span>
				</div>
			<?php
			endforeach;
		?>				
	</div>
</body>
</html>
