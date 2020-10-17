$(()=>{
	configRetweetDiario()
	configFavDiario()
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
		let bbvaData = data.bbva
		let labels = bbvaData.map(element=>formatDate(new Date(element.fecha.date)));
		let bbvaDataset = bbvaData.map(element=>element.promedioRT)
		let datasets = Object.entries(data).map((element,index)=>({
			label               : element[0],
			backgroundColor     : index == 0 ? 'rgba(60,141,188,0.5)' : index == 1 ? 'rgba(188, 60, 141, 0.5)' : 'rgba(141, 188, 60, 0.5)',
			borderColor         : index == 0 ? 'rgba(60,141,188,0.8)' : index == 1 ? 'rgba(188, 60, 141, 0.8)' : 'rgba(141, 188, 60, 0.8)',
			data                : (labels.map(item=> {
				let find = (element[1].find(data => formatDate(new Date(data.fecha.date)) == item)) 
				return find ? find.promedioRT : 0
			}))})
		)
		var areaChartData = {
			labels,
			datasets
		}
		retweetDiarioChart.data = areaChartData
		retweetDiarioChart.update()
	})
}

var favDiarioChart
function configFavDiario() {
	var areaChartCanvas = $('#favDiario').get(0).getContext('2d')
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
    favDiarioChart = new Chart(areaChartCanvas, {
    	type: 'line',
    	// data: areaChartData,
    	options: areaChartOptions
    })

    updateFavDiario()
}

function updateFavDiario() {
	fetch('/api/getMaxDataByDay').then(j=>j.json()).then(data => {
		let bbvaData = data.bbva
		let labels = bbvaData.map(element=>formatDate(new Date(element.fecha.date)));
		let bbvaDataset = bbvaData.map(element=>element.promedioRT)
		let datasets = Object.entries(data).map((element,index)=>({
			label               : element[0],
			backgroundColor     : index == 0 ? 'rgba(200,128,200,0.5)' : index == 1 ? 'rgba(0, 0, 259, 0.5)' : 'rgba(128, 128, 0, 0.5)',
			borderColor         : index == 0 ? 'rgba(60,141,188,0.8)' : index == 1 ? 'rgba(188, 60, 141, 0.8)' : 'rgba(141, 188, 60, 0.8)',
			data                : (labels.map(item=> {
				let find = (element[1].find(data => formatDate(new Date(data.fecha.date)) == item)) 
				console.log(find);
				return find ? find.promedioRT : 0
			}))})
		)
		var areaChartData = {
			labels,
			datasets
		}
		favDiarioChart.data = areaChartData
		favDiarioChart.update()
	})
}


function formatDate(date) {
	return date.getDate()+"-"+date.getMonth()+"-"+date.getFullYear()
}