<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

$sel_classes = "SELECT rb_fc_classi.id_classe AS id,
	rb_fc_classi.descrizione AS descrizione,
	COUNT(rb_fc_alunni.id_alunno) AS alunni
	FROM rb_fc_classi LEFT JOIN rb_fc_alunni ON rb_fc_classi.id_classe = rb_fc_alunni.id_classe
	WHERE rb_fc_classi.ordine_di_scuola = {$_SESSION['__school_order__']}
	GROUP BY rb_fc_classi.id_classe, descrizione
	ORDER BY descrizione";

try{
	$res_classes = $db->executeQuery($sel_classes);
} catch(MySQLException $ex){
	$ex->redirect();
}
$n_cls = $res_classes->num_rows;

$sel_mv = "SELECT ROUND(AVG(voto), 2) FROM rb_fc_alunni WHERE ordine_di_scuola = {$_SESSION['__school_order__']}";
$mv = $db->executeCount($sel_mv);

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Classi prime";

include "classes.html.php";
