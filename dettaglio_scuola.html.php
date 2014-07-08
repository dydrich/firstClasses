<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime scuola secondaria</title>
	<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="theme/style.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
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
		<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
			Scuola di provenienza
		</div>
		<div id="not1" class="notification"></div>
		<form id="my_form" style="border: 1px solid #666666; border-radius: 10px; margin-top: 20px; text-align: left; width: 80%; margin-left: auto; margin-right: auto">
		<table style='width: 95%; margin: 20px auto 20px auto; padding-top: 20px;'>
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
				<td style='width: 40%; font-weight: bold'>Primaria comprensivo</td>
				<td style='width: 60%'>
					<input type='checkbox' class="form_input" name='is_sc' id='is_sc' value='1' <?php if (isset($school) && $school['comprensivo'] == 1) echo "checked" ?> />
				</td>
			</tr>
			<tr>
				<td colspan='2' style='padding-top: 20px; text-align: right; padding-right: 5%'>
					<a href='#' class="standard_link nav_link_first" onclick='upd_school(<?php echo $action ?>, <?php echo $_REQUEST['id'] ?>, <?php echo $classes_count ?>)'>Salva</a>
					<?php if ($_REQUEST['id'] != 0): ?>
					|<a href='#' class="standard_link nav_link_last" id='del_h' onclick='upd_school(3, <?php echo $_REQUEST['id'] ?>, <?php echo $classes_count ?>)'>Elimina</a>
					<?php endif; ?>
				</td>
			</tr>
		</table>
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
</body>
</html>