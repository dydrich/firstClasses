<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$sel_schools = "SELECT rb_fc_scuole_provenienza.descrizione AS school,
				rb_fc_scuole_provenienza.codice AS code,
				rb_fc_scuole_provenienza.id_scuola AS id_sc,
				comprensivo, rb_fc_classi_provenienza.id_classe AS id_cl,
				rb_fc_classi_provenienza.descrizione AS cls
				FROM rb_fc_scuole_provenienza LEFT JOIN rb_fc_classi_provenienza
				ON rb_fc_scuole_provenienza.id_scuola = rb_fc_classi_provenienza.id_scuola
				WHERE ordine_di_scuola = {$_SESSION['__school_order__']}
				ORDER BY rb_fc_classi_provenienza.id_scuola, id_classe";

try {
	$res_schools = $db->execute($sel_schools);
} catch (MySQLException $ex) {
	$ex->redirect();
}
$schools = array();
while($sc = $res_schools->fetch_assoc()){
	if(!isset($schools[$sc['id_sc']])){
		$schools[$sc['id_sc']] = array();
		$schools[$sc['id_sc']]['descrizione'] = $sc['school'];
		$schools[$sc['id_sc']]['codice'] = $sc['code'];
		$schools[$sc['id_sc']]['comprensivo'] = $sc['comprensivo'];
		$schools[$sc['id_sc']]['classi'] = array();
	}
	if($sc['id_cl'] != ""){
		$schools[$sc['id_sc']]['classi'][] = array("school_id" => $sc['id_sc'], "school" => $sc['school'], "class_id" => $sc['id_cl'], "class" => $sc['cls']);
	}
}

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Scuole di provenienza";

include "schools.html.php";
