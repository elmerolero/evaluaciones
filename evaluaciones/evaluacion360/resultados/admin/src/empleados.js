// Para guardar los datos de los empleados
var empleados = [];

// Índice, para mostrar solo la cierta cantidad de empleados
const indice = 15;

// Cuántas páginas de empleados hay
var paginaMaxima = 0;

// La página para mostrar qué página de empleados está siendo mostrada
var pagina = 1;


function funcionPrincipal()
{
	obtenerEmpleados();
	$("#buscarEmpleado").on('keyup', buscarEmpleado);
}

function obtenerEmpleados()
{
	var modoConsulta = 1;

	$.ajax({ type			: 'POST',
		     url	 		: '/evaluaciones/Inicio/Administrar/Empleados/obtenerEmpleados.php',
		     data			: modoConsulta,
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		if(data.exitoso)
		{
			// Guarda los datos de los empleados en el arreglo empleados
			empleados = [];

			for(var i = 0; i < data.length; i++)
			{
				empleados.push(data[i]);
			}

			// Establecemos la cantidad de páginas de empleados que hay
			paginaMaxima = parseInt((empleados.length / 15) + 1);
			establecerPaginas();
			crearListaEmpleados(pagina);
			if(paginaMaxima < 1){
				$("#pagEmpleados").show();
				establecerPaginas();
			}
		}
		else {
			console.log("Error");
		}
	});
}

// Para que cree la lista de empleados de qué pagina
function crearListaEmpleados(pagina)
{
	// Respalda el primer item
	var atempItem = $('.empleado-1:first').clone();

	// Elimina todo de empleados
	$("#empleados").empty();

	// Establece el primer item al princio
	atempItem.prependTo('#empleados');			

	// Para el primer item
	if(empleados[0 + (indice * (pagina-1))].Foto != "NULL")
	{
		$('.fotoEmp:first').attr("src", empleados[0 + (indice * (pagina-1))].Foto);
	}
	else
	{
		$('.fotoEmp:first').attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");
	}

	$('.nombreEmp:first').text(empleados[0 + (indice * (pagina-1))].Apellidos + " " + empleados[0 + (indice * (pagina-1))].Nombre);
	$('.codEmp:first').text(empleados[0 + (indice * (pagina-1))].Codigo);
	$('.areaEmp:first').text(empleados[0 + (indice * (pagina-1))].Proceso);
	$('.consultar:first').attr("href", "resultados.php?codigo=" + (empleados[0 + (indice * (pagina-1))].Codigo));

	var cantResultMostrar = (pagina === paginaMaxima ? empleados.length % indice : indice);
	var indiceEmpleado = 0;	// ¿Qué indice de empleado le corresponde?
	
	for(var i = 1; i < cantResultMostrar; i++)
	{
		// Calcula el índice del empleado
		indiceEmpleado = 15 * (pagina - 1) + i;

		// Clona el primer item
		atempItem = $('.empleado-1:first').clone();

		// Modifica los atributos del primer item
		if(empleados[i + (indice * (pagina-1))].Foto != "NULL")
		{
			$('.fotoEmp:first').attr("src", empleados[i + (indice * (pagina-1))].Foto);
		}
		else
		{
			$('.fotoEmp:first').attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");
		}

		$('.nombreEmp:first').text(empleados[i + (indice * (pagina-1))].Apellidos + " " + empleados[i + (indice * (pagina-1))].Nombre);
		$('.codEmp:first').text(empleados[i + (indice * (pagina-1))].Codigo); 
		$('.areaEmp:first').text(empleados[i + (indice * (pagina-1))].Proceso);
		$('.consultar:first').attr("href", "resultados.php?codigo=" + empleados[i + (indice * (pagina-1))].Codigo); // Evento

		// ¿El sobrante de contador entre dos es 0?
		if( i % 2 != 0)
		{
			// Establece estilo 2
			$('#empleado-1').attr('class','empleado-2 ' + i);
		}
		else
		{
			// Establece estilo 1
			$('#empleado-1').attr('class','empleado-1 ' + i);
		}

		// Manda el empleado "i" a empleados
		$('#empleado-1').appendTo("#empleados");

		// Manda el primer item al principio
		atempItem.prependTo('#empleados');
	}
}

function establecerPaginas()
{
	// Respalda el primer item
	var atempItem = $('.botonp:first').clone();
	$('#pagEmpleados').empty();
	atempItem.appendTo("#pagEmpleados");

	for(var i = 1; i < paginaMaxima; i++)
	{
		// Respalda el primer item
		var atempItem = $('.botonp:first').clone();
		atempItem.attr("id", "pag" + (i + 1));
		atempItem.text(i + 1);
		atempItem.attr("onclick", "crearListaEmpleados(" + (i + 1) + ")");
		atempItem.appendTo("#pagEmpleados");
	}
}

function buscarEmpleado(){
	// Obtiene lo que va a buscar
	var texto = document.getElementById("buscarEmpleado").value;

	if(texto != ""){
		// Realiza la solicitud AJAX
		$.ajax({ type			: 'POST',
		     	url	 		: '/evaluaciones/Inicio/Administrar/Empleados/buscarEmpleados.php',
		     	data			: {consulta: texto},
		     	dataType		: 'json',
		     	encode			: true
		    	})
		.done(function(data){
			if(data.exitoso)
			{
				// Vacía los resultados anteriores
				resultados = [];

				// Agrega los nuevos resultados
				for (var i = 0; i <= data.length; i++) {
					resultados.push(data[i]);
				}

				// Crea la lista de resultados obtenidos
				crearListaResultados();
			}
			else{
				obtenerEmpleados();
			}
		});
	}
	else{
		crearListaEmpleados(1);
	}
}

function crearListaResultados()
{
	// Respalda el primer item
	var atempItem = $('.empleado-1:first').clone();

	// Elimina todo de empleados
	$("#empleados").empty();

	// Establece el primer item al princio
	atempItem.prependTo('#empleados');			

	// Para el primer item
	if(resultados[0].Foto != "NULL")
	{
		$('.fotoEmp:first').attr("src", resultados[0].Foto);
	}
	else
	{
		$('.fotoEmp:first').attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");
	}

	$('.nombreEmp:first').text(resultados[0].Nombre);
	$('.codEmp:first').text(resultados[0].Codigo);
	$('.areaEmp:first').text(resultados[0].Proceso);
	$('.consultar:first').attr("href", "resultados.php?codigo=" + resultados[0].Codigo); // Evento

	for(var i = 1; i < resultados.length; i++)
	{
		// Clona el primer item
		atempItem = $('.empleado-1:first').clone();

		// Modifica los atributos del primer item
		if(resultados[i].Foto != "NULL")
		{
			$('.fotoEmp:first').attr("src", resultados[i].Foto);
		}
		else
		{
			$('.fotoEmp:first').attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");
		}

		$('.nombreEmp:first').text(resultados[i].Nombre);
		$('.codEmp:first').text(resultados[i].Codigo); 
		$('.areaEmp:first').text(resultados[i].Proceso);
		$('.consultar:first').attr("href", "resultados.php?codigo=" + resultados[i].Codigo); // Evento

		// ¿El sobrante de contador entre dos es 0?
		if( i % 2 != 0)
		{
			// Establece estilo 2
			$('#empleado-1').attr('class','empleado-2 ' + i);
		}
		else
		{
			// Establece estilo 1
			$('#empleado-1').attr('class','empleado-1 ' + i);
		}

		// Manda el empleado "i" a empleados
		$('#empleado-1').appendTo("#empleados");

		// Manda el primer item al principio
		atempItem.prependTo('#empleados');
	}
}