<?php 

function script_run ($script) {
	$return = array();
	$return ['script'] = $script;
	$return ['output'] = shell_exec($script);
	sleep (1);
	return $return;
}

function zenon_mount_m () {
	$return = array();
	$return ['errcode'] = 0;
	$script = "./mount-m.sh";
	$shecho = script_run ($script);
	$return ['message'] = "Kapcsolódás az adásgéphez.<br>";
	$return ['message'] .= "Shell script: ".$script."<br>";
	$return ['message'] .= "Shell script válasza: ".$shecho ['output'];
	if (file_exists($GLOBALS['mega_sound_path'])) {
		$return ['message'] .= "Mega Rádió zenei mappa elérhető: ".$GLOBALS ['mega_sound_path']."<br>";
	} else {
		$return ['message'] .= "Mega Rádió zenei mappa nem elérhető: ".$GLOBALS ['mega_sound_path']."<br>";
		$return ['errcode'] .= 4;
	}
	if (file_exists($GLOBALS['csaba_sound_path'])) {
		$return ['message'] .= "Csaba Rádió zenei mappa elérhető: ".$GLOBALS ['csaba_sound_path']."<br>";
	} else {
		$return ['message'] .= "Csaba Rádió zenei mappa nem elérhető: ".$GLOBALS ['csaba_sound_path']."<br>";
		$return ['errcode'] .= 4;
	}
	if ($GLOBALS ['log'] == 1)  db_phplog ($return ['errcode'], "functions.php", "zenon_mount_m ()", $return ['message']);
	return $return;
}

function zenon_umount_m () {
	$return = array();
	$return ['errcode'] = 0;
	$script = "./umount-m.sh";
	$shecho = script_run ($script);
	$return ['message'] = "Adásgép leválasztása.<br>";
	$return ['message'] .= "Shell script: ".$script."<br>";
	$return ['message'] .= "Shell script válasza: ".$shecho ['output'];
	if (!file_exists($GLOBALS['mega_sound_path'])) {
		$return ['message'] .= "Mega Rádió zenei mappa leválasztva: ".$GLOBALS ['mega_sound_path']."<br>";
	} else {
		$return ['message'] .= "Mega Rádió zenei mappát nem sikerült leválasztani: ".$GLOBALS ['mega_sound_path']."<br>";
		$return ['errcode'] .= 1;
	}
	if (!file_exists($GLOBALS['csaba_sound_path'])) {
		$return ['message'] .= "Csaba Rádió zenei mappa leválasztva: ".$GLOBALS ['csaba_sound_path']."<br>";
	} else {
		$return ['message'] .= "Csaba Rádió zenei mappát nem sikerült leválasztani: ".$GLOBALS ['csaba_sound_path']."<br>";
		$return ['errcode'] .= 1;
	}
	if ($GLOBALS ['log'] == 1)  db_phplog ($return ['errcode'], "functions.php", "zenon_umount_m ()", $return ['message']);
	return $return;
}

function dbselect_info_rotacount ($radio) {
	$conn 	= dbconn ($radio);
	$sql = "SELECT COUNT(1) AS N FROM t_rotation";
	if (!$resault = $conn-> query($sql)) {
		$message = "Adatbázis hiba lekérdezésben. MySQL üzenete: ";
		$message.= $conn -> error;
		$message.= " SQL kérés: ".$sql;
		if ($GLOBALS ['log'] == 1)  db_phplog ("1", "functions.php", "dbselect_info_rotacount", $message);
	} else {
		$row = $resault->  fetch_assoc();
		$message = "Adatbázis sikeres lekérdezés. SQL kérés: ".$sql;
		if ($GLOBALS ['log'] == 1)  db_phplog ("0", "functions.php", "dbselect_info_rotacount", $message);
	}
	$conn->close();
	return $row ['N'];
}

