<?php
	SESSION_START();

	if(isset($_SESSION['ID_Empleado'])){
		header("Location: /evaluaciones/Inicio/");	
	}
?>
<!doctype html>
<html lang="es-mx">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<link rel="stylesheet" href="/evaluaciones/style.css">
		<link rel="stylesheet" href="contrasena.css">
		<title>Distribuidora Valcon</title>
	</head>
	<body>
		<div class="wrapper">
			<header>
				<div class="container">
					<div class="row">
						<div class="col-11">
							<div id="logo">
								<img src="/evaluaciones/imagenes/logo.jpg" alt="Valcon" width="80">
							</div>
						</div>
						<div class="centrado registro col-1">
							<a href="/evaluaciones/Registro/registro.html" class="registro">Registro</a>
						</div>
					</div>
				</div>
			</header><br><br>	
			<div id="formulario-usuario">
				<form class="form-signin" method="POST" id="validar-usr">
					<h2 align="center">Tengo un código</h2><br>
					<input type="text" class="form-control" id="codigoUsr" placeholder="Código de empleado">
					<p id="mensaje-error-usr" align="center">Mensaje de error.</p>
					<input type="submit" class="btn btn-primary btn-block" value="Seguiente" id="validar-usuario">
				</form>
				<a href="/evaluaciones/">Regresar</a>
			</div>
			<div id="formulario-codigo">
				<form class="form-signin" method="POST" id="validar-cod">
					<h2 align="center">Tengo un código</h2><br>
					<input type="text" class="form-control" id="codigo" placeholder="Escribe el código asignado">
					<p id="mensaje-error-cod" align="center">Mensaje de error.</p>
					<input type="submit" class="btn btn-primary btn-block" value="Seguiente" id="validar-codigo">
				</form>
				<a href="/evaluaciones/">Regresar</a>
			</div>
			<div id="formulario-restablecer">
				<form class="form-signin" method="POST" id="restablecer-contrasena">
					<h2 align="center">Restablecimiento de contraseña</h2><br>
					<input type="password" class="form-control" id="contrasena" placeholder="Escribe una contraseña">
					<input type="password" class="form-control" id="contrasenar" placeholder="Confirma contraseña">
					<p id="mensaje-error-con" align="center">Mensaje de error.</p>
					<input type="submit" class="btn btn-primary btn-block" value="Restablecer" id="restablecer">
				</form>
				<a href="/evaluaciones/">Regresar</a>
			</div>
			<div id="restablecimiento-exitoso">
				<h2 align="center">Se ha reestablecido la contraseña</h2>
				<img src="/evaluaciones/imagenes/Sesion/checked.png" style="display: block; margin: auto;"/>
				<p align="center">Intenta iniciar sesión con tu nueva contraseña.</p>
				<a href="/evaluaciones/">Iniciar Sesión</a>
			</div>
		</div>
		<footer class="footer">
			<div class="container">
				<div class="row">
					<p class="col-10">Distribuidora Valcon <br> Programa de Evaluación de empleados</p>
					<a href="http://www.valcon.com.mx" class="centrado col">www.valcon.com.mx</a>
				</div>
			</div>
		</footer>

		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

		<!-- Optional JavaScript -->
		<script src="src/restablecerContrasena.js" type="text/javascript"></script>
	</body>
</html>