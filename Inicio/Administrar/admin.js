// ¿Hay una sesión activa?
$(document).ready(funcionPrincipal);

function funcionPrincipal()
{
	// Se asegura de que exista una sesión activa
	estadoSesion();

	// Solicita los datos a la base de datos y configura la página
	obtenerDatos();

	// Muestra las evaluaciones disponibles
	crearListaEvaluaciones();

	// Agrega los procesos en el formulario
	obtenerProcesos();

	// Evento agregar empleados
	$("#registrar").click(agregarEmpleados);
	$("#agregar-empleados").click(mostrarFormularioEmpleados);
	$("#consultar-empleados").click(mostrarConsultar);
	$("#consulta-evaluaciones").click(mostrarEvaluaciones);
	$("#editar_emp").click(actualizarEmpleados);
	$("#buscarEmpleado").on('keyup', function(){buscarEmpleado()});
}


/* Se asegura de que no exista una sesión activa, de otro modo, redirecciona a la página de inicio*/
function estadoSesion()
{
	$.ajax({ type			: 'POST',
		     url	 		: '/evaluaciones/Sesion/estadoSesion.php',
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data);
		// ¿Fue exitoso?
		if(!data.exitoso)
		{
			location.href ="/evaluaciones/";
		}
		else if(!data.Administrador)
		{
			location.href ="/evaluaciones/Inicio/index.html";
		}
	});
}

/* Obtiene los datos del usuario que va a mostrar */
function obtenerDatos()
{
	$.ajax({ type			: 'POST',
		     url	 		: '/evaluaciones/Sesion/datosSesion.php',
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data);
		// ¿Fue exitoso?
		if(data.exitoso)
		{
			// Establece los datos
			$("#nombre").text(data.Nombre);

			// Y la foto
			if(data.Foto != 'NULL')
				$("#img-perfil").attr("src", data.Foto);
			else
				$("#img-perfil").attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");

			if(data.Administrador)
			{
				$("#admin").show();
				$("#nombreSesion").text(data.Nombre);
				$("#administrador").text("(Administrador)");
			}
			else
			{
				console.log("No admin");
			}
		}
	});
}


