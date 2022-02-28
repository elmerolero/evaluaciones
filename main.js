// ¿Hay una sesión activa?
estadoSesion();
$(document).ready(funcionPrincipal);

function funcionPrincipal()
{
	// Evento submit
	$('#inicio_sesion').submit(function(event){
		event.preventDefault();
		funcionConectar();
	});
}

/* Se asegura de que no exista una sesión activa, de otro modo, redirecciona a la página de inicio*/
function estadoSesion()
{
	$.ajax({ type			: 'POST',
		     url	 		: 'Sesion/estadoSesion.php',
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data);
		// ¿Fue exitoso?
		if(data.exitoso)
		{
			location.href ="/evaluaciones/Inicio/index.html";
		}
	});
}

function funcionConectar()
{
	// Crea un archivo JSON para enviar los datos
	var datosUsuario = 
	{
		'codigo'     : $("#codigoUsr").val(),
		'contrasena' : $("#contrasena").val()
	};

	$.ajax({ type			: 'POST',
		     url	 		: '/evaluaciones/Sesion/autenticar.php',
		     data			: datosUsuario,
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data);
		// ¿Fue exitoso?
		if(data.exitoso)
		{
			console.log("Inicio de sesión exitoso");
			$("#usrErroneo").hide();
			$("#contErronea").hide();
			location.href ="/evaluaciones/Inicio/index.html";
		}
		else
		{
			// Identifica el código de error
			if(data.codigo === 1)
			{
				$("#usrErroneo").text("Introduzca un código");
				$("#usrErroneo").show();
				$("#contErronea").hide();
			}
			else if(data.codigo === 2)
			{
				$("#contErronea").text("Introduzca una contraseña");
				$("#contErronea").show();
				$("#usrErroneo").hide();
			}
			// ¿Codigo de error 3? (Usuario no encontrado)
			else if(data.codigo === 3)
			{
				$("#usrErroneo").text("Codigo Incorrecto");
				$("#usrErroneo").show();
				$("#contErronea").hide();
			}
			// ¿Codigo de error 4? (Contraseña incorrecta)
			else if(data.codigo === 4)
			{
				$("#contErronea").text("Contraseña Incorrecta");
				$("#contErronea").show();
				$("#usrErroneo").hide();
			}
		}
	});
}