function dbselect_rotarray ($radio) {
	$return = array();
	$return ['array'] = array();
	$conn 	= dbconn ($radio);
	$sql = "SELECT NUM_ROTATION AS ID FROM t_rotation";
	if (!$resault = $conn-> query($sql)) {
		$message = "Adatbázis hiba lekérdezésben. MySQL üzenete: ";
		$message.= $conn -> error;
		$message.= " SQL kérés: ".$sql;
		if ($GLOBALS ['log'] == 1)  db_phplog ("1", "functions.php", "dbselect_rotarray", $message);
	} else {
		$return ['count'] = $resault -> num_rows;
		while ($row = $resault->  fetch_assoc()) {
			array_push($return ['array'], $row['ID']);
		}
		$message = "Adatbázis sikeres lekérdezés. SQL kérés: ".$sql;
		if ($GLOBALS ['log'] == 1)  db_phplog ("0", "functions.php", "dbselect_rotarray", $message);
	}
	$conn->close();
	return $return;
}

function get_num_type ($radio, $id) {
	/* 
		VISSZAKÜLDÖT HIBAKÓDOK:
			0 :		Nincs hiba;
			1 :		Nem találta ezt a NUM_ROTATION értéket, 0 elemű eredményhalmaz.
			2 :		Több mint 1 elemet talált a NUM_ROTATION lekérdezésben.
			
			Ha a hibakód értéke nagyobb mint 0, semmilyen adatbázismódosító-műveletet nem szabad végrehajtani, mert esetleg olyan
			bejegyzést is módosítani fog, amit nem szeretnénk. 
	*/
	$conn 	= dbconn ($radio); $return = array();
	$errortext = array();
	$errortext [0] = "Nincs hiba.";
	$errortext [1] = "Nem találta ezt a NUM_ROTATION értéket, 0 elemű eredményhalmaz.";
	$errortext [2] = "Több mint 1 elemet talált a NUM_ROTATION lekérdezésben.";
	$return ['error'] = 0;
	$sql = sprintf("SELECT NUM_TYPE AS N FROM t_rotation WHERE NUM_ROTATION LIKE '%s'", $id);
	if (!$resault = $conn-> query($sql)) {
		$message = "Adatbázis hiba lekérdezésben. MySQL üzenete: ";
		$message.= $conn -> error;
		$message.= " SQL kérés: ".$sql;
		if ($GLOBALS ['log'] == 1) db_phplog ("1", "functions.php", "get_num_type", $message);
	} else {
		$return ['count'] = $resault -> num_rows;
		$row = $resault->  fetch_assoc(); 
		$return ['type'] = $row['N'];
		
		if ($return ['count'] == 1) $e_code = 0;
		if ($return ['count'] < 1) $e_code = 1;
		if ($return ['count'] > 1) $e_code = 2;
		
		$message = "Adatbázis sikeres lekérdezés. SQL kérés: ".$sql;
		$message.= "<br>A függvény közlendője: ".$errortext[$e_code];
		
		if ($GLOBALS ['log'] == 1) db_phplog ("0", "functions.php", "get_num_type", $message);
	}
	$conn->close();
	
	/* 
		Kiemeneti tömb elemei
			'count'	:	Talált elemek száma;
			'error'	:	Hibakód.;
			'type'	:	A kért azonosító (NUM_ROTATION) típusa (NUM_TYPE);
	
	*/
	return $return;
}

function z_music_path ($r, $id) {
	/*
		$id	:	NUM_ROTATION
		$r	:	RADIO
		
		OUTPUT: full path to play
			
	*/
	$id = (string)$id; $preType = ''; $typepath = ''; 
	// $tmask = '000'; $idmask = '0000000000';
	$t_idpath = str_pad($id, 10, "0", STR_PAD_LEFT);
	$idpath = "/"; $slash = array(1,3,5,7);
	$sbasedir = $GLOBALS['SOUNDBASEDIR'];
	if (substr($sbasedir, -1) == "/") $sbasedir = substr($sbasedir, 0, -1); 
	$x = strlen($t_idpath) - 1;
	for ($i = 0; $i <= $x ; $i++) {
		$idpath.= substr($t_idpath, $i, 1);
		if (in_array($i, $slash)) $idpath .= "/";
	}
	if ($r == $GLOBALS ['type_sensitive_path']) {
		$t = get_num_type ($r, $id);
		$t = (string)$t; 
		if ((int)$t > 59) { $idmask = '00000000'; $slash = array(1,3,5); }
		$typepath = str_pad($t, 3, "0", STR_PAD_LEFT);
		$typepath = substr($tmask, 0, strlen($tmask) - strlen($t)).$t;
		$preType = "type"; if ($t == '5') $preType = "Type"; if ((int)$t > 59) { $preType = ''; $typepath = '00'; }
	}
	$path = $sbasedir.$preType.$typepath.$idpath.".wav";
	if (!file_exists($path)) $path = $sbasedir.$preType.$typepath.$idpath.".mp3";
	if (!file_exists($path)) $path = $sbasedir.$preType.$typepath.$idpath.".mp2";
	if (!file_exists($path)) $path = $sbasedir.$preType.$typepath.$idpath.".mpg";
	if (!file_exists($path)) $path = NULL;
	return $path;
}

