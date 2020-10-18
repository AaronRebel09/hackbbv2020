const randomNumber = (min, max) => Math.floor(Math.random() * (max - min + 1) + min);
const randomByte = () => randomNumber(0, 255)
const randomPercent = () => (randomNumber(50, 100) * 0.01).toFixed(2)
const randomCssRgba = () => `rgba(${[randomByte(), randomByte(), randomByte(), randomPercent()].join(',')}`

$(()=>{
	configRetweetDiario()
	configFavDiario()
	configKnob()
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
		

		let datasets = Object.entries(data).map((element,index)=>{
			let color = randomCssRgba()
			console.log(color);
			return ({
			label               : element[0],
			backgroundColor     : color,// + ",0.2)",
			borderColor         : color,// + ",0.8)",
			data                : (labels.map(item=> {
				let find = (element[1].find(data => formatDate(new Date(data.fecha.date)) == item)) 
				return find ? find.promedioRT : 0
			}))})
	}
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
		let datasets = Object.entries(data).map((element,index)=>{
			let color = randomCssRgba()
			return ({
			label               : element[0],
			backgroundColor     : color,// + ', 0.5)',
			borderColor         : color,// + ', 0.8)',
			data                : (labels.map(item=> {
				let find = (element[1].find(data => formatDate(new Date(data.fecha.date)) == item)) 
				console.log(find);
				return find ? find.promedioRT : 0
			}))})
		}
		)
		var areaChartData = {
			labels,
			datasets
		}
		favDiarioChart.data = areaChartData
		favDiarioChart.update()
	})
}

function configKnob() {
	$.get('/api/getSentimentOverview',data=> {
		$(".overview").html(data)
		$('.knob').knob({
			draw: function () {
        if (this.$.data('skin') == 'tron') {
          var a   = this.angle(this.cv),
              sa  = this.startAngle,
              sat = this.startAngle,
              ea,
              eat = sat + a,
              r   = true
              this.g.lineWidth = this.lineWidth
              this.o.cursor
              && (sat = eat - 0.3)
              && (eat = eat + 0.3)
              if (this.o.displayPrevious) {
              	ea = this.startAngle + this.angle(this.value)
              	this.o.cursor
              	&& (sa = ea - 0.3)
              	&& (ea = ea + 0.3)
              	this.g.beginPath()
              	this.g.strokeStyle = this.previousColor
              	this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
              	this.g.stroke()
              }
              this.g.beginPath()
              this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
              this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
              this.g.stroke()
              this.g.lineWidth = 2
              this.g.beginPath()
              this.g.strokeStyle = this.o.fgColor
              this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
              this.g.stroke()
              return false
          }
      },
    stopper: true,
    readOnly: true,//if true This will Set the Knob readonly cannot click
    release: function (value) {
      //Do something as you release the mouse
    },
}).children().off('mousewheel DOMMouseScroll');  
  })
    
}

function formatDate(date) {
	return date.getDate()+"-"+date.getMonth()+"-"+date.getFullYear()
}