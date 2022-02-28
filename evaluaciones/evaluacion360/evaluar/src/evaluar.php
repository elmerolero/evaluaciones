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

    /* Estado del registro */
    $estado = array();

  	/* Inicia la sesión */
  	SESSION_START();

    // ¿Hay una sesión iniciada?
    if(!isset($_SESSION['ID_Empleado']))
        header('Location: /evaluaciones/');

    // Obtiene los datos de la evaluacion
    $datos['evaluado'] = $_POST['codigoEvaluado'];
    $datos['evaluador'] = $_POST['codigoEvaluador'];
    $datos['opinion'] = $_POST['opinion'];
    $datos['relacion'] = $_POST['relacion'];
    $datos['respuestas'] = $_POST['respuestas'];

    // Obtenemos los resultados de
    $honradez = 0;
    $trabajoEquipo = 0;
    $calidad = 0;
    $respeto = 0;
    $responsabilidad = 0;
    $cincoS = 0;
    $seguridad = 0;

    // Opcional
    $liderazgo = 0;

    // Comenzamos calculando cada uno de los aspectos
    // Honradez
    for($i =  0; $i < 4; $i++){
        $honradez += $datos['respuestas'][$i];
    }

    $datos['honradez'] = $honradez /= 4;

    // Trabajo en equipo
    for($i =  4; $i < 8; $i++)
        $trabajoEquipo += $datos['respuestas'][$i];
    
    $datos['trabajoEquipo'] = $trabajoEquipo /= 4;

    // Calidad
    for($i =  8; $i < 15; $i++)
        $calidad += $datos['respuestas'][$i];
    
    $datos['calidad'] = $calidad /= 7;

    // Respeto
    for($i =  15; $i < 19; $i++)
        $respeto += $datos['respuestas'][$i];
    
    $datos['respeto'] = $respeto /= 4;

    // Respondabilidad
    for($i =  19; $i < 23; $i++){
        $responsabilidad += $datos['respuestas'][$i];
    }
    
    $datos['responsabilidad'] = $responsabilidad /= 4;

    // Cinco S
    for($i =  23; $i < 28; $i++){
        $cincoS += $datos['respuestas'][$i];
    }
    
    $datos['cincoS'] = $cincoS /= 5;

    // Seguridad
    for($i =  28; $i < 31; $i++)
        $seguridad += $datos['respuestas'][$i];
    
    $datos['seguridad'] = $seguridad /= 3;

    // Evalúa Liderazgo
    if($datos['relacion'] == 'Jefe' || $datos['relacion'] == 'Yo mismo')
    {
        for($i =  31; $i < 35; $i++)
            $liderazgo += $datos['respuestas'][$i];
    
        $datos['liderazgo'] = $liderazgo /= 4;
    }
    else{
        for($i =  31; $i < 35; $i++)
            $datos['respuestas'][$i] = 0;

        $datos['liderazgo'] = 0;
    }

    // Super comando de registro de respuestas
    $comando = "UPDATE `evaluacion360` SET `Tipo_Relacion`='" . $_POST['relacion'] . "',`R1`=" . $datos['respuestas'][0] . ",`R2`=" . $datos['respuestas'][1] . ",`R3`=" . $datos['respuestas'][2] . ",`R4`=" . $datos['respuestas'][3] . ",`Honradez`=" . $datos['honradez'] . ",`R5`=" . $datos['respuestas'][4] . ",`R6`=" . $datos['respuestas'][5] . ",`R7`=" . $datos['respuestas'][6] . ",`R8`=" . $datos['respuestas'][7] . ",`Trabajo_Equipo`=" . $datos['trabajoEquipo'] . ",`R9`=" . $datos['respuestas'][8] . ",`R10`=" . $datos['respuestas'][9] . ",`R11`=" . $datos['respuestas'][10] . ",`R12`=" . $datos['respuestas'][11] . ",`R13`=" . $datos['respuestas'][12] . ",`R14`=" . $datos['respuestas'][13] . ",`R15`=" . $datos['respuestas'][14] . ",`Calidad`=" . $datos['calidad'] . ",`R16`=" . $datos['respuestas'][15] . ",`R17`=" . $datos['respuestas'][16] . ",`R18`=" . $datos['respuestas'][17] . ",`R19`=" . $datos['respuestas'][18] . ",`Respeto`=" . $datos['respeto'] . ",`R20`=" . $datos['respuestas'][19] . ",`R21`=" . $datos['respuestas'][20] . ",`R22`=" . $datos['respuestas'][21] . ",`R23`=" . $datos['respuestas'][22] . ",`Responsabilidad`=" . $datos['responsabilidad'] . ",`R24`=" . $datos['respuestas'][23] . ",`R25`=" . $datos['respuestas'][24] . ",`R26`=" . $datos['respuestas'][25] . ",`R27`=" . $datos['respuestas'][26] . ",`R28`=" . $datos['respuestas'][27] . ",`CincoS`=" . $datos['cincoS'] . ",`R29`=" . $datos['respuestas'][28] . ",`R30`=" . $datos['respuestas'][29] . ",`R31`=" . $datos['respuestas'][30] . ",`Seguridad`=" . $datos['seguridad'] . ",`R32`=" . $datos['respuestas'][31] . ",`R33`=" . $datos['respuestas'][32] . ",`R34`=" . $datos['respuestas'][33] . ",`R35`=" . $datos['respuestas'][34] . ",`Liderazgo`=" . $datos['liderazgo'] . ",`Opinion`='" . $datos['opinion'] . "',`Realizada`= 1 WHERE ID_Evaluador = " . $datos['evaluador'] . " AND ID_Evaluado = " . $datos['evaluado'];

    if( $mysqli -> query($comando) )
    {
        $estado['exitoso'] = true;
        $estado['mensaje'] = 'Evaluación enviada correctamente.';
    }
    else{
        $estado['mensaje'] = 'Ha ocurrido un error.';
    }

    echo json_encode($estado);

    $mysqli -> close();
?>