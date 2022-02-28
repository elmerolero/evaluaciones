
<?php
	/* Objetivo del script
	 * Obtiene los empleados que aún pueden ser evaluados, ejemplo:         *
	 * Si quiero que 'Juan' evalúe a 'Francisco' pero aún no le habilito la *
	 * evaluación, este script debera devolver los datos de Francisco para  *
	 * que sea mostrado para habilitarle su evaluación correspondiente.     */
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
	if(isset($_SESSION['ID_Empleado']))
	{
		// ¿Es el admin?
		if($_SESSION['Administrador']);
		else header('Location: /evaluaciones/Inicio');
	}
	else
	{
		header('Location: /evaluaciones/');
	}

	// ¿Cuál fue la persona seleccionada?
	$codigo = $_POST['codigo'];

	/* Comando para consultar a la base de datos */
	$comandoConsulta = "SELECT ID_Empleado, Nombre, Apellidos, Nombre_Proceso, Foto FROM empleados JOIN procesos ON procesos.ID_Proceso = empleados.ID_Proceso ORDER BY Apellidos";

	// Obtenemos los empleados
	$empleados = $mysqli -> query($comandoConsulta);

	// ¿Hay empleados?
	if($empleados -> num_rows > 0){
		// Definimos el tamaño del arreglo
		$datos['length'] = 0;

		// Para cada uno de los empleados
		while($info = $empleados -> fetch_assoc())
		{
			// Buscamos una evaluacion en la que la persona X tenga evaluados y el empleado seleccionado sea el evaluado
			$codigoEvaluado = $info['ID_Empleado'];

			// Consulta
			$comandoConsulta = "SELECT ID_Evaluacion FROM evaluacion360 WHERE ID_Evaluador = " . $codigo . " AND ID_Evaluado = " . $codigoEvaluado;

			// Obtenemos el empleado
			$resultados = $mysqli -> query($comandoConsulta);

			// ¿NO hay resultados?
			if($resultados -> num_rows <= 0)
			{
				$datos[$datos['length']]['ID_Empleado'] = $info['ID_Empleado'];
				$datos[$datos['length']]['Nombre'] = $info['Nombre'];
				$datos[$datos['length']]['Apellidos'] = $info['Apellidos'];
				$datos[$datos['length']]['Proceso'] = $info['Nombre_Proceso'];
				$datos[$datos['length']]['Foto'] = $info['Foto'];
				$datos['length']++;
			}
		}

		$datos['exitoso'] = true;
	}


	echo json_encode($datos);

	$mysqli -> close();
?>