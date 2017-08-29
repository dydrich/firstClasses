<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 27/08/17
 * Time: 21.34
 */
include "../../lib/start.php";

ini_set('display_errors', 1);

check_session();
check_permission(DIR_PERM|SEG_PERM);

$year = $_SESSION['__current_year__']->get_ID();

$sel_students = "SELECT id_alunno, cognome, nome FROM rb_fc_alunni WHERE ordine_di_scuola = {$_SESSION['__school_order__']} ORDER BY cognome, nome";
//echo $sel_students;
$res_students = $db->executeQuery($sel_students);
$students = array();
while ($st = $res_students->fetch_assoc()){
	$students[$st['id_alunno']] = array("cognome" => $st['cognome'], "nome" => $st['nome'], 'preferenze' => (array()));
	$sel_prefs_teach = "SELECT cognome, nome, uid, tipo_preferenza, valore
						FROM rb_utenti, rb_fc_preferenze_didattiche 
						WHERE uid = valore AND tipo_preferenza = 1 AND alunno = {$st['id_alunno']} ORDER BY cognome, nome";
	//echo $sel_prefs;
	$res_prefs = $db->executeQuery($sel_prefs_teach);
	while ($row = $res_prefs->fetch_assoc()){
		if (!isset($students[$st['id_alunno']]['preferenze'][1])) {
			$students[$st['id_alunno']]['preferenze'][1] = [];
		}
		$students[$st['id_alunno']]['preferenze'][1][] = $row['cognome']." ".$row['nome'];
	}

	/* sezione */
	$res_prefs = $db->executeQuery("SELECT valore FROM rb_fc_preferenze_didattiche WHERE tipo_preferenza = 2 AND alunno = ".$st['id_alunno']);
	if ($res_prefs->num_rows > 0) {
		while ($row = $res_prefs->fetch_assoc()) {
			if (!isset($students[$st['id_alunno']]['preferenze'][2])) {
				$students[$st['id_alunno']]['preferenze'][2] = [];
			}
			$students[$st['id_alunno']]['preferenze'][2] = $row['valore'];
		}
	}

	/* altro */
	$res_ot = $db->executeQuery("SELECT id_p, valore FROM rb_fc_preferenze_didattiche WHERE tipo_preferenza = 3 AND alunno = ".$st['id_alunno']);
	if ($res_ot->num_rows > 0) {
		while ($row = $res_ot->fetch_assoc()) {
			if (!isset($students[$st['id_alunno']]['preferenze'][3])) {
				$students[$st['id_alunno']]['preferenze'][3] = [];
			}
			$students[$st['id_alunno']]['preferenze'][3][$row['id_p']] = $row['valore'];
		}
	}

	/* note docente */
	$res_ot = $db->executeQuery("SELECT id_p, valore FROM rb_fc_preferenze_didattiche WHERE tipo_preferenza = 4 AND alunno = ".$st['id_alunno']);
	if ($res_ot->num_rows > 0) {
		while ($row = $res_ot->fetch_assoc()) {
			if (!isset($students[$st['id_alunno']]['preferenze'][4])) {
				$students[$st['id_alunno']]['preferenze'][4] = [];
			}
			$students[$st['id_alunno']]['preferenze'][4][$row['id_p']] = $row['valore'];
		}
	}
}

/* teachers */
$res_t = $db->executeQuery("SELECT uid, cognome, nome FROM rb_utenti, rb_docenti WHERE uid = id_docente AND tipologia_scuola = 1 AND rb_utenti.attivo = 1 ORDER BY cognome, nome");
$teachers = [];
while ($r = $res_t->fetch_assoc()) {
	$teachers[] = $r;
}

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Preferenze didattiche";

include "preferenze_didattica.html.php";