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
	var associa = function(){
		$.ajax({
			type: "POST",
			url: "class_manager.php",
			data: {action: 3},
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
				}
			}
		});
	};

	var termina = function(){
		$.ajax({
			type: "POST",
			url: "manage_student.php",
			data: {action: 8},
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
			Gestione nuove classi prime
		</div>
		<div id="not1" class="notification"></div>
		<div class="welcome">
			<p id="w_head">Classi</p>
			<p class="w_text" style="width: 350px">
				<?php if($n_cls < 1){ ?>
					- Non hai ancora inserito nessuna classe.
				<?php
				}
				else{
					print "Hai inserito $n_cls classi";
				}
				?>
				<p><a href="#" onclick="associa()">Associa le classi</a></p>
			</p>
		</div>
		<div class="welcome">
			<p id="w_head">Alunni</p>
			<?php if($n_std < 1){ ?>
				- Non hai ancora inserito nessun alunno.
			<?php
			}
			else{
				print "Sono presenti $n_std alunni<br />$not_assigned alunni non sono ancora stati assegnati ad una classe";
			}
			?>
				<p><a href="#" onclick="termina()">Importa i dati</a></p>
			</p>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
</body>
</html>
