<div class="col-sm-12 col-lg-12">


    <div class="well row">
        <div class="col-sm-12 col-xs-12">
            <h1>View Data: <em style="color: #72000f"><?php echo $comment; ?></em></h1>

            Wenn Sie mehr als <?php echo getConfig()->get('global')->maxSubsamples; ?> Datenpunkte in dem ausgewählten
            Zeitraum vorhanden sind, werden die Datenpunkte mit einem linearen subsampling Verfahren
            auf <?php echo getConfig()->get('global')->maxSubsamples; ?> Datenpunkte begrenzt.
        </div>

        <div class="clearfix">
            <hr/>
        </div>

        <div class="col-sm-3 col-xs-12 data-container">
            Start Datum: <input class="form-control" id="datetimepickerStart" type="text"> <br/>
            End Datum: <input class="form-control" id="datetimepickerEnd" type="text">

        </div>
        <div class="col-sm-2 col-xs-12 data-container">&nbsp;
        </div>
        <div class="col-sm-7 col-xs-12 data-container">
            Übersicht:
            <div id="overview" style="height: 100px; font-size: 14px; line-height: 1.2em;"></div>
        </div>


        <div class="clearfix">
            <hr/>
        </div>


        <div class="col-sm-12 col-xs-12 data-container">
            Stream Daten:
            <div id="placeholder" style="height: 200px; font-size: 14px; line-height: 1.2em;"></div>
        </div>
        <div class="col-sm-12 col-xs-12 data-container">
            <button class="btn btn-fab btn-raised btn-primary pull-right" onclick="resetOverview();"><i
                    class="fa fa-undo"></i></button>
        </div>
    </div>

</div>
<script type="text/javascript">

    moment.locale('de');
    var startTimestamp = moment().subtract(7, 'days').format("DD.MM.YYYY hh:mm");
    var endTimestamp = moment().format("DD.MM.YYYY hh:mm");
    var sid = <?php echo $sid; ?>;
    var bp = '<?php echo $bp; ?>';

    var dateTimePickerOptions = {
        lang: 'de',
        i18n: {
            de: {
                months: [
                    'Januar', 'Februar', 'März', 'April',
                    'Mai', 'Juni', 'Juli', 'August',
                    'September', 'Oktober', 'November', 'Dezember',
                ],
                dayOfWeek: [
                    "So.", "Mo", "Di", "Mi",
                    "Do", "Fr", "Sa.",
                ]
            }
        },
        format: 'd.m.Y H:i',

        onChangeDateTime: function (dp, $input) {
            resetOverview();
        }


    };

    function resetOverview() {
        startTimestamp = $("#datetimepickerStart").val(); // TODO GMT + 1 ?
        endTimestamp = $("#datetimepickerEnd").val(); // TODO GMT + 1 ?
        updateData();

    }

    $("#datetimepickerStart").val(startTimestamp)
    $('#datetimepickerStart').datetimepicker(dateTimePickerOptions);


    $("#datetimepickerEnd").val(endTimestamp)
    $('#datetimepickerEnd').datetimepicker(dateTimePickerOptions);

    function getTimestamp(str) {
        var d = Math.round(+new Date(str) / 1000);
        return d;
    }
    var data = [];

    var i = 0;
</script>


<script src="<?php echo $bp; ?>lib/js/viewData.js">