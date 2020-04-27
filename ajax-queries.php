<?php
include ("db_init.php");
include ("init.php");
include ("functions.php");
include ("init_process.php");
error_reporting(E_ERROR);


switch ($_GET['f']) {
	case "get_type":
		$radio = $_GET ['r'];
		$id = $_GET ['id'];
		$a = get_num_type ($radio, $id);
		echo json_encode($a);
	break;
	case "onair":
		$radio = $_GET ['r'];
		$a = onair_now ($radio);
		if ($a == NULL) $a = 0;
		$update_icecast2 = "http://admin:rad4515cx@192.168.66.146:8000/admin/metadata?mount=/megaair.mp3&mode=updinfo&song=".rawurlencode($a [1]." - ".$a[2]);
		file_get_contents($update_icecast2);
		$update_icecast2 = "http://admin:rad4515cx@192.168.66.146:8000/admin/metadata?mount=/megaair128.mp3&mode=updinfo&song=".rawurlencode($a [1]." - ".$a[2]);
		file_get_contents($update_icecast2);
		echo json_encode($a);
	break;
	case "onair-test":
		$radio = $_GET ['r'];
		$a = onair_now_test ($radio);
		if ($a == NULL) $a = 0;
		$update_icecast2 = "http://admin:rad4515cx@192.168.66.146:8000/admin/metadata?mount=/megaair.mp3&mode=updinfo&song=".rawurlencode($a [1]." - ".$a[2]);
		echo $update_icecast2."<br>";
		echo file_get_contents($update_icecast2);

		echo json_encode($a, JSON_UNESCAPED_UNICODE);
	break;
	case "get_icecast_info":
		print_r (get_icecast_info("192.168.66.146", "8000", "admin", "rad4515cx"));
	break;
	case "status-m-dsp":
		$a = status_m_diskspace ();
		echo json_encode($a, JSON_UNESCAPED_UNICODE);
	break;
	case "status-p-dsp":
		$a = status_p_diskspace ();
		echo json_encode($a, JSON_UNESCAPED_UNICODE);
	break;
	case "barix":
		$radio = $_GET ['r'];
		$a = get_barix_status ($radio);
		echo json_encode($a, JSON_UNESCAPED_UNICODE);
	break;
}


?>
