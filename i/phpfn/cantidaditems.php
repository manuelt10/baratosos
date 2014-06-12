<?php 
session_start();
$_SESSION["itemsQty"] = $_POST["itemsQty"];
session_write_close();
?>