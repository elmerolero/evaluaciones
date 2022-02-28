<?php
	/* Objetivo del script
	 * Obtiene los resultado del empleado que está en la sesión             *
	 * En el supuesto de que pedro desee acceder a sus resultados,          *
	 * se llamará a este script que obtendrá los resultados de los últimos  *
	 * cinco años transcurridos. Este script devuelve un json que      *
	 * contiene:															*
	 *     - El promedio de los últimos cinco años transcurridos.        *
	 *     - Las opiniones del último año en que se le evaluó.              */
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
		/*if($_SESSION['Administrador']);
		else header('Location: /evaluaciones/Inicio');*/
	}
	else
	{
		header('Location: /evaluaciones/');
	}

	// ¿Cuál fue la persona seleccionada?
	$codigo = $_SESSION["ID_Empleado"];

	// ¿Año en curso?
	$anio = $_POST['Anio'];

	/* Comando para consultar a la base de datos */
	$consulta = "SELECT TRUNCATE(AVG(Honradez), 2), TRUNCATE(AVG(Trabajo_Equipo), 2), TRUNCATE(AVG(Calidad), 2), TRUNCATE(AVG(Respeto), 2), TRUNCATE(AVG(Responsabilidad), 2), TRUNCATE(AVG(CincoS), 2), TRUNCATE(AVG(Seguridad), 2) FROM `evaluacion360` WHERE Realizada = true AND Anio = " . $anio . " AND ID_Evaluado =" . $codigo;

	// Obtenemos las evaluaciones que pedimos
	$resultados = $mysqli -> query($consulta);

	// ¿No hay resultados
	if(empty($resultados)){
		echo $mysqli -> error;
	}
	else if($resultados -> num_rows > 0){
		// Obtiene los resultados de la base de datos
		$aspectos = $resultados -> fetch_assoc();

		// Establece los datos requeridos por el json
		$datos['Honradez'] = $aspectos['TRUNCATE(AVG(Honradez), 2)'];
		$datos['Trabajo_Equipo'] = $aspectos['TRUNCATE(AVG(Trabajo_Equipo), 2)'];
		$datos['Calidad'] = $aspectos['TRUNCATE(AVG(Calidad), 2)'];
		$datos['Respeto'] = $aspectos['TRUNCATE(AVG(Respeto), 2)'];
		$datos['Responsabilidad'] = $aspectos['TRUNCATE(AVG(Responsabilidad), 2)'];
		$datos['CincoS'] = $aspectos['TRUNCATE(AVG(CincoS), 2)'];
		$datos['Seguridad'] = $aspectos['TRUNCATE(AVG(Seguridad), 2)'];

		// Si fue evaluado
		$datos['Evaluado'] = true;
	}
	else{
		$datos['Evaluado'] = false;
	}

	// Obtiene los resultados de liderazgo
	$consulta = "SELECT Liderazgo FROM evaluacion360 WHERE Realizada = true AND Tipo_Relacion = 'Jefe' AND Anio = " . $anio . " AND ID_Evaluado = " . $codigo;

	$resultados = $mysqli -> query($consulta);
	if(!empty($resultados) && $resultados -> num_rows > 0){
		// Obtiene los resultados de liderazgo
		$consulta = "SELECT TRUNCATE(AVG(Liderazgo), 2) FROM evaluacion360 WHERE Realizada = true AND (Tipo_Relacion = 'Jefe' OR Tipo_Relacion = 'Yo mismo') AND Anio = " . $anio . " AND ID_Evaluado = " . $codigo;
		$resultados = $mysqli -> query($consulta);

		$liderazgo = $resultados -> fetch_assoc();
		$datos['Liderazgo'] = $liderazgo['TRUNCATE(AVG(Liderazgo), 2)'];

		// Calcula el promedio
		$datos['Promedio_Final'] = ($datos['Honradez'] + $datos['Trabajo_Equipo'] + $datos['Calidad'] + $datos['Respeto'] + $datos['Responsabilidad'] + $datos['CincoS'] + $datos['Seguridad'] + $datos['Liderazgo']) / 8;
	} else{
		$datos['Liderazgo'] = -1;
		$datos['Promedio_Final'] = ($datos['Honradez'] + $datos['Trabajo_Equipo'] + $datos['Calidad'] + $datos['Respeto'] + $datos['Responsabilidad'] + $datos['CincoS'] + $datos['Seguridad']) / 7;
	}

	// Obtiene las opiniones
	$consulta = "SELECT Opinion FROM `evaluacion360` WHERE Realizada = true AND Anio = " . $anio . " AND ID_Evaluado = " . $codigo;
	$resultados = $mysqli -> query($consulta);
	if(!empty($resultados)){
		for($i = 0; $opiniones = $resultados -> fetch_assoc(); $i++){
			if($opiniones['Opinion'] != 'No oponión');
				$datos['Opinion'][$i] = $opiniones['Opinion'];
		}
	}else{
		$datos['Opinion'] = array(0);
	}

	// Establece el año
	$datos['Anio'] = $anio;

	echo json_encode($datos);

	$mysqli -> close();
?>