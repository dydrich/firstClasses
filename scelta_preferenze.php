<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 27/08/17
 * Time: 22.01
 */

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

$year = $_SESSION['__current_year__']->get_ID();

$stid = $_REQUEST['stid'];

$sel_students = "SELECT cognome, nome FROM rb_fc_alunni WHERE id_alunno = ".$_REQUEST['stid'];
//echo $sel_students;
$res_students = $db->executeQuery($sel_students);
$student = $res_students->fetch_assoc();

/* teachers */
$res_t = $db->executeQuery("SELECT uid, cognome, nome FROM rb_utenti, rb_docenti WHERE uid = id_docente AND tipologia_scuola = 1 AND rb_utenti.attivo = 1 ORDER BY cognome, nome");
$teachers = [];
while ($r = $res_t->fetch_assoc()) {
	$teachers[$r['uid']] = $r;
}

/* sezioni */
$res_s = $db->executeQuery("SELECT id_classe, descrizione FROM rb_fc_classi ORDER BY descrizione");
$sezioni = [];
while ($rs = $res_s->fetch_assoc()) {
	$sezioni[$rs['id_classe']] = substr($rs['descrizione'], 1, 1);
}

/* preferenze */
$res_prefs = $db->executeQuery("SELECT id_p, tipo_preferenza, valore FROM rb_fc_preferenze_didattiche WHERE alunno = ".$_REQUEST['stid']);
if ($res_prefs->num_rows > 0) {
	while ($row = $res_prefs->fetch_assoc()) {
		if (!isset($student[$row['tipo_preferenza']])) {
			$student[$row['tipo_preferenza']] = [];
		}
		if ($row['tipo_preferenza'] == 1) {
			$res = $db->executeQuery("SELECT uid, cognome, nome FROM rb_utenti WHERE uid = {$row['valore']}");
			$t = $res->fetch_assoc();
			$student[$row['tipo_preferenza']][$t['uid']] = $t;
		}
		else if ($row['tipo_preferenza'] == 3 || $row['tipo_preferenza'] == 4) {
			$student[$row['tipo_preferenza']][$row['id_p']] = $row['valore'];
		}
	else {
			$student[$row['tipo_preferenza']][] = $row['valore'];
		}
	}
}

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Indicazione preferenze ".$student['cognome']." ".$student['nome'];

include "scelta_preferenze.html.php";