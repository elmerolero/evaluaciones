$(document).ready(funcionPrincipal);

function funcionPrincipal()
{
	// Se asegura de que exista una sesión activa
	estadoSesion();

	// Solicita los datos a la base de datos y configura la página
	obtenerDatos();
}

