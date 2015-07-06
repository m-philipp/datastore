<div class="col-sm-12 col-lg-12">


    <div class="well row">
        <div class="col-sm-12 col-xs-12">
            <h1>Live View: <em style="color: #72000f"><?php echo $comment; ?></em></h1>
        </div>

        <div class="clearfix">
            <hr/>
        </div>


        <div class="col-sm-5 col-xs-12 data-container">
            <p>Update Intervall: <input id="updateInterval" type="text" value=""
                                        style="text-align: right; width:5em"> milliseconds</p>
        </div>
        <div class="col-sm-2 col-xs-12 data-container">&nbsp;
        </div>
        <div class="col-sm-5 col-xs-12 data-container">
            <p>Number of Values: <input id="totalPoints" type="text" value="" style="text-align: right; width:5em">
                Values</p>
        </div>

        <div class="clearfix">
            <hr/>
        </div>

        <div class="col-sm-12 col-xs-12 data-container">
            Stream Data:
            <div id="placeholder" style="height: 100px; font-size: 14px; line-height: 1.2em;"></div>
        </div>

    </div>
</div>


<script type="text/javascript">
    moment.locale('de');

    //var updateData;

    var sid = <?php echo $sid; ?>;
    var bp = '<?php echo $bp; ?>';

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


</script>

<script src="<?php echo $bp; ?>lib/js/viewStream.js">


</script>