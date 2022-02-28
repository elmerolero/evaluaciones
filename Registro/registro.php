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

	// Permite avanzar en el registro, si hay un error tornará falso
	$permitirRegistro = true;

	/* Estado de la autenticación */
	$estado = array();

	// Averigua qué paso es
	$pasoRegistro = $_POST['paso'];

	if($pasoRegistro == 1)
	{
		// Establece el código
		if(!empty($_POST['codigo']))
		{
			$codigo = $_POST['codigo'];
		}
		else
		{
			// No se envió un código y pide que envíe 1
			$estado['exitoso'] = false;
			$estado['codigo'] =	1;
			$estado['mensaje'] = "Ingrese un código por favor.";
			$permitirRegistro = false;
		}
	}
	else if( $pasoRegistro == 2 )
	{
		// Establece el código
		if(!empty($_POST['correo']))
		{
			$correo = $_POST['correo'];
		}
		else
		{
			// No se envió un código y pide que envíe 1
			$estado['exitoso'] = false;
			$estado['codigo'] =	2;
			$estado['mensaje'] = "Ingrese un correo electrónico por favor.";
			$permitirRegistro = false;
		}
	}
	else if( $pasoRegistro == 3 )
	{
		$codigo = $_POST['codigo'];
		$contrasena = $_POST['contrasena'];
		$correo = $_POST['correo'];
	}

	if($permitirRegistro)
	{
		switch($pasoRegistro)
		{
			// Paso 1 identificación de código
			case 1:
				//Hace una consulta
				registroPasoUno();
				break;
			case 2:
				// Realiza paso2
				registroPasoDos();
				break;
			case 3:
				// Registra al usuario
				registroPasoTres();
				break;
		}
	}

	function registroPasoUno()
	{
		// Define las variables globalmente
		global $mysqli, $codigo, $estado;

		// Comando de consulta
		$comandoConsulta = "SELECT ID_Empleado, Activada FROM empleados WHERE ID_Empleado = " . $codigo . "";
		
		// Hace la consulta
		if($consulta = $mysqli -> query($comandoConsulta))
		{
			// ¿Hay alguna coincidencia?
			if($consulta -> num_rows > 0)
			{
				// Accede al elemento encontrado
				if($resultado = $consulta -> fetch_assoc())
				{
					// ¿El código que se quiere utilizar para crear la cuenta ya esta activado?
					if(!$resultado['Activada'])
					{
						$estado['exitoso'] = true;
						$estado['mensaje'] = "Paso uno completado";
					}
					else
					{
						$estado['exitoso'] = false;
						$estado['mensaje'] = "Esta cuenta ya ha sido activada";
					}
				}
			}
			else
			{
				// Indica el error
				$estado['exitoso'] = false;
				$estado['mensaje'] = "El código ingresado no existe";
			}
		}
	}

	function registroPasoDos()
	{
		// Define las variables globalmente
		global $mysqli, $correo, $estado;

		// Comando de consulta
		$comandoConsulta = "SELECT ID_Empleado FROM usuarios WHERE Correo = '" . $correo . "'";

		// Hace la consulta
		if($consulta = $mysqli -> query($comandoConsulta))
		{
			// ¿Existe alguien que utiliza ya ese correo electrónico?
			if($consulta -> num_rows > 0)
			{
				// Indica el error
				$estado['exitoso'] = false;
				$estado['mensaje'] = "El correo introducido ya está en uso";
			}
			else
			{
				// Puede proseguir
				$estado['exitoso'] = true;
				$estado['mensaje'] = "Paso dos completado";
			}
		}
	}

	function registroPasoTres()
	{
		// Define las variables globalmente
		global $mysqli, $codigo, $correo, $contrasena, $estado;

		// Crea un hash para la contraseña
		$hash = password_hash($contrasena, PASSWORD_BCRYPT);
		
		// Crea el usuario
		$comandoInsercion = "INSERT INTO `usuarios`(`ID_Empleado`, `Correo`, `Contrasena`) VALUES (" . $codigo . ", '" . $correo . "', '" . $hash . "')";

		// Realiza la inserción
		if( $mysqli -> query($comandoInsercion) )
		{
			// Activa el usuario (es decir, que la cuenta de ese usuario ya fue activada)
			$comandoActualizacion = "UPDATE empleados SET Activada = 1 WHERE ID_Empleado = " . $codigo . "";

			// Realiza la inserción
			if( $mysqli -> query($comandoActualizacion) )
			{
				$estado['exitoso'] = true;
				$estado['codigo'] = 0;
				$estado['mensaje'] = "Has activado tu cuenta correctamente.";
			}
			else
			{
				$estado['exitoso'] = false;
				$estado['codigo'] = -1;
				$estado['mensaje'] = $mysqli -> error;
			}
		}
		else
		{
			$estado['exitoso'] = false;
			$estado['codigo'] = -1;
			$estado['mensaje'] = $mysqli -> error;
		}
	}


	echo json_encode($estado);
	
	$mysqli -> close();

?>