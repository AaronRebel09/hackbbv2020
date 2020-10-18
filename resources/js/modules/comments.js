$(()=>{
	crearTablasBanco()
})

function crearTablasBanco() {
	$.get('/api/getBancks', function(data) {
		data.forEach(e=>{
			obtenerTablaBancoNegativo(e)
			obtenerTablaBancoPositivo(e)
		})
	});
}
function obtenerTablaBancoNegativo(banco) {
	$.get('/api/getWorstComments?banco='+banco, function(data) {
		$(".tablas-negativo").append(data);
		$('body #'+banco+"negativo").DataTable( {
    		"autoWidth": true,
    		"searching": true
		});
	});
}
function obtenerTablaBancoPositivo(banco) {
	$.get('/api/getBestComments?banco='+banco, function(data) {
		$(".tablas-positivo").append(data);
		$('body #'+banco+"positivo").DataTable( {
    		"autoWidth": true,
    		"searching": true
		});
	});
}