function status_m_diskspace () {
	/*
		status_m_diskspace RETURN:
		[status_base] 		-> Tömb: t_referencies tábla dsp_m (ID: 1) sora. Tömb index = mező érték;
		[sql1]				-> SQL kérés: státuszt tartalmazó fájl helyének lekérdezése;
		[free_space_byte]	-> fájlból lekérdezett szabad tárhely Bájtban;
		[free_space_GB]		-> fájlból lekérdezett szabad tárhely GBájtban;
		[sql2]				-> SQL kérés: Referencia-értékek lekérdezése.;
		[status_code]		-> 0: függvény végrehajtása rendben lezajlott - 1: Hiba a függvény végrahajtásában.
		[fds_code]			-> Szabad hely státusza: [0] Rendben - [1] Veszély - [2] Kritikus;
		[status]			-> Egysoros, szöveges visszajelzés az állapotról.
	*/
	$conn 	= dbconn ("rsm"); $return = array();
	$file_open_error = 0; $status_code = 0; $fds_code = 0;
	$file_fds_status = array('rendben', 'veszélyes', 'kritikus');
	$sql	= "SELECT VALUE FROM t_global_settings WHERE GS_KEY LIKE 'base_status_m'";
	
	if (!$resault = $conn-> query($sql)) {
		$message = "Adatbázis hiba lekérdezésben. MySQL üzenete: ";
		$message.= $conn -> error;
		$message.= " SQL kérés: státuszt tartalmazó fájl helyének lekérdezése.";
		if ($GLOBALS ['log'] == 1)  db_phplog ("1", "functions.php", "status_m_diskspace", $message);
		$status_code = 1;
	} else {
		$row = $resault->  fetch_assoc();
		$return ['status_base'] = $row['VALUE'];
		$message = "A státuszt tartalmazó fájl útvonalának betöltése sikeres";
		if ($GLOBALS ['log'] == 1)  db_phplog ("0", "functions.php", "status_m_diskspace", $message);
		$return ['sql1'] = $sql;
	}
	$myfile = fopen($return ['status_base'], "r") or $file_open_error = 1;
	if ($file_open_error == 0) {
		$line = fgets($myfile); $line = mb_convert_encoding($line, "UTF-8", "ISO-8859-1");
		if ($line == '' or !is_numeric($line)) {
			$file_open_error = 1; $file_error = 'A kapott értékkel baj van! '.$line;
			$status_code = 1;
		} else {
			$return['free_space_byte'] = $line;
			$return['free_space_GB'] = round($line / (1024 * 1024 * 1024), 1);
			$sql = "SELECT * FROM t_references WHERE ID = 1";
			if (!$resault = $conn-> query($sql)) {
				$message = "Adatbázis hiba lekérdezésben. Referencia-értékek lekérdezése. Tábla: t_references";
				$message.= $conn -> error;
				$message.= " SQL kérés: ".$sql;
				if ($GLOBALS ['log'] == 1)  db_phplog ("1", "functions.php", "status_m_diskspace", $message);
				$status_code = 1;
			} else {
				$row = $resault->  fetch_assoc();
				$return ['status_base'] = $row;
				$message = "A Referencia-értékek lekérdezése sikeres.";
				if ($GLOBALS ['log'] == 1)  db_phplog ("0", "functions.php", "status_m_diskspace", $message);
				$return ['sql2'] = $sql;
			}
			
			if ($line < $row ['WARN_N1']) $fds_code = 1;
			if ($line < $row ['ERROR_N1']) $fds_code = 2;
			$return ['status'] = "Adásgép szabad tárhely: ".$file_fds_status[$fds_code]." (".$return['free_space_GB']."GB)";
		}
 		
	} else {
		$file_error = 'Nem tudtam megnyitni a státusz állományt! '.$return ['status_base']; 
		$status_code = 1;
	}
	$return ['status_code'] = $status_code;
	$return ['fds_code']	= $fds_code;
	if ($file_open_error == 1) $return ['status'] = $file_error;
	$conn->close();
	return $return;
}


