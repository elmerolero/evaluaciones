var codigoEvaluador;
var codigoEvaluado;
var empleados;
var legnthEvaluacion;	// La cantidad de preguntas que tiene el examen

function funcionPrincipal()
{
	establecerCodigoEvaluador();

	$(window).scroll(function(){
		var pos = $(document).scrollTop();
		var headerSize = $("header").css("height");

		if(pos > 150){
			$("#logo").css("margin-top", "10px"); $("#logo").css("margin-bottom", "10px"); // Dsminuye los margenes
			$("#logo-img").css("width", "60px"); //$("#logo").css("margin-bottom", "10px");
			$("#titulo").empty(); $("#titulo").append("<h2 class='text-center'>Evaluacion 360 - 180</h2>");
			$("#img-perfil").css("height", "85px"); $("#sesion").css("padding-bottom", "10px"); $("#sesion").css("padding-top", "10px");
			$("#error").css("top", headerSize);

		}
		else{
			$("#logo").css("margin-top", "25px"); $("#logo").css("margin-bottom", "25px"); // Los devuelve a la normalidad
			$("#logo-img").css("width", "80px"); //$("#logo").css("margin-bottom", "10px");
			$("#titulo").empty(); $("#titulo").append("<h2 class='text-center'>Evaluacion 360 - 180<br><span style='font-size: 0.8em'>DISTRIBUIDORA VALCON S.A. DE C.V.</span></h2>");
			$("#img-perfil").css("height", "115px"); $("#sesion").css("padding-bottom", "30px"); $("#sesion").css("padding-top", "30px");
			$("#error").css("top", headerSize);
		}
	});

	$("#regresar").click(function(event){event.preventDefault(); regresarSeleccion()});
	$("#evaluacion").submit(function(event){event.preventDefault(); enviarEvaluacion()});
}

/* Establece el código de la persona que evalua */
function establecerCodigoEvaluador()
{
	$.ajax({	type			: 'POST',
		    	url	 			: '/evaluaciones/Sesion/datosSesion.php',
		    	dataType		: 'json',
		    	encode			: true
		    })
	.done(function(data){
		// ¿Fue exitoso?
		if(data.exitoso)
		{
			// Asigna los empleados :D
			codigoEvaluador = data.ID_Empleado;
			obtenerEmpleadosEvaluar(codigoEvaluador);
		}
	});
}

/* Obtiene los empleados a evaluar */
function obtenerEmpleadosEvaluar(codigoEvaluador)
{
	var codigo = {
		'codigo' : codigoEvaluador
	};

	$.ajax({	type			: 'POST',
		    	url	 			: 'src/obtenerEmpleados.php',
		    	dataType		: 'json',
		    	data 			: codigo,
		    	encode			: true
		    })
	.done(function(data){
		// ¿Fue exitoso?
		if(data.exitoso)
		{
			console.log(data);
			// Asigna los empleados :D
			empleados = data;		// Crea un elemento emplead

			seleccionarEvaluado();
		}
	});
}

/* Abre la seccion para seleccionar el evaluado */
function seleccionarEvaluado()
{
	// ¿Si hay empleados a evaluar?
	if(empleados.length > 0)
	{
		// Guarda el item a duplicar
		var itemTemp = $(".Empleado1:first").clone();

		// Deja vacío el contenedor principal
		$("#empleados").empty();

		// Muestra la sección de empleados
		$("#empleados").show();

		// Crea los elementos
		for(var i = 0; i < empleados.length; i++)
		{
			// Guarda el item a duplicar
			var itemTemp2 = itemTemp.clone();

			// Establece la foto
			if(empleados[i].Foto != 'NULL')
				itemTemp2.children('img').attr("src", empleados[i].Foto);
			else
				itemTemp2.children('img').attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");

			itemTemp2.children('p').text(empleados[i].Nombre + " " + empleados[i].Apellidos);
			itemTemp2.attr("onclick", "obtenerEvaluacion(" + i + ")");
			itemTemp2.appendTo("#empleados");
		}
	}
	else
	{
		// Oculta la seccion de empleados
		$("#empleados").hide();

		// Notifica que no hay empleados a evaluar
		$("#seleccion").append("<br><img src='/evaluaciones/imagenes/evaluacion.png' style='width: 20%; margin: auto; display: block;'/>");
		$("#seleccion").append("<br><br><p align='center'>No hay empleados para evaluar. Vuelve otro día.</p>");
	}
}

function regresarSeleccion(){
	// Oculta la sección de notificaciones
	$("#error").hide();

	// Oculta la seccion de evaluacion
	$("#evaluacion").hide();

	// Lama a establecer código evalaudor
	obtenerEmpleadosEvaluar(codigoEvaluador);

	// Seleccion
	$("#seleccion").show();
}

