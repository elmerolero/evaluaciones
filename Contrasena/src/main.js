$(document).ready(function(){
	$("#mensaje-error").hide();
	$("#enlace").hide();
	$("form").submit(function(event){
    	event.preventDefault();
    	crearCodigo();
  	});
});

function crearCodigo(){
	// Obtiene el código introducido
	var codigoUsr = $("#codigo").val();

	console.log("Código introducido: " + codigoUsr);

	$.ajax({ type			: 'POST',
		     url	 		: 'src/crearCodigo.php',
		     data			: {codigo : codigoUsr},
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		if(data.exitoso){
			console.log("Exitoso");
			$("#formulario-contrasena").hide();
			$("#mensaje").show();
		}
		else{
			console.log(data.Mensaje);
			$("#mensaje-error").text(data.Mensaje).show();
			if(data.Enlace != 'NULL'){
				$("#enlace").attr("href", data.Enlace).show();
			}else{
				$("#enlace").attr("href", data.Enlace).hide();
			}
		}
	});
}