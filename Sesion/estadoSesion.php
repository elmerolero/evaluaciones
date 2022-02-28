<?php
	SESSION_START();

	if(isset($_SESSION['ID_Empleado']))
	{
		$estado['exitoso'] = true;
		if($_SESSION['Administrador'])
			$estado['Administrador'] = true;
	}
	else
	{
		$estado['exitoso'] = false;
	}

	echo json_encode($estado);
?>