// Para guardar los datos de los empleados
var empleados = [];

// Guarda los resultados de búsqueda, hasta que así lo decida el usuario
var resultados = [];

// Índice, para mostrar solo la cierta cantidad de empleados
const indice = 15;

// Cuántas páginas de empleados hay
var paginaMaxima = 0;

// La página para mostrar qué página de empleados está siendo mostrada
var pagina = 1;

// Guarda el anterior código de empleado
var codTemp = 0;

// Muestra la sección agregar empleados
function mostrarFormularioEmpleados()
{
	// Muestra la sección agregar empleados
	$("#sec-empleados-agregar").show();

	// Oculta las secciones activas
	$("#sec-empleados-consultar").hide();
	$("#sec-evaluaciones").hide();
	$("#editable").text("").hide();
	$("#sec-empleados-edit").hide();

	// Reestablece los valores introducidos antes
	$("#formAdd")[0].reset();
}

// Muestra la seccion de consultar empleados
function mostrarConsultar()
{
	// Muestra la sección agregar empleados
	$("#sec-empleados-consultar").show();

	// Oculta las secciones activas
	$("#sec-empleados-agregar").hide();
	$("#editable").hide();
	$("#sec-evaluaciones").hide();
	$("#sec-empleados-edit").hide();
	
	// Crear lista de empleados
	obtenerEmpleados();
}


// Obtiene los procesos disponibles para anclarlos al select
function obtenerProcesos()
{
	// Consulta
	$.ajax({ type			: 'POST',
		     url	 		: 'Empleados/procesos.php',
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data);
		// ¿Fue exitoso?
		if(!data.exitoso)
		{
			console.log("Error");
		}
		else
		{
			// Establecer contador a uno
			for(var i = 0; i < data.length; i++)
			{
				$('#select-proceso').append('<option value=' + data[i].ID_Proceso + '>' + data[i].Nombre_Proceso + '</option>');
				$('#selec-proceso').append('<option value=' + data[i].ID_Proceso + '>' + data[i].Nombre_Proceso + '</option>');
			}
		}
	});
}

// Establece el valor del checkbox admin
function establecerValorCheck(elemento)
{
	console.log(elemento);
	if( $(elemento).is(':checked') )
	{
		alert("Advertencia: Le está otorgando permisos de administrador a un empleado.");
		$(elemento).attr("value", 1);
	}
	else
	{
		$(elemento).attr("value", 0);
	}
}

// Agrega los empleados
function agregarEmpleados()
{
	// Archivo
	var archivo = $('#foto_emp')[0].files[0];

	// Crea un archivo JSON para enviar los datos
	var datosEmpleado = new FormData();

	datosEmpleado.append('codigo',		$("#codigoUsr").val());
	datosEmpleado.append('nombre',		$("#nombreUsr").val());
	datosEmpleado.append('apellidos', 	$("#apellidosUsr").val());
	datosEmpleado.append('proceso', 	$("#select-proceso").val());
	datosEmpleado.append('admin', 		$("#admin").val());
	datosEmpleado.append('foto_empleado', archivo);
	
	$.ajax({ type			: 'POST',
		     url	 		: 'Empleados/registrarEmpleados.php',
		     data			: datosEmpleado,
		     contentType: false,
             processData: false,
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data);
		if(data.exitoso)
		{
			// Muestra la sección agregar empleados
			$("#sec-empleados-agregar").hide();
			$("#editable").text(" Usuario registrado correctamente").show();
			$("#editable").prepend('<img src="/evaluaciones/imagenes/Sesion/checked.png" width="40">');

			// Notifica las advertencias
			if(data.codigo == 11)
			{
				$("#editable").append('<br><p>No se subió una foto de empleado, puede registrarla más tarde.</p');
			}
			else if(data.codigo == 12)
			{
				$("#editable").append('<br><p>Formato de imagen no válido.</p');
			}
		}
		else
		{
			// Identifica el código de error
			if(data.codigo === 1)
			{
				$("#showError").text("Campo de nombre vacío.");			
			}
			else if(data.codigo === 2)
			{
				$("#showError").text("Introduzca un código.");
			}
			// ¿Codigo de error 3? (Usuario no encontrado)
			else if(data.codigo === 3)
			{
				$("#showError").text("Campo de apellidos vacío.");
			}
			// ¿Codigo de error 4? (Contraseña incorrecta)
			else if(data.codigo === 4)
			{
				$("#showError").text("Seleccione un área.");
			}
			else if(data.codigo === 5)
			{
				$("#showError").text("Ya existe un empleado con ese código.");
			}

			// Lo muestra
			$("#showError").show();
		}
	});
}

