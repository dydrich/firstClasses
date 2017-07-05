<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

$query_params = "";
$order = "cognome, nome";
$req_order = "";
if(isset($_REQUEST['order'])){
	$req_order = $_REQUEST['order'];
	switch ($_REQUEST['order']){
		case "from":
			$order = "rb_fc_alunni.classe_provenienza, cognome, nome";
			break;
		case "rip":
			$order = "ripetente DESC, cognome, nome";
			break;
		case "sex":
			$order = "sesso, cognome, nome";
			break;
		case "grade":
			$order = "voto DESC, cognome, nome";
			break;
		case "cls":
			$order = "fc_alunni.id_classe, cognome, nome";
			break;
		case "h":
			$order = "H DESC, cognome, nome";
			break;
		default:
			$order = "cognome, nome";
		break;
	}
}
$q = "";
if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
	switch($_REQUEST['q']){
		case "assigned":
			$query_params = "AND rb_fc_alunni.id_classe IS NOT NULL";
			break;
		case "not_assigned":
			$query_params = "AND rb_fc_alunni.id_classe IS NULL";
			break;
		default:
			$query_params = "";
			break;
	}
}
	
$sel_students = "SELECT id_alunno,
				 CONCAT_WS(' ', cognome, nome) AS name,
				 ripetente, H, sesso, voto,
				 CONCAT_WS('. ', diagnosi_h, note) AS note,
				 rb_fc_scuole_provenienza.id_scuola AS school,
				 rb_fc_alunni.id_classe,
				 classe_provenienza,
				 CONCAT_WS(', ', rb_fc_scuole_provenienza.codice, rb_fc_classi_provenienza.descrizione) AS class_from
				 FROM rb_fc_alunni, rb_fc_classi_provenienza, rb_fc_scuole_provenienza
				 WHERE rb_fc_alunni.ordine_di_scuola = {$_SESSION['__school_order__']}
				 AND rb_fc_alunni.classe_provenienza = rb_fc_classi_provenienza.id_classe
				 AND rb_fc_classi_provenienza.id_scuola = rb_fc_scuole_provenienza.id_scuola $query_params
				 ORDER BY $order";

try{
	$res_students = $db->executeQuery($sel_students);
} catch(MySQLException $ex){
	$ex->redirect();
}
$n_std = $res_students->num_rows;

/*
 * let's match colors and classes
 */
$sel_cls = "SELECT * FROM rb_fc_classi WHERE ordine_di_scuola = {$_SESSION['__school_order__']} ORDER BY descrizione";
$res_cls = $db->executeQuery($sel_cls);
$classes_and_colors = array();
$x = 1;
while($cls = $res_cls->fetch_assoc()){
	$classes_and_colors[$cls['id_classe']] = array("id" => $cls['id_classe'], "name" => $cls['descrizione'], "color" =>$_SESSION['__colors__'][$x]['color']);
	$x++;
}

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Elenco alunni (estratti ". $n_std ." studenti)";

include "students.html.php";
