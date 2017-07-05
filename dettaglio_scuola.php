<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 07/07/14
 * Time: 19.33
 */

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

if ($_REQUEST['id'] != 0){
	$sel_sc = "SELECT * FROM rb_fc_scuole_provenienza WHERE id_scuola = {$_REQUEST['id']}";
	$res_sc = $db->executeQuery($sel_sc);
	$school = $res_sc->fetch_assoc();
	$classes_count = $db->executeCount("SELECT COUNT(*) FROM rb_fc_classi_provenienza WHERE id_scuola = {$_REQUEST['id']}");
	$action = 1;
}
else {
	$classes_count = 0;
	$action = 2;
}

$navigation_label = "";
if ($_SESSION['__school_order__'] ==1) {
	$navigation_label = "scuola secondaria";
}
else {
	$navigation_label = "scuola primaria";
}
$drawer_label = "Scuola di provenienza";

include "dettaglio_scuola.html.php";
