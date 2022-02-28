<?php
	/* El propósito de éste script es el de obtener los datos deaquellas 
		personas que puede evaluar el usuario en sesión */
	/* Hace una conexión a la base de datos */
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

	/* Inicia la sesión */
	SESSION_START();

	// ¿Hay una sesión iniciada?
	if(!isset($_SESSION['ID_Empleado']))
	{
		
		header('Location: /evaluaciones/');
	}

	// ¿Cuál fue la persona seleccionada?
	$codigoEvaluador = $_POST['codigo'];

	// Obtiene el año actual
	$anio = date("Y");

	/* Comando para consultar a la base de datos */
	$comandoConsulta = "SELECT ID_Evaluado, Nombre, Apellidos, Foto, Anio FROM evaluacion360 JOIN empleados ON evaluacion360.ID_Evaluado = empleados.ID_Empleado WHERE evaluacion360.ID_Evaluador = " . $codigoEvaluador . " AND Realizada = 0 AND Anio = " . $anio . " ORDER BY Apellidos";

	// Obtenemos los empleados
	$empleados = $mysqli -> query($comandoConsulta);

	echo $mysqli -> error;

	// ¿Hay empleados?
	if($empleados -> num_rows > 0){

		// Para cada uno de los empleados
		for($datos['length'] = 0; $info = $empleados -> fetch_assoc(); $datos['length']++)
		{
			
				$datos[$datos['length']]['ID_Empleado'] = $info['ID_Evaluado'];
				$datos[$datos['length']]['Nombre'] = $info['Nombre'];
				$datos[$datos['length']]['Apellidos'] = $info['Apellidos'];
				$datos[$datos['length']]['Foto'] = $info['Foto'];
				$datos[$datos['length']]['Anio'] = $info['Anio'];
		}

		$datos['exitoso'] = true;
	}
	else{
		$datos['exitoso'] = true;
		$datos['length'] = 0;
		$datos['mensaje'] = 'NO hay empleados a evaluar';
	}


	echo json_encode($datos);

	$mysqli -> close();
?>