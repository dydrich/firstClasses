<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

$_SESSION['__class_id__'] = $_REQUEST['id_classe'];
	
$sel_desc = "SELECT descrizione FROM rb_fc_classi WHERE id_classe = ".$_REQUEST['id_classe'];
$class_desc = $db->executeCount($sel_desc);

if(isset($_REQUEST['order']) && $_REQUEST['order'] == "from") {
	$order = "rb_fc_scuole_provenienza.id_scuola, classe_provenienza,";
}
else {
	$order = "";
}

/* students list */
$students = [];
$sel_students = "
		SELECT id_alunno,
		CONCAT_WS(' ', cognome, nome) AS name,
		ripetente,
		H,
		sesso,
		voto,
		CONCAT_WS('. ', diagnosi_h, note) AS note,
		rb_fc_scuole_provenienza.id_scuola AS school,
		 rb_fc_alunni.id_classe, classe_provenienza,
		 CONCAT_WS(', ', rb_fc_scuole_provenienza.codice, rb_fc_classi_provenienza.descrizione) AS class_from
		 FROM rb_fc_alunni, rb_fc_classi_provenienza, rb_fc_scuole_provenienza
		 WHERE rb_fc_alunni.classe_provenienza = rb_fc_classi_provenienza.id_classe
		 AND rb_fc_classi_provenienza.id_scuola = rb_fc_scuole_provenienza.id_scuola
		 AND rb_fc_alunni.id_classe = ".$_REQUEST['id_classe']."
		 ORDER BY $order cognome, nome";

try{
	$res_students = $db->executeQuery($sel_students);
} catch(MySQLException $ex){
	$ex->redirect();
}
while ($row = $res_students->fetch_assoc()) {
	$students[$row['id_alunno']] = $row;
	$students[$row['id_alunno']]['preferenze'] = [];
	$p1 = $db->executeCount("SELECT COUNT(*) FROM rb_fc_preferenze_didattiche WHERE tipo_preferenza = 1 AND alunno = ".$row['id_alunno']);
	if ($p1 > 0) {
		$students[$row['id_alunno']]['preferenze'][] = 1;
	}
	$p2 = $db->executeCount("SELECT COUNT(*) FROM rb_fc_preferenze_didattiche WHERE tipo_preferenza = 2 AND alunno = ".$row['id_alunno']);
	if ($p2 > 0) {
		$students[$row['id_alunno']]['preferenze'][] = 2;
		$students[$row['id_alunno']]['sect'] = $db->executeCount("SELECT valore FROM rb_fc_preferenze_didattiche WHERE tipo_preferenza = 2 AND alunno = ".$row['id_alunno']);
	}
	$p3 = $db->executeCount("SELECT COUNT(*) FROM rb_fc_preferenze_didattiche WHERE tipo_preferenza = 3 AND alunno = ".$row['id_alunno']);
	if ($p3 > 0) {
		$students[$row['id_alunno']]['preferenze'][] = 3;
	}
	$p4 = $db->executeCount("SELECT COUNT(*) FROM rb_fc_preferenze_didattiche WHERE tipo_preferenza = 4 AND alunno = ".$row['id_alunno']);
	if ($p4 > 0) {
		$students[$row['id_alunno']]['preferenze'][] = 4;
	}
	$p5 = $db->executeCount("SELECT COUNT(*) FROM rb_fc_preferenze_alunni WHERE alunno = ".$row['id_alunno']);
	if ($p5 > 0) {
		$students[$row['id_alunno']]['preferenze'][] = 5;
	}
}
$n_std = $res_students->num_rows;

/* summary */
$sel_sex = "SELECT sesso, COUNT(sesso) AS count FROM rb_fc_alunni WHERE id_classe = ".$_REQUEST['id_classe']." GROUP BY sesso";
$res_sex = $db->executeQuery($sel_sex);
$male = $female = 0;
while($sx = $res_sex->fetch_assoc()){
	if($sx['sesso'] == 'M')
	$male = $sx['count'];
	else
	$female = $sx['count'];
}
$sel_rip = "SELECT COUNT(id_alunno) FROM rb_fc_alunni WHERE id_classe = ".$_REQUEST['id_classe']." AND ripetente = 1";
$ripetenti = $db->executeCount($sel_rip);
 
$sel_h = "SELECT H FROM rb_fc_alunni WHERE id_classe = ".$_REQUEST['id_classe']." AND H IS NOT NULL AND H <> 0";
$res_h = $db->executeQuery($sel_h);
$h = $dsa = 0;
while($al = $res_h->fetch_assoc()){
	if($al['H'] < 4)
	$dsa++;
	if($al['H'] > 1)
	$h++;
}

$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM rb_fc_alunni WHERE id_classe = ".$_REQUEST['id_classe'];
$avg = $db->executeCount($sel_avg);

/* class colors */
$sel_classes_from = "SELECT rb_fc_classi_provenienza.id_classe, rb_fc_classi_provenienza.id_scuola, CONCAT_WS(', ', rb_fc_scuole_provenienza.codice, rb_fc_classi_provenienza.descrizione) AS description ";
$sel_classes_from .= "FROM rb_fc_classi_provenienza, rb_fc_scuole_provenienza WHERE rb_fc_classi_provenienza.id_scuola = rb_fc_scuole_provenienza.id_scuola AND rb_fc_classi_provenienza.ordine_di_scuola = {$_SESSION['__school_order__']} ORDER BY rb_fc_scuole_provenienza.id_scuola, rb_fc_classi_provenienza.id_scuola";
$res_classes_from = $db->executeQuery($sel_classes_from);
$colors_from = array();
$x = 1;
while($class_from = $res_classes_from->fetch_assoc()){
	$colors_from[$class_from['id_classe']] = array("color" => $_SESSION['__colors__'][$x]['color'], "class" => $class_from['description']);
	$x++;
}

$sel_mv = "SELECT ROUND(AVG(voto), 2) FROM rb_fc_alunni WHERE ordine_di_scuola = {$_SESSION['__school_order__']}";
$mv = $db->executeCount($sel_mv);

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Classe $class_desc";

include "classe_prima.html.php";
