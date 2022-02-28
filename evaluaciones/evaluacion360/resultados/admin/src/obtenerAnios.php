<?php
	/* Este script devuelve los años en que ha sido evaluado el usario.  *
	 * Por ejemplo, si fue evaluado en 2018, 2019 y 2024, devolverá esos *
	 * años para ser almacenados en un select */

	/* Inicia la sesión */
	SESSION_START();

	// ¿Hay una sesión iniciada?
	if(!isset($_SESSION['ID_Empleado'])){
		header('Location: /evaluaciones/');
	}

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

	// Obtiene el usuario que se consulta
	$usuario = $_SESSION['Codigo'];

	$consulta = "SELECT Anio FROM `evaluacion360` WHERE ID_Evaluado = " . $usuario . " AND Realizada = 1 ORDER BY Anio";

	$resultado = $mysqli -> query($consulta);

	if(isset($resultado) && $resultado -> num_rows > 0){
		$datos['exitoso'] = true;
		$i = 0;
		$anioActual = 0;

		while($anios = $resultado -> fetch_assoc()){
			if($anios['Anio'] != $anioActual){
				$anioActual = $anios['Anio'];
				$datos['Anio'][$i] = $anios['Anio'];
				$i++;
				$datos['length'] = $i;
			}
		}
	}
	else{
		$datos['exitoso'] = false;
		$datos['Mensaje'] = "Este usuario nunca ha sido evaluado.";
	}

	echo  json_encode($datos);

	$mysqli -> close();
?>