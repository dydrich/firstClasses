<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

$navigation_label = "welcome";

$school_order = $_SESSION['__school_order__'];

$sel_classes = "SELECT * FROM rb_fc_classi WHERE ordine_di_scuola = $school_order ORDER BY descrizione";
$res_classes = $db->executeQuery($sel_classes);
$n_cls = $res_classes->num_rows;

$sel_students = "SELECT COUNT(id_alunno) FROM rb_fc_alunni WHERE ordine_di_scuola = $school_order ";
$n_std = $db->executeCount($sel_students);
if($n_std > 0){
	$sel_not_assigned = "SELECT COUNT(id_alunno) FROM rb_fc_alunni WHERE ordine_di_scuola = $school_order AND id_classe IS NULL";
	$not_assigned = $db->executeCount($sel_not_assigned);
}

/*
 * color for class visualization
 * stored in session
 */
if(!isset($_SESSION['__colors__'])){
	$sel_colors = "SELECT * FROM rb_fc_backgrounds ORDER BY id";
	$res_colors = $db->executeQuery($sel_colors);
	$_SESSION['__colors__'] = array();
	while($color = $res_colors->fetch_assoc()){
		$_SESSION['__colors__'][$color['id']] = array("color" => $color['colore'], "is_used" => false);
	}
}

$_SESSION['__fc__'] = array();
$sel_fc = "SELECT * FROM rb_fc_classi WHERE ordine_di_scuola = $school_order ORDER BY id_classe";
$res_fc = $db->executeQuery($sel_fc);
$x = 1;
while($cl1 = $res_fc->fetch_assoc()){
	$_SESSION['__fc__'][$cl1['id_classe']] = array("class" => $cl1['descrizione'], "color" => $_SESSION['__colors__'][$x]['color']);
	$_SESSION['__colors__'][$x]['is_used'] = true;
	$x++;
}

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Nuove classi";

include "index.html.php";
