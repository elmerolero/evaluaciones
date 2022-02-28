<?php
	/* Objetivo del script
	 * Crea un código que permitirá a los empleados reestablecer su  *
	 * contraseña, aunque para obtenerlo tendrán que ir a recursos   *
	 * humanos por su código										 */
	/* Inicia la sesión */
	SESSION_START();

	// ¿Hay una sesión iniciada?
	if(isset($_SESSION['ID_Empleado'])){
		header('Location: /evaluaciones/Inicio');
	}

	$mysqli = new mysqli("localhost", "root", "", "valcon");

	/* Verifica la conexión */
	if (mysqli_connect_errno()) 
	{
		printf("Falló la conexión: %s\n", mysqli_connect_error());
		exit();
	}

	/* Establece el charset */
	if(!$mysqli -> set_charset("utf8"))
	{
		printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
		exit();
	}

	/* Arreglo donde guardará la info */
	$datos = array();

	// Código introducido
	$codigo = $_POST['codigo'];

	// Establece la fecha de hoy
	$fecha = date("Y") . '-' . date("m") . '-' . date("d");

	// ¿El código creado existe?
	$consulta = "SELECT ID_Empleado, Habilitada, Activada FROM empleados where ID_Empleado = " . $codigo;
	$resultados = $mysqli -> query($consulta);
	if(!empty($resultados) && $resultados -> num_rows > 0){
		$empleado = $resultados -> fetch_assoc();
		if($empleado['Habilitada'] == true){
			if($empleado['Activada'] == true)
			{
				// Establece el código de seguridad
				$codigo_seguridad = substr(uniqid(), 0, 6);

				// Se asegura de que no se hubiera creado ya una solicitud antes
				$consulta = "SELECT * FROM `codigos_seguridad` WHERE ID_Empleado = " . $codigo . " AND Utilizada = 0";
				$resultados = $mysqli -> query($consulta);
				if(!empty($resultados) && $resultados -> num_rows > 0){
						$datos['exitoso'] = true;
				}else{
					// Comando de insersión
					$consulta = "INSERT INTO `codigos_seguridad`(`ID_Codigo`, `Fecha_Solicitud`, `ID_Empleado`, `Codigo`, `Utilizada`) VALUES (NULL, '" . $fecha . "', " . $codigo . ", '" . $codigo_seguridad . "', 0)";
					if($mysqli -> query($consulta)){
						$datos['exitoso'] = true;
					}
				}
			}
			else{
				$datos['exitoso'] = false;
				$datos['Enlace'] = "/evaluaciones/registro/registro.html";
				$datos['Mensaje'] = "No has activado tu usuario. Actívalo ";
			}
		}else{
			$datos['exitoso'] = false;
			$datos['Enlace'] = "/evaluaciones/registro/registro.html";
			$datos['Mensaje'] = "Ya no trabajas aquí. Más información ";
		}
	}else{
		$datos['exitoso'] = false;
		$datos['Enlace'] = 'NULL';
		$datos['Mensaje'] = "Este empleado no existe.";
	}

	echo json_encode($datos);

	$mysqli -> close();
?>