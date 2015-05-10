<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 09/07/14
 * Time: 11.11
 * mostra tutti gli studenti delle classi quinte dell'archivio e permette di scegliere quelli da importare
 */

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$year = $_SESSION['__current_year__']->get_ID();

$sel_students = "SELECT id_alunno, cognome, nome, rb_alunni.id_classe, sezione, ROUND(AVG(voto), 2) AS voto
	FROM rb_alunni, rb_classi, rb_scrutini
	WHERE rb_alunni.id_classe = rb_classi.id_classe
	AND anno_corso = 5
	AND id_alunno = alunno
	AND anno = {$year}
	AND quadrimestre = 2
	AND (materia <> 30 AND materia <> 40)
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
$drawer_label = "Alunni classi quinte";

include "import_students.html.php";
