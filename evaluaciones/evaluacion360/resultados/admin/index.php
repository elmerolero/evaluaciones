<?php
  // Inicia sesión
  SESSION_START();

  // ¿Inicio sesión?
  if(!isset($_SESSION['ID_Empleado']))
    header("Location: /evaluaciones/");
  // ¿Es admin?
  else if(!$_SESSION['Administrador']) 
    header("Location: /evaluaciones/evaluaciones/evaluacion360/resultados/user/");
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
    <title>Distribuidora Valcon</title>
  </head>
  <body>
    <header>
      <div class="container">
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
    </header>
    <br>
    <div id="contenedor" background="white">
        <div id="sec-empleados-consultar">
            <h2 class="text-center" style="color: rgb(50, 50, 200)">Seleccionar Empleado</h2>
            <input type="text" id="buscarEmpleado" placeholder="Buscar por nombre o apellidos" class="form-control"/>
            <br> <br>
            <div id="empleados">
                <div id="empleado-1" class="empleado-1">
                    <img src="/evaluaciones/imagenes/Perfiles/Default/default.png" class="fotoEmp" width="55" height="55" style="border-radius: 30px; float: left; margin-right: 10px">
                    <p id="nombreEmpleado"><strong>Nombre: </strong><span class="nombreEmp">Nombre empleado.</span></p>
                    <p id="codigoEmpleado"><strong>Código: </strong><span class="codEmp">XXXXXX</span></p>
                    <p id="puestoEmpleado"><strong>Puesto: </strong><span class="areaEmp">Proceso</span></p>
                    <a class="consultar" href="resultados.php">Obtener Resultados</a>
                </div>
            </div>
            <div id="pagEmpleados">
                <button class="botonp" id="pag1" onclick="crearListaEmpleados(1)">1</button>
            </div>
        </div> <br>
        <br><a href="/evaluaciones/Inicio/Resultados/">Regresar</a>
    </div>
    <br>
    <footer>
      <div class="container">
        <div class="row">
          <p class="col-10">Distribuidora Valcon <br> Programa de Evaluación de empleados</p>
          <a href="http://www.valcon.com.mx" class="centrado col">www.valcon.com.mx</a>
        </div>
      </div>
    </footer>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <!-- Optional JavaScript -->
    <script src="/evaluaciones/Inicio/sesion.js" type="text/javascript"></script>
    <script src="src/empleados.js" type="text/javascript"></script>
  </body>
</html>