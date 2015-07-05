/* $(function () {
 */
var data = {'data': []};

var yMax = Number.MIN_VALUE;
var yMin = Number.MAX_VALUE;

var plotOptions = {
    xaxis: {
        mode: "time",
        tickLength: 5
    },
    selection: {
        mode: "x"
    }
};

var overviewOptions = {
    series: {
        lines: {
            show: true,
            lineWidth: 1
        },
        shadowSize: 0
    },
    xaxis: {
        mode: "time",
        ticks: []
    },
    yaxis: {
        ticks: [],
        //min: 0,
        autoscaleMargin: 0.1
    },
    selection: {
        mode: "x"
    }
};

/*
 function getAxis() {
 for (var i = 0; i < data.data.length; ++i) {

 if (data.data[i][1] > yMax)
 yMax = data.data[i][1];
 if (data.data[i][1] < yMin) {
 yMin = data.data[i][1];

 }
 }
 }
 */
function getAxis(from, to) {

    console.log("from: " + from + " to: " + to);

    var yaxis = {'from': Number.MAX_VALUE, 'to': Number.MIN_VALUE};

    for (var i = 0; i < data.data.length; ++i) {

        if (data.data[i][0] > from && data.data[i][0] < to) {

            if (data.data[i][1] > yaxis.to)
                yaxis.to = data.data[i][1];
            if (data.data[i][1] < yaxis.from) {
                yaxis.from = data.data[i][1];

            }
        }
    }

    // plus and minus 10% to achieve some "padding" in the plot window.
    yaxis.from = yaxis.from - ((yaxis.to - yaxis.from) * 0.1);
    yaxis.to = yaxis.to + ((yaxis.to - yaxis.from) * 0.1);

    console.log("yaxis: %o", yaxis);
    return yaxis;
}

function updateData() {
    console.log("updateData called");


    us = +moment(startTimestamp, "DD.MM.YYYY hh:mm");
    ue = +moment(endTimestamp, "DD.MM.YYYY hh:mm");

    console.log(us);

    $.getJSON(bp + "retrieve/" + sid + "/" + us + "/" + ue, "", function (myData) {
        console.log("JSON received: %o", myData);

        data = myData;

    });

};


//var plot = $.plot("#placeholder", [getData()]);
var plot = $.plot("#placeholder", [data.data]);
var overview = $.plot("#overview", [data.data]);

function replot() {
    console.log("called update");

    /*
     plot.setData([data.data]);

     getAxis();

     plot.getOptions().yaxes[0].min = yMin;
     plot.getOptions().yaxes[0].max = yMax;

     // Since the axes don't change, we don't need to call plot.setupGrid()
     plot.setupGrid();
     plot.draw();
     //setTimeout(update, updateInterval);
     */

    plot = $.plot("#placeholder", [data.data], plotOptions);
    overview = $.plot("#overview", [data.data], overviewOptions);

    $("#placeholder").bind("plotselected", function (event, ranges) {

        // do the zooming
        $.each(plot.getXAxes(), function (_, axis) {
            var opts = axis.options;
            opts.min = ranges.xaxis.from;
            opts.max = ranges.xaxis.to;
        });
        $.each(plot.getYAxes(), function (_, axis) {
            var opts = axis.options;

            var yaxis = getAxis(ranges.xaxis.from, ranges.xaxis.to);

            opts.min = yaxis.from;
            opts.max = yaxis.to;
        });
        plot.setupGrid();
        plot.draw();
        plot.clearSelection();

        // don't fire event on the overview to prevent eternal loop

        overview.setSelection(ranges, true);
    });

    $("#overview").bind("plotselected", function (event, ranges) {
        plot.setSelection(ranges);
    });
}


/*


 $(document).ready(function () {});

 plotData();

 }
 )
 ;
 */
