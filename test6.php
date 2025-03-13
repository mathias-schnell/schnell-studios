<html>
	<head>
		<title>Canvas Test</title>
		<style>
			#canvas {
				margin: 0 auto;
			}
			#container {
				width: 800px;
				height: 600px;
				margin: 0 auto;
			}
		</style>
		<script type='text/javascript'>
			 window.requestAnimFrame = (function(){
			  return  window.requestAnimationFrame       || 
					  window.webkitRequestAnimationFrame || 
					  window.mozRequestAnimationFrame    || 
					  window.oRequestAnimationFrame      || 
					  window.msRequestAnimationFrame     || 
					  function(/* function */ callback, /* DOMElement */ element){
						window.setTimeout(callback, 1000 / 60);
					  };
			})();

			function draw_arc(ctx, color, radius, width, endpoint) {
                ctx.beginPath();
                ctx.arc(0, 0, radius, 0, endpoint);
                ctx.lineWidth = width;
                ctx.strokeStyle = color
                ctx.stroke();
            }
            
            function draw_arc_text(ctx, str, radius){
                ctx.save();
                radius = radius - 12;
                var radians_per_char = 0;
				if(isNaN(str)) {
					radians_per_char = (ctx.measureText('W').width * Math.PI/180) * (Math.log(str.length * 3)/14);
				} else {
					radians_per_char = (ctx.measureText('0').width * Math.PI/180) * (100/(radius * 2));
				}
                
                ctx.rotate((110 - 800/radius) * Math.PI/180);
                
                for (var n = 0; n < str.length; n++) {					
                    ctx.rotate(radians_per_char);
                    ctx.fillText(str[n], 0, -radius);
                }
                ctx.restore();
            }
            
			window.onload = function(){
                var c = document.getElementById('canvas');
                var ctx = c.getContext("2d");
                var d = new Date();
                var day_of_week = ["Sunday", 
                                    "Monday", 
                                    "Tuesday", 
                                    "Wednesday", 
                                    "Thursday", 
                                    "Friday",
                                    "Saturday"];
                var month_of_year = ["January", 
                                    "February", 
                                    "March",
                                    "April", 
                                    "May", 
                                    "June", 
                                    "July",
                                    "August", 
                                    "September", 
                                    "October",
                                    "November", 
                                    "December"];
                var endpoint = 0;
                var extra = 0;
                var width = 30;
                var start = 50;
                var space = 32;
                
                ctx.font = "32px Arial bold";
                ctx.textAlign = "center";
                ctx.fillStyle = "black";
                ctx.lineWidth = 5;
                
                var millisecs = {
                                    color:    	"#FF0000",
                                    radius:		start,
                                    preval:		d.getMilliseconds(),
                                    val:     	d.getMilliseconds(),
                                    strval:		String(d.getMilliseconds())[0],
                                    maxval: 	1000
                                }
                var seconds =   {
                                    color:		"#FFA500",
                                    radius:		millisecs.radius + space,
                                    preval:		d.getSeconds(),
                                    val:     	d.getSeconds(),
                                    strval:		String(d.getSeconds()),
                                    maxval: 	60
                                }
                var minutes =   {
                                    color:     	"#FFFF00",
                                    radius: 	seconds.radius + space,
                                    preval:		d.getMinutes(),
                                    val:     	d.getMinutes(),
                                    strval:		String(d.getMinutes()),
                                    maxval: 	60
                                }
                var hours =     {
                                    color:     "#00FF00",
                                    radius: 	minutes.radius + space,
                                    preval:		d.getHours(),
                                    val:     	d.getHours(),
                                    strval:		String(d.getHours()),
                                    maxval: 	24
                                }
                var days =      {
                                    color:     "#0000FF",
                                    radius: 	hours.radius + space,
                                    preval:		d.getDay(),
                                    val:     	d.getDay(),
                                    strval:		day_of_week[d.getDay()].toUpperCase(),
                                    maxval: 	7
                                }
                var months =	{
                                    color:     "#8F00FF",
                                    radius: 	days.radius + space,
                                    preval:		d.getMonth(),
                                    val:     	d.getMonth(),
                                    strval:		month_of_year[d.getMonth()].toUpperCase(),
                                    maxval: 	12
                                }
                                
                var clock_array = [millisecs, seconds, minutes, hours, days, months];
                        
                ctx.translate(400,300);
                ctx.rotate(-90*Math.PI/180);
                draw_arc(ctx, "#000000", 5, 10, 2.0*Math.PI);
                ctx.save();
                
                for(var i = 0; i < clock_array.length; i++)    {
                    obj = clock_array[i];					
					endpoint = ((obj.val + extra)/obj.maxval) * (2.0 * Math.PI);
					draw_arc(ctx, "#FFFFFF", obj.radius, width + 2, 2.0 * Math.PI);
					draw_arc(ctx, obj.color, obj.radius, width, endpoint);
					draw_arc_text(ctx, obj.strval, obj.radius);
					extra = (obj.val/obj.maxval);
                }
                
                function render() {
					d = new Date();
					clock_array[0].preval = clock_array[0].val;
					clock_array[1].preval = clock_array[1].val;
					clock_array[2].preval = clock_array[2].val;
					clock_array[3].preval = clock_array[3].val;
					clock_array[4].preval = clock_array[4].val;
					clock_array[5].preval = clock_array[5].val;
					
					clock_array[0].val = d.getMilliseconds();
					clock_array[1].val = d.getSeconds();
					clock_array[2].val = d.getMinutes();
					clock_array[3].val = d.getHours();
					clock_array[4].val = d.getDay();
					clock_array[5].val = d.getMonth();
					
					clock_array[0].strval = String(clock_array[0].val)[0];
					clock_array[1].strval = String(d.getSeconds());
					clock_array[2].strval = String(d.getMinutes());
					clock_array[3].strval = String(d.getHours());
					clock_array[4].strval = day_of_week[d.getDay()].toUpperCase();
					clock_array[5].strval = month_of_year[d.getMonth()].toUpperCase();
										
					for(var i = 0; i < clock_array.length; i++) {
						obj = clock_array[i];
						if(obj.preval == obj.val) { continue; }					
						endpoint = ((obj.val + extra)/obj.maxval) * (2.0 * Math.PI);
						draw_arc(ctx, "#FFFFFF", obj.radius, width + 2, 2.0 * Math.PI);
						draw_arc(ctx, obj.color, obj.radius, width, endpoint);
						draw_arc_text(ctx, obj.strval, obj.radius);
						extra = (obj.val/obj.maxval);
					}  
				requestAnimFrame(render);                    
			}
                
                render();
			}
		</script>
	</head>
	<body>
		<div id='container'>
			<canvas id='canvas' width='800' height='600'></canvas>
		</div>
	</body>
</html>
