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

	/* Estado del registro */
	$estado = array();

	// Inicia sesión
	SESSION_START();

	// ¿Hay una sesión activa?
	if(!isset($_SESSION['ID_Empleado']))
	{
		$mysqli -> close();
		header("Location: /evaluaciones/");
		$actualizarActivado = false;
	}

	if(isset($_POST['consulta'])){
		// Obtiene el texto a consultar
		$texto_consultar = $mysqli -> real_escape_string($_POST['consulta']);

		$consulta = "SELECT ID_Empleado, Nombre_Proceso, Nombre, Apellidos, Foto FROM `empleados` JOIN procesos  ON empleados.ID_Proceso = procesos.ID_Proceso WHERE Nombre LIKE " . "'%" . $texto_consultar . "%'" . " OR Apellidos LIKE " . "'%" . $texto_consultar . "%'";

		// Hace la consulta
		$resultado = $mysqli -> query($consulta);

		if($resultado -> num_rows > 0)
		{
			$datos['exitoso'] = true;
  			$i = 0;
			while($evaluaciones = $resultado -> fetch_assoc())
  			{
  				// Asigna el codigo de empleado
  				$datos[$i]['Codigo'] = $evaluaciones['ID_Empleado'];

  				// Asigna el codigo de empleado
  				$datos[$i]['Proceso'] = $evaluaciones['Nombre_Proceso'];

        		// Asigna el nombre
  				$datos[$i]['Nombre'] = $evaluaciones['Nombre'];

        		// Apellidos
        		$datos[$i]['Apellidos'] = $evaluaciones['Apellidos'];

          		// Foto (si tien3)
          		$datos[$i]['Foto'] = $evaluaciones['Foto'];

        		// Establece el tamaño del arreglo
        		$datos['length'] = $i;

        		// Incrementa el iterador
  				$i++;
  			}
  		}
    	else
    	{
      		$datos['exitoso'] = false;
    	}
	}
	else
	{
		$datos['exitoso'] = false;
	}

	echo json_encode($datos);

  	$mysqli -> close();
?>