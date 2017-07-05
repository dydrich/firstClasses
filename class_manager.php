<?php

/*
 * modifica le classi in rb_fc_classi
 * action = 1: inserisce nuova classe
 * action = 2: cancella classe
 * action = 3: associa le classi a quelle nell'archivio principale
 */

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

if (isset($_REQUEST['id'])){
	$id = $_REQUEST['id'];
}

$statement = null;
switch($_REQUEST['action']){
	case "1":
		$sezioni = explode(",", $_REQUEST['name']);
		$statement = "INSERT INTO rb_fc_classi (descrizione, ordine_di_scuola) VALUES ";
		foreach ($sezioni as $sezione) {
			$statement .= "('1".$sezione."', {$_SESSION['__school_order__']}),";
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
	case "3":
		/*
		 * passaggio di associazione: gli alunni devono essere inseriti in rb_alunni
		 * questo passaggio aggiorna l'id_classe, prendendo quello assegnato in fc
		 * chiamato da index.php
		 */
		try {
			$arch_cls = $db->executeQuery("SELECT id_classe, sezione FROM rb_classi WHERE ordine_di_scuola = {$_SESSION['__school_order__']} AND anno_corso = 1 ORDER BY sezione");
			if (count($arch_cls) < 1){
				$response['message'] = "Le classi non sono ancora state inserite in archivio";
				echo json_encode($response);
				exit;
			}
			foreach ($arch_cls as $row){
				$upd = "UPDATE rb_fc_classi SET classe_archivio = {$row['id_classe']} WHERE ordine_di_scuola = {$_SESSION['__school_order__']} AND descrizione = '1{$row['sezione']}'";
				$db->executeUpdate($upd);
				//echo $upd;
			}
			/*
			 * associazione alunni
			 */
			$sel_fc_cls = "SELECT id_classe, classe_archivio FROM rb_fc_classi WHERE ordine_di_scuola = {$_SESSION['__school_order__']}";
			$res_fc_cls = $db->executeQuery($sel_fc_cls);
			$fc_cls = array();
			while ($r = $res_fc_cls->fetch_assoc()) {
				$fc_cls[$r['id_classe']] = $r['classe_archivio'];
			}

			$res_fc_alunni = $db->executeQuery("SELECT id_classe, id_archivio FROM rb_fc_alunni WHERE ordine_di_scuola = {$_SESSION['__school_order__']}");
			while ($row = $res_fc_alunni->fetch_assoc()) {
				$new_cls = $fc_cls[$row['id_classe']];
				$db->executeUpdate("UPDATE rb_alunni SET attivo = 1, id_classe = ".$new_cls." WHERE id_alunno = ".$row['id_archivio']);
				//echo "UPDATE rb_alunni SET id_classe = ".$new_cls." WHERE id_alunno = ".$row['id_archivio'];
			}
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			$response['error'] = $error;
			echo json_encode($response);
			exit;
		}
		$response['message'] = "Associazione completata";
		echo json_encode($response);
		exit;
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
