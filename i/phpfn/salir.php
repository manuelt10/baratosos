<?php 
session_start();
unset($_SESSION["autenticado"]);
unset($_SESSION["usuario"]);
header("location: ../accesar");
session_write_close();
?>
