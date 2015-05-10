<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime scuola secondaria</title>
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

		var upd_school = function(action, id, classes_count){

			if(action == 3 && classes_count > 0){
				alert("Impossibile cancellare la scuola: sono presenti delle classi. Cancellare prima tutte le classi associate alla scuola"+classes_count);
				return false;
			}
			var code = $('#code').val();
			var name = $('#nome').val();
			var comp = ($('#is_sc').prop('checked')) ? 1 : 0;
			$.ajax({
				type: "POST",
				url: "manage_schools_from.php",
				data: {action: action, class_id: id, class_name: name, class_code: code, is_sc: comp},
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
						j_alert("error", "Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
						return;
					}
					else {
						j_alert("alert", json.message);
						if (action == 3){
							window.setTimeout("document.location.href='schools.php'", 3000);
						}
					}
				}
			});
		};
	</script>
	<style>

	</style>
</head>
<body>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<form id="my_form" style="margin-top: 20px; text-align: left; width: 80%; margin-left: auto; margin-right: auto">
		<table style='width: 95%; margin: 20px auto 10px auto; padding-top: 20px;'>
			<tr>
				<td style='width: 40%; font-weight: bold'>Nome</td>
				<td style='width: 60%'>
					<input type='text' class="form_input" style='width: 90%;' name='nome' id='nome' value='<?php if (isset($school)) echo $school['descrizione'] ?>' />
				</td>
			</tr>
			<tr>
				<td style='width: 40%; font-weight: bold'>Codice (max 3 lett)</td>
				<td style='width: 60%'>
					<input type='text' class="form_input" style='width: 90%' name='code' id='code' value='<?php if (isset($school)) echo $school['codice'] ?>' />
				</td>
			</tr>
			<tr>
				<td style='width: 40%; font-weight: bold'><?php if ($_SESSION['__school_order__'] == 1) echo "Primaria"; else echo "Infanzia" ?>  comprensivo</td>
				<td style='width: 60%'>
					<input type='checkbox' class="form_input" name='is_sc' id='is_sc' value='1' <?php if (isset($school) && $school['comprensivo'] == 1) echo "checked" ?> />
				</td>
			</tr>
			<tr>
				<td colspan='2' style='padding-top: 20px; text-align: right; padding-right: 5%'>
					<a href='#' class="material_link nav_link_first" onclick='upd_school(<?php echo $action ?>, <?php echo $_REQUEST['id'] ?>, <?php echo $classes_count ?>)'>Salva</a>
					<?php if ($_REQUEST['id'] != 0): ?>
					<a href='#' class="material_link nav_link_last" id='del_h' onclick='upd_school(3, <?php echo $_REQUEST['id'] ?>, <?php echo $classes_count ?>)'>Elimina</a>
					<?php endif; ?>
				</td>
			</tr>
		</table>
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
