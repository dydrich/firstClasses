<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/07/14
 * Time: 17.09
 */

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$year = $_SESSION['__current_year__']->get_ID();

$sel_students = "SELECT id_alunno, cognome, nome, rb_alunni.id_classe, sezione, ROUND(AVG(voto), 2) AS voto ";
$sel_students .= "FROM rb_alunni, rb_classi, rb_scrutini ";
$sel_students .= "WHERE rb_alunni.id_classe = rb_classi.id_classe AND anno_corso = 1 AND ordine_di_scuola = 1 AND id_alunno = alunno AND anno = {$year} AND quadrimestre = 2 AND (materia <> 2 AND materia <> 26) AND id_alunno NOT IN (SELECT id_archivio FROM rb_fc_alunni WHERE id_archivio IS NOT NULL) ";
$sel_students .= "GROUP BY alunno ORDER BY sezione, cognome, nome";
//echo $sel_students;
$res_students = $db->executeQuery($sel_students);
$students = array();
while ($st = $res_students->fetch_assoc()){
	if (!isset($students[$st['id_classe']])) {
		$students[$st['id_classe']] = array('sezione' => $st['sezione']);
		$students[$st['id_classe']]['alunni'] = array();
	}
	$students[$st['id_classe']]['alunni'][$st['id_alunno']] = array("cognome" => $st['cognome'], "nome" => $st['nome'], "voto" => $st['voto']);
}

include "ripetenti.html.php";