<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime scuola secondaria</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" /><script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
	var _diagnose = function(sel){
		if(sel.value > 1)
			$('#tr_diag').hide();
		else
			$('#tr_diag').show();
	};


	var save = function(){
		if($('stid').value != 0)
			action = 1;
		else
			action = 2;

		$.ajax({
			type: "POST",
			url: "manage_student.php",
			data:  $('#my_form').serialize(true),
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
					window.setTimeout("document.location.href='students.php'", 2000);
				}
			}
		});
	};
	</script>
</head>
<body>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">
			Modifica alunno
		</div>
		<div id="not1" class="notification"></div>
		<form id="my_form" style="border: 1px solid #666666; border-radius: 10px; margin-top: 20px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post">
		<table style="width: 90%; border: 0; margin: 20px auto">
		<thead>
			<tr>
				<td style="width: 30%; font-weight: bold">Nome</td>
				<td style="width: 70%">
					<input type="text" name="fname" id="fname" value="<?php if($student) print $student['nome'] ?>" style="width: 90%" class="form_input" />
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold">Cognome</td>
				<td style="width: 70%">
					<input type="text" name="lname" id="lname" value="<?php if($student) print $student['cognome'] ?>" style="width: 90%" class="form_input" />
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold">Provenienza</td>
				<td style="width: 70%">
					<select name="from" id="from" style="width: 90%" class="form_input">
						<option value="0">.</option>
					<?php
					while($from = $res_classes_from->fetch_assoc()){
					?>
						<option <?php if($student && $student['classe_provenienza'] == $from['id_classe']) print "selected='selected'" ?>  value="<?php print $from['id_classe'] ?>"><?php print $from['description'] ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold">Sesso</td>
				<td style="width: 70%">
					<select name="sex" id="sex" style="width: 90%" class="form_input">
						<option value="all">.</option>
						<option <?php if($student && $student['sesso'] == "F") print "selected='selected'" ?> value="F">Femmina</option>
						<option <?php if($student && $student['sesso'] == "M") print "selected='selected'" ?> value="M">Maschio</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold">H e DSA</td>
				<td style="width: 70%">
					<select name="h" id="h" onchange="_diagnose(this)" style="width: 90%" class="form_input">
						<option <?php if($student && $student['H'] == "0") print "selected='selected'" ?> value="0">No</option>
						<option <?php if($student && $student['H'] == "1") print "selected='selected'" ?> value="1">DSA</option>
						<option <?php if($student && $student['H'] == "2") print "selected='selected'" ?> value="2">Sostegno non grave + DSA</option>
						<option <?php if($student && $student['H'] == "3") print "selected='selected'" ?> value="3">Sostegno grave + DSA</option>
						<option <?php if($student && $student['H'] == "4") print "selected='selected'" ?> value="4">Sostegno non grave</option>
						<option <?php if($student && $student['H'] == "5") print "selected='selected'" ?> value="5">Sostegno grave</option>
					</select>
				</td>
			</tr>
			<tr id="tr_diag" <?php if($student && $student['H'] < 2){ ?>style="display: none"<?php } ?>>
				<td style="width: 30%; font-weight: bold">Diagnosi H</td>
				<td style="width: 70%">
					<textarea id="diagnose" name="diagnose" style="width: 90%" class="form_input"><?php if($student && trim($student['diagnosi_h']) != "") print trim($student['diagnosi_h']) ?></textarea>
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold">Voto</td>
				<td style="width: 70%">
					<input type="text" name="grade" id="grade" style="width: 90%" class="form_input" value="<?php if (isset($student)) echo $student['voto'] ?>" />
				</td>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold">Note</td>
				<td style="width: 70%">
					<textarea id="note" name="note" style="width: 90%" class="form_input"><?php if($student && $student['note'] != "") trim(print $student['note']) ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: right; ">
					<input type="hidden" name="stid" id="stid" value="<?php print $_REQUEST['stid'] ?>" />
					<input type="hidden" name="action" id="action" value="1" />
				</td>
			</tr>
		</thead>
		</table>
		</form>
		<div style="width: 95%; text-align: right; margin-top: 20px">
			<a href="#" onclick="save()" class="standard_link">Salva le modifiche</a>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
</body>
</html>
