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

    if(empty($_POST['evaluador']) AND empty($_POST['evaluados'])){
        $datos['exitoso'] = false;
    }
    else
    {
        // Establece el evaluador
        $evaluador = $_POST['evaluador'];
        $datos['length'] = 0;
        // Añade los empleados evaluados
        for($i = 0; $i < $_POST['length']; $i++)
        {   
            $evaluado = $_POST['evaluados'][$i];
            $anio = date("Y");
            $comandoInsercion = "INSERT INTO `evaluacion360`(`ID_Evaluacion`, `ID_Tipo`, `ID_Evaluador`, `ID_Evaluado`, `Anio`) VALUES (null, 1, " . $evaluador . ", " . $evaluado . ", " . $anio . ")";

            if($mysqli -> query($comandoInsercion)){
                $datos['evaluados'][$i] = $evaluado;
                $datos['length']++;
            }
        }

        // Establece los demás datos
        $datos['exitoso'] = true;
        $datos['evaluador'] = $evaluador;
    }

    echo json_encode($datos);

    $mysqli -> close();

?>

