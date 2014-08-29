<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime scuola secondaria</title>
	<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="theme/style.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" /><script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
	var save = function(_continue){
		$.ajax({
			type: "POST",
			url: "manage_student.php?action=2",
			data: $('#my_form').serialize(true),
			dataType: 'json',
			error: function(data, status, errore) {
				alert("Si e' verificato un errore");
				return false;
			},
			succes: function(result) {
				alert("ok");
			},
			complete: function(data, status){
				r = data.responseText;
				var json = $.parseJSON(r);
				if(json.status == "kosql"){
					alert("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
					return;
				}
				else {
					$('#not1').text(json.message);
					$('#not1').show(1000);
					window.setTimeout("$('#not1').hide(1000)", 2000);
					if(!_continue)
						document.location.href = "students.php";
					else{
						$('#fname').text("");
						$('#lname').text("");
						$('#from').selectedIndex = 0;
						$('#sex').selectedIndex = 0;
						$('#h').selectedIndex = 0;
						$('#diagnose').text("");
						$('#tr_diag').style.display = "none";
						$('#grade').selectedIndex = 0;
						$('#note').text("");
						$('#note').val("");
						$('#fname').focus();
					}
				}
			}
		});
	};
	</script>
</head>
<body onload="$('fname').focus()">
<?php include "../../intranet/{$_SESSION['__mod_area__']}/header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">
			Inserimento studenti
		</div>
		<div id="not1" class="notification"></div>
		<form id="my_form" style="border: 1px solid #666666; border-radius: 10px; margin-top: 20px; text-align: left; width: 80%; margin-left: auto; margin-right: auto">
		<table style="width: 85%; border: 0; margin: 20px auto 20px auto">
		<thead>
			<tr>
				<td style="width: 30%; font-weight: bold; font-size: 12px">Nome</td>
				<td style="width: 70%">
					<input type="text" name="fname" id="fname" style="width: 320px" class="form_input" />
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold; font-size: 12px">Cognome</td>
				<td style="width: 70%">
					<input type="text" name="lname" id="lname" style="width: 320px" class="form_input" />
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold; font-size: 12px">Provenienza</td>
				<td style="width: 70%">
					<select name="from" id="from" style="width: 320px"  class="form_input">
						<option value="0">.</option>
					<?php
					while($from = $res_classes_from->fetch_assoc()){
					?>
						<option value="<?php print $from['id_classe'] ?>"><?php print $from['description'] ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold; font-size: 12px">Sesso</td>
				<td style="width: 70%">
					<select name="sex" id="sex" style="width: 320px" class="form_input">
						<option value="all">.</option>
						<option value="F">Femmina</option>
						<option value="M">Maschio</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold; font-size: 12px">H e DSA</td>
				<td style="width: 70%">
					<select name="h" id="h" onchange="_diagnose(this)" style="width: 320px" class="form_input">
						<option value="0">No</option>
						<option value="1">DSA</option>
						<option value="2">Sostegno non grave + DSA</option>
						<option value="3">Sostegno grave + DSA</option>
						<option value="4">Sostegno non grave</option>
						<option value="5">Sostegno grave</option>
						<option value="6">BES</option>
					</select>
				</td>
			</tr>
			<tr id="tr_diag" style="display: none; font-size: 12px">
				<td style="width: 30%; font-weight: bold">Diagnosi H</td>
				<td style="width: 70%">
					<textarea id="diagnose" name="diagnose" style="width: 320px" class="form_input"></textarea>
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold; font-size: 12px">Voto</td>
				<td style="width: 70%">
					<select name="grade" id="grade" style="width: 320px" class="form_input">
						<option value="0">.</option>
					<?php
					for($i = 4; $i < 11; $i++){
					?>
						<option value="<?php print $i ?>"><?php print $i ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold; font-size: 12px">Note</td>
				<td style="width: 70%">
					<textarea id="note" name="note" style="width: 320px" class="form_input"></textarea>
				</td>
			</tr>

		</thead>
		</table>
		</form>
        <div style="width: 90%; text-align: right; margin-top: 20px">
	        <a href="#" class="standard_link nav_link_first" onclick="save(false)">Salva ed esci</a>
	        |<a href="#" class="standard_link nav_link_last" onclick="save(true)">Salva e continua</a>
        </div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
</body>
</html>
