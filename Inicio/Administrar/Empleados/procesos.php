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

  /* Comando para consultar a la base de datos */
  $comandoConsulta = "SELECT * FROM procesos";

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

      while($procesos = $resultado -> fetch_assoc())
      {
        // Asigna el nombree
        $datos[$i]['ID_Proceso'] = $procesos['ID_Proceso'];

        // Asigna la ruta de seguimiento
        $datos[$i]['Nombre_Proceso'] = $procesos['Nombre_Proceso'];

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