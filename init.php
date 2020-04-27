<?php
	$GLOBALS ['log'] 							= 1;
	$GLOBALS ['phplog_tbl']						= "t_phplog";
	$GLOBALS ['mega_send_txt_file_path']		= "./playlist/mega/";
	$GLOBALS ['984_send_txt_file_path']			= "./playlist/984/";
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	include ('barix-functions.php');
	

function db_phplog ($warn, $phpscript, $function, $event) {
	$conn 	= dbconn ("rsm");
	$tbl 	= $GLOBALS ['phplog_tbl'];
	$sql 	= sprintf ("INSERT INTO %s (WARN, PHPSCRIPT, FUNCTION, EVENT) VALUES (%s, '%s', '%s', '%s')", $tbl, $warn, $phpscript, $function, $event);
	$conn -> query($sql);
	$conn -> close();
}

function set_active_radio ($radio) {
	$conn 	= dbconn ('fs');
	$sql = sprintf("UPDATE `zenontools`.`t_settings` SET `VALUE` = '%s' WHERE `t_settings`.`ID` =4;", $radio);
	if (!$resault = $conn-> query($sql)) {
		$message = "Adatbázis hiba lekérdezésben. MySQL üzenete: ";
		$message.= $conn -> error;
		$message.= " SQL kérés: ".$sql;
		if ($GLOBALS ['log'] == 1)  db_phplog ("1", "init.php", "set_active_radio", $message);
	} else {
		$message = "Aktív rádió sikeresen módosítva. SQL kérés: ".$sql;
		if ($GLOBALS ['log'] == 1)  db_phplog ("0", "init.php", "set_active_radio", $message);
	}
}

function list_autotext ($file) {
	$fUrl = $GLOBALS ['mega_send_txt_file_path'].$file;
	echo $fUrl.'<br>';
	$myfile = fopen($fUrl, "r") or die("Nem tudtam a $fUrl fájlt megnyitni.");
	while(!feof($myfile)) {
	  echo fgets($myfile) . "<br>";
	}
	fclose($myfile);
}

function onair_now ($radio) {
	$txt_line = array();
	/* switch ($radio) {
		case "mega":
			$basepath = $GLOBALS ['mega_send_txt_file_path'];
		break;
		case "984":
			$basepath = $GLOBALS ['984_send_txt_file_path'];
		break;
	}
	*/ 
	$basepath = $GLOBALS ['mega_send_txt_file_path'];
	if (date("H") == "21" || date("H") == "22" || date("H") == "05" || date("H") == "14") $basepath = $GLOBALS ['984_send_txt_file_path'];
	
	$file = date("dmY").".txt";
	$fUrl = $basepath.$file;
	$myfile = fopen($fUrl, "r") or die("Nem tudtam a $fUrl fájlt megnyitni.");
	$marker = 0; $title_tmp = '';
	while(!feof($myfile)) {
		$line = fgets($myfile); $line = mb_convert_encoding($line, "UTF-8", "ISO-8859-1");
		$text_line = explode(";", $line);
		# print_r($text_line);
		if (is_array($txt_line)) {
			$time_now = strtotime(date ("H:i:s")); $time_in_line = strtotime($text_line[0]);
			if ((intval($time_now) <= intval($time_in_line)) && $marker == 0) {
				$return = $title_tmp;
				$marker = 1;		  
			} else {
				if ($marker == 0) {
					$title_tmp = $text_line [2];
					$return_full_line = $text_line;
				}
			}
		}
	}
	fclose($myfile);
	$return = explode("/", $return);
	$return = array_map ('trim', $return);
	$return ['fullline'] = $return_full_line;
	return $return;
}

function onair_now_test ($radio) {
	$txt_line = array();
	/* switch ($radio) {
		case "mega":
			$basepath = $GLOBALS ['mega_send_txt_file_path'];
		break;
		case "984":
			$basepath = $GLOBALS ['984_send_txt_file_path'];
		break;
	}
	*/ 
	$basepath = $GLOBALS ['mega_send_txt_file_path'];
	if (date("H") == "21" || date("H") == "22" || date("H") == "05" || date("H") == "14") $basepath = $GLOBALS ['984_send_txt_file_path'];
	
	$file = date("dmY").".txt";
	$fUrl = $basepath.$file;
	$myfile = fopen($fUrl, "r") or die("Nem tudtam a $fUrl fájlt megnyitni.");
	$marker = 0; $title_tmp = '';
	while(!feof($myfile)) {
		$line = fgets($myfile); $line = mb_convert_encoding($line, "UTF-8", "ISO-8859-1");
		$text_line = explode(";", $line);
		print_r ($text_line);
		# print_r($text_line);
		if (is_array($txt_line)) {
			$time_now = strtotime(date ("H:i:s")); $time_in_line = strtotime($text_line[0]);
			if ((intval($time_now) <= intval($time_in_line)) && $marker == 0) {
				$return = $title_tmp;
				$marker = 1;		  
			} else {
				if ($marker == 0) {
					$title_tmp = $text_line [2];
					$return_full_line = $text_line;
				}
			}
		}
		print "<br>";
	}
	fclose($myfile);
	$return = explode("/", $return);
	$return = array_map ('trim', $return);
	$return ['fullline'] = $return_full_line;
	return $return;
}

function get_icecast_info($server_ip, $server_port, $admin_user, $admin_password) {
	$url = "http://".$admin_user.":".$admin_password."@".$server_ip.":".$server_port."/admin/stats.xml";
	$index = file_get_contents($url);
	if($index) {
        $xml = new DOMDocument(); if(!$xml->loadXML($index)) return false; $arr = array(); $listItem = $xml->getElementsByTagName("source");
        foreach($listItem as $element) {
            if($element->childNodes->length) {
                foreach($element->childNodes as $i){ $arr[$element->getAttribute("mount")][$i->nodeName] = $i->nodeValue; }
            }
        }
        return $arr;
    } return false;
}

?>