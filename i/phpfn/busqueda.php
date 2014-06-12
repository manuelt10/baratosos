<?php 
session_start();
$_SESSION["buscarLista"] = $_POST["buscarLista"];
session_write_close();

?>