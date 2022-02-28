// El json con los resultados
var resultados;
var anios = [];

function funcionPrincipal(){
	createContext("grafico", contexto);	// Crea una variable con referencia al área de dibujo
	getYears();
	$("#SelectAnio").on("change", changeResults);
}

function createContext(idCanvas){
	var canvas = document.getElementById(idCanvas);
	canvas.width = 400;
	canvas.height = 250;
	contexto = canvas.getContext("2d");
	contexto.font = "14px sans-serif";
}

function getYears()
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
			for(var i = 0; i < data.length; i++){
				anios.push(data.Anio[i]);
			}
			console.log(anios);
			setYears();
			getResults(anios[anios.length-1]);
		}
		else{
			// Elimina los elementos
			$("#resultadosEmpleado").empty();
			var noResultados = $('<p/>', {
  									'html' : data.Mensaje,
  									'align': 'center',
								});

			noResultados.appendTo("#resultadosEmpleado");
			noResultados = $('<img/>', {
  								'src' : '/evaluaciones/imagenes/evaluation.png',
  								'style': 'display: block; margin: auto;',
							});
			noResultados.appendTo("#resultadosEmpleado");
		}
	});
}

// Establece la peticion
function setYears()
{
	// Reinicia la etiqueta select
	$("#SelectAnio").empty();

	// Para cada uno de los anios
	for(var i = 0; i < anios.length; i++){
		// ¿NO es el último elemento elemento del arreglo?
		if(i != anios.length - 1)
			opcionFinal = $('#SelectAnio').append('<option value=' + anios[i] + '>' + anios[i] + '</option>');	// Ancla una opcion de año
		else
			opcionFinal = $('#SelectAnio').append('<option value=' + anios[i] + ' selected="selected">' + anios[i] + '</option>'); // Ancla la opción y la establece como la seleccionada
	}
}

function changeResults()
{
	// Obtiene el valor del año seleccionado
	var anio = $("#SelectAnio option:selected").val();

	// Pone la pantalla de carga
	$("#loadImg").show();
	$("#graficoEvaluacion").hide();
	$("#resultadoEvaluacion").hide();
	
	// Obtiene los resultados
	getResults(anio);
}

function getResults(anio){
	$.ajax({	type			: 'POST',
		    	url	 			: 'src/obtenerResultados.php',
		    	data 			: {'Anio' : anio},
		    	dataType		: 'json',
		    	encode			: true
		    })
	.done(function(data){
		// Establece los resultados
		resultados = data;
		showElements();
		showResults();
	});
}

function showElements(){
	$("#loadImg").hide();
	$("#graficoEvaluacion").show();
	$("#resultadoEvaluacion").show();
}

function showComments(){
	$("#opiniones").empty();
	for(var i = 0; i < resultados.Opinion.length; i++){
		if(i % 2 != 0){
			$("#opiniones").append("<div class='opinion opinion2'><p>" + resultados.Opinion[i] + "</p></div>");
		}
		else{
			$("#opiniones").append("<div class='opinion opinion1'><p>" + resultados.Opinion[i] + "</p></div>");
		}
	}
}

function showResults(){
	// Estblace el año 
	$("#anio").text(resultados.Anio);

	// Establece el puntaje
	$("#puntajeTotal").text(resultados.Promedio_Final.toFixed(2));

	var elemento = document.getElementById("grafico");
	var contexto = elemento.getContext("2d");

	contexto.clearRect(0, 0, 400, 400);
	contexto.beginPath();

	// Dibuja las lineas base de la gráfica
	color("rgb(0, 0, 0)");
	line(90, 50,  90, 200);	// X
	line(90, 51, 340,  51);  // Y

	// Dibuja las líneas del eje Y
	for(var i = 0; i < 11; i++)
	{
		var ancho = (i * ((340 - 90) / 10)) + 90;
		line(ancho, 30, ancho, 50);
		write(i * 10, ancho - 7, 25);
	}

	// Anchura maxima
	var anchuraMax = 250;
	var anchura = 0;
	var altura = 150/8;
	var rest = 150/7;

	// No dibuja el rectángulo de liderazgo
	if(resultados.Liderazgo < 0)
	{
		anchura = 0;
		var altura = 150/7;

		// Dibuja honradez 
		anchura = anchuraMax / 100 * resultados.Honradez;
		color('rgb(66, 134, 244)');
		rect(90, 50, anchura, altura);
		$("#Liderazgo").hide();
		$("#Lider").hide();
	}
	else{
		anchura = 0;
		altura = 150/8;
		rest = 0;

		color('rgb(0, 0, 0)');
		write("Liderazgo", 5, altura + 46);

		// Dibuja liderazgo
		anchura = anchuraMax / 100 * resultados.Liderazgo;
		color('rgb(255, 255, 255)');
		rect(90, 50, anchura, altura);

		// Dibuja honradez 
		anchura = anchuraMax / 100 * resultados.Honradez;
		color('rgb(66, 134, 244)');
		rect(90, 50 + altura, anchura, altura);

		// Liderazgo
		$("#Liderazgo").show();
		$("#Lider").show();
		$("#Liderazgo").text(resultados.Liderazgo);
	} 

	// Nombra los aspectos dentro de la gráfica
	color('rgb(0, 0, 0)');
	write("Honradez", 5, (altura * 2) + 46 - rest);
	write("Trabajo Equi", 5, (altura * 3) + 46 - rest);
	write("Calidad", 5, (altura * 4) + 46 - rest);
	write("Respeto", 5, (altura * 5) + 46 - rest);
	write("Responsa...", 5, (altura * 6) + 46 - rest);
	write("Cinco S's", 5, (altura * 7) + 46 - rest);
	write("Seguridad", 5, (altura * 8) + 46 - rest);

	// Dibuja trabajo en equipo
	anchura = anchuraMax / 100 * resultados.Trabajo_Equipo;
	color('rgb(66, 244, 188)');
	rect(90, 50 + (altura * 2) - rest, anchura, altura);

	// Dibuja calidad
	anchura = anchuraMax / 100 * resultados.Calidad;
	color('rgb(255, 210, 63)');
	rect(90, 50 + (altura * 3) - rest, anchura, altura);

	// Dibuja respeto
	anchura = anchuraMax / 100 * resultados.Respeto;
	color('rgb(111, 63, 255)');
	rect(90, 50 + (altura * 4) - rest, anchura, altura);

	// Dibuja Responsabilidad
	anchura = anchuraMax / 100 * resultados.Responsabilidad;
	color('rgb(255, 241, 91)');
	rect(90, 50 + (altura * 5) - rest, anchura, altura);

	// Dibuja Responsabilidad
	anchura = anchuraMax / 100 * resultados.CincoS;
	color('rgb(255, 40, 40)');
	rect(90, 50 + (altura * 6) - rest, anchura, altura);

	// Dibuja Responsabilidad
	anchura = anchuraMax / 100 * resultados.Seguridad;
	color('rgb(76, 114, 255)');
	rect(90, 50 + (altura * 7) - rest, anchura, altura);

	// Asigna los valores en la tabla de aspectos
	$("#Honradez").text(resultados.Honradez);
	$("#TrabajoEquipo").text(resultados.Trabajo_Equipo);
	$("#Calidad").text(resultados.Calidad);
	$("#Respeto").text(resultados.Respeto);
	$("#Responsabilidad").text(resultados.Responsabilidad);
	$("#CincoS").text(resultados.CincoS);
	$("#Seguridad").text(resultados.Seguridad);

	// Muestra las opiniones.
	showComments();
}