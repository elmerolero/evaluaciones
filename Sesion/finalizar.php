<?php
	/* Inicializa la sesiçon */
	SESSION_START();

	/* Elimina todas las variables temporales existentes */
	SESSION_UNSET();

	/* Finaliza la sesión */
	SESSION_DESTROY();

	header("Location: /evaluaciones/");
?>