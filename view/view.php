<div class="col-sm-12">
    <h1>View Data: <?php echo $comment; ?></h1>

    <br/>

    <div class="well">
        Start Date: <input id="datetimepickerStart" type="text"> <br/>
        End Date: <input id="datetimepickerEnd" type="text">


        <hr/>

        <div id="placeholder" style="width: 100%; height: 100px; font-size: 14px; line-height: 1.2em;"></div>
    </div>

</div>
<script type="text/javascript">

    moment.locale('de');

    var plotData;

    var startTimestamp = moment().subtract(7, 'days').format("DD.MM.YYYY hh:mm");
    var endTimestamp = moment().format("DD.MM.YYYY hh:mm");
    var sid = <?php echo $sid; ?>;
    var bp = '<?php echo $bp; ?>';

    var options = {
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

            startTimestamp = $("#datetimepickerStart").val(); // TODO GMT + 1 ?
            endTimestamp = $("#datetimepickerEnd").val(); // TODO GMT + 1 ?
            // startTimestamp = getTimestamp($(this).val());
            plotData();
        }

    };

    $("#datetimepickerStart").val(startTimestamp)
    $('#datetimepickerStart').datetimepicker(options);


    $("#datetimepickerEnd").val(endTimestamp)
    $('#datetimepickerEnd').datetimepicker(options);

    function getTimestamp(str) {
        var d = Math.round(+new Date(str) / 1000);
        return d;
    }
    var data = [];

    var i = 0;


    // $.plot("#placeholder", [data]);
</script>


<script src="<?php echo $bp; ?>lib/js/viewData.js">