<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime scuola secondaria</title>
	<link rel="stylesheet" href="../../intranet/teachers/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="theme/style.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">



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
			</p>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
</body>
</html>