<?php

include "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$data = array();

$sel_sex = "SELECT sesso, COUNT(sesso) AS count FROM rb_fc_alunni WHERE id_classe = ".$_REQUEST['cl']." GROUP BY sesso";
$res_sex = $db->executeQuery($sel_sex);
$male = $female = 0;
while($sx = $res_sex->fetch_assoc()){
	if($sx['sesso'] == 'M'){
		$male = $sx['count'];
	}
	else{
		$female = $sx['count'];
	}
}
$data['male'] = $male;
$data['female'] = $female;

$sel_rip = "SELECT COUNT(id_alunno) FROM rb_fc_alunni WHERE id_classe = ".$_REQUEST['cl']." AND ripetente = 1";
$ripetenti = $db->executeCount($sel_rip);
$data['rip'] = $ripetenti;

$sel_h = "SELECT H FROM rb_fc_alunni WHERE id_classe = ".$_REQUEST['cl']." AND H IS NOT NULL AND H <> 0";
$res_h = $db->executeQuery($sel_h);
$h = $dsa = $bes = 0;;
while($al = $res_h->fetch_assoc()){
	switch ($al['H']){
		case 1:
			$dsa++;
			break;
		case 2:
			$dsa++;
			$h++;
			break;
		case 3:
			$dsa++;
			$h++;
			break;
		case 4:
			$h++;
			break;
		case 5:
			$h++;
			break;
		case 6:
			$bes++;
			break;
	}
}
$data['H'] = $h;
$data['dsa'] = $dsa;
$data['sos'] = $res_h->num_rows;

$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM rb_fc_alunni WHERE id_classe = ".$_REQUEST['cl'];
$data['avg'] = $db->executeCount($sel_avg);

$response['data'] = $data;
echo json_encode($response);
exit;