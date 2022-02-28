<?php
  /* Objetivo:
  /* Obtiene absolutamente a todos los empleados y algunos de sus datos para seleccionar *
   * a uno para decidir a quienes va a evaluar */

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

  	/* Comando para consultar a la base de datos */
  	$comandoConsulta = "SELECT ID_Empleado, Nombre, Apellidos, Nombre_Proceso, Foto FROM empleados JOIN procesos ON empleados.ID_Proceso = procesos.ID_Proceso ORDER BY Apellidos";

  	/* Realiza la consulta */
 	$resultado = $mysqli -> query($comandoConsulta);
  
  	if (!$resultado) 
  	{
      	trigger_error('Invalid query: ' . $mysqli->error);
  	}
  	else
  	{
  		if($resultado -> num_rows > 0)
  		{
      		$datos['exitoso'] = true;
  			$i = 0;

  			while($evaluaciones = $resultado -> fetch_assoc())
  			{
          // Asigna el ID
          $datos[$i]['ID_Empleado'] = $evaluaciones['ID_Empleado'];

        	// Asigna el nombre
  				$datos[$i]['Nombre'] = $evaluaciones['Nombre'];

        	// Apellidos
        	$datos[$i]['Apellidos'] = $evaluaciones['Apellidos'];

          // Proceso
          $datos[$i]['Proceso'] = $evaluaciones['Nombre_Proceso'];

          // Foto (si tien3)
          $datos[$i]['Foto'] = $evaluaciones['Foto'];

        		// Incrementa el iterador
  				$i++;

        		// Establece el tamaño del arreglo
        		$datos['length'] = $i;
  			}
  		}
    	else
    	{
      		$datos['exitoso'] = false;
    	}
  }

  echo json_encode($datos);

  $mysqli -> close();

?>