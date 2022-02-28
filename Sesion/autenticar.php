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

	/* Estado de la autenticación */
	$estado = array();

	/* Verifica que los campos no estén vacíos */
	if(empty($_POST['codigo']))
	{
		$estado['exito'] = false;	// No será exitoso
		$estado['codigo'] = 1;		// El código de error es 1 (Campo codigo vacío)
	}
	else
	{
		/* Datos de código y contraseña */
		$codigo = $_POST['codigo'];

		/* Comando para consultar a la base de datos */
		$comandoConsulta = "SELECT Contrasena, Administrador, Activada FROM usuarios JOIN empleados ON usuarios.ID_Empleado = empleados.ID_Empleado WHERE usuarios.ID_Empleado = '" . $codigo . "'";

		/* Realiza la consulta */
		$resultado = $mysqli -> query($comandoConsulta);
		if (!$resultado) {
    		trigger_error('Invalid query: ' . $mysqli->error);
		}

		// Comprueba que arroje resultados
		if($resultado ->num_rows > 0)
		{
			// Obtiene el primer resultado (en realidad debe de haber solo un resultado porque la ID es única)
			if( $datos = $resultado -> fetch_assoc() )
			{
				if(empty($_POST['contrasena']))
				{
					$estado['exito'] = false;	// No será exitoso
					$estado['codigo'] = 2;		// El código de error es 2 (Campo contraseña vacío)
				}
				else{
					$contrasena = $_POST['contrasena'];	
				
					/* Hace falta comprobar que las contraseñas coincidan, obtiene la contraseña de la consulta */
					$conEmpleado = $datos['Contrasena'];

					/* Compara la contraseña de la base de datos con la introducida por el usuario */
					if(password_verify($contrasena, $conEmpleado))
					{
						$estado['exitoso'] = true;	// El inicio de sesión fue exitoso
						$estado['codigo'] = 0;	// El código de error es 0 (NO ERROR)

						// Inicia sesión
						SESSION_START();

						// Almacena el ID del empleado que inició sesión
						$_SESSION['ID_Empleado'] = $codigo;

						// Almacena si es administrador
						if($datos['Administrador'] == 1)
							$_SESSION['Administrador'] = true;
						else
							$_SESSION['Administrador'] = false;
					}
					else
					{
						$estado['codigo'] = 4;	// El código de error es 4 (Contraseña incorrecta)
						$estado['exitoso'] = false; // El inicio de sesión no fue exitoso
					}
				}
			}
		}
		//
		else
		{
			$estado['exitoso'] = false;	// No hubo incio de sesión exitoso
			$estado['codigo']  = 3;		// Código de error 3 (Usuario no encontrado)
		}
	}
	
	echo json_encode($estado);
	

	$mysqli -> close();
?>