$(document).ready(function(){estadoSesion(); obtenerDatos(); funcionPrincipal()});


/* Se asegura de que exista una sesión activa */
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
			if(data.Foto != 'NULL')
				$("#img-perfil").attr("src", data.Foto);
			else
				$("#img-perfil").attr("src", "/evaluaciones/imagenes/Perfiles/Default/default.png");

			// Busca segundos nombres
			var index = data.Nombre.indexOf(" ");

			// ¿No los hay?
			if(index === -1)
				var nombre = data.Nombre;
			// ¿Si los hay?
			else
				var nombre = data.Nombre.substring(0, index);
			$("#nombreSesion").text(nombre);

			if(data.Administrador)
			{
				$("#admin").show();
				$("#administrador").text("(Administrador)");
			}
			else
			{
				console.log("No admin");
			}
		}
	});
}