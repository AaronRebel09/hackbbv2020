$(()=>{
	crearTablasBanco()
})

function crearTablasBanco() {
	$.get('/api/getBancks', function(data) {
		data.forEach(e=>obtenerTablaBanco(e))
		// for (var i = data.length - 1; i >= 0; i--) {
		// 	obtenerTablaBanco(data[i]);
		// }
	});
}
function obtenerTablaBanco(banco) {
	$.get('/api/getWorstComments?banco='+banco, function(data) {
		$(".tablas").append(data);
		$('body #'+banco).DataTable( {
    		"autoWidth": true,
    		"searching": true
		});
	});
}