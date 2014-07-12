<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$student = $_REQUEST['stid'];
$grade = $_REQUEST['grade'];

try{
	$upd = "UPDATE rb_fc_alunni SET voto = {$grade} WHERE id_alunno = {$student}";
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM rb_fc_alunni WHERE classe_provenienza = ".$_REQUEST['cl'];
$avg = $db->executeCount($sel_avg);

$response['avg'] = $avg;
echo json_encode($response);
exit;