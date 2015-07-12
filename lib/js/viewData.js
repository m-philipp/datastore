/* $(function () {
 */
var data = {'data': []};

var plotOptions = {
    xaxis: {
        mode: "time",
        tickLength: 5
    },
    colors: ["#009688"],
    selection: {
        mode: "x"
    },
    points: {
        show: true,
        radius: 2
    },
    lines: {
        show: true,
        fill: true,
        fillColor: {colors: [{opacity: 0.7}, {opacity: 0.1}]}
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
    colors: ["#72000f"],
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
    },
    grid: {
        borderColor: "#bbb",
        backgroundColor: "#eeeeee"
    }
};


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

        replot();
    });

};


var plot = $.plot("#placeholder", [data.data], plotOptions);
var overview = $.plot("#overview", [data.data], overviewOptions);


function replot() {
    console.log("called update");

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
            console.log("for each ....");
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

window.onresize = function (event) {
    replot();
}


updateData();
