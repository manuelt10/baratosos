<?php 
session_start();
$_SESSION["search"] = $_POST["search"];
session_write_close();
?>

