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
            <h2 class="text-center">Programa de Evaluación de Empleados</h2><br><h3 class="text-center">Resultados de Evaluacion</h3>
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
    <div id="contenedor"> 
        <h2 id="nEvaluacion" align="center">Evaluacion 360
        <?php
            // Inicia sesión
            SESSION_START();
            if($_SESSION['Administrador']) 
              echo "<a href='/evaluaciones/evaluaciones/evaluacion360/resultados/admin/' style='display: inline-block; font-size: 20px'> Modo Administrador</a><br>"; 
        ?></h2>
        <div id="resultadosEmpleado">
        <div style="width: 300px; margin: auto;">
          <p style="display: inline-block; width: 190px">Seleccionar año: </p>
          <select id="SelectAnio" class="form-control" style="display: inline-block; width: 100px; position: right;">
            <option selected="selected" value="0">Año</option>
          </select>
        </div>
        <h3>Puntaje <span id="anio">XXXX</span></h3><br>
        <div id="contenedorResultados">
            <img id="loadImg" src="/evaluaciones/imagenes/cargando.gif">
            <div id="graficoEvaluacion">
                <canvas id="grafico">
                </canvas>
            </div>
            <div id="resultadoEvaluacion">
                <h3 align="center">Tu puntaje de este año es:</h3><br>
                <div>
                    <h1 id="puntajeTotal" align="center">XX.XX puntos</h1>
                </div>
            </div>
        </div><br><br>
        <div id="evaluacionAspectos">
            <h3>Aspectos Evaluados</h3><br>
            <table id="aspectos">
              <tr>
                <th>Honradez</th>
                <th>Trabajo en equipo</th>
                <th>Calidad</th>
                <th>Respeto</th>
                <th>Responsabilidad</th>
                <th>Cinco S's</th>
                <th>Seguridad</th>
                <th id="Lider">Liderazgo</th>
              </tr>
              <tr>
                <td id="Honradez"></td>
                <td id="TrabajoEquipo"></td>
                <td id="Calidad"></td>
                <td id="Respeto"></td>
                <td id="Responsabilidad"></td>
                <td id="CincoS"></td>
                <td id="Seguridad"></td>
                <td id="Liderazgo"></td>
              </tr>
            </table>
        </div>
        <div id="contenedorOpiniones">
            <br><h3>Opiniones</h3>
            <div id="opiniones">
            </div>
        </div>
        </div>
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
    <script src="src/draw.js" type="text/javascript"></script>
    <script src="src/resultados.js" type="text/javascript"></script>
    <script src="/evaluaciones/Inicio/sesion.js" type="text/javascript"></script>
  </body>
</html>