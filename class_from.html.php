<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" /><script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});

		var cls_desc;
		var upd_grade = function(sel, stid){
			var grade = sel.value;

			$.ajax({
				type: "POST",
				url: "upd_grade.php",
				data: {stid: stid, grade: grade, cl: <?php print $_REQUEST['class_id'] ?>},
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
						j_alert("error", "Errore SQL");
						return;
					}
					else {
						$('#avg').text(json.avg);
					}
				}
			});
		};

		var mod_class = function(){
			var new_desc = prompt("Inserisci il nome della classe");
			if (new_desc == "" || new_desc == null){
				return false;
			}
			cls_desc = new_desc;
			_upd_class(1);
		};

		var _upd_class = function(action){
			if(action == 3){
				if(!confirm("Sei sicuro di voler cancellare la classe? Dovrai poi assegnare gli studenti ad un'altra classe."))
					return false;
			}

			var cl = <?php print(isset($_REQUEST['class_id']) ? $_REQUEST['class_id'] : 0) ?>;
			$.ajax({
				type: "POST",
				url: "manage_classes_from.php",
				data: {action: action, class_id: cl, class_name: cls_desc},
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
						j_alert("error", "Errore SQL");
						return;
					}
					else {
						j_alert("alert", json.message);
						if(action == 1){
							$('#cls_d').text(cls_desc);
						}
						else if(action == 3){
							setTimeout(function(){
								document.location.href = "schools.php";
							}, 2000);
						}
					}
				}
			});
		};
	</script>
<body>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
			<?php print $sc['sc'] ?>:: classe <span id="cls_d"><?php print $sc['descrizione'] ?></span>
		</div>
		<div id="not1" class="notification"></div>
		<form id="my_form" style="border: 1px solid #aaaaaa; border-radius: 10px; margin-top: 20px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post">
	 	    <table style="border-collapse: collapse; width: 90%; margin: 10px auto">
	 	    <thead>
	 	    	<tr>
	 	    		<td colspan="4" style="text-align: center; font-weight: bold; border: 0">Riepilogo</td>
	 	    		<td></td>
	 	    		<td colspan="5" style="text-align: center; font-weight: bold; border: 0">Classi assegnate</td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 8%; border-top: 1px solid #cccccc; border-left: 1px solid #cccccc">Alunni: </td>
					<td id="nmb_st" style="width: 8%; border-top: 1px solid #cccccc"><?php print $n_std ?></td>
					<td style="width: 8%; border-top: 1px solid #cccccc">Maschi: </td>
					<td id="nmb_male" style="width: 8%; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc"><?php print $male ?></td>
					<td rowspan="2" style="width: 2%; text-align: center"></td>
					<?php 
					$index = 0;
					foreach($classes_and_colors as $color){
						if($index > 5)
							break;
						if($index == 0){
					?>
					<td style="width: 8%; text-align: center; border-top: 1px solid #cccccc; border-left: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else if($index == 5){
					?>
					<td style="width: 8%; text-align: center; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else{
					?>
					<td style="width: 8%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						$index++;
					}
					?>
						    	
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 8%; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc">Media: </td>
					<td id="avg" style="width: 8%; border-bottom: 1px solid #cccccc"><?php print $avg ?></td>
					<td style="width: 8%; border-bottom: 1px solid #cccccc">Femmine: </td>
					<td id="nmb_female" style="width: 8%; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc"><?php print $female ?></td>
					<?php 
					$ar = array_slice($classes_and_colors, 6);
					$index = 0;
					foreach($ar as $color){
						if($index > 5)
							break;
						if($index == 0){
					?>
					<td style="width: 8%; text-align: center; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-bottom: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else if($index == 5){
					?>
					<td style="width: 8%; text-align: center; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else{
					?>
					<td style="width: 8%; text-align: center; border-bottom: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-bottom: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						$index++;
					}
					?>  	
	 	    	</tr>
			</thead>
	 	    </table>
	 	    <p></p>
	 	    <table style="border-collapse: collapse; width: 90%; margin: 30px auto">
	 	    	<thead>
	 	    	<tr>
	 	    		<td colspan="6" style="text-align: right; padding: 0 20px 10px 0"><a href="class_from.php?class_id=<?php print $_REQUEST['class_id'] ?>" style="float: left">Ordina per cognome</a><span style="float: left">&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="class_from.php?class_id=<?php print $_REQUEST['class_id'] ?>&order=cls" style="float: left">Ordina per classe</a></td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold; height: 30px">
					<td style="width: 32%; border-bottom: 1px solid #cccccc">Cognome e nome</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">BES</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Sesso</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Voto</td>
					<td style="width: 35%; text-align: center; border-bottom: 1px solid #cccccc">Note</td>
					<td style="width: 3%; text-align: center; border-bottom: 1px solid #cccccc"></td>	 	    	
	 	    	</tr>
	 	    	</thead>
	 	    	<tbody>
	 	    	<?php
	 	    	while($st = $res_students->fetch_assoc()) {
	 	    		$ripetente = (isset($st['ripetente']) && $st['ripetente'] == 1) ? "SI" : "NO";
	 	    		$h = $dsa = $sost = "";
	 	    		if($st['H'] != 0){
	 	    			if($st['H'] < 4)
	 	    				$dsa = "DSA";
	 	    			if($st['H'] == 2 || $st['H'] == 4)
	 	    				$h = "H";
	 	    			else if($st['H'] == 3 || $st['H'] == 5)
	 	    				$h = "<span style='color: red; font-weight: bold'>H</span>";
	 	    		}
	 	    		if($h != ""){
	 	    			$sost = $h;
	 	    			if($dsa != ""){
	 	    				if($h != "")
	 	    					$sost .= " / $dsa";
	 	    			}
	 	    		}
	 	    		else if($dsa != "")
	 	    			$sost = $dsa;
	 	    	?>
	 	    	<tr id="tr<?php print $st['id_alunno'] ?>" style="<?php if($st['school'] != "5") print("background-color: #".$classes_and_colors[$st['id_classe']]['color']) ?>">
					<td style="width: 32%; border-bottom: 1px solid #cccccc;"><?php print $st['name'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $sost ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['sesso'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;">
						<input type="text" name="grade" id="grade" style="width: 40px; " class="form_input _right" value="<?php echo $st['voto'] ?>" onchange="upd_grade(this, <?php print $st['id_alunno'] ?>)" />
					</td>
					<td style="width: 35%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['note'] ?></td>
					<td style="width: 3%; font-weight: bold; text-align: center; border-bottom: 1px solid #cccccc;"><a style="color: red; font-weight: bold" href="#" onclick="upd_cls(<?php print $st['id_alunno'] ?>)">x</a></td>	 	    	
	 	    	</tr>
	 	    	<?php } ?>
	 	    	</tbody>
	 	    	<tfoot>
	 	    	</tfoot>
	 	    </table>
			<!-- END CONTENT -->
			</form>
		<div style="width: 95%; text-align: right; margin-top: 20px">
			<a href="#" onclick="mod_class()" class="material_link nav_link_first">Modifica classe</a>
			<a href="#" onclick="_upd_class(3)" class="material_link nav_link_last">Cancella classe</a>
		</div>
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
