<?php
$bp = "./";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Flot Examples: Real-time updates</title>
	<script language="javascript" type="text/javascript" src="<?php echo $bp; ?>lib/flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $bp; ?>lib/flot/jquery.flot.js"></script>
	<script type="text/javascript">

	$(function() {

		// We use an inline data source in the example, usually data would
		// be fetched from a server

		var data = [],
			totalPoints = 30;

		var	yMax = Number.MIN_VALUE;
		var	yMin = Number.MAX_VALUE;
		
		function getAxis() {
			for (var i = 0; i < data.length; ++i) {
				if(data[i][1] > yMax)
					yMax = data[i][1];
				if(data[i][1] < yMin)
					yMin = data[i][1];
			}
			
		}
		
		function getData() {
			
			if (data.length > totalPoints)
				data = data.slice(data.length-totalPoints);

		
			var temp = [];
			$(document).ready(function () {
				$.getJSON( "retrieve/123", "", function (myData){
					
				
					//data.push([myData[0], myData[1]]);
					data.push(myData);
					return;
				
					var i = 0;
					//alert(data);
					$.each( myData, function( key, val ) {
						//alert(key + " " + val);
						data.push(val);
					});
			
				});
			
			});
			
			var res = [];
			for (var i = 0; i < data.length; ++i) {
				res.push([data[i][0], data[i][1]])
			}
	
			return res;
		}

		function getRandomData() {

			if (data.length > 0)
				data = data.slice(1);

			// Do a random walk

			while (data.length < totalPoints) {

				var prev = data.length > 0 ? data[data.length - 1] : 50,
					y = prev + Math.random() * 10 - 5;

				if (y < 0) {
					y = 0;
				} else if (y > 100) {
					y = 100;
				}

				data.push(y);
			}

			// Zip the generated y values with the x values

			var res = [];
			for (var i = 0; i < data.length; ++i) {
				res.push([i, data[i]])
			}

			return res;
		}
		// Set up the control widget

		var updateInterval = 100;
		$("#updateInterval").val(updateInterval).change(function () {
			var v = $(this).val();
			if (v && !isNaN(+v)) {
				updateInterval = +v;
				if (updateInterval < 1) {
					updateInterval = 1;
				} else if (updateInterval > 2000) {
					updateInterval = 2000;
				}
				$(this).val("" + updateInterval);
			}
		});
		
		totalPoints = 30;
		$("#totalPoints").val(totalPoints).change(function () {
			var v = $(this).val();
			if (v && !isNaN(+v)) {
				totalPoints = +v;
				if (totalPoints < 5) {
					totalPoints = 5;
				} else if (totalPoints > 10000) {
					totalPoints = 10000;
				}
				$(this).val("" + totalPoints);
			}
		});
		
		var plot = $.plot("#placeholder", [ getData() ]);
		
		
		function update() {

			plot.setData([getData()]);
			getAxis();
			
			plot.getOptions().yaxes[0].min = yMin;
            plot.getOptions().yaxes[0].max = yMax;

			// Since the axes don't change, we don't need to call plot.setupGrid()
			plot.setupGrid();
			plot.draw();
			setTimeout(update, updateInterval);
		}

		update();

	});

	</script>
</head>
<body>

	<div id="header">
		<h2>Real-time updates</h2>
	</div>

	<div id="content">

		<div class="demo-container">
			<div id="placeholder" style="width:600px;height:300px"></div>
		</div>

		
		<p>You can update a chart periodically to get a real-time effect by using a timer to insert the new data in the plot and redraw it.</p>

		<p>Time between updates: <input id="updateInterval" type="text" value="" style="text-align: right; width:5em"> milliseconds</p><br />
		<p>Number of Values: <input id="totalPoints" type="text" value="" style="text-align: right; width:5em"> Values</p>
		
	</div>


</body>
</html>