function obtenerEmpleados()
{
	var modoConsulta = 1;

	$.ajax({ type			: 'POST',
		     url	 		: 'Empleados/obtenerEmpleados.php',
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
	$('.editar:first').attr("onclick", "editarEmpleado(" + (15 * (pagina - 1)) + ")");

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
		$('.editar:first').attr("onclick", "editarEmpleado(" + indiceEmpleado + ")"); // Evento

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

// Activa la sección del empleado y obtiene los datos de la persona a editar
// indiceE significa "índice de empleado"
function editarEmpleado(indiceE){
	// Muestra la sección agregar empleados
	$("#sec-empleados-edit").show();

	// Oculta las secciones activas
	$("#sec-empleados-consultar").hide();
	$("#sec-evaluaciones").hide();
	$("#editable").text("").hide();
	$("#sec-empleados-agregar").hide();

	// Establece los atributos
	$("#id_emp").val(empleados[indiceE].Codigo); codTemp = empleados[indiceE].Codigo;
	$("#nom_emp").attr("value", empleados[indiceE].Nombre);	 // Nombre
	$("#ape_emp").attr("value", empleados[indiceE].Apellidos); // Apellidos
	$("#selec-proceso option[value=" + empleados[indiceE].Proceso + "]").attr("selected", "selected"); // Proceso
	if(empleados[indiceE].Foto != 'NULL')
		$("#img_emp").attr("src", empleados[indiceE].Foto); // Apellidos
	else
		$("#img_emp").attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");

	if(empleados[indiceE].Administrador)
	{
		$("#adminAct").attr("value", 1);
		$("#adminAct").prop("checked", true);
	}
	else
	{
		$("#adminAct").prop("checked", false);
	}
}

// Activa la sección del empleado y obtiene los datos de la persona a editar
// indiceE significa "índice de empleado"
function editarEmpleadoResult(indiceE){
	// Muestra la sección agregar empleados
	$("#sec-empleados-edit").show();

	// Oculta las secciones activas
	$("#sec-empleados-consultar").hide();
	$("#sec-evaluaciones").hide();
	$("#editable").text("").hide();
	$("#sec-empleados-agregar").hide();

	// Establece los atributos
	$("#id_emp").val(resultados[indiceE].Codigo); codTemp = resultados[indiceE].Codigo;
	$("#nom_emp").attr("value", resultados[indiceE].Nombre);	 // Nombre
	$("#ape_emp").attr("value", resultados[indiceE].Apellidos); // Apellidos
	$("#selec-proceso option[value=" + resultados[indiceE].Proceso + "]").attr("selected", "selected"); // Proceso
	if(resultados[indiceE].Foto != 'NULL')
		$("#img_emp").attr("src", resultados[indiceE].Foto); // Apellidos
	else
		$("#img_emp").attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");

	if(resultados[indiceE].Administrador)
	{
		$("#adminAct").attr("value", 1);
		$("#adminAct").prop("checked", true);
	}
	else
	{
		$("#adminAct").prop("checked", false);
	}
}

// Agrega los empleados
function actualizarEmpleados()
{
	// Archivo
	var archivo = $('#foto_empE')[0].files[0];

	// Crea un archivo JSON para enviar los datos
	var datosEmpleado = new FormData();

	// Anexa los datos
	datosEmpleado.append('codigoAntE', codTemp);
	datosEmpleado.append('codigoE',		$("#id_emp").val());
	datosEmpleado.append('nombreE',		$("#nom_emp").val());
	datosEmpleado.append('apellidosE', 	$("#ape_emp").val());
	datosEmpleado.append('procesoE', 	$("#selec-proceso").val());
	datosEmpleado.append('adminE', 		$("#adminAct").val());
	datosEmpleado.append('foto_empE', archivo);
	
	console.log(archivo);
	// Oculta el editable
	$("#editable").hide();

	// Realiza la solicitud AJAX
	$.ajax({ type			: 'POST',
		     url	 		: 'Empleados/actualizarEmpleados.php',
		     data			: datosEmpleado,
		     contentType: false,
             processData: false,
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data.codigoAntE);
		if(data.exitoso)
		{
			// Muestra la sección agregar empleados
			$("#sec-empleados-editar").hide();
			$("#editable").text(" Usuario actualizado correctamente").show();
			$("#editable").prepend('<img src="/evaluaciones/imagenes/Sesion/checked.png" width="40">');

			// Notifica las advertencias
			if(data.codigo == 11)
			{
				$("#editable").append('<br><p>No se subió una foto de empleado, puede registrarla más tarde.</p');
			}
			else if(data.codigo == 12)
			{
				$("#editable").append('<br><p>Formato de imagen no válido.</p');
			}

			obtenerEmpleados();
			codTemp = $("#id_emp").val();
		}
	});
}

function buscarEmpleado(){
	// Obtiene lo que va a buscar
	var texto = document.getElementById("buscarEmpleado").value;

	if(texto != ""){
		// Realiza la solicitud AJAX
		$.ajax({ type			: 'POST',
			     url	 		: 'Empleados/buscarEmpleados.php',
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
	$('.editar:first').attr("onclick", "editarEmpleadoResult(" + 0 + ")");

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
		$('.editar:first').attr("onclick", "editarEmpleadoResult(" + i + ")"); // Evento

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
