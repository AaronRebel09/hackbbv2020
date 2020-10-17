$(()=>{
	configRetweetDiario()
})
var retweetDiarioChart
function configRetweetDiario() {
	var areaChartCanvas = $('#retweetDiario').get(0).getContext('2d')
	var areaChartOptions = {
		maintainAspectRatio : false,
		responsive : true,
		legend: {
			display: false
		},
		scales: {
			xAxes: [{
				gridLines : {
					display : false,
				}
			}],
			yAxes: [{
				gridLines : {
					display : false,
				}
			}]
		}
	}

    // This will get the first returned node in the jQuery collection.
    retweetDiarioChart = new Chart(areaChartCanvas, {
    	type: 'line',
    	// data: areaChartData,
    	options: areaChartOptions
    })

    updateRetweetDiario()
}

function updateRetweetDiario() {
	fetch('/api/getMaxDataByDay').then(j=>j.json()).then(data => {
		let bbvaData = data.filter(element=>element.query == 'bbva')
		let labels = bbvaData.map(element=>formatDate(new Date(element.fecha.date)));
		let bbvaDataset = data.map(element=>element.promedioRT)
		let bancos = []
		data.forEach(element=>{
			if (!bancos.includes(element.query)) {
				bancos.push(element.query)
			}
		})
		console.log(bancos);
		var areaChartData = {
			labels,
			datasets: [
			{
				label               : 'Digital Goods',
				backgroundColor     : 'rgba(60,141,188,0.9)',
				borderColor         : 'rgba(60,141,188,0.8)',
				pointRadius          : false,
				pointColor          : '#3b8bba',
				pointStrokeColor    : 'rgba(60,141,188,1)',
				pointHighlightFill  : '#fff',
				pointHighlightStroke: 'rgba(60,141,188,1)',
				data                : bbvaDataset
			},
			// {
			// 	label               : 'Electronics',
			// 	backgroundColor     : 'rgba(210, 214, 222, 1)',
			// 	borderColor         : 'rgba(210, 214, 222, 1)',
			// 	pointRadius         : false,
			// 	pointColor          : 'rgba(210, 214, 222, 1)',
			// 	pointStrokeColor    : '#c1c7d1',
			// 	pointHighlightFill  : '#fff',
			// 	pointHighlightStroke: 'rgba(220,220,220,1)',
			// 	data                : [65, 59, 80, 81, 56, 55, 40]
			// },
			]
		}
		retweetDiarioChart.data = areaChartData
		retweetDiarioChart.update()
	})
}

function formatDate(date) {
	return date.getDate()+"-"+date.getMonth()+"-"+date.getFullYear()
}