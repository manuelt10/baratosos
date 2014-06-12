<?php

session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
require('../../phpfn/fpdf/fpdf.php');
require_once('../../phpfn/cndb.php');
$pdf = new FPDF('P','mm','Letter');
$pdf->AddPage();
$pdf->Image('../../images/logo-min.png',10,6,50);
$pdf->SetFont('Helvetica','B',15);
$pdf->Cell(80);
$pdf->Cell(30,10,'Reporte de tiendas',0,0,'C');
$pdf->Ln(20);
$pdf->SetFont('Helvetica','B',12);
$pdf->Cell(45,10,'Nombre Tienda');
$pdf->Cell(45,10,'Propietario');
$pdf->Cell(70,10,'Correo');
$pdf->Cell(50,10,iconv('UTF-8', 'windows-1252','Teléfono'));
$pdf->Ln(15);
$pdf->SetFont('Helvetica','',10);
$con = conection();
$query = $con->prepare('SELECT * FROM `usuario` where idtipousuario = 2');
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_OBJ);
foreach($usuarios as $u)
{
	$nombretienda = iconv('UTF-8', 'windows-1252', $u->nombretienda);
	$nombre = iconv('UTF-8', 'windows-1252', $u->nombre);
	$pdf->Cell(45,10,substr($nombretienda,0,20));
	$pdf->Cell(45,10,substr($nombre,0,20));
	$pdf->Cell(70,10,$u->correo);
	$pdf->Cell(50,10,$u->telefono1);
	$pdf->Ln(8);
}
$pdf->Output();
}
else
	{
		header('Location: '. $_SERVER["HTTP_REFERER"]);
	}
?>