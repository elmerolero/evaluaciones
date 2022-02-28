<?php

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

	// Retorna true si encuentra al empleado
    function existeEmpleadoEvaluado($evaluacion, $evaluador, $evaluado)
    {
        global $mysqli;

        // Comando de consulta
        $comandoConsulta = "SELECT ID_Evaluado FROM `empleados_evaluar` WHERE ID_Tipo = " . $evaluacion . " AND ID_Evaluador = " . $evaluador . " AND ID_Evaluado = " . $evaluado . "";

        // Hace la consulta
        if($resultados = $mysqli -> query($comandoConsulta)){
        	if( $resultados -> num_rows > 0 )
            	return true;
        	else
            	return false;
        }
    }

    echo existeEmpleadoEvaluado($_GET['evaluacion'], $_GET['evaluador'], $_GET['evaluado']);
?>