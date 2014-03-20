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
	<h1>View Data Stream: <?php echo $stream; ?></h1>

	<br />

	<div class="well">
		Start Date: <input id="datetimepickerStart" type="text" >

	</div>
	<div class="well">
		End Date: <input id="datetimepickerEnd" type="text" >
	</div>


	<div class="demo-container">
		<div id="placeholder" style="width:700px;height:300px"></div>
	</div>
	<br />


	<script type="text/javascript">
		var x;

		var startTimestamp = <?php echo $startTimestamp; ?>;
		var endTimestamp = <?php echo $endTimestamp; ?>;

		$("#datetimepickerStart").val("<?php echo $start; ?>")
		$('#datetimepickerStart').datetimepicker({
			onChangeDateTime:function(dp,$input){
				startTimestamp = 3600 + getTimestamp($input.val()); // GMT + 1
				x();
			}
		});

		$("#datetimepickerEnd").val("<?php echo $end; ?>")
		$('#datetimepickerEnd').datetimepicker({
			onChangeDateTime:function(dp,$input){
				endTimestamp = 3600 +  getTimestamp($input.val()); // GMT + 1
				x();
			}
		});

		function getTimestamp(str){
			var d = Math.round(+new Date(str)/1000);
			return d;
		}



		var streamId = <?php echo $stream; ?>;





	</script>

	<script src="../lib/viewData.js">



	</script>

	<hr/>
</div>