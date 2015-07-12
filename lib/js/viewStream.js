// We use an inline data source in the example, usually data would
// be fetched from a server

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

function getAxis() {

    console.log("get Axes");

    var yaxis = {'from': Number.MAX_VALUE, 'to': Number.MIN_VALUE};

    for (var i = 0; i < data.data.length; ++i) {


        if (data.data[i][1] > yaxis.to)
            yaxis.to = data.data[i][1];
        if (data.data[i][1] < yaxis.from) {
            yaxis.from = data.data[i][1];

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


    $.getJSON(bp + "api/retrieve/" + sid + "/numValues/" + totalPoints, "", function (myData) {
        console.log("JSON received: %o", myData);

        data = myData;

        replot();
    });


    setTimeout(updateData, updateInterval);
};


var plot = $.plot("#placeholder", [data.data], plotOptions);


function replot() {
    console.log("called update");

    plot = $.plot("#placeholder", [data.data], plotOptions);

    $.each(plot.getYAxes(), function (_, axis) {
        console.log("for each ....");
        var opts = axis.options;


        var yaxis = getAxis();

        opts.min = yaxis.from;
        opts.max = yaxis.to;
    });
    plot.setupGrid();
    plot.draw();
}

window.onresize = function (event) {
    replot();
}


updateData();

