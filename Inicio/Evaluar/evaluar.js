$(document).ready(funcionPrincipal);

function funcionPrincipal()
{
	estadoSesion();
	obtenerDatos();
	obtenerEvaluaciones();
}

function obtenerEvaluaciones()
{
	$.ajax({ type			: 'POST',
			 url	 		: 'evaluar.php',
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
			// Respalda el primer item
			var atempItem = $('.opcion-evaluacion-1:first').clone();

			// Elimina todo de empleados
			$("#elementos").empty();

			// Establece el primer item al princio
			atempItem.prependTo('#elementos');	

			// Para el primer item
			$('.enlace-evaluacion:first').attr("href", data[0].Ruta_Seguimiento);
			$('.nombre-evaluacion:first').text(data[0].Nombre);

			//Leemos los items
			for(var i = 1; i < data.length; i++)
			{
				// Clona el primer item
				atempItem = $('.opcion-evaluacion-1:first').clone();

				// Modifica los items del primer elemento
				$('.nombre-evaluacion:first').text(data[i].Nombre);

				// ¿El sobrante de contador entre dos es 0?
				if( i % 2 != 0)
				{
						// Establece estilo 2
					$('#evaluacion-1').attr('class','row opcion-evaluacion-2');
				}

				// Envía el elemento creado al último
				$('#evaluacion-1').appendTo('#elementos');

				// Envía el original a al principio
				atempItem.prependTo('#elementos');
			}
		}
	});
}