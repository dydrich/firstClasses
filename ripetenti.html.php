<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime scuola secondaria</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" /><script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var check_val = function(idc){
			if ($('#cl_'+idc).prop('checked')){
				$('input:checkbox.ck'+idc).prop('checked', true);
			}
			else {
				$('input:checkbox.ck'+idc).prop('checked', false);
			}
		};

		var import_students = function(){
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
			Alunni ripetenti
		</div>
		<div id="not1" class="notification"></div>
		<form id="my_form" style="border: 1px solid #aaaaaa; border-radius: 10px; margin-top: 20px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post">
			<table style="border-collapse: collapse; width: 90%; margin: 10px auto 20px auto">
				<tbody>
				<?php
				foreach ($students as $idc => $class) {
					?>
					<tr style="font-weight: bold; height: 30px" class="manager_row">
						<td colspan="4" class="_bold _center" style="padding-top: 20px">Classe 1, sezione <?php echo $class['sezione'] ?></td>
					</tr>
					<tr style="font-weight: bold; height: 30px">
						<td style="width: 40%; border-bottom: 1px solid #cccccc">Cognome</td>
						<td style="width: 40%; border-bottom: 1px solid #cccccc">Nome</td>
						<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Voto</td>
						<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">
							<input type="checkbox" name="cl_<?php echo $idc ?>" id="cl_<?php echo $idc ?>" onchange="check_val(<?php echo $idc ?>)" value="1" />
						</td>
					</tr>
					<?php
					foreach ($class['alunni'] as $id => $alunno){
						?>
						<tr class="bottom_decoration">
							<td style="width: 40%"><?php echo $alunno['cognome'] ?></td>
							<td style="width: 40%"><?php echo $alunno['nome'] ?></td>
							<td style="width: 10%; text-align: center"><?php echo $alunno['voto'] ?></td>
							<td style="width: 10%; text-align: center">
								<input type="checkbox" name="sts[]" id="st_<?php echo $id ?>" class="ck<?php echo $idc ?>" value="<?php echo $id ?>" />
							</td>
						</tr>
					<?php
					}
				}
				?>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<!-- END CONTENT -->
			<input type="hidden" name="action" id="action" value="5" />
		</form>
		<div style="width: 95%; margin-top: 20px" class="_right">
			<a href="#" onclick="import_students()" class="standard_link">Importa alunni</a>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
</body>
</html>
