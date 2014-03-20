<div class="col-sm-12">
	<h1>View</h1>

	&nbsp;
	<div class="bs-callout bs-callout-danger">

		<strong class="text-danger">Unter Construction</strong>

	</div>


	<br/>
	<hr/>
</div>


<div class="col-sm-12">
	<h1>Stream <?php echo $stream; ?>:</h1>
		<div class="demo-container">
			<div id="placeholder" style="width:700px;height:300px"></div>
		</div>
		<br />
		<p>Time between updates: <input id="updateInterval" type="text" value="" style="text-align: right; width:5em"> milliseconds</p><br />
		<p>Number of Values: <input id="totalPoints" type="text" value="" style="text-align: right; width:5em"> Values</p>
	<script type="text/javascript">
		// Set up the control widget

		var updateInterval = 300;
		$("#updateInterval").val(updateInterval).change(function () {
			var v = $(this).val();
			if (v && !isNaN(+v)) {
				updateInterval = +v;
				if (updateInterval < 1) {
					updateInterval = 1;
				} else if (updateInterval > 20000) {
					updateInterval = 20000;
				}
				$(this).val("" + updateInterval);
			}
		});

		var totalPoints = 100;
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

		var streamId = <?php echo $stream; ?>;

	</script>

	<script src="../lib/viewStream.js">


	</script>

	<hr/>
</div>