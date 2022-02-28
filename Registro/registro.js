// ¿Hay una sesión activa?
estadoSesion();
$(document).ready(funcionPrincipal);

var codigo;
var correo;

function funcionPrincipal()
{
	$("#paso-1").submit(function(event){
		event.preventDefault();
		funcionSiguiente1();
	});
	$("#paso-2").submit(function(event){
		event.preventDefault();
		funcionSiguiente2();
	});
	$("#paso-3").submit(function(event){
		event.preventDefault();
		funcionSiguiente3();
	});
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
		if(data.exitoso)
		{
			location.href ="/evaluaciones/Inicio/index.html";
		}
	});
}


function funcionSiguiente1()
{
	// Establece la información a enviar
	var info;

	// Establece el código
	codigo = $("#codigo").val();

	// La informacion a enviar
	info = {'paso' : 1, 'codigo' : $("#codigo").val()};

	console.log(info);

	$.ajax({ type			: 'POST',
		     url	 		: 'registro.php',
		     data 			: info,
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		console.log(data);
		// ¿Fue exitoso?
		if(data.exitoso)
		{
			$("#paso-1").hide();
			$("#error").hide();
			$("#paso-2").show();
		}
		else
		{
			$("#error").text(data.mensaje).show();
		}
	});
}

function funcionSiguiente2()
{
	// Establece la información a enviar
	var info;

	// ¿Es un e-mail?
	if(validar_email($("#correo").val()))
	{
		// Establece el correo electrónico
		correo = $("#correo").val();

		info = {'paso' : 2, 'correo' : $("#correo").val()};


		$.ajax({ type			: 'POST',
			     url	 		: 'registro.php',
			     data 			: info,
			     dataType		: 'json',
			     encode			: true
			    })
		.done(function(data){
			console.log(data);
			// ¿Fue exitoso?
			if(data.exitoso)
			{
				$("#paso-1").hide();
				$("#error").hide();
				$("#paso-2").hide();
				$("#paso-3").show();
			}
			else
			{
				$("#error2").text(data.mensaje).show();
			}
		});
	}
	else
	{
		$("#error2").text("Introduce un correo electrónico válido").show();
	}
}

function funcionSiguiente3()
{
	// Cifra la contraseña
	var contrasena =  $("#contrasena").val();

	if(contrasena != '')
	{
		// Establece la información que va a enviar
		var info = {
			'paso'		: 3,
			'codigo'	: codigo,
			'contrasena': contrasena,
			'correo'	: correo
		}
		console.log(info);
		$.ajax({ type			: 'POST',
			     url	 		: 'registro.php',
			     data 			: info,
			     dataType		: 'json',
			     encode			: true
			})
		.done(function(data){
			console.log(data);
			// ¿Fue exitoso?
			if(data.exitoso)
			{
				// oculta el resto
				$("#paso-3").hide();
				$("#form-registro").prepend("<br><br><br>");
				$("#editable").text(data.mensaje).show();
				$("#editable").prepend('<img src="/evaluaciones/imagenes/Sesion/checked.png" width="40">');
				$("#editable").append("<br><a href='/evaluaciones/'>Volver al inicio.</a>");
			}
			else
			{
				$("#error2").text(data.mensaje).show();
			}
		});
	}
	else
	{
		$("#error3").text("Introduzca una contraseña").show();
	}
};

function validar_email( email ) 
{
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
}