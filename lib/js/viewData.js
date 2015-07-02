$(function() {

	// We use an inline data source in the example, usually data would
	// be fetched from a server


	var data = [];

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

	x = function(){
		$(document).ready(function () {
			$.getJSON( "../retrieve/"+streamId+"/" + startTimestamp + "/" + endTimestamp, "", function (myData){

				data = [];

				$.each( myData, function( key, val ) {
					//alert(key + " " + val);
					data.push([key, parseFloat(val)]);


				});
				update();
			});

		});
	};

	function getData() {
		var res = [];
		for (var i = 0; i < data.length; ++i) {
			//res.push([data[i][0], data[i][1]]);
			res.push(data[i]);
		}

		return res;
	}





	var plot = $.plot("#placeholder", [ getData() ]);

	function update() {

		var d = getData();
		plot.setData([d]);

		getAxis();

		plot.getOptions().yaxes[0].min = yMin;
		plot.getOptions().yaxes[0].max = yMax;

		// Since the axes don't change, we don't need to call plot.setupGrid()
		plot.setupGrid();
		plot.draw();
		//setTimeout(update, updateInterval);
	}

	x();

});
