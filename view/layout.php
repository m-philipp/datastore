<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="arcwind datastore">
    <meta name="author" content="Martin Philipp">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>

    <title>Arcwind</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo $bp; ?>lib/css/bootstrap.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $bp; ?>lib/css/font-awesome.css">

    <!-- CSS for Material Effects -->
    <link href="<?php echo $bp; ?>lib/css/roboto.css" rel="stylesheet">
    <link href="<?php echo $bp; ?>lib/css/material.css" rel="stylesheet">
    <link href="<?php echo $bp; ?>lib/css/ripples.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo $bp; ?>lib/css/datastore-layout.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo $bp; ?>lib/bootstrap/js/html5shiv.js"></script>
    <script src="<?php echo $bp; ?>lib/bootstrap/js/respond.min.js"></script>
    <![endif]-->

    <link href="<?php echo $bp; ?>lib/css/jquery.datetimepicker.css" rel="stylesheet">


    <script src="<?php echo $bp; ?>lib/js/jquery-2.1.4.js"></script>
    <script src="<?php echo $bp; ?>lib/js/jquery.datetimepicker.js"></script>

    <script src="<?php echo $bp; ?>lib/js/moment-with-locales.js"></script>

    <script language="javascript" type="text/javascript" src="<?php echo $bp; ?>lib/js/jquery.flot.js"></script>


</head>

<body>
<div id="wrap">

    <!-- Fixed navbar -->
    <?php print($navigationTemplate); ?>
    <div class="container">
        <?php print($content); ?>
    </div>
    <!-- /.container -->

</div>

<?php print($footer); ?>

<!-- site ends -->

<script src="<?php echo $bp; ?>lib/js/bootstrap.js"></script>

<script src="<?php echo $bp; ?>lib/js/ripples.js"></script>
<script src="<?php echo $bp; ?>lib/js/material.js"></script>


<script>
    $(document).ready(function () {
        // This command is used to initialize some elements and make them work properly
        $.material.init();
    });
</script>
</body>

</html>
