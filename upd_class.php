<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$student = $_REQUEST['std'];
$_class = $_REQUEST['cl'];
if($_class == "0"){
	$_class = "NULL";
}

try{
	$upd = "UPDATE rb_fc_alunni SET id_classe = $_class WHERE id_alunno = $student";
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$response['id'] = $student;
echo json_encode($response);
exit;