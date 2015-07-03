$(function () {

    //var data = [];

    var yMax = Number.MIN_VALUE;
    var yMin = Number.MAX_VALUE;

    function getAxis() {
        for (var i = 0; i < data.length; ++i) {
            if (data[i][1] > yMax)
                yMax = data[i][1];
            if (data[i][1] < yMin)
                yMin = data[i][1];
        }
    }

    plotData = function () {
        console.log("plotData called");


        $(document).ready(function () {
            console.log("ready called");

            us = +moment(startTimestamp, "DD.MM.YYYY hh:mm");
            ue = +moment(endTimestamp, "DD.MM.YYYY hh:mm");

            console.log(us);

            $.getJSON(bp + "retrieve/" + sid + "/" + us + "/" + ue, "", function (myData) {
                console.log("JSON received: %o", myData);

                /*
                 data = [];
                 $.each(myData, function (key, val) {
                 //alert(key + " " + val);
                 data.push([key, parseFloat(val)]);
                 });
                 */
                data = myData;
                update();
            });

        });
    };

    function getData() {
        var res = [];
        for (var i = 0; i < data.length; ++i) {
            //res.push([data[i][0], data[i][1]]);
            res.push(data[i]);
        }

        return res;
    }


    //var plot = $.plot("#placeholder", [getData()]);
    var plot = $.plot("#placeholder", [data]);

    function update() {
        console.log("called update");

        plot.setData([data]);

        getAxis();

        plot.getOptions().yaxes[0].min = yMin;
        plot.getOptions().yaxes[0].max = yMax;

        // Since the axes don't change, we don't need to call plot.setupGrid()
        plot.setupGrid();
        plot.draw();
        //setTimeout(update, updateInterval);

        plot = $.plot("#placeholder", [data]);
    }

    plotData();

});
