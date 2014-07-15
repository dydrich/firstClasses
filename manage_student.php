<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

if($_REQUEST['action'] == 1 || $_REQUEST['action'] == 2){
	$fname = $db->real_escape_string($_REQUEST['fname']);
	$lname = $db->real_escape_string($_REQUEST['lname']);
	$from = $_REQUEST['from'];
	$sex = $_REQUEST['sex'];
	$h = $_REQUEST['h'];
	if($h > 1)
		$diagnose = $_REQUEST['diagnose'];
	else
		$diagnose = "";
	$grade = $_REQUEST['grade'];
	$note = $_REQUEST['note'];
	
	/* controllo ripetente */
	$sel_school_from = "SELECT id_scuola FROM rb_fc_classi_provenienza WHERE id_classe = $from";
	$school_from = $db->executeCount($sel_school_from);
	$ripetente = 0;
}

switch($_REQUEST['action']){
	case "1":
		$query = "UPDATE rb_fc_alunni SET nome = '$fname', cognome = '$lname', sesso = '$sex', classe_provenienza = $from, H = $h, diagnosi_h = ".field_null($diagnose, true).", voto = $grade, note = ".field_null(utf8_encode($note), true)." WHERE id_alunno = ".$_REQUEST['stid'];
		break;
	case "2":
		$query = "INSERT INTO rb_fc_alunni (nome, cognome, sesso, ripetente, classe_provenienza, h, diagnosi_h, voto, note) VALUES ('$fname', '$lname', '$sex', $ripetente, $from, $h, ".field_null($diagnose, true).", $grade, ".field_null(utf8_encode($note), true).")";
		break;
	case "3":
		$query = "DELETE FROM rb_fc_alunni WHERE id_alunno = ".$_REQUEST['stid'];
		break;
	case 4:
		/*
		importa alunni classi quinte
		*/
		$year = $_SESSION['__current_year__']->get_ID();
		foreach ($_REQUEST['sts'] as $id_st){
			try{
				$sel_students = "SELECT id_alunno, cognome, nome, sesso, rb_alunni.id_classe, sezione, ROUND(AVG(voto), 2) AS voto ";
				$sel_students .= "FROM rb_alunni, rb_classi, rb_scrutini ";
				$sel_students .= "WHERE rb_alunni.id_classe = rb_classi.id_classe AND anno_corso = 5 AND id_alunno = alunno AND anno = {$year} AND quadrimestre = 2 AND (materia <> 30 AND materia <> 40) AND id_alunno = {$id_st} ";
				$sel_students .= "GROUP BY alunno ORDER BY sezione, cognome, nome";
				$res_student = $db->executeQuery($sel_students);
				$student = $res_student->fetch_assoc();
				$cognome = $db->real_escape_string($student['cognome']);
				$nome = $db->real_escape_string($student['nome']);
				$cl_from = $db->executeCount("SELECT id_classe FROM rb_fc_classi_provenienza WHERE classe_archivio = {$student['id_classe']}");
				$insert = "INSERT INTO rb_fc_alunni (cognome, nome, classe_provenienza, sesso, voto, id_archivio, H, DSA, BES, ripetente) ";
				$insert .= "VALUES ('{$cognome}', '{$nome}', {$cl_from}, '{$student['sesso']}', {$student['voto']}, {$id_st}, 0, 0, 0, 0)";
				$db->executeUpdate($insert);
				$update = "UPDATE rb_fc_alunni SET voto = {$student['voto']} WHERE id_archivio = {$id_st}";
			} catch (MySQLException $ex){
				$response['status'] = "kosql";
				$response['message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}

		}

		/*
		$stsds = $db->executeQuery("SELECT id_archivio FROM rb_fc_alunni WHERE id_archivio IS NOT NULL");
		while ($row = $stsds->fetch_assoc()){
			echo $row['id_archivio'];
			$voto = $db->executeCount("SELECT ROUND(AVG(voto), 2) FROM rb_scrutini WHERE alunno = ".$row['id_archivio']." AND anno = 2 AND quadrimestre = 2 AND (materia <> 30 AND materia <> 40)");
			$db->executeUpdate("UPDATE rb_fc_alunni SET voto = {$voto} WHERE id_archivio = ".$row['id_archivio']);
		}
		*/
		echo json_encode($response);
		exit;
		break;
	case 5:
		/*
		importa alunni ripetenti
		*/
		$year = $_SESSION['__current_year__']->get_ID();
		foreach ($_REQUEST['sts'] as $id_st){
			try{
				$sel_students = "SELECT id_alunno, cognome, nome, sesso, rb_alunni.id_classe, sezione, ROUND(AVG(voto), 2) AS voto ";
				$sel_students .= "FROM rb_alunni, rb_classi, rb_scrutini ";
				$sel_students .= "WHERE rb_alunni.id_classe = rb_classi.id_classe AND anno_corso = 1 AND ordine_di_scuola = 1 AND id_alunno = alunno AND anno = {$year} AND quadrimestre = 2 AND (materia <> 2 AND materia <> 26) AND id_alunno = {$id_st} ";
				$sel_students .= "GROUP BY alunno ORDER BY sezione, cognome, nome";
				$res_student = $db->executeQuery($sel_students);
				$student = $res_student->fetch_assoc();
				$cognome = $db->real_escape_string($student['cognome']);
				$nome = $db->real_escape_string($student['nome']);
				$cl_from = $db->executeCount("SELECT id_classe FROM rb_fc_classi_provenienza WHERE classe_archivio = {$student['id_classe']}");
				$insert = "INSERT INTO rb_fc_alunni (cognome, nome, classe_provenienza, sesso, voto, id_archivio, H, DSA, BES, ripetente) ";
				$insert .= "VALUES ('{$cognome}', '{$nome}', {$cl_from}, '{$student['sesso']}', {$student['voto']}, {$id_st}, 0, 0, 0, 0)";
				$db->executeUpdate($insert);
				$update = "UPDATE rb_fc_alunni SET voto = {$student['voto']} WHERE id_archivio = {$id_st}";
			} catch (MySQLException $ex){
				$response['status'] = "kosql";
				$response['message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}

		}

		echo json_encode($response);
		exit;
		break;
	case 6:
		// aggiunta preferenza
		$id_alunno = $_REQUEST['id_alunno'];
		$comp = $_REQUEST['pref'];
		try{
			$response['max'] = $db->executeUpdate("INSERT INTO rb_fc_preferenze_alunni (alunno, preferenza) VALUES ({$id_alunno}, {$comp})");
			$response['name'] = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_fc_alunni WHERE id_alunno = {$comp}");
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		echo json_encode($response);
		exit;
		break;
	case 7:
		// cancellazione preferenza
		$id_alunno = $_REQUEST['id_alunno'];
		$comp = $_REQUEST['pref'];
		try{
			$db->executeUpdate("DELETE FROM rb_fc_preferenze_alunni WHERE alunno = {$id_alunno} AND preferenza = {$comp}");
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		echo json_encode($response);
		exit;
		break;
		break;
}
try{
	$db->executeUpdate($query);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}
echo json_encode($response);
exit;