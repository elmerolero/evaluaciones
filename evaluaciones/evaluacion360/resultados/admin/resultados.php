<?php
 	// Inicia sesión
 	SESSION_START();

 	// ¿Inicio sesión?
 	if(!isset($_SESSION['ID_Empleado']))
		header("Location: /evaluaciones/");
	// ¿Es admin?
	else if(!$_SESSION['Administrador']) 
		header("Location: /evaluaciones/evaluaciones/evaluacion360/resultados/user/");
	else
		$_SESSION['Codigo'] = $_GET['codigo'];

	// Conecta con la base de datos
	$mysqli = new mysqli("localhost", "root", "", "valcon");

	// Verifica la conexión
	if (mysqli_connect_errno()){
		printf("Falló la conexión: %s\n", mysqli_connect_error());
		exit();
	}

	// Charset UTF-8
	if(!$mysqli -> set_charset("utf8")){
		printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
		exit();
	}
?>
<html lang="es-mx">
	<head>
    	<!-- Required meta tags -->
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    	<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
     	<link rel="stylesheet" href="/evaluaciones/Inicio/sesion.css">
     	<link rel="stylesheet" href="resultados.css">
        <link rel="stylesheet" href="impresion.css">
    	<title>Detalles resultados</title>
  	</head>
 	<body>
    	<header>
     		<div class="container">
                <div id="web-header">
        		    <div class="row">
          			   <div class="col-2">
            		   <div id="logo">
              			   <img src="/evaluaciones/imagenes/logo.jpg" alt="Valcon" id="logo-img" width="80">
            		    </div>
          		    </div>
          		    <div  id="titulo" class="centrado">
            	       <h2 class="text-center">Evaluación Personal 360-180</h2><br><h3 class="text-center">Distribuidora Valcon, S.A. de C.V.</h3>
          		    </div>
          		    <div id="Sesion" class="right-block">
            		  <p id="nombreSesion">---------------<br><span id="administrador"></span></p>
            		  <a href="/evaluaciones/Sesion/finalizar.php">Cerrar Sesión</a>
          		    </div>
          		        <img class="right-block" src="/evaluaciones/imagenes/Perfiles/Default/default.png" id="img-perfil" style="margin: 10px; border-radius: 10px"/>
        		    </div>
                </div>
                <div id="print-header">
                    <div id="margen">
                        <div class="row">
                            <div class="col-2">
                            <div id="logo">
                                <img src="/evaluaciones/imagenes/logo.jpg" alt="Valcon" id="logo-img" width="60">
                            </div>
                        </div>
                        <div style="display: inline-block; width: 65%; vertical-align: middle;">
                            <h2 align="center">Evaluación Personal 360-180</h2><h3 align="center">Distribuidora Valcon, S.A. de C.V.</h3>
                        </div>
                        <div style="display: inline-block; margin: auto 0px auto 0px; vertical-align: middle;">
                            <p>Código: <strong>AD-REG-57</strong><br> Edición: <strong>01</strong><br> Año: <strong><span id="anio-resultados-h">XXXX</span></strong></p>
                         </div>
                    </div>
                </div>
      		</div>
    	</header>
    	<div id="datos-resultados">
    		<p align="center">Código: <strong>AD-REG-57</strong> Edición: <strong>01</strong> Año: <strong><span id="anio-resultados">XXXX</span></strong></p>
    	</div>
    	<div id="contenedor">
    		<div id="formato-resultados">
    			<div id="datos-empleado">
    				<label for="empleado-evaluado">Nombre del empleado evaluado: </label><input type="text" id="empleado-evaluado" class="form-control" readonly/>
    				<label for="anio-evaluacion">Año: </label>
    				<select id="SelecAnio" class="form-control">
    					<option>Año</option>
    				</select>
    				<br>
    			</div>
    			<p id="titulo-relacion">Relaciones con el empleado</p>
    			<div class="check-relacion"></label><input type="checkbox" id="R1" onclick="javascript: return false;"/><label for="R1"> 1. Yo mismo			</div>
    			<div class="check-relacion"><input type="checkbox" id="R2" onclick="javascript: return false;"/><label for="R2">2. Jefe				</label></div>
    			<div class="check-relacion"><input type="checkbox" id="R3" onclick="javascript: return false;"/><label for="R3">3. Compañero de área</label></div>
    			<div class="check-relacion"><input type="checkbox" id="R4" onclick="javascript: return false;"/><label for="R4">4. Subordinado		</label></div>
    			<div class="check-relacion"><input type="checkbox" id="R5" onclick="javascript: return false;"/><label for="R5">5. Otro 			</label></div>
    			<div class="check-relacion"><input type="checkbox" id="R6"onclick="javascript: return false;"/><label for="R6">6. Cliente			</label></div>
    			<div class="check-relacion"><input type="checkbox" id="R7" onclick="javascript: return false;"/><label for="R7">7. Proveedor		</label></div>
    			<div>
    				<table id='Resultados'>
    					<tr>
                            <td></td>
                            <td align="right" colspan="2">Quien evalúa </td>
    						<td class="resultadose">1</td>
    						<td class="resultadose">Total</td>
    						<td class="resultadose" rowspan="2">Yo</td>
    						<td class="resultadose" rowspan="2">Total Área</td>
    					</tr>
    					<tr>
                            <td></td>
                            <td>Preguntas</td>
    						<td align="right">Numero </td>
    						<td class="resultadose">1</td>
    						<td class="resultadose"><strong id="Total">XX.XX</strong></td>
    					</tr>
    				<?php
    					// Obtiene las preguntas de la evaluacion
						$json = file_get_contents('C:\wamp64\www\evaluaciones\evaluaciones\evaluacion360\evaluar\form\evaluacion360.json');
						$evaluacion = json_decode($json, true);

						$aspecto = "";
						$nPreguntas = 0;

						for($i = 0, $j = $i, $k = 0; $i < count($evaluacion['Preguntas']); $i++)
						{
							// ¿Cambió el aspecto a evaluar?
							if($aspecto != $evaluacion['Preguntas'][$i]['aspecto']){
								$aspecto = $evaluacion['Preguntas'][$i]['aspecto'];
								echo "<tr><td></td><td class='preguntas'><strong>"  . $aspecto . "</strong></td></tr>";
								// Cuenta cuantas preguntas tienen el mismo aspecto a evaluar
								while($aspecto == $evaluacion['Preguntas'][$j]['aspecto']){
									$nPreguntas++;
									$j++;
									if(!isset($evaluacion['Preguntas'][$j]['aspecto']))
										break;
								}
							}
		
							echo "<tr>";
							echo "<td class='numero'>" . ($i + 1) . "</td>";									// Número de pregunta
							echo "<td class='preguntas' colspan='2'>" . $evaluacion['Preguntas'][$i]['pregunta'] . "</td>";	// Pregunta
							echo "<td id='rautoevaluacion" . ($i + 1) . "' class='resultadose'>XX.XX</td>";		    // Respuesta de autoevaluacion
							echo "<td id='rgeneral" . ($i + 1) . "'class='resultadose'>XX.XX</td>";// Promedio general
							if($nPreguntas > 1){
								echo "<td id='pautoevaluacion" . $k . "' class='pautoevaluacion' rowspan='" . $nPreguntas . "'>XX.XX</td>";	// Puntaje personal
								echo "<td id='pgeneral" . $k . "' class='pgeneral' rowspan='" . $nPreguntas . "'>XX.XX</td>";
								$k++;
								$nPreguntas = 0;
							}
							echo "</tr>";
						}

						echo "</table>";
    				?>
    				<div id="contenedorOpiniones" style="margin-top: 5px">
            			<label style="margin-top: 0px"><strong>Opiniones</strong></label>
            			<div id="opiniones" style="margin-top: 0px">
            			</div>
       				</div>
    			</div>
    		</div>
    		<div id="no-resultados">
    		</div>
    		<a href="/evaluaciones/evaluaciones/evaluacion360/resultados/admin/" id="Regresar">Regresar</a>
    	</div>
	</body>
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <!-- Optional JavaScript -->
    <script src="/evaluaciones/Inicio/sesion.js" type="text/javascript"></script>
    <script src="src/resultados.js" type="text/javascript"></script>
</html>