<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime scuola secondaria</title>
	<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="theme/style.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
	var _diagnose = function(sel){
		if(sel.value > 1)
			$('tr_diag').style.display = "";
		else
			$('tr_diag').style.display = "none";
	};

	var _close = function(){
		//parent.document.location.href = "students.php";
		parent.win.close();
	};

	var save = function(){
		if($('stid').value != 0)
			action = 1;
		else
			action = 2;
		var req = new Ajax.Request('manage_student.php?action='+action,
				  {
				        method:'post',
				        asynchronous: false,
				        parameters: $('_form').serialize(true),
				        onSuccess: function(transport){
				            var response = transport.responseText || "no response text";
				            //alert(response);
				            var dati = response.split("|");
			                if(dati[0] == "ko"){
								alert(dati[1]+"##"+dati[2]);
								return false;
			                }
			                //alert("Aggiornamento terminato");
			                parent.win.close();
			                parent.document.location.href = "students.php?q=<?php print $_REQUEST['q'] ?>&order=<?php print $_REQUEST['order'] ?>";
				        },
				        onFailure: function(){ alert("Si e' verificato un errore..."); }
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
		<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
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
					<select name="grade" id="grade" style="width: 90%" class="form_input">
						<option value="0">.</option>
					<?php
					for($i = 4; $i < 11; $i++){
					?>
						<option  <?php if($student && $student['voto'] == $i) print "selected='selected'" ?> value="<?php print $i ?>"><?php print $i ?></option>
					<?php } ?>
					</select>
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
				</td>
			</tr>
		</thead>
		</table>
		</form>
		<div style="width: 95%; text-align: right; margin-top: 20px">
			<a href="#" onclick="save()" class="standard_link nav_link_first">Salva le modifiche</a>
			|<a href="#" onclick="_close()" class="standard_link nav_link_last">Chiudi</a>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
</body>
</html>