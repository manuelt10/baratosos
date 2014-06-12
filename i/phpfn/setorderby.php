<?php 
session_start();
$_SESSION["orderby"] = $_POST["orderby"];
session_write_close();

?>