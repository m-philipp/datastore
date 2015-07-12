<div class="col-sm-12 col-lg-12">


    <div class="well row">
        <div class="col-sm-12 col-xs-12">
            <h1>Live View: <em style="color: #72000f"><?php echo $comment; ?></em></h1>
        </div>

        <div class="clearfix">
            <hr/>
        </div>

        <div class="col-sm-12 col-xs-12 data-container">
            <div id="updateIntervallSlider" class="slider slider-material-orange"></div>


            <p>Update Intervall: <span id="updateInterval">0</span> Millisekunden</p>
        </div>

        <div class="col-sm-12 col-xs-12 data-container">
            <div id="totalPointsSlider" class="slider slider-material-orange"></div>
            <p>Datenpunkte: <span id="totalPoints">0</span> Werte</p>
        </div>

        <div class="clearfix">
            <hr/>
        </div>

        <div class="col-sm-12 col-xs-12 data-container">
            Stream Data:
            <div id="placeholder" style="height: 200px; font-size: 14px; line-height: 1.2em;"></div>
        </div>

    </div>
</div>


<script type="text/javascript">
    moment.locale('de');

    var sid = <?php echo $sid; ?>;
    var bp = '<?php echo $bp; ?>';

    var updateInterval = 2000;
    $("#updateInterval").html(updateInterval);
    var totalPoints = 100;
    $("#totalPoints").html(totalPoints);


    var intervallSlider = $("#updateIntervallSlider").noUiSlider({
        start: updateInterval,
        connect: "lower",
        range: {
            min: 200,
            max: 20000
        }
    });
    intervallSlider.change(function () {
        updateInterval = Math.round(intervallSlider.val());
        $("#updateInterval").html(updateInterval);
    });

    var totalPointsSlider = $("#totalPointsSlider").noUiSlider({
        start: totalPoints,
        connect: "lower",
        range: {
            min: 20,
            max: 2000
        }
    });
    totalPointsSlider.change(function () {
        totalPoints = Math.round(totalPointsSlider.val());
        $("#totalPoints").html(totalPoints);
    });
</script>

<script src="<?php echo $bp; ?>lib/js/viewStream.js">


</script>



