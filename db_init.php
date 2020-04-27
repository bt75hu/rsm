<?php

/***************************************************************************************
*								ADATBÁZIS INICIALIZÁCIÓ								   *
*								v 3.0 by: Baranyai Tibor							   *	
*					   GNU Licence - 2013. - bt75hu@gmail.com						   *
***************************************************************************************/

/*
Adatbázis beállításai
---------------------
*/

$GLOBALS["mega"]["dbservername"] 	= 	"192.168.67.10";
$GLOBALS["mega"]["dbusername"]		=	"root";
$GLOBALS["mega"]["password"] 		= 	"cart32";
$GLOBALS["mega"]["dbname"] 			= 	"mega";

$GLOBALS["csaba"]["dbservername"] 	= 	"192.168.67.10";
$GLOBALS["csaba"]["dbusername"]		=	"root";
$GLOBALS["csaba"]["password"] 		= 	"cart32";
$GLOBALS["csaba"]["dbname"] 		= 	"radio1";


$GLOBALS["rsm"]["dbservername"] 	= 	"localhost";
$GLOBALS["rsm"]["dbusername"]		=	"rsm-system";
$GLOBALS["rsm"]["password"] 		= 	"X99B8QONcC7gptuZ";
$GLOBALS["rsm"]["dbname"] 			= 	"rsm-system";

function dbconn ($server) {
	$servername 	= $GLOBALS[$server]["dbservername"];
	$username		= $GLOBALS[$server]["dbusername"];
	$password		= $GLOBALS[$server]["password"];
	$dbname			= $GLOBALS[$server]["dbname"];
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	$conn -> query("SET CHARACTER SET utf8");
	
	// Check connection
	if ($conn->connect_error) {
		die ("Adatbázis: kapcsolódási hiba! <br>Közlendő: " . $conn->connect_error);
	}
	return $conn;
} 

?>