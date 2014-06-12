<?php 
session_start();
$_SESSION["searchUsr"] = $_POST["searchUsr"];
$_SESSION["userType"] = $_POST["userType"];
session_write_close();
?>