function status_p_diskspace () {
	/*
		status_m_diskspace RETURN:
		[status_base] 		-> Tömb: t_referencies tábla dsp_p (ID: 5) sora. Tömb index = mező érték;
		[sql1]				-> SQL kérés: státuszt tartalmazó fájl helyének lekérdezése;
		[free_space_byte]	-> fájlból lekérdezett szabad tárhely Bájtban;
		[free_space_GB]		-> fájlból lekérdezett szabad tárhely GBájtban;
		[sql2]				-> SQL kérés: Referencia-értékek lekérdezése.;
		[status_code]		-> 0: függvény végrehajtása rendben lezajlott - 1: Hiba a függvény végrahajtásában.
		[fds_code]			-> Szabad hely státusza: [0] Rendben - [1] Veszély - [2] Kritikus;
		[status]			-> Egysoros, szöveges visszajelzés az állapotról.
	*/
	$conn 	= dbconn ("rsm"); $return = array();
	$file_open_error = 0; $status_code = 0; $fds_code = 0;
	$file_fds_status = array('rendben', 'veszélyes', 'kritikus');
	$sql	= "SELECT VALUE FROM t_global_settings WHERE GS_KEY LIKE 'base_status_p'";
	
	if (!$resault = $conn-> query($sql)) {
		$message = "Adatbázis hiba lekérdezésben. MySQL üzenete: ";
		$message.= $conn -> error;
		$message.= " SQL kérés: státuszt tartalmazó fájl helyének lekérdezése.";
		if ($GLOBALS ['log'] == 1)  db_phplog ("1", "functions.php", "status_p_diskspace", $message);
		$status_code = 1;
	} else {
		$row = $resault->  fetch_assoc();
		$return ['status_base'] = $row['VALUE'];
		$message = "A státuszt tartalmazó fájl útvonalának betöltése sikeres";
		if ($GLOBALS ['log'] == 1)  db_phplog ("0", "functions.php", "status_p_diskspace", $message);
		$return ['sql1'] = $sql;
	}
	$myfile = fopen($return ['status_base'], "r") or $file_open_error = 1;
	if ($file_open_error == 0) {
		$line = fgets($myfile); $line = mb_convert_encoding($line, "UTF-8", "ISO-8859-1");
		if ($line == '' or !is_numeric($line)) {
			$file_open_error = 1; $file_error = 'A kapott értékkel baj van! '.$line;
			$status_code = 1;
		} else {
			$return['free_space_byte'] = $line;
			$return['free_space_GB'] = round($line / (1024 * 1024 * 1024), 1);
			$sql = "SELECT * FROM t_references WHERE ID = 5";
			if (!$resault = $conn-> query($sql)) {
				$message = "Adatbázis hiba lekérdezésben. Referencia-értékek lekérdezése. Tábla: t_references";
				$message.= $conn -> error;
				$message.= " SQL kérés: ".$sql;
				if ($GLOBALS ['log'] == 1)  db_phplog ("1", "functions.php", "status_p_diskspace", $message);
				$status_code = 1;
			} else {
				$row = $resault->  fetch_assoc();
				$return ['status_base'] = $row;
				$message = "A Referencia-értékek lekérdezése sikeres.";
				if ($GLOBALS ['log'] == 1)  db_phplog ("0", "functions.php", "status_p_diskspace", $message);
				$return ['sql2'] = $sql;
			}
			
			if ($line < $row ['WARN_N1']) $fds_code = 1;
			if ($line < $row ['ERROR_N1']) $fds_code = 2;
			$return ['status'] = "FileServer szabad tárhely: ".$file_fds_status[$fds_code]." (".$return['free_space_GB']."GB)";
		}
 		
	} else {
		$file_error = 'Nem tudtam megnyitni a státusz állományt! '.$return ['status_base']; 
		$status_code = 1;
	}
	$return ['status_code'] = $status_code;
	$return ['fds_code']	= $fds_code;
	if ($file_open_error == 1) $return ['status'] = $file_error;
	$conn->close();
	return $return;
}


?>