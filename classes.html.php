<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});

	var _classes = function(){
		var _cls = prompt("Inserisci le sezioni che vuoi creare, separate da una virgola");

		if(_cls == "" || _cls == null){
			alert("Non hai inserito nessuna sezione");
			return false;
		}
		$.ajax({
			type: "POST",
			url: "class_manager.php",
			data: {action: 1, id: 0, name: _cls},
			dataType: 'json',
			error: function(data, status, errore) {
				j_alert("error", "Si e' verificato un errore");
				return false;
			},
			succes: function(result) {
				alert("ok");
			},
			complete: function(data, status){
				r = data.responseText;
				var json = $.parseJSON(r);
				if(json.status == "kosql"){
					j_alert("error", "Errore SQL. ");
					return;
				}
				else {
					j_alert("alert", json.message);
					setTimeout(function(){
						document.location.href = "classi.php";
					}, 2000);
				}
			}
		});
	};

	var del_cls = function(id){
		if(!confirm("Sei sicuro di voler cancellare questa classe?")){
			return false;
		}

		$.ajax({
			type: "POST",
			url: "class_manager.php",
			data: {action: 2, id: id, name: ""},
			dataType: 'json',
			error: function(data, status, errore) {
				j_alert("error", "Si e' verificato un errore");
				return false;
			},
			succes: function(result) {
				j_alert("alert", "ok");
			},
			complete: function(data, status){
				r = data.responseText;
				var json = $.parseJSON(r);
				if(json.status == "kosql"){
					j_alert("error", "Errore SQL. ");
					return;
				}
				else {
					j_alert("alert", json.message);
					$('#tr'+json.id).hide();
				}
			}
		});
	};

	var associa = function(){
		$.ajax({
			type: "POST",
			url: "class_manager.php",
			data: {action: 3},
			dataType: 'json',
			error: function(data, status, errore) {
				j_alert("error", "Si e' verificato un errore");
				return false;
			},
			succes: function(result) {
				alert("ok");
			},
			complete: function(data, status){
				r = data.responseText;
				var json = $.parseJSON(r);
				if(json.status == "kosql"){
					j_alert("error", "Errore SQL. ");
					return;
				}
				else {
					j_alert("alert", json.message);
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
		<form id="my_form" style="margin-top: 20px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post">
	 	    <?php if($n_cls < 1){ ?>
	 	    <p style="margin-top: 20px; margin-bottom: 50px" class="_bold _center">Non hai ancora inserito nessuna classe.</p>
	 	    <div style="width: 90%; text-align: right">
		        <a href="#" onclick="_classes()" class="material_link">Inserisci classi</a>
		        <input type="hidden" name="cls" id="cls" />
	 	    </div>
	 	    <?php
	        } else{
	 	    ?>	
	 	    <table style="border-collapse: collapse; width: 95%; margin: 30px auto 10px auto">
	 	    	<thead>
	 	    	<tr style="font-weight: bold; height: 30px">
					<td style="width: 13%; border-bottom: 1px solid #cccccc">Classe</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Alunni</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Ripetenti</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Maschi</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Femmine</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">H / DSA</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Media</td>
					<td style="width: 9%; text-align: center; border-bottom: 1px solid #cccccc">Elimina</td>	 	    	
	 	    	</tr>
	 	    	</thead>
	 	    	<tbody>
	 	    	<?php
	 	    	while($cl = $res_classes->fetch_assoc()) {
	 	    		$sel_sex = "SELECT sesso, COUNT(sesso) AS count FROM rb_fc_alunni WHERE id_classe = ".$cl['id']." GROUP BY sesso";
	 	    		$res_sex = $db->executeQuery($sel_sex);
	 	    		$male = $female = 0;
	 	    		while($sx = $res_sex->fetch_assoc()){
						if($sx['sesso'] == 'M')
							$male = $sx['count'];
						else
							$female = $sx['count'];
	 	    		}
	 	    		$sel_rip = "SELECT COUNT(id_alunno) FROM rb_fc_alunni WHERE id_classe = ".$cl['id']." AND ripetente = 1";
	 	    		$ripetenti = $db->executeCount($sel_rip); 
	 	    		
	 	    		$sel_h = "SELECT H FROM rb_fc_alunni WHERE id_classe = ".$cl['id']." AND H IS NOT NULL AND H <> 0";
	 	    		$res_h = $db->executeQuery($sel_h);
	 	    		$h = $dsa = 0;
	 	    		while($al = $res_h->fetch_assoc()){
	 	    			if($al['H'] < 4)
	 	    				$dsa++;
	 	    			if($al['H'] > 1)
	 	    				$h++;	
	 	    		}
	 	    		$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM rb_fc_alunni WHERE id_classe = ".$cl['id'];
	 	    		$avg = $db->executeCount($sel_avg);
	 	    	?>
	 	    	<tr id="tr<?php print $cl['id'] ?>">
					<td style="width: 13%; border-bottom: 1px solid #cccccc"><a href="classe_prima.php?id_classe=<?php print $cl['id'] ?>"><?php print $cl['descrizione'] ?></a></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $cl['alunni'] ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $ripetenti ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $male ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $female ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print ($h." / ".$dsa." (".($res_h->num_rows).")") ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $avg ?></td>
					<td style="width: 9%; font-weight: bold; text-align: center; border-bottom: 1px solid #cccccc"><a style="color: red; font-weight: bold" href="#" onclick="del_cls(<?php print $cl['id'] ?>)">x</a></td>	 	    	
	 	    	</tr>
	 	    	<?php } ?>
	 	    	</tbody>
	 	    	<tfoot>
	 	   		<tr>
	 				<td colspan="8" style="text-align: right; margin-right: 10px; padding-top: 30px">
					    <a href="#" class="material_link nav_link_first" onclick="associa()">Associa classi</a>
						<a href="#" class="material_link nav_link_last" onclick="_classes()">Aggiungi classi</a>
						<input type="hidden" name="cls" id="cls" /> 				
	 				</td>    		
	 	   		</tr>
	 	    	</tfoot>
	 	    </table>
	 	    <?php } ?>

			<!-- END CONTENT -->
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../intranet/<?php echo $_SESSION['__mod_area__'] ?>/index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../../intranet/<?php echo $_SESSION['__mod_area__'] ?>/profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<div class="drawer_link"><a href="../../intranet/<?php echo $_SESSION['__mod_area__'] ?>/utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
