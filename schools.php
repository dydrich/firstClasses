<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$sel_schools = "SELECT rb_fc_scuole_provenienza.descrizione AS school, rb_fc_scuole_provenienza.codice AS code, rb_fc_scuole_provenienza.id_scuola AS id_sc, rb_fc_classi_provenienza.id_classe AS id_cl, rb_fc_classi_provenienza.descrizione AS cls FROM rb_fc_scuole_provenienza LEFT JOIN rb_fc_classi_provenienza ON rb_fc_scuole_provenienza.id_scuola = rb_fc_classi_provenienza.id_scuola WHERE rb_fc_scuole_provenienza.id_scuola <> 6 ORDER BY rb_fc_classi_provenienza.id_scuola, id_classe";
$res_schools = $db->execute($sel_schools);
$schools = array();
while($sc = $res_schools->fetch_assoc()){
	if(!isset($schools[$sc['id_sc']])){
		$schools[$sc['id_sc']] = array();
		array_push($schools[$sc['id_sc']], $sc['school']."#".$sc['code']);
		$schools[$sc['id_sc']][1] = array();
	}
	if($sc['id_cl'] != "")
		array_push($schools[$sc['id_sc']][1], array("school_id" => $sc['id_sc'], "school" => $sc['school'], "class_id" => $sc['id_cl'], "class" => $sc['cls']));
}
//print_r($schools);
include "schools.html.php";