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

	// ¿Actualizar la información?
	$actualizarActivado = true;

	// Inicia sesión
	SESSION_START();

	// ¿Hay una sesión activa?
	if(!isset($_SESSION['ID_Empleado']))
	{
		header("Location: /evaluaciones/");
		$actualizarActivado = false;
	}

	// ¿Está autorizada la persona?
	if(!$_SESSION['Administrador'])
	{
		$actualizarActivado = false;
	}
	
	// Guarda en variables los datos obtenidos de la base de datos
	$nombreDB;
	$apellidosDB;
	$procesoDB;
	$fotoDB;
	$adminDB;

	// Guarda en variables los datos recibidos por POST
	if(!isset($_POST['nombreE']))
		$estado['exitoso'] = false;
	else
		$nombre = $_POST['nombreE'];

	$apellidos = $_POST['apellidosE'];
	$codigoAnterior = $_POST['codigoAntE'];
	$proceso = $_POST['procesoE'];
	if(isset($_POST['adminE']))
		$admin = $_POST['adminE'];
	else
		$admin = 0;

	/* Obtiene los datos del usuario a actualizar de la base de datos */
	$comandoConsulta = "SELECT * FROM `empleados` WHERE ID_Empleado = " . $codigoAnterior;

	if($consulta = $mysqli -> query($comandoConsulta))
	{
		// ¿Existe un resultado?
		if($consulta -> num_rows > 0)
		{
			if($datos = $consulta -> fetch_assoc())
			{
				$nombreDB = $datos['Nombre'];
				$apellidosDB = $datos['Apellidos'];
				$procesoDB = $datos['ID_Proceso'];
				$fotoDB = $datos['Foto'];
				$adminDB = $datos['Administrador'];
			}
		}
	}

	// ¿El apellido es diferente al de la base de datos?
	if($apellidosDB !== $apellidos and $actualizarActivado)
	{

		$comandoActualizacion = "UPDATE empleados SET Apellidos = '" . $apellidos . "' WHERE ID_Empleado = " . $codigo . "";
		if( $mysqli -> query($comandoActualizacion) )
		{
			$estado['exitoso'] = true;
			$estado['codigo'] = 0;		// Actualización exitosa
		}
	}

	// ¿El proceso es diferente al de la base de datos?
	if($procesoDB !== $proceso and $actualizarActivado)
	{

		$comandoActualizacion = "UPDATE empleados SET ID_Proceso = " . $proceso . " WHERE ID_Empleado = " . $codigo . "";
		if( $mysqli -> query($comandoActualizacion) )
		{
			$estado['exitoso'] = true;
			$estado['codigo'] = 0;		// Actualización exitosa
		}
	}

	// ¿El proceso es diferente al de la base de datos?
	if($adminDB !== $admin and $actualizarActivado)
	{

		$comandoActualizacion = "UPDATE empleados SET Administrador = " . $admin . " WHERE ID_Empleado = " . $codigo . "";
		if( $mysqli -> query($comandoActualizacion) )
		{

			$estado['exitoso'] = true;
			$estado['codigo'] = 0;		// Actualización exitosa
		}
	}

	// ¿Está actualizando su propia información?
	if($_SESSION['ID_Empleado'] == $codigo)
	{
		// ¿Cambió su código de usuario?
		if($codigo !== $codigoAnterior)
		{
			// Actualiza el código
			$_SESSION['ID_Empleado'] = $codigo;
		}
	}

	// No hubo problemas y actualizará los datos
	if( !empty($_FILES['foto_empE']) )
	{
		// En caso de error tornará falso
		$fotoCargada = true;

		// Obtiene el tamaño de la foto
		$foto_size = $_FILES['foto_empE']['size'];
		
		// Establecemos el nombre de archivo
		if($_FILES['foto_empE']['type'] =="image/jpeg")
		{
			$nombreArchivo = "$codigo". ".jpg";
		}
		else if($_FILES['foto_empE']['type'] =="image/png")
		{
			$nombreArchivo = "$codigoUsr". ".png";
		}
		else
		{
			$estado['codigo'] = 12;		// Código de advertencia 2: Formato de imagen no válido
			$fotoCargada = false;
		}
			
		if($fotoCargada)
		{
			// Se subió una foto anteriormente
			if($fotoDB != 'NULL')
			{
				// Obtiene el directorio de la foto
				$directorio = "C:/wamp64/www" . $fotoDB;

				// Destruye el archivo
				unlink($directorio);

				// Mueve el archivo
				if(move_uploaded_file($_FILES['foto_empE']['tmp_name'], $directorio))
				{
					$estado['exitoso'] = true;
					$estado['codigo'] = 0;
				}
			}
			else
			{
				// Define el directorio de tu madre
				$directorio = "C:/wamp64/www/evaluaciones/imagenes/Perfiles/" . $codigo;

				//¿El directorio no existe?
				if(!file_exists($directorio))
				{
					// Lo crea
					mkdir($directorio);
				}

				// Establece dónde se va aguardar el archivo y cómo se va a llamar
				$directorio = "C:/wamp64/www/evaluaciones/imagenes/Perfiles/" . $codigo . "/" . $nombreArchivo;

				// Mueve el archivo
				if(move_uploaded_file($_FILES['foto_empE']['tmp_name'], $directorio))
				{
					// Recortamos el directorio
					$directorio = "/evaluaciones/imagenes/Perfiles/" . $codigo . "/" . $nombreArchivo;
					$comandoInsersion = "UPDATE empleados SET Foto ='" . $directorio . "' WHERE ID_Empleado = '" . $codigo ."'";

					if($mysqli -> query($comandoInsersion))
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