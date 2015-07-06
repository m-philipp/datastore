// We use an inline data source in the example, usually data would
// be fetched from a server

// TODO implememnt some sensefull startValue
var lastValue = 0;

var data = [];

var yMax = Number.MIN_VALUE;
var yMin = Number.MAX_VALUE;

var waiting = false;
;

function getAxis() {
    for (var i = 0; i < data.length; ++i) {
        if (data[i][1] > yMax)
            yMax = data[i][1];
        if (data[i][1] < yMin)
            yMin = data[i][1];
    }

}

function getData() {

    if (data.length > totalPoints)
        data = data.slice(data.length - totalPoints);

    waiting = true;

    $(document).ready(function () {
        //$.getJSON( "../retrieve/123", "", function (myData){
        $.getJSON("../retrieve/" + sid + "/" + lastValue, "", function (myData) {

            $.each(myData, function (key, val) {
                //alert(key + " " + val);
                data.push([parseFloat(key), parseFloat(val)]);

                if (key > lastValue) {
                    lastValue = parseFloat(key);
                }
            });

            waiting = false;

        });

    });

    var res = [];
    for (var i = 0; i < data.length; ++i) {
        //res.push([data[i][0], data[i][1]]);
        res.push(data[i]);
    }

    return res;
}


var plot = $.plot("#placeholder", [getData()]);


function update() {
    if (!waiting) {

        var d = getData();
        plot.setData([d]);

        getAxis();

        plot.getOptions().yaxes[0].min = yMin;
        plot.getOptions().yaxes[0].max = yMax;

        // Since the axes don't change, we don't need to call plot.setupGrid()
        plot.setupGrid();
        plot.draw();

    }

    setTimeout(update, updateInterval);
}

update();

window.onresize = function (event) {
    update();
}
