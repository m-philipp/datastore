<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>

	<title>DataStore</title>


	<!-- Bootstrap core CSS -->
	<link href="<?php echo $bp; ?>lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="<?php echo $bp; ?>lib/bootstrap/css/layout.css" rel="stylesheet">
	<link href="<?php echo $bp; ?>lib/bootstrap/css/docs.min.css" rel="stylesheet">


	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="<?php echo $bp; ?>lib/bootstrap/js/html5shiv.js"></script>
	<script src="<?php echo $bp; ?>lib/bootstrap/js/respond.min.js"></script>
	<![endif]-->

	<script language="javascript" type="text/javascript" src="<?php echo $bp; ?>lib/flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $bp; ?>lib/flot/jquery.flot.js"></script>

	<link href="<?php echo $bp; ?>lib/jquery.datetimepicker.css" rel="stylesheet">
	<script language="javascript" type="text/javascript" src="<?php echo $bp; ?>lib/jquery.datetimepicker.js"></script>

</head>

<body>
<div id="wrap">

	<!-- Fixed navbar -->
	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo $bp; ?>">DataStore</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<?php foreach ($navigation as $link) {
						if ($link["type"] == "menu") {
							?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $link["title"]; ?>
									<b
										class="caret"></b></a>
								<ul class="dropdown-menu">
									<?php foreach (array_slice($link, 2) as $innerLink) {
										if ($innerLink['title'] == "divider") {
											?>
											<li class="divider"></li>
										<?php
										} else {


											?>
											<li>
												<a href="<?php echo $bp . $innerLink['link']; ?>"><?php echo $innerLink['title']; ?></a>
											</li>
										<?php
										}
									}
									?>
								</ul>
							</li>

						<?php
						} else {
							?>
							<li><a href="<?php echo $bp . $link['link']; ?>"><?php echo $link['title']; ?></a></li>
						<?php
						}
					} ?>

				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>

	<div class="container">

		<?php print($content); ?>

	</div>
	<!-- /.container -->

</div>
<div id="footer">
	<div class="container">
		<p class="text-muted">Brought to you by Martin Zittel.</p>
	</div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--<script src="<?php echo $bp; ?>lib/bootstrap/js/jquery-1.10.2.min.js"></script>-->
<script src="<?php echo $bp; ?>lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
