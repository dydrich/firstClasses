<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch($_REQUEST['action']){
	case "1":
		$query = "UPDATE rb_fc_scuole_provenienza SET descrizione = '".$_REQUEST['class_name']."', codice = '".$_REQUEST['class_code']."' WHERE id_scuola = ".$_REQUEST['class_id'];
		break;
	case "2":
		$query = "INSERT INTO rb_fc_scuole_provenienza (descrizione, codice) VALUES ('".$_REQUEST['class_name']."', '".$_REQUEST['class_code']."')";
		break;
	case "3":
		$query = "DELETE FROM rb_fc_scuole_provenienza WHERE id_scuola = ".$_REQUEST['class_id'];
		break;
}
$out = "ok";
try{
	$db->executeUpdate($query);
	if($_REQUEST['action'] == 2){
		$sel_last = "SELECT MAX(id_scuola) FROM rb_fc_scuole_provenienza";
		$max = $db->executeCount($sel_last);
		$response['max'] = $max;
	}
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

echo json_encode($response);
exit;