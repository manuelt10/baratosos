<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	if(!empty($_POST["cantidad_comprar"]))
	{
		require_once('mysqlManager.php');
		$db = new mysqlManager();
		$cupon = $db->selectRecord('cupon',NULL,Array('idcupon' => $_POST["idCupon"]));
		$cupon_usr = $db->selectRecord('cupon_compras',NULL,Array('idusuario' => $session["usuario"], 'idcupon' => $_POST["idCupon"]));
		if($cupon->data[0]->cantidad_compra >= ($cupon_usr->rowcount + $_POST["cantidad_comprar"]))
		{
			require_once('stringmanager.php');
			$sM = new stringManager();
			$n = 0;
			do{
				$code = $sM->generateStringCode("mayus", 10);
				$cupon_code = $db->selectRecord('cupon_compras', NULL, Array('codigo' => $code));
				if($cupon_code->rowcount == 0)
				{
					$record = Array(
						'idcupon' => $_POST["idCupon"],
						'idusuario' => $session["usuario"],
						'codigo' => $code
					);
					$db->insertRecord('cupon_compras',$record);
					$n++;
				}
				
			}while($_POST["cantidad_comprar"] > $n);
			header("Location: ../cupon_end?id=" . $_POST["idCupon"] );
		}
		else
		{
			session_start();
			$_SESSION["error"] = 1;
			session_write_close();
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
		
	}
	
	
}


?>