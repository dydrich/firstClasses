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
		var id_alunno = 0;

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.show_list').click(function(event){
				event.preventDefault();
				strs = this.id.split('_');
				id_alunno = strs[2];
				show_students();
			});
			$('.sprefs').click(function(event){
				event.preventDefault();
				strs = this.id.split('_');
				id_pref = strs[1];
				save_pref(id_pref, 6);
			});
		});

		var show_students = function (){
			$('#students_list').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 500
				},
				hide: {
					effect: "slide",
					duration: 300
				},
				buttons: [{
					text: "Chiudi",
					click: function() {
						$( this ).dialog( "close" );
					}
				}],
				modal: true,
				width: 450,
				title: 'Elenco alunni',
				open: function(event, ui){

				}
			});

			//$( "#dialog" ).dialog( "open" );
		}

		var save_pref = function(id_pref, action){
			$.ajax({
				type: "POST",
				url: "manage_student.php",
				data:  {id_alunno: id_alunno, pref: id_pref, action: action},
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
						if (action == 6){
							simple_text = document.createTextNode(", ");
							_a = document.createElement("A");
							_a.setAttribute("href", "#");
							a_text = document.createTextNode(json.name);
							_a.appendChild(a_text);
							if ($('#sp_pref_'+id_alunno).text() != ""){
								$('#sp_pref_'+id_alunno).append(simple_text);
							}
							$('#sp_pref_'+id_alunno).append(_a);
						}
						if (action == 7){
							document.location.reload();
						}
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
		<form id="my_form" style="border: 1px solid #aaaaaa; border-radius: 10px; margin-top: 20px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post">
			<table style="border-collapse: collapse; width: 90%; margin: 10px auto 20px auto">
				<tr style="font-weight: bold; height: 30px">
					<td style="width: 40%; border-bottom: 1px solid #cccccc">Cognome e nome</td>
					<td style="width: 60%; border-bottom: 1px solid #cccccc">Preferenze</td>
				</tr>
				<tbody>
			<?php
			foreach ($students as $id => $alunno){
			?>
				<tr>
					<td style="width: 40%; border-bottom: 1px solid rgba(211, 222, 199, 0.6);">
						<a href='#' class='show_list' id='show_list_<?php echo $id ?>'>
							<?php echo $alunno['cognome']." ".$alunno['nome'] ?></a>
					</td>
					<td style="width: 60%; border-bottom: 1px solid rgba(211, 222, 199, 0.6);" id="trpref_<?php echo $id ?>">
				<?php
				$str = "";
				foreach ($alunno['preferenze'] as $id_c => $comp){
					$str .= "<a href='#' onclick='id_alunno = {$id};save_pref({$id_c}, 7)'>{$comp}</a>, ";
				}
				$str = substr($str, 0, strlen($str) - 2);
				if (count($alunno['preferenze']) > 0){
					$disp = "inline";
				}
			?>
						<span id="sp_pref_<?php echo $id ?>"><?php echo $str ?></span>
					</td>
				</tr>
			<?php
		}
		?>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<!-- END CONTENT -->
			<input type="hidden" name="action" id="action" value="4" />
		</form>
	</div>
	<p class="spacer"></p>
</div>
<div id="students_list" style="display: none">
<?php
reset($students);
foreach ($students as $id => $alunno){
	?>
	<p style="line-height: 10px; height: 10px; font-size: 11px">
		<a href="#" class="sprefs" id="pref_<?php echo $id ?>"><?php echo $alunno['cognome']." ".$alunno['nome'] ?></a>
	</p>
<?php
}
?>
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
