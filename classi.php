<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

if(isset($_REQUEST['update'])){
	//print $_REQUEST['cls'];
	$cls = explode(",", $_REQUEST['cls']);
	$index = 0;
	foreach($cls as $a){
		$desc = strtoupper("1".trim($a));
		$ins = "INSERT INTO rb_fc_classi (descrizione) VALUE ('$desc')";
		try{
			$res = $db->executeUpdate($ins);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		/*
		 * class - color association
		 * stored in session
		 */
		
	}
}
	
$sel_classes = "SELECT rb_fc_classi.id_classe AS id, rb_fc_classi.descrizione AS descrizione, COUNT(rb_fc_alunni.id_alunno) AS alunni FROM rb_fc_classi LEFT JOIN rb_fc_alunni ON rb_fc_classi.id_classe = rb_fc_alunni.id_classe GROUP BY rb_fc_classi.id_classe, descrizione ORDER BY descrizione";
try{
	$res_classes = $db->executeQuery($sel_classes);
} catch(MySQLException $ex){
	$ex->redirect();
}
$n_cls = $res_classes->num_rows;

$sel_mv = "SELECT ROUND(AVG(voto), 2) FROM rb_fc_alunni";
$mv = $db->executeCount($sel_mv);

$navigation_label = "registro elettronico ";
$drawer_label = "Classi prime";

include "classes.html.php";
