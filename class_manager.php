<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$id = $_REQUEST['id'];

switch($_REQUEST['action']){
	case "1":
		$sezioni = explode(",", $_REQUEST['name']);
		$statement = "INSERT INTO rb_fc_classi (descrizione) VALUES ";
		foreach ($sezioni as $sezione) {
			$statement .= "('1".$sezione."'),";
		}
		$error = "Errore nella creazione della classe";
		$statement = substr($statement, 0, strlen($statement) - 1);
		break;
	case "2":
		$upd = "UPDATE rb_fc_alunni SET id_classe = NULL WHERE id_classe = {$id}";
		$db->executeUpdate($upd);
		$statement = "DELETE FROM rb_fc_classi WHERE id_classe = {$id}";
		$error = "Errore nella cancellazione della classe";
		break;
}
try{
	$res = $db->execute($statement);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	$response['error'] = $error;
	echo json_encode($response);
	exit;
}

$response['id'] = $id;
echo json_encode($response);
exit;