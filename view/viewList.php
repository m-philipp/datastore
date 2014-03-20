<div class="col-sm-12">
	<h1>View Stream List</h1>

	&nbsp;
	<div class="bs-callout bs-callout-danger">

		<strong class="text-danger">Unter Construction</strong>

	</div>


	<br/>
	<hr/>
</div>


<div class="col-sm-12">
	<h3>Streams:</h3>



	<table class="table table-hover">
		<thead>
		<tr>
			<th>StreamId</th>
			<th>viewStream</th>
			<th>viesData</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($streams as $stream) {
			?>
			<tr>
				<td><?php echo $stream; ?></td>
				<td><a href="./stream/<?php echo $stream; ?>">viewStream</a></td>
				<td><a href="./view/<?php echo $stream; ?>">viewData</a></td>
			</tr>
		<?php } ?>

		</tbody>
	</table>

	<hr/>
</div>