<?php 
session_start();
	$_SESSION["buscarTienda"] = $_POST["buscarTienda"];
session_write_close();

?>