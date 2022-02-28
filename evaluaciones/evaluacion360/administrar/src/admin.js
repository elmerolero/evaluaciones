// Variables necesarias
var empleados;
var empleadosE;
var evaluador = {
	'subindice' : 0,
	'codigo'	: 0
};

var total = 0;
var listaEvaluados = [];

function funcionPrincipal()
{
	// Obtiene todos los empleados que se quieren evaluar
	obtenerEmpleados();

	// Evento del form
	$("#seleccionEvaluados").submit(function(event){event.preventDefault(); habilitarEvaluaciones()});

	// Funcion del scroll
	$(window).scroll(function(){
		var pos = $(document).scrollTop();

		if(pos > 150){
			pos = 100;
		}
		else{
			pos = 256 - $(document).scrollTop();
		}

		$("#div-fijo").css("top", pos);
	});
}

/* Obtiene los datos a evaluar */
function obtenerEmpleados()
{
	$.ajax({	type			: 'POST',
		    	url	 			: 'src/obtenerEmpleados.php',
		    	dataType		: 'json',
		    	encode			: true
		    })
	.done(function(data){
		// ¿Fue exitoso?
		if(data.exitoso)
		{
			// Asigna los empleados :D
			empleados = data;		// Crea un elemento emplead
			seleccionarEvaluador();
		}
	});
}

/* Abre la seccion para seleccionar el evaluador */
function seleccionarEvaluador()
{
	// Guarda el item a duplicar
	var itemTemp = $(".Empleado1").clone();

	// Deja vacío el contenedor principal
	$("#empleados").empty();

	// Crea los elementos
	for(var i = 0; i < empleados.length; i++)
	{
		// Guarda el item a duplicar
		var itemTemp2 = itemTemp.clone();

		// Establece la foto
		if(empleados[i].Foto != 'NULL')
			itemTemp2.children('img').attr("src", empleados[i].Foto);

		itemTemp2.children('p').text(empleados[i].Nombre + " " + empleados[i].Apellidos);
		itemTemp2.attr("onclick", "seccionEvaluados(" + i + ")");
		itemTemp2.appendTo("#empleados");
	}
}

// Lleva la sección para 
function seccionEvaluados(subindice)
{
	// Oculata lo demás
	$("#seleccionEmpleados").hide();

	// Muestra la sección de a quién evalúa
	$("#seleccionEvaluados").show();

	// Establce el evaluador
	establecerEvaluador(subindice);

	// Establece evaluados
	establecerEvaluados(subindice);
}

/* Establece los datos del evaluador */
function establecerEvaluador(subindice)
{
	// Establece el código del evaluador
	evaluador.codigo = empleados[subindice].ID_Empleado;
	evaluador.subindice = subindice;

	$("#volver").hide();

	// Establece los datos del evaluador
	$("#nombreEvaluador").text(empleados[subindice].Nombre + " " + empleados[subindice].Apellidos);
	$("#codigoEvaluador").text(empleados[subindice].ID_Empleado);
	$("#areaEvaluador").text(empleados[subindice].Proceso);

	// Establce la foto
	if(empleados[subindice].Foto != 'NULL')
		$("#imgEvaluador").attr("src", empleados[subindice].Foto);
	else
		$("#imgEvaluador").attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");
}

/* Establece los datos de los empleados a evaluar */
function establecerEvaluados(subindice)
{
	var codigoEmpleado = {
		'codigo' : empleados[subindice].ID_Empleado,
	};

	$.ajax({ type			: 'POST',
		     url	 		: 'src/evaluacionesHabilitables.php',
		     data			: codigoEmpleado,
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data);
		if(data.length > 0)
		{	
			if(data.length > 1)
				$("#div-relativo").css("top", 256).attr("id", "div-fijo");
			else
				$("#div-fijo").css("top", "0").attr("id", "div-relativo");
			
			empleadosE = data;

			// Restablece ciertos valores
			$(".seleccionEvaluados").css("height", "100%");

			// Guarda el item a duplicar
			var itemTemp = $(".empleadoEvaluado:first").show().clone();
			itemTemp.children('div').children('input').prop("checked", false);

			// Deja vacío el contenedor principal
			$(".seleccionEvaluados").empty();

			// Crea los elementos
			for(var i = 0; i < data.length; i++)
			{
				// Guarda el item a duplicar
				var itemTemp2 = itemTemp.clone();

				// Establece la foto
				if(data[i].Foto != 'NULL')
					itemTemp2.children('img').attr("src", data[i].Foto);
				else
					itemTemp2.children('img').attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");

				itemTemp2.children('div').find('.nomEvaluado').text(data[i].Apellidos + " " + data[i].Nombre);
				itemTemp2.children('div').find('.codEvaluado').text(data[i].ID_Empleado);
				itemTemp2.children('div').find('.aEvaluado').text(data[i].Proceso);
				itemTemp2.children('div').children('input').attr("id", "check" + i);
				itemTemp2.children('div').children('input').attr("onchange", "comprobarCheck(" + i + ")");
				itemTemp2.appendTo(".seleccionEvaluados");

				total = i + 1;
			}
			console.log($("#contenedor").css("height"));
		}
		else
		{
			$("#div-fijo").css("top", "0").attr("id", "div-relativo");
			// Guarda el item a duplicar
			var itemTemp = $(".empleadoEvaluado:first").hide().clone();

			// Deja vacío el contenedor principal
			$(".seleccionEvaluados").empty();
			itemTemp.appendTo(".seleccionEvaluados");
			$(".seleccionEvaluados").append("<p align='center' style='width: 90%; margin: auto; padding-left: 10px;'>No hay empleados disponibles que pueda evaluar esta persona.</p>");
		}
	});
}

function comprobarCheck(subindice)
{
	if( $("#check" + subindice).is(':checked') )
	{
		alert("Añadido: " + empleadosE[subindice].Nombre + ' ' + empleadosE[subindice].Apellidos);
	}
	else
	{
		alert("Eliminado: " + empleadosE[subindice].Nombre + ' ' + empleadosE[subindice].Apellidos);
	}
}

function seleccionarEvaluados()
{
	// Reinicia los arreglos
	listaEvaluados = [];

	// Para cada uno de los items creados
	for(var i = 0; i < total; i++)
	{
		// Si está checado
		if($("#check" + i).is(':checked')){
			// Lo añade a la lista de no evaluados
			listaEvaluados.push(empleadosE[i].ID_Empleado);
		}
	}
}

function regresarSelecEvaluador()
{
	// Oculata lo demás
	$("#seleccionEmpleados").show();
	listaEvaluados = [];
	$("#volver").show();

	// Muestra la sección de a quién evalúa
	$("#seleccionEvaluados").hide();
}

function habilitarEvaluaciones()
{
	// Obtenemos los evaluados y los que no
	seleccionarEvaluados();

	// Creamos el form que vamos a enviar PHP
	var empleadosEvaluacion = {
		'evaluador'		: evaluador.codigo,
		'evaluados'		: listaEvaluados,
		'length'		: listaEvaluados.length,
	};

	console.log(empleadosEvaluacion);

	$.ajax({ type			: 'POST',
		     url	 		: 'src/habilitarEvaluaciones.php',
		     data			: empleadosEvaluacion,
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data);
		establecerEvaluados(evaluador.subindice);
	});
}