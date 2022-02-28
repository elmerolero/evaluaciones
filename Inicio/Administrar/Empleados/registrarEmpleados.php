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

	/* Verifica que los campos no estén vacíos */
	if(empty($_POST['nombre']))
	{
		$estado['exitoso'] = false;	// No será exitoso
		$estado['codigo'] = 1;		// El código de error es 1 (Campo nombre vacío)
	}
	else if(empty($_POST['codigo']))
	{
		$estado['exitoso'] = false;	// No será exitoso
		$estado['codigo'] = 2;		// El código de error es 2 (Campo contraseña vacío)
	}
	else if(empty($_POST['apellidos']))
	{
		$estado['exitoso'] = false;	// No será exitoso
		$estado['codigo'] = 3;		// El código de error es 3 (Campo apellidos vacío)
	}
	else if(empty($_POST['proceso']))
	{
		$estado['exitoso'] = false;	// No será exitoso
		$estado['codigo'] = 4;		// El código de error es 4 (No se seleccionó un área)
	}
	else
	{
		// Definimos los datos POST en variables
		$nombreUsr = $_POST['nombre'];
		$apellidosUsr = $_POST['apellidos'];
		$codigoUsr = $_POST['codigo'];
		$proceso = $_POST['proceso'];

		if(empty($_POST['admin']))
		{
			$administrador = 0;
		}
		else
		{
			$administrador = $_POST['admin'];
		}

		/* Comando para consultar a la base de datos */
		$comandoConsulta = "SELECT ID_Empleado FROM empleados WHERE ID_Empleado = '" . $codigoUsr . "'";

		/* Realiza la consulta */
		$resultado = $mysqli -> query($comandoConsulta);

		// ¿Encontró un elemento con el mismo ID?
		if($resultado -> num_rows > 0 )
		{
			$estado['exitoso'] = false;	// El registro no fue exitoso
			$estado['codigo'] = 5;		// El código de error es 5 (ID repetido)
		}
		else
		{
			/* Comando para registrar al usuario */
			$comandoInsersion = "INSERT into empleados (`ID_Empleado`, `ID_Proceso`, `Nombre`, `Apellidos`, `Foto`, `Administrador`, `Activada`) VALUES (" . $codigoUsr . ", " . $proceso . ", '" . $nombreUsr . "', '" . $apellidosUsr . "', '', " . $administrador . ", 0)";

			/* Realiza la insersion */
			$insersion = $mysqli -> query($comandoInsersion);
			if ($insersion) {	
				$estado['exitoso'] = true;
				$estado['codigo'] = 0;
			}
			else
			{
				trigger_error('Invalid query: ' . $mysqli->error);
			}
		}
	}

	// ¿Si se registro un usuario?
	if($estado['exitoso'])
	{
		// Si no se sube un archivo
		if(empty($_FILES['foto_empleado']))
		{
			$comandoInsersion = "UPDATE empleados SET Foto = 'NULL' WHERE ID_Empleado='". $codigoUsr ."'";
			if( $mysqli -> query($comandoInsersion) )
			{
				$estado['codigo'] = 11;		// Código de advertencia 1: No se subió una foto
			}
		}
		else
		{
			// En caso de error tornará falso
			$fotoCargada = true;
			// Obtiene el tamaño de la foto
			$foto_size = $_FILES['foto_empleado']['size'];
		
			// Establecemos el nombre de archivo
			if($_FILES['foto_empleado']['type'] =="image/jpeg")
			{
				$nombreArchivo = "$codigoUsr". ".jpg";
			}
			else if($_FILES['foto_empleado']['type'] =="image/png")
			{
				$nombreArchivo = "$codigoUsr". ".png";
			}
			else
			{
				$estado['codigo'] = 12;		// Código de advertencia 2: Formato de imagen no válido
				$fotoCargada = false;
			}

			$direcctorio = "/evaluaciones/imagenes/Perfiles/" . $codigoUsr . "/" . $nombreArchivo;
			
			if($fotoCargada)
			{
				// ¿El directorio no existe?
				if(!file_exists($direcctorio))
				{
					// Lo crea
					mkdir("C:/wamp64/www/evaluaciones/imagenes/Perfiles/" . $codigoUsr);
				}

				// Mueve el archivo
				if(move_uploaded_file($_FILES['foto_empleado']['tmp_name'], "C:/wamp64/www" . $direcctorio))
				{
					$comandoInsersion = "UPDATE empleados SET Foto ='" . $direcctorio . "' WHERE ID_Empleado = '" . $codigoUsr ."'";

					if( $mysqli -> query($comandoInsersion) )
					{
						$estado['exitoso'] = true;
						$estado['codigo'] = 0;
					}
				}
			}
		}
	}
	
	echo json_encode($estado);
	

	$mysqli -> close();
?>