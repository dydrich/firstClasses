<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Classi prime scuola secondaria</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" /><script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
        $(function(){
            load_jalert();
            setOverlayEvent();
        });
	</script>
	<style>
		TR {
			height: 25px;
		}
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
		<form id="my_form" style="border: 1px solid #aaaaaa; border-radius: 10px; margin-top: 20px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post">
			<table style="border-collapse: collapse; width: 90%; margin: 10px auto 20px auto">
				<tr style="font-weight: bold; height: 30px" class="accent_decoration">
					<td style="width: 40%">Cognome e nome</td>
					<td style="width: 60%" class="_center">Preferenze</td>
				</tr>
				<tbody>
				<?php
				foreach ($students as $id => $alunno){
					?>
					<tr class="bottom_decoration">
						<td style="width: 40%">
							<a href='scelta_preferenze.php?stid=<?php echo $id ?>' class='show_list' id='show_list_<?php echo $id ?>'>
								<?php echo $alunno['cognome']." ".$alunno['nome'] ?></a>
						</td>
						<td style="width: 60%" id="trpref_<?php echo $id ?>">
							<span id="sect_<?php echo $id ?>"><?php if (isset($alunno['preferenze'][2])) echo "Corso ".$alunno['preferenze'][2].". " ?></span>
                            <span id="teac_<?php echo $id ?>"><?php if (isset($alunno['preferenze'][1])) echo "Docenti: ".implode(", ", $alunno['preferenze'][1]).". " ?></span>
                            <span id="othe_<?php echo $id ?>"><?php if (isset($alunno['preferenze'][3])) echo "Altre richieste: ".implode(", ", $alunno['preferenze'][3])."." ?></span>
                            <span id="othe_<?php echo $id ?>"><?php if (isset($alunno['preferenze'][4])) echo "Note dei docenti: ".implode(", ", $alunno['preferenze'][4]) ?></span>
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
<div id="teachers_list" style="display: none">
	<?php
	foreach ($teacher as $id => $teacher){
		?>
		<p style="line-height: 10px; height: 10px; font-size: 11px">
			<a href="#" class="sprefs" id="pref_<?php echo $id ?>"><?php echo $teacher['cognome']." ".$teacher['nome'] ?></a>
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
