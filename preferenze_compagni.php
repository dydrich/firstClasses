<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 15/07/14
 * Time: 10.52
 */

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$year = $_SESSION['__current_year__']->get_ID();

$sel_students = "SELECT id_alunno, cognome, nome FROM rb_fc_alunni ORDER BY cognome, nome";
//echo $sel_students;
$res_students = $db->executeQuery($sel_students);
$students = array();
while ($st = $res_students->fetch_assoc()){
	$students[$st['id_alunno']] = array("cognome" => $st['cognome'], "nome" => $st['nome']);
	$sel_prefs = "SELECT cognome, nome, rb_fc_alunni.id_alunno FROM rb_fc_alunni, rb_fc_preferenze_alunni WHERE preferenza = id_alunno AND alunno = {$st['id_alunno']} ORDER BY cognome, nome";
	//echo $sel_prefs;
	$res_prefs = $db->executeQuery($sel_prefs);
	$students[$st['id_alunno']]['preferenze'] = array();
	while ($row = $res_prefs->fetch_assoc()){
		$students[$st['id_alunno']]['preferenze'][$row['id_alunno']] = $row['cognome']." ".$row['nome'];
	}
}

include "preferenze_compagni.html.php";