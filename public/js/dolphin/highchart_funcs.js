var rsem_toggle = false;
var tophat_toggle = false;
var chip_toggle = false;

var rsem_categories = [];
var rsem_series = {};
var tophat_categories = [];
var tophat_series = {};
var chip_categories = [];
var chip_series = {};

function createHighchart(categories, series, title, yaxis, master_container, container) {
	var master = document.getElementById(master_container);
	console.log(master);
	master.appendChild(createElement('div', ['id', 'style'], [container, 'min-width: 310px;margin: 0 auto']))
	$('#'+container).highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: title
        },
        xAxis: {
            categories: categories
        },
        yAxis: {
            min: 0,
            title: {
                text: yaxis
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
            shared: true
        },
        plotOptions: {
            column: {
                stacking: 'percent'
            }
        },
        series: series
    });
	document.getElementById(container).style.display = 'none';
}

function showHighchart(container) {
	var childnodes = document.getElementById(container).childNodes;
	console.log(childnodes)
	for (var x = 0; x < childnodes.length; x++) {
		if (childnodes[x].id != undefined) {
			var node = childnodes[x]
			console.log(node)
			node.setAttribute('style', 'display:show');
		}
	}
}