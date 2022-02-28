<?php
	// Inicia sesión
 	SESSION_START();

 	// ¿Inicio sesión?
 	if(!isset($_SESSION['ID_Empleado']))
		header("Location: /evaluaciones/");
	// ¿Es admin?
	else if(!$_SESSION['Administrador']) 
		header("Location: /evaluaciones/evaluaciones/evaluacion360/resultados/user/");

	// Conecta con la base de datos
	$mysqli = new mysqli("localhost", "root", "", "valcon");

	// Verifica la conexión
	if (mysqli_connect_errno()){
		printf("Falló la conexión: %s\n", mysqli_connect_error());
		exit();
	}

	// Charset UTF-8
	if(!$mysqli -> set_charset("utf8")){
		printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
		exit();
	}

	// Obtiene las preguntas de la evaluacion
	$json = file_get_contents('C:\wamp64\www\evaluaciones\evaluaciones\evaluacion360\evaluar\form\evaluacion360.json');
	$evaluacion = json_decode($json, true);

	// Arreglo para obtener los datos del empleado a consultar
	$datos = array();

	// Anio seleccionado
	$anio = $_POST['Anio'];

	// Bandera que indica si es jefe o no
	$jefe = false;

	// Cantidad de preguntas que tiene la evaluacion
	$numeroPreguntas = count($evaluacion['Preguntas']);

	// Promedio total
	$Promedio_Total = 0;

	// Establece el año
	$datos['Anio'] = $anio;

	// Consulta para obtener el nombre del empleado
	$consulta = "SELECT Nombre, Apellidos FROM `empleados` WHERE empleados.ID_Empleado = " . $_SESSION['Codigo'] . " AND empleados.Habilitada = 1";
	$resultados = $mysqli -> query($consulta);
	if($resultados -> num_rows > 0){
		$empleado = $resultados -> fetch_assoc();
		$datos['Nombre'] = $empleado['Apellidos'] . " " . $empleado['Nombre'];
	}

	// Obtiene la relación de los evaluadores con el empleado evaluado
	$consulta = "SELECT Tipo_Relacion FROM `evaluacion360` WHERE ID_Evaluado = " . $_SESSION['Codigo'] . " AND Anio = " . $anio . " ORDER BY Tipo_Relacion";
	$resultados = $mysqli -> query($consulta);
	$relacion = ""; $i = 0;
	if($resultados -> num_rows > 0){
		while($relaciones = $resultados -> fetch_assoc()){
			if($relacion != $relaciones['Tipo_Relacion']){
				$relacion = $relaciones['Tipo_Relacion'];
				$datos['Relaciones'][$i] = $relaciones['Tipo_Relacion'];
				$i++;
				if($relacion == 'Jefe')
					$jefe = true;
			}
		}
	}

	// Consulta para obtener las respuestas de la auto-evaluación
	$consulta = "SELECT * FROM `evaluacion360` WHERE Realizada = true AND ID_Evaluador = ".$_SESSION['Codigo'] . " AND ID_Evaluado = " . $_SESSION['Codigo'] . " AND Anio = " . $anio;

	// Consulta los resultados de su propia evaluacion
	$resultados = $mysqli -> query($consulta);
	if(isset($resultados) && $resultados -> num_rows > 0){
		$respuestas = $resultados -> fetch_assoc();

		for($i = 0, $j = 0; $i < $numeroPreguntas - 4; $i++)
		{
			$datos['Autoevaluacion'][$i] = $respuestas['R'.($i+1)];		
		}

		// Establece los aspectos de su propia evaluacion
		$datos['Mis_aspectos']['Honradez'] = $respuestas['Honradez'];
		$datos['Mis_aspectos']['Trabajo_Equipo'] = $respuestas['Trabajo_Equipo'];
		$datos['Mis_aspectos']['Calidad'] = $respuestas['Calidad'];
		$datos['Mis_aspectos']['Respeto'] = $respuestas['Respeto'];
		$datos['Mis_aspectos']['Responsabilidad'] = $respuestas['Responsabilidad'];
		$datos['Mis_aspectos']['CincoS'] = $respuestas['CincoS'];
		$datos['Mis_aspectos']['Seguridad'] = $respuestas['Seguridad'];
	}
	else{
		$datos['Mensaje'] = "Error. No se ha evaluado a el mismo.";
		$datos['Autoevaluacion'] = '-';

		// Obtiene el promedio de los aspectos que evaluó en sí mismo.
		$datos['Mis_aspectos']['Honradez'] = '-';
		$datos['Mis_aspectos']['Trabajo_Equipo'] = '-';
		$datos['Mis_aspectos']['Calidad'] = '-';
		$datos['Mis_aspectos']['Respeto'] = '-';
		$datos['Mis_aspectos']['Responsabilidad'] = '-';
		$datos['Mis_aspectos']['CincoS'] = '-';
		$datos['Mis_aspectos']['Seguridad'] = '-';	
	}
	$datos['Mis_aspectos']['Liderazgo'] = '-';

	// Promedio general por pregunta
	for($i = 0; $i < $numeroPreguntas - 4; $i++)
	{
		$consulta = "SELECT TRUNCATE(AVG(R" . ($i + 1) . "), 2) as R" . $i . " FROM `evaluacion360` WHERE ID_Evaluado = " . $_SESSION['Codigo'] . " AND Anio = " . $anio;
		$resultados = $mysqli -> query($consulta);

		if(isset($resultados) && $resultados -> num_rows > 0){
			$promedio = $resultados -> fetch_assoc();
		}
		
		$datos['Promedios'][$i] = $promedio["R" . $i];
	}

	// Obtiene el promedio general en cada aspecto
	$consulta = "SELECT TRUNCATE(AVG(Honradez), 2) as Honradez, TRUNCATE(AVG(Trabajo_Equipo), 2) as Trabajo_Equipo, TRUNCATE(AVG(Calidad), 2) as Calidad, TRUNCATE(AVG(Respeto), 2) as Respeto, TRUNCATE(AVG(Responsabilidad), 2) as Responsabilidad, TRUNCATE(AVG(CincoS), 2) as CincoS, TRUNCATE(AVG(Seguridad), 2) as Seguridad FROM evaluacion360 WHERE Realizada = true AND Anio = " . $anio . " AND ID_Evaluado = " . $_SESSION['Codigo'];

	$resultados = $mysqli -> query($consulta);
	if(!empty($resultados) && $resultados -> num_rows > 0){
		if($aspectos = $resultados -> fetch_assoc()){
			$datos['Aspectos']['Honradez'] = $aspectos['Honradez'];
			$datos['Aspectos']['Trabajo_Equipo'] = $aspectos['Trabajo_Equipo'];
			$datos['Aspectos']['Calidad'] = $aspectos['Calidad'];
			$datos['Aspectos']['Respeto'] = $aspectos['Respeto'];
			$datos['Aspectos']['Responsabilidad'] = $aspectos['Responsabilidad'];
			$datos['Aspectos']['CincoS'] = $aspectos['CincoS'];
			$datos['Aspectos']['Seguridad'] = $aspectos['Seguridad'];
		}
	}

	$datos['Aspectos']['Liderazgo'] = '-';

	// Si es jefe...
	if($jefe){
		// Obtiene las respuestas de su autoevaluación de liderazgo
		$consulta = "SELECT R32, R33, R34, R35, Liderazgo FROM `evaluacion360` WHERE Realizada = true AND Anio = " . $anio . " AND ID_Evaluador = " . $_SESSION['Codigo'] . " AND ID_Evaluado = " . $_SESSION['Codigo'];
		$resultados = $mysqli -> query($consulta);
		if(!empty($resultados) && $resultados -> num_rows > 0){
			$respuestas = $resultados -> fetch_assoc();
			$datos['Autoevaluacion'][31] = $respuestas['R32'];
			$datos['Autoevaluacion'][32] = $respuestas['R33'];
			$datos['Autoevaluacion'][33] = $respuestas['R34'];
			$datos['Autoevaluacion'][34] = $respuestas['R35'];

			// El promedio de las respuestas de la auto-evaluación Liderazgo
			$datos['Mis_aspectos']['Liderazgo'] = $respuestas['Liderazgo'];
		}

		// Promedio por respuesta de las preguntas de liderazgo
		$consulta = "SELECT TRUNCATE(AVG(R32), 2) AS R32, TRUNCATE(AVG(R33), 2) AS R33, TRUNCATE(AVG(R34), 2) AS R34, TRUNCATE(AVG(R35), 2) AS R35, TRUNCATE(AVG(Liderazgo), 2) AS Liderazgo FROM `evaluacion360` WHERE Realizada = true AND Anio = " . $anio . " AND (Tipo_Relacion = 'Jefe' OR Tipo_Relacion = 'Yo mismo') AND ID_Evaluado = " . $_SESSION['Codigo'];
		$resultados = $mysqli -> query($consulta);
		if(!empty($resultados) && $resultados -> num_rows > 0){
			$respuestas = $resultados -> fetch_assoc();
			$datos['Promedios'][31] = $respuestas['R32'];
			$datos['Promedios'][32] = $respuestas['R33'];
			$datos['Promedios'][33] = $respuestas['R34'];
			$datos['Promedios'][34] = $respuestas['R35'];

			// El promedio de las respuestas de la auto-evaluación Liderazgo
			$datos['Aspectos']['Liderazgo'] = $respuestas['Liderazgo'];	
		}

		// Obtiene el promedio final
		$Promedio_Total = ($datos['Aspectos']['Honradez'] + $datos['Aspectos']['Trabajo_Equipo'] + $datos['Aspectos']['Calidad'] + $datos['Aspectos']['Respeto'] + $datos['Aspectos']['Responsabilidad'] + $datos['Aspectos']['CincoS'] + $datos['Aspectos']['Seguridad'] + $datos['Aspectos']['Liderazgo']) / 8;
	}
	else{
		// Campos vacío
		$datos['Autoevaluacion'][31] = '-';
		$datos['Autoevaluacion'][32] = '-';
		$datos['Autoevaluacion'][33] = '-';
		$datos['Autoevaluacion'][34] = '-';
		$datos['Promedios'][31] = '-';
		$datos['Promedios'][32] = '-';
		$datos['Promedios'][33] = '-';
		$datos['Promedios'][34] = '-';

		// Obtiene el promedio final
		$Promedio_Total = ($datos['Aspectos']['Honradez'] + $datos['Aspectos']['Trabajo_Equipo'] + $datos['Aspectos']['Calidad'] + $datos['Aspectos']['Respeto'] + $datos['Aspectos']['Responsabilidad'] + $datos['Aspectos']['CincoS'] + $datos['Aspectos']['Seguridad']) / 7;
	}

	// Obtiene el promedio final
	$datos['Promedio_Total'] = $Promedio_Total;
	$datos['Opiniones'] = "";

	// Obtiene las opiniones
	$consulta = "SELECT Nombre, Apellidos, Opinion FROM empleados JOIN evaluacion360 where evaluacion360.Realizada = true AND evaluacion360.Anio = " . $anio . " AND evaluacion360.ID_Evaluador = empleados.ID_Empleado AND evaluacion360.ID_Evaluado = " . $_SESSION['Codigo'];
	$resultados = $mysqli -> query($consulta);
	if(!empty($resultados) && $resultados -> num_rows > 0){
		while ($opiniones = $resultados	-> fetch_assoc()) {
			$datos['Opiniones'] .= $opiniones['Apellidos'] . ' ' . $opiniones['Nombre'] . ': ' . $opiniones['Opinion'] . ' | '; 
		}
	}

	echo json_encode($datos);

	$mysqli -> close();
?>