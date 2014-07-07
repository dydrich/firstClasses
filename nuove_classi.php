<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$sel = "SELECT * FROM rb_fc_nuove_classi ORDER BY sezione, classe";
$result = $db->execute($sel);
$classi = array();
while($classe = $result->fetch_assoc()){
	if(!isset($classi[$classe['sezione']])){
		$classi[$classe['sezione']] = array();
	}
	$classi[$classe['sezione']][$classe['classe']] = $classe;
}
$sezioni = array_keys($classi);
$alfabeto = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "Z");

include "nuove_classi.html.php";