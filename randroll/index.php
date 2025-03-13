<!DOCTYPE html>
<html>
<head>
	<title>Random Settlement Generator</title>
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
		
		function getTableResult(randMax, rollID, data) {
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
		
		function getSettlementName() {
			var prefix, suffix;
			
			jQuery.getJSON("json/s_pref.json", function (data) {
				prefix = getTableResult(100, "#s_name_num1", data);
			});
			jQuery.getJSON("json/s_suff.json", function (data) {
				suffix = getTableResult(100, "#s_name_num2", data);
			});
			jQuery("#s_name").html(prefix["result"].replace("-", "") + suffix["result"].replace("-", ""));
		}
		
		function getSettlementGeography() {
			var geo;
			
			jQuery.getJSON("json/s_geog.json", function (data) {
				geo = getTableResult(100, "#s_geog_num", data);
			});
			jQuery("#s_geog").html(geo["result"]);
		}
		
		function getSettlementType() {
			var type;
			
			jQuery.getJSON("json/s_type.json", function (data) {
				type = getTableResult(8, "#s_type_num", data);
			});
			jQuery("#s_type").html(type["result"]);
			jQuery("#s_type_population").html(type["population"]);
			jQuery("#s_type_businesses").html(type["businesses"]);
			jQuery("#s_type_govt").html(type["govt"]);
			
			if((type["govt"] == "No") || (type["govt"] == "Sometimes" && getRandomInt(2) == 0)) {
				jQuery("#s_govt").hide();
			} else {
				jQuery("#s_govt").show();
			}
		}
		
		function getSettlementAge() {
			var age;
			
			jQuery.getJSON("json/s_age.json", function (data) {
				age = getTableResult(6, "#s_age_num", data);
			});
			jQuery("#s_age").html(age["result"]);
			jQuery("#s_age_desc").html(age["description"]);
		}
		
		function getSettlementGovernment() {
			var govt;
					
			jQuery.getJSON("json/s_govt.json", function (data) {
				govt = getTableResult(100, "#s_govt_num", data);				
				console.log(data);
				console.log(govt);
			});			
			jQuery("#s_govt").html(govt["result"]);
		}
		
		function getSettlementShops(event, numshops = 1) {	
			var shops = [], shop;
			var range = jQuery("#s_type_businesses").html();
			
			if(range) {
				var range = range.split("-");
				if(range[0].indexOf("+") > -1) {
					range[0] = parseInt(range[0]);
					range[1] = Math.floor(range[0] + (range[0] * 0.33));
				}
				numshops = randomNumberRange(range[0], range[1]);
			}
			
			jQuery.getJSON("json/s_shop.json", function (data) {
				for(i = 0; i < numshops; i++) {
					shop = getTableResult(100, "#s_shop_num", data);
					shops.push(shop["result"]);
				}
			});
			jQuery("#s_shop").html("(" + shops.length + ") " + shops.join(", "));
		}
		
		function getSettlementKnownFor() {
			var know;
						
			jQuery.getJSON("json/s_know.json", function (data) {
				know = getTableResult(100, "#s_know_num", data);
			});			
			jQuery("#s_know").html(know["result"]);
		}
		
		function getSettlementExports() {
			var expo;
						
			jQuery.getJSON("json/s_expo.json", function (data) {
				expo = getTableResult(100, "#s_expo_num", data);
			});			
			jQuery("#s_expo").html(expo["result"]);
		}
		
		function getSettlementEncounters() {
			var enco;
						
			jQuery.getJSON("json/s_enco.json", function (data) {
				enco = getTableResult(100, "#s_enco_num", data);
			});			
			jQuery("#s_enco").html(enco["result"]);
		}
		
		function getSettlementProblems() {
			var prob;
			
			jQuery.getJSON("json/s_prob.json", function (data) {
				prob = getTableResult(100, "#s_prob_num", data);
			});			
			jQuery("#s_prob").html(prob["result"]);
		}
		
		function getSettlementRuler() {
			var rule_race, rule_age, rule_result;	
					
			jQuery.getJSON("json/s_rule_race.json", function (data) {				
				rule_race = getTableResult(12, "#s_rule_race_num", data);
			});
			jQuery("#s_rule_race").html(rule_race["result"]);
			
			jQuery.getJSON("json/s_rule_age.json", function (data) {
				rule_age = getTableResult(8, "#s_rule_age_num", data);
			});
			jQuery("#s_rule_age").html(rule_age["result"]);
			
			jQuery.getJSON("json/s_rule_trait.json", function (data) {
				rule_trait = getTableResult(100, "#s_rule_trait_num", data);
			});
			jQuery("#s_rule_trait").html(rule_trait["result"]);
		}
		
		jQuery(function() {
			jQuery.ajaxSetup({
				async: false
			});		
			
			jQuery("button#s_all_roll").on("click", function() {
				getSettlementName();
				getSettlementGeography();
				getSettlementType();
				getSettlementAge();
				getSettlementGovernment();
				getSettlementShops();
				getSettlementKnownFor();
				getSettlementExports();
				getSettlementEncounters();
				getSettlementProblems();
				getSettlementRuler();
				if((jQuery("#s_type_govt").html() == "No") || (jQuery("#s_type_govt").html() == "Sometimes" && getRandomInt(2) == 0)) {
					jQuery("#s_govt").hide();
				} else {
					jQuery("#s_govt").show();
				}
			});
			jQuery("button#s_name_roll").on("click", getSettlementName);
			jQuery("button#s_geog_roll").on("click", getSettlementGeography);
			jQuery("button#s_type_roll").on("click", getSettlementType);
			jQuery("button#s_age_roll").on("click", getSettlementAge);
			jQuery("button#s_govt_roll").on("click", getSettlementGovernment);
			jQuery("button#s_shop_roll").on("click", getSettlementShops);
			jQuery("button#s_know_roll").on("click", getSettlementKnownFor);
			jQuery("button#s_expo_roll").on("click", getSettlementExports);
			jQuery("button#s_enco_roll").on("click", getSettlementEncounters);
			jQuery("button#s_prob_roll").on("click", getSettlementProblems);
			jQuery("button#s_rule_roll").on("click", getSettlementRuler);
		});
	</script>
</head>
<body>
	<button id='s_all_roll'>Roll Everything!</button>
	<div id="roll_container">	
		<div class='half_block'>
			<span class='roll_block'>
				<button id='s_name_roll'>Roll!</button>	
				<button id='s_name_num1' class='roll_number' /></button>
				<button id='s_name_num2' class='roll_number' /></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Name</label>
				<div id='s_name' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>
				<button id='s_geog_roll'>Roll!</button>
				<button id='s_geog_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Geography</label>
				<div id='s_geog' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>		
				<button id='s_type_roll'>Roll!</button>
				<button id='s_type_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Type:</label>
				<div id='s_type' class='textarea'/></div>
				
				<label>Settlement Population</label>
				<div id='s_type_population' class='textarea'/></div>
				
				<label>Number of Businesses</label> 
				<div id='s_type_businesses' class='textarea'/></div>
				
				<label>Settlement has Government</label> 
				<div id='s_type_govt' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>
				<button id='s_age_roll'>Roll!</button>
				<button id='s_age_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Age:</label>
				<div id='s_age' class='textarea'/></div>
				
				<label>Description</label>
				<div id='s_age_desc' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>
				<button id='s_govt_roll'>Roll!</button>
				<button id='s_govt_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Government</label>
				<div id='s_govt' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>
			<button id='s_shop_roll'>Roll!</button>
				<button id='s_shop_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Shops</label>
				<div id='s_shop' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>
				<button id='s_know_roll'>Roll!</button>
				<button id='s_know_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Known For</label>
				<div id='s_know' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>
				<button id='s_expo_roll'>Roll!</button>
				<button id='s_expo_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Exports</label>
				<div id='s_expo' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>
				<button id='s_enco_roll'>Roll!</button>
				<button id='s_enco_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Encounter</label>
				<div id='s_enco' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>
				<button id='s_prob_roll'>Roll!</button>		
				<button id='s_prob_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>
				<label>Settlement Problems</label>
				<div id='s_prob' class='textarea'/></div>
			</span>
		</div>
		
		<div class='half_block'>
			<span class='roll_block'>
				<button id='s_rule_roll'>Roll!</button>		
				<button id='s_rule_trait_num' class='roll_number'/></button>
				<button id='s_rule_age_num' class='roll_number'/></button>
				<button id='s_rule_race_num' class='roll_number'/></button>
			</span>
			<span class='indent_block'>			
				<label>Settlement Ruler Trait</label>
				<div id='s_rule_trait' class='textarea'/></div>
				
				<label>Settlement Ruler Age</label>
				<div id='s_rule_age' class='textarea'/></div>			
				
				<label>Settlement Ruler Race</label>
				<div id='s_rule_race' class='textarea'/></div>
			</span>
		</div>
	</div>
</body>
</html>
