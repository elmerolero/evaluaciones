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

  /* Inicia la sesión */
  SESSION_START();

  /* Comando para consultar a la base de datos */
  $comandoConsulta = "SELECT Nombre_Tipo, Ruta_Evaluacion FROM `tipos_evaluacion` WHERE 1";
  
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
        // Asigna el nombree
  			$datos[$i]['Nombre'] = $evaluaciones['Nombre_Tipo'];

        // Asigna la ruta de seguimiento
        $datos[$i]['Ruta_Seguimiento'] = $evaluaciones['Ruta_Evaluacion'];

        // Incrementa el iterador
  			$i++;

        // Establece el tamaño del arreglo
        $datos['length'] = $i;
  		}
  	}
  }

  echo json_encode($datos);

  $mysqli -> close();

?>