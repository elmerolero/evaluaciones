<?php
	/* Objetivo del script
	 * Valida el código introducido sea correcto *
	/* Inicia la sesión */
	SESSION_START();

	// ¿Hay una sesión iniciada?
	if(isset($_SESSION['ID_Empleado'])){
		header('Location: /evaluaciones/Inicio');			// Regresa a la sala de contención
	}

	$mysqli = new mysqli("localhost", "root", "", "valcon");

	/* Verifica la conexión */
	if (mysqli_connect_errno()) 
	{
		printf("Falló la conexión: %s\n", mysqli_connect_error());
		exit();
	}

	/* Establece el charset */
	if(!$mysqli -> set_charset("utf8")){
		printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
		exit();
	}

	// Donde se guarda la información devuelta
	$datos = array();

	if(!empty($_POST['codigoUsr'])){
		$codigoUsr = $_POST['codigoUsr'];
		// Busca el código de empleado introducido.
		$consulta = "SELECT ID_Empleado FROM `codigos_seguridad` WHERE ID_Empleado = " . $codigoUsr . " AND Utilizada = false";
		$resultados = $mysqli -> query($consulta);
		if(!empty($resultados) && $resultados -> num_rows > 0){
			$empleado = $resultados -> fetch_assoc();
			if($codigoUsr == $empleado['ID_Empleado']){
				$_SESSION['UsrValidado'] = $codigoUsr;
				$datos['exitoso'] = true;
			}
		}
		else{
			$datos['exitoso'] = false;
			$datos['Mensaje'] = "No haz proporcionado información correcta.";
		}

	}
	else if(!empty($_POST['codigo']) && !empty($_SESSION['UsrValidado'])){
		// Variables para validar el restablecimiento de contraseña
		$codigoUsr = $_SESSION['UsrValidado'];
		$codigo = $_POST['codigo'];

		// Busca el código de empleado introducido.
		$consulta = "SELECT ID_Empleado FROM `codigos_seguridad` WHERE Codigo = '" . $codigo . "' AND Utilizada = false";
		$resultados = $mysqli -> query($consulta);
		if(!empty($resultados) && $resultados -> num_rows > 0){
			$empleado = $resultados -> fetch_assoc();
			// Revisa que haya coherencia entre el empleado sobre el que se desea cambiar la contraseña y el código.
			if($codigoUsr == $empleado['ID_Empleado']){
				$_SESSION['Codigo'] = $codigo;
				$datos['exitoso'] = true;
				$_SESSION['Validado'] = true;
			}else{
				$datos['exitoso'] = false;
				$datos['Mensaje'] = "Incoherencia de empleado y código. Asegúrate de que tu código de restablecimiento de contraseña coincida con tu empleado. Consúltalo con Recursos Humanos.";
			}
		}
		else{
			$datos['exitoso'] = false;
			$datos['Mensaje'] = "Codigo incorrecto.";
		}
	}else{
		$datos['exitoso'] = false;
		$datos['Mensaje'] = "No se ha autenticado el usuario.";
	}

	echo json_encode($datos);

	$mysqli -> close();
?>