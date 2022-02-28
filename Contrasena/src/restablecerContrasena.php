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
	// Verifica que estñe autorizada la operación
	if(!empty($_SESSION['UsrValidado']) && $_SESSION['Validado'])
	{
		// Obtiene ambas contraseñas
		$contrasena1 = $_POST['contrasena1'];
		$contrasena2 = $_POST['contrasena2'];

		// No dejó espacios vacíos
		if(!empty($contrasena1) && !empty($contrasena2)){
			// Coincidencia de contraseñas
			if($contrasena1 == $contrasena2){
				// Establece los parámetros de consulta
				$codigoUsr = $_SESSION['UsrValidado'];
				$codigo = $_SESSION['Codigo'];
				$hash = password_hash($contrasena1, PASSWORD_BCRYPT);

				// Actuliza la contraseña
				$consulta = "UPDATE `usuarios` SET `Contrasena`= '" . $hash . "' WHERE ID_Empleado = " . $codigoUsr;
				if($mysqli -> query($consulta)){
					$consulta = "UPDATE `codigos_seguridad` SET `Utilizada`= 1 WHERE ID_Empleado = " . $codigoUsr . " AND Codigo = '" . $codigo . "'";
					if($mysqli -> query($consulta)){
						$datos['exitoso'] = true;
						/* Elimina todas las variables temporales existentes */
						SESSION_DESTROY();
					}else{
						$datos['exitoso'] = false;
						$datos['Mensaje'] = "Error de desarrollador, notifícalo si existe alguna falla.";
					}
				}else{
					$datos['exitoso'] = false;
					$datos['Mensaje'] = "Error de desarrollo, notifícalo si existe alguna falla.";
				}			
			}
			else{
				$datos['exitoso'] = false;
				$datos['Mensaje'] = "Las contraseñas no coinciden.";
			}
		}
		else{
			$datos['exitoso'] = false;
			$datos['Mensaje'] = "Hay campos vacios.";
		}
	}
	else{
		$datos['exitoso'] = false;
		$datos['Mensaje'] = "No código de empleado ni código de autorización.";
	}

	echo json_encode($datos);

	$mysqli -> close();
?>