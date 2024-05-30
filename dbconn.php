<?php
	/* php & mysqldb connection file */
	$user = "root"; 			//mysqlusername
	$pass = ""; 				//mysqlpassword
	$host = "localhost"; 		//server name or ipaddress //127.0.0.1
	$dbname = "zclinicdbtest"; 	//your db name

	try {
    	$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
    	die("Could not connect to the database $dbname :" . $e->getMessage());
	}
?>
