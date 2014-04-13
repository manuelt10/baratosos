<?php 
function conection()
	{
		$cn = new \PDO(  'mysql:host=127.0.0.1;dbname=rmanu_baratosos', 
                        'rmanu_db', 
                        'GuNQSuMa1{Rx', 
                        array(
                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, 
                            \PDO::ATTR_PERSISTENT => false, 
                            \PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
                        )
                    );
		return $cn; 
		}

?>