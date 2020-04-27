<?php 

function get_barix_status ($radio) {
	$sql = sprintf("SELECT * FROM t_references WHERE TOOL_KEY LIKE 'barix_%s'", $radio);
	$ipandport = array(); $return = array(); $status_code = 0; $return ['brx_code'] = 0;
	// $fsock = fsockopen($host, $port, $errno, $errstr, $timeout);
	$conn 	= dbconn ("rsm");
	if (!$resault = $conn-> query($sql)) {
		$message = "Adatbázis hiba lekérdezésben. MySQL üzenete: ";
		$message.= $conn -> error;
		$message.= " SQL kérés: BARIX referenciaértékek lekérdezése";
		if ($GLOBALS ['log'] == 1)  db_phplog ("1", "barix-functions.php", "get_barix_status", $message);
	} else {
		$ref_array = $resault->  fetch_assoc();
		$message = "Adatbázis sikeres lekérdezés. SQL kérés: BARIX referenciaértékek lekérdezése";
		if ($GLOBALS ['log'] == 1)  db_phplog ("0", "barix-functions.php", "get_barix_status", $message);
		$ipandport = explode(":", $ref_array ['s1']);
		$timeout = 20;

/* Elérhetőség ellenörzése */
		$fsock = fsockopen($ipandport [0], $ipandport [1], $errno, $errstr, $timeout);
		if (!$fsock) {
			$return ['chk_conn_status'] = 0;
			$return ['chk_conn_msg']	= $ref_array ['s1']." nem elérhető (".$radio.")";
			$status_code				= 1;
			$return ['status'] = $ref_array ['HTML']."<br>Nem elérhető!";
			$return ['brx_code'] 		= 2; 
		} else {
			$return ['chk_conn_status'] = 1;
			$return ['chk_conn_msg']	= "Kapcsolat rendben (".$radio." ".$ref_array ['s1'].")";
		}
	}
	
/* Barix státuszfájl beolvasása */

	if ($status_code == 0) {
		$linkBarix = "http://".$ref_array ['s1']."/realtime_status.txt";
		if ($contentBarix = file_get_contents($linkBarix)) {
			$arrayBarixStatus = array();
			$arrayBarixStatus = array_map('trim', explode(";", utf8_encode($contentBarix)));
			$return ['arrayBarixStatus'] = $arrayBarixStatus;
		} else {
			$status_code = 1;
			$return ['status'] = "BARIX státusz nem elérhető, de kapcsolat van vele.<br>(".$radio.")";
			$return ['brx_code'] = 1; 
		}
	}
	
/* Barix forrás stream ellenőrzése */

	if ($status_code == 0) {
		$refURL 	= $ref_array ['s2'];
		$streamURL 	= $arrayBarixStatus [20];
		if ($refURL == $streamURL) {
			$return ['status'] = $ref_array ['HTML']."<br>Kapcsolat rendben.";
		} elseif ($streamURL == $ref_array ['s3']) {
			$return ['status'] = $ref_array ['HTML']."<br>KÖZPONTI ADÁS!";
			$return ['brx_code'] = 1;
		} else {
			$return ['status'] = $ref_array ['HTML']."<br>VÉSZADÁS VAGY CSEND!";
			$return ['brx_code'] = 2;
		}
	}
	
	$return ['status_code'] 	= $status_code;
	$return ['barix_ip'] 		= $ipandport [0];
	$return ['barix_port'] 		= $ipandport [1];
	$return ['refURL'] 			= $refURL;
	$return ['streamURL'] 		= $streamURL;

	$conn->close();
	return $return;
}
?>