function obtenerEvaluacion(subindice)
{
	$.ajax({	type			: 'POST',
		    	url	 			: 'form/evaluacion360.json',
		    	dataType		: 'json',
		    	encode			: true
		    })
	.done(function(data){
		codigoEvaluado = empleados[subindice].ID_Empleado;
		// Oculta la sección anterior
		$("#seleccion").hide();
		legnthEvaluacion = data.Preguntas.length;
		// Establece los datos del empleado evaluado
		$("#codigo").val(empleados[subindice].ID_Empleado); $("#codigo").attr("readonly", "true");
		$("#empleado").val(empleados[subindice].Nombre + " " + empleados[subindice].Apellidos); $("#empleado").attr("readonly", "true");
		$("#anio").val(empleados[subindice].Anio); $("#anio").attr("readonly", "true");
		// Establece el formato de evaluación requerido
		$("#instrucciones").text(data.Instrucciones);
		leerPreguntas(data.Preguntas);
	});

}

function leerPreguntas(arreglo)
{
	// Reinicia
	$(".preguntas").empty();
	$(".aspecto").empty();
	// Muestra la seccion
	$("#evaluacion").show();

	// Establece el aspecto
	var aspecto = arreglo[0].aspecto;

	// Crea una variable temporal de la clase
	var atempItem = $('.preguntas:first').clone();

	// Para cada una de las preguntas
	for(var i = 0; i < arreglo.length; i++)
	{
		// Escribe el aspecto de la pregunta
		if(i === 0 || aspecto != arreglo[i].aspecto){
			aspecto = arreglo[i].aspecto;
			$("#tabla").append("<tr><td class='aspecto'>" + aspecto + "</td></tr>");
		}

		// Crea la pregunta
		var pregunta = atempItem.clone();

		//Escribe la pregunta
		pregunta.append("<td class='pregunta'>" + (i+1) + ".- " + arreglo[i].pregunta + "</td>");

		// Habilita las respuestas de la pregunta
		pregunta.append("<td class='respuesta1 respuesta'><input type='radio' value='100' name='r" + (i + 1) + "'></td>");
		pregunta.append("<td class='respuesta2 respuesta'><input type='radio' value='80' name='r" + (i + 1) + "'></td>");
		pregunta.append("<td class='respuesta1 respuesta'><input type='radio' value='60' name='r" + (i + 1) + "'></td>");
		pregunta.append("<td class='respuesta2 respuesta'><input type='radio' value='0' name='r" + (i + 1) + "'></td>");

		// Pasa la pregunta 
		pregunta.appendTo("#tabla");
	}
}

function enviarEvaluacion()
{
	// Obtiene la respuestas de las preguntas
	var respuestas = [];

	// No permitirá que se haga la petición ajax si hay una respuesta no contestada
	var realizarPeticion = true; $("#error").css("background", "red");

	// Comprueba que se hubiera contestado la opción relación*/
	var relacion = $("input[name='relacion']:checked").val();
	if(typeof relacion === 'undefined'){
		$("#error").show();
		$("#error").text("Establezca un tipo de relación.");
		realizarPeticion = false;
	}

	for(var i = 0; i < legnthEvaluacion && realizarPeticion; i++)
	{
		respuestas[i] = $("input[name='r" + (i + 1) + "']:checked").val();
		if(typeof respuestas[i] === 'undefined'){
			if(i <= 30){
				$("#error").css('background', 'red');
				$("#error").text("Falta contestar la pregunta " + (i + 1));
				$("#error").show();
				realizarPeticion = false;
			}
			else if(relacion === 'Jefe' || relacion === 'Yo mismo'){
				$("#error").css('background', 'red');
				$("#error").text("Falta contestar la pregunta " + (i + 1));
				$("#error").show();
				realizarPeticion = false;
			}else
			{
				respuestas[i] = 0;
			}
		}
	}
	
	// Obtiene el valor de opinion
	var opinion = document.getElementById("Opinion").value;

	if(opinion === '')
		opinion = "No opinión";


	if(realizarPeticion){
		$("#error").hide();

		var respuestasEvaluacion = {
			'codigoEvaluador' : codigoEvaluador,
			'codigoEvaluado'  : codigoEvaluado,
			'relacion'		  : relacion,
			'respuestas'	  : respuestas,
			'opinion'		  : opinion
		};

		$.ajax({	
				type			: 'POST',
		    	url	 			: 'src/evaluar.php',
		    	data 			: respuestasEvaluacion, 
		    	dataType		: 'json',
		    	encode			: true
		    })
		.done(function(data){
			console.log(data);
			if(data.exitoso)
			{
				regresarSeleccion();
				$("#error").css('background', 'green');
				$("#error").text(data.mensaje);
				$("#error").show();
				$("#logo").css("margin-top", "25px"); $("#logo").css("margin-bottom", "25px"); // Los devuelve a la normalidad
				$("#logo-img").css("width", "80px"); //$("#logo").css("margin-bottom", "10px");
				$("#titulo").empty(); $("#titulo").append("<h2 class='text-center'>Evaluacion 360 - 180<br><span style='font-size: 0.8em'>DISTRIBUIDORA VALCON S.A. DE C.V.</span></h2>");
				$("#img-perfil").css("height", "115px"); $("#sesion").css("padding-bottom", "30px"); $("#sesion").css("padding-top", "30px");
				var headerSize = $("header").css("height");
				$("#error").css("top", headerSize);
			}
			else{
				$("#error").css('background', 'red');
				$("#error").text(data.mensaje);
				$("#error").show();
			}
		});
	}
}