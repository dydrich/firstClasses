<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

if($_REQUEST['stid'] != 0){
	$sel_student = "SELECT * FROM rb_fc_alunni WHERE id_alunno = ".$_REQUEST['stid'];
	try{
		$res_student = $db->executeQuery($sel_student);
	} catch (MySQLException $ex){
		$ex->fake_alert();
	}
	$student = $res_student->fetch_assoc();
}

if(!isset($_REQUEST['rip'])){
	$sel_classes_from = "SELECT rb_fc_classi_provenienza.id_classe,
						 rb_fc_classi_provenienza.id_scuola,
						 CONCAT_WS(', ', rb_fc_scuole_provenienza.descrizione,
						 rb_fc_classi_provenienza.descrizione) AS description
						 FROM rb_fc_classi_provenienza, rb_fc_scuole_provenienza
						 WHERE rb_fc_classi_provenienza.id_scuola <> 5
						 AND rb_fc_classi_provenienza.id_scuola = rb_fc_scuole_provenienza.id_scuola
						 AND rb_fc_classi_provenienza.ordine_di_scuola = {$_SESSION['__school_order__']}";
}
else {
	$sel_classes_from = "SELECT id_classe, descrizione AS description FROM rb_fc_classi_provenienza WHERE rb_fc_classi_provenienza.id_scuola = 5 ORDER BY descrizione ";
}
$res_classes_from = $db->executeQuery($sel_classes_from);

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Modifica alunno";

include "student.html.php";
