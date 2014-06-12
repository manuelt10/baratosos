<?php 
if($_POST["form"] == 1)
{
	require_once('form/cert_form.php');
}
else if($_POST["form"] == 2)
{
	require_once('form/adver_form.php');
}
else if($_POST["form"] == 3)
{
	require_once('form/offer_form.php');
}
?>