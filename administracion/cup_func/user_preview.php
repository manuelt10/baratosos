<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$where = Array("idusuario" => $_POST["idusuario"]);
	$record = $db->selectRecord("usuario",null,$where);
	if($record->data[0]->idtipousuario == 2)
	{
	?>
		<span class="itmImageMask itmListMask usrImageMask">
			<?	
			if(!empty($record->data[0]->imagen))
			{
			?>
				<img class="userImage" src="/images/profile/cr/<?php echo $record->data[0]->imagen ?>">
			<?php
			}
			else
			{
			?>
				<img class="userImage" src="/images/resources/storePNG-100.png">
			<?php
			}
			?>
		</span>
		<div class="itmListDescrWrap">
			<span class="userName"><?php echo $record->data[0]->nombre ?></span><br>
			<span class="storeName"><?php echo $record->data[0]->nombretienda ?></span><br>
			<span class="eMail"><?php echo $record->data[0]->correo ?></span><br>
		</div>
	<?
	}
	else
	{
		?>
		<span class="error-label validation-label">Este ID de usuario no pertenece a una tienda.</span>
		<?php
	}
}
?>