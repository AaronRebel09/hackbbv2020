var estado=":)";
var banco="";
$(()=>{
	listener()
	$("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'))
      $("body .bootstrap-switch-success").text(":)")
      $("body .bootstrap-switch-danger").text(":(")
	})
	crearTablasBanco()
})


function listener() {
	$('body').on('click', 'nav-tabs .nav-item', function(e) {
		e.preventDefault()
		let elmento=$(this)
		let bancoSelect=elmento.find('a').attr('data-bank')
		$('body nav-tabs .nav-item a').removeClass('active')
		elmento.find('a').addClass('active')
		banco=bancoSelect
		obtenerTablaBanco(bancoSelect)
	})

	$('#checkbox').on('change.bootstrapSwitch', function(e) {
		estado=(e.target.checked)?":)":":(";
		obtenerTablaBanco(banco)
	});
}

function crearTablasBanco() {
	$.get('/api/getBancks', function(data) {
		let i=0
		data.forEach(function(e){
			let tab=$(".nav-item-template").clone()
			tab.find('a').text(e)
			tab.find('a').attr('data-bank',e)
			tab.removeClass('nav-item-template').addClass('nav-item')
			
			//si es primer elmento
			if(i==0){
				tab.find('a').addClass('active')
				banco=e
				i++
				obtenerTablaBanco(e)
			}
			tab.removeAttr("style")
			$(".nav-tabs").append(tab)	

		})
	})
}
function obtenerTablaBanco(banco) {
	var url=estado===":)"?'/api/getBestComments?banco='+banco:'/api/getWorstComments?banco='+banco
	$.get(url, function(data) {
		$(".tab-pane").find('.tabla').html(data)
		$('body #data_table'+banco).DataTable({
			"autoWidth": true,
    		"searching": true
		})
	})
}