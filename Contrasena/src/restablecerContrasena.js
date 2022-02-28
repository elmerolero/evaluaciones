$(document).ready(function(){
	$("#mensaje-error-usr").hide();
	$("#mensaje-error-cod").hide();
	$("#mensaje-error-con").hide();
	$("#validar-usr").submit(function(event){
    	event.preventDefault();
    	validarCodigoUsr();
  	});
  	$("#validar-cod").submit(function(event){
    	event.preventDefault();
    	validarCodigo();
  	});
  	$("#restablecer-contrasena").submit(function(event){
    	event.preventDefault();
    	restablecerContrasena();
  	});
});

function validarCodigoUsr(){
	// Obtiene el código introducido
	var codigoUsr = $("#codigoUsr").val();

	$.ajax({ type			: 'POST',
		     url	 		: 'src/validarCodigo.php',
		     data			: {codigoUsr : codigoUsr},
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		if(data.exitoso){
			console.log(data);
			$("#formulario-usuario").hide();
			$("#formulario-codigo").show();
		}
		else{
			console.log(data.Mensaje);
			$("#mensaje-error-usr").text(data.Mensaje).show();
		}
	});
}

function validarCodigo(){
	// Obtiene el código introducido
	var codigoC = $("#codigo").val();

	$.ajax({ type			: 'POST',
		     url	 		: 'src/validarCodigo.php',
		     data			: {codigo : codigoC},
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		if(data.exitoso){
			console.log(data);
			$("#formulario-codigo").hide();
			$("#formulario-restablecer").show();
		}
		else{
			console.log(data.Mensaje);
			$("#mensaje-error-cod").text(data.Mensaje).show();
		}
	});
}

function restablecerContrasena(){
	// Obtiene el código introducido
	var contrasena1 = $("#contrasena").val();
	var contrasena2 = $("#contrasenar").val();

	$.ajax({ type			: 'POST',
		     url	 		: 'src/restablecerContrasena.php',
		     data			: {contrasena1 : contrasena1,
		     				   contrasena2 : contrasena2},
		     dataType		: 'json',
		     encode			: true
		    })
	.done(function(data){
		if(data.exitoso){
			console.log(data);
			$("#contrasena").val("");
			$("#contrasenar").val("");
			$("#formulario-restablecer").hide();
			$("#restablecimiento-exitoso").show();
		}
		else{
			console.log(data.Mensaje);
			$("#mensaje-error-con").text(data.Mensaje).show();
		}
	});
}