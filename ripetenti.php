<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/07/14
 * Time: 17.09
 */

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

$year = $_SESSION['__current_year__']->get_ID();

$sel_students = "SELECT id_alunno, cognome, nome, rb_alunni.id_classe, sezione, ROUND(AVG(voto), 2) AS voto
				 FROM rb_alunni, rb_classi, rb_scrutini
				 WHERE rb_alunni.id_classe = rb_classi.id_classe
				 AND anno_corso = 1
				 AND ordine_di_scuola = {$_SESSION['__school_order__']}
				 AND id_alunno = alunno
				 AND anno = 4
				 AND quadrimestre = 2
				 AND (materia <> 2 AND materia <> 26)
				 AND id_alunno NOT IN (SELECT id_archivio FROM rb_fc_alunni WHERE id_archivio IS NOT NULL)
				 GROUP BY alunno
				 ORDER BY sezione, cognome, nome";

try {
	$res_students = $db->executeQuery($sel_students);
} catch (MySQLException $ex) {
	$ex->redirect();
}
$students = array();
while ($st = $res_students->fetch_assoc()){
	if (!isset($students[$st['id_classe']])) {
		$students[$st['id_classe']] = array('sezione' => $st['sezione']);
		$students[$st['id_classe']]['alunni'] = array();
	}
	$students[$st['id_classe']]['alunni'][$st['id_alunno']] = array("cognome" => $st['cognome'], "nome" => $st['nome'], "voto" => $st['voto']);
}

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Alunni ripetenti";

include "ripetenti.html.php";
