<?php

/*
 * gestore classi di provenienza
 * 1: aggiornamento classe
 * 2: inserimento classe/i
 * 3: cancellazione classe
 * 4: importazione classi quinte scuola primaria (scuola secondaria) o classi prime scuola primaria (scuola primaria)
 */

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch($_REQUEST['action']){
	case "1":
		$query = "UPDATE rb_fc_classi_provenienza SET descrizione = '".$_REQUEST['class_name']."' WHERE id_classe = ".$_REQUEST['class_id'];
		break;
	case "2":
		$cls = explode(",", $_REQUEST['class_names']);
		$query = "INSERT INTO rb_fc_classi_provenienza (descrizione, id_scuola, ordine_di_scuola) VALUES";
		foreach ($cls as $cl) {
			$query .= " ('".$cl."', ".$_REQUEST['school_id'].", {$_SESSION['__school_order__']}),";
		}
		$query = substr($query, 0, strlen($query) - 1);
		$response['classi'] = $_REQUEST['class_names'];
		break;
	case "3":
		$upd = $db->executeQuery("UPDATE rb_fc_alunni SET classe_provenienza = NULL WHERE classe_provenienza = ".$_REQUEST['class_id']);
		$query = "DELETE FROM rb_fc_classi_provenienza WHERE id_classe = ".$_REQUEST['class_id'];
		break;
	case 4:
		/*
		*  importazione delle classi della scuola da cui potrebbero provenire gli alunni
		*  nel caso del procedimento per la secondaria, importa i dati delle classi quinte della primaria e dalle prime della secondaria
		*  nel caso del procedimento per la primaria, importa le classi prime della primaria stessa (per i ripetenti)
		*/
		if ($_SESSION['__school_order__'] == 1) {
			$sel_import = "SELECT id_classe, sezione FROM rb_classi WHERE ordine_di_scuola = 2 AND anno_corso = 5 ORDER BY sezione";
			$imports = $db->executeQuery($sel_import);
			$query = "INSERT INTO rb_fc_classi_provenienza (descrizione, id_scuola, classe_archivio, ordine_di_scuola) VALUES";
			foreach ($imports as $cl) {
				$query .= " ('5{$cl['sezione']}', {$_REQUEST['school_id']}, {$cl['id_classe']}, 1),";
			}
			$sel_import2 = "SELECT id_classe, sezione FROM rb_classi WHERE ordine_di_scuola = 1 AND anno_corso = 1 ORDER BY sezione";
			$imports2 = $db->executeQuery($sel_import2);
			foreach ($imports2 as $cl) {
				$query .= " ('1{$cl['sezione']}', {$_REQUEST['school_id']}, {$cl['id_classe']}, 1),";
			}
		}
		else {
			$sel_import = "SELECT id_classe, sezione FROM rb_classi WHERE ordine_di_scuola = 2 AND anno_corso = 1 ORDER BY sezione";
			$imports = $db->executeQuery($sel_import);
			$query = "INSERT INTO rb_fc_classi_provenienza (descrizione, id_scuola, classe_archivio, ordine_di_scuola) VALUES ";
			foreach ($imports as $cl) {
				$query .= " ('1{$cl['sezione']}', {$_REQUEST['school_id']}, {$cl['id_classe']}, 2),";
			}
		}
		$query = substr($query, 0, strlen($query) - 1);
		break;
}
$out = "ok";
try{
	$db->executeUpdate($query);
	if($_REQUEST['action'] == 2 || $_REQUEST['action'] == 4){
		$sel_last = "SELECT MAX(id_classe) FROM rb_fc_classi_provenienza";
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
