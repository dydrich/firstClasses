<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$sel_classes_from = "SELECT id_classe, CONCAT_WS(', ', rb_fc_scuole_provenienza.descrizione, rb_fc_classi_provenienza.descrizione) AS description FROM rb_fc_classi_provenienza, rb_fc_scuole_provenienza WHERE rb_fc_classi_provenienza.id_scuola = rb_fc_scuole_provenienza.id_scuola ORDER BY rb_fc_scuole_provenienza.id_scuola, rb_fc_classi_provenienza.descrizione ";
$res_classes_from = $db->executeQuery($sel_classes_from);

include "insert_students.html.php";