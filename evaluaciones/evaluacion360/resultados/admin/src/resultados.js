// Guarda los resultados de búsqueda, hasta que así lo decida el usuario
var resultados = [];

// Anios
var anios = [];

function funcionPrincipal()
{
	obtenerAnios();
	$("#SelecAnio").on("change", changeResults);
	$("#datos-resultados-h").hide();
	$("#print-header").hide();
	window.onbeforeprint = function(event){prepararImpresion()};
	window.onafterprint = function(event){despuesImpresion()};
}

function obtenerAnios()
{
	$.ajax({	type			: 'POST',
		    	url	 			: 'src/obtenerAnios.php',
		    	dataType		: 'json',
		    	encode			: true
		    })
	.done(function(data){
		// ¿Fue exitoso?
		if(data.exitoso){
			// Establce el arreglo
			anios = [];
			for(var i = 0; i < data.length; i++){
				anios.push(data.Anio[i]);
			}
			console.log(anios);
			establecerAnios();
			obtenerResultados(anios[anios.length - 1]);
		}
		else{
			// Elimina los elementos
			$("#formato-resultados").empty().hide();
			var noResultados = $('<p/>', {
  									'html' : data.Mensaje,
  									'align': 'center',
								});

			noResultados.appendTo("#no-resultados");
			noResultados = $('<img/>', {
  								'src' : '/evaluaciones/imagenes/evaluacion.png',
  								'style': 'display: block; margin: auto;',
							});
			noResultados.appendTo("#no-resultados");
		}
	});
}

// Establece la peticion
function establecerAnios()
{
	// Reinicia la etiqueta select
	$("#SelecAnio").empty();

	// Para cada uno de los anios
	for(var i = 0; i < anios.length; i++){
		// ¿NO es el último elemento elemento del arreglo?
		if(i != anios.length - 1)
			opcionFinal = $('#SelecAnio').append('<option value=' + anios[i] + '>' + anios[i] + '</option>');	// Ancla una opcion de año
		else
			opcionFinal = $('#SelecAnio').append('<option value=' + anios[i] + ' selected="selected">' + anios[i] + '</option>'); // Ancla la opción y la establece como la seleccionada
	}
}

function changeResults()
{
	// Obtiene el valor del año seleccionado
	var anio = $("#SelecAnio option:selected").val();

	// Obtiene los resultados del año seleccionado
	obtenerResultados(anio);
}

function obtenerResultados(anio)
{
	console.log(anio);
	$.ajax({	type			: 'POST',
		    	url	 			: 'src/detallesEvaluacion.php',
		    	data 			: {'Anio' : anio},
		    	dataType		: 'json',
		    	encode			: true
		    })
	.done(function(data){
		// Establece los resultados
		resultados = [];
		resultados = data;
		console.log(data);
		establecerValores();
	});
}

function establecerValores()
{
	// Establece el año de evaluacion
	$("#anio-resultados").text(resultados.Anio);
	$("#anio-resultados-h").text(resultados.Anio);

	// Establece los datos del evaluado
	$("#empleado-evaluado").val(resultados.Nombre);
	document.title = "Empleado: " + resultados.Nombre;

	// Marca las relaciones con los evaluados
	for(var i = 0; i < resultados.Relaciones.length; i++){
		if(resultados.Relaciones[i] == 'Yo mismo')
			$("#R1").attr("checked", "true");
		else if(resultados.Relaciones[i] == 'Jefe')
			$("#R2").attr("checked", "true");
		else if(resultados.Relaciones[i] == 'Compañero de area')
			$("#R3").attr("checked", "true");
		else if(resultados.Relaciones[i] == 'Subordinado')
			$("#R4").attr("checked", "true");
		else if(resultados.Relaciones[i] == 'Otro')
			$("#R5").attr("checked", "true");
		else if(resultados.Relaciones[i] == 'Cliente')
			$("#R6").attr("checked", "true");
		else if(resultados.Relaciones[i] == 'Proveedor')
			$("#R7").attr("checked", "true");
	}

	// Establece las respuestas de la autoevaluación
	if(resultados.Autoevaluacion != '-'){
		for(var i = 0; i < resultados.Autoevaluacion.length; i++){
			$('#rautoevaluacion' + (i+1)).text(resultados.Autoevaluacion[i]);
		}
	}
	else{
		for(var i = 0; i < resultados.Promedios.length; i++){
			$('#rautoevaluacion' + (i+1)).text('-');
		}
	}

	// El promedio de auto evaluacion
	$('#pautoevaluacion' + 0).text(resultados.Mis_aspectos.Honradez);
	$('#pautoevaluacion' + 1).text(resultados.Mis_aspectos.Trabajo_Equipo);
	$('#pautoevaluacion' + 2).text(resultados.Mis_aspectos.Calidad);
	$('#pautoevaluacion' + 3).text(resultados.Mis_aspectos.Respeto);
	$('#pautoevaluacion' + 4).text(resultados.Mis_aspectos.Responsabilidad);
	$('#pautoevaluacion' + 5).text(resultados.Mis_aspectos.CincoS);
	$('#pautoevaluacion' + 6).text(resultados.Mis_aspectos.Seguridad);
	$('#pautoevaluacion' + 7).text(resultados.Mis_aspectos.Liderazgo);

	// Establece los promedios de su evaluacion
	for(var i = 0; i < resultados.Promedios.length; i++){
		$('#rgeneral' + (i+1)).text(resultados.Promedios[i]);
	}

	// El promedio general
	$('#pgeneral' + 0).text(resultados.Aspectos.Honradez);
	$('#pgeneral' + 1).text(resultados.Aspectos.Trabajo_Equipo);
	$('#pgeneral' + 2).text(resultados.Aspectos.Calidad);
	$('#pgeneral' + 3).text(resultados.Aspectos.Respeto);
	$('#pgeneral' + 4).text(resultados.Aspectos.Responsabilidad);
	$('#pgeneral' + 5).text(resultados.Aspectos.CincoS);
	$('#pgeneral' + 6).text(resultados.Aspectos.Seguridad);
	$('#pgeneral' + 7).text(resultados.Aspectos.Liderazgo);

	// Establece el total
	$('#Total').text(resultados.Promedio_Total.toFixed(2));

	// Establece las opiniones
	$('#opiniones').text(resultados.Opiniones);
	if(resultados.Autoevaluacion == '-')
		alert(resultados.Mensaje);
}

// Prepara todo para al impresión
function prepararImpresion(){
	$("#web-header").hide();
	$("#print-header").show();
	$("#datos-resultados").hide();
}

// Devuelve todo a su lugar
function despuesImpresion(){
	$("#web-header").show();
	$("#print-header").hide();
	$("#datos-resultados").show();
}
