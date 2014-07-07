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
	var win;

	var add_class = function(school){
		win = new Window({className: "mac_os_x", width:200, height:null, zIndex: 100, resizable: true, title: "Nuova classe", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
		win.getContent().update("<table style='width: 95%; margin: auto; padding-top: 20px;'><tr><td style='width: 40%; font-weight: bold'>Classe</td><td style='width: 60%'><input type='text' style='width: 90%; border: 1px solid #dddddd; font-size: 11px' name='nome' id='nome' /></tr><tr><td colspan='2' style='padding-top: 20px; text-align: right; padding-right: 5%'><a href='#' onclick='_upd_class("+school+")'>Salva</a></td></tr></table>");
		win.showCenter(false);
	};

	var _upd_class = function(school){
		var name = $('nome').value;
		var req = new Ajax.Request('manage_classes_from.php',
				  {
				        method:'post',
				        parameters: {action: '2', school_id: school, class_name: name},
				        onSuccess: function(transport){
				            var response = transport.responseText || "no response text";
				            //alert(response);
				            var dati = response.split("#");
			                if(dati[0] == "ko"){
								alert("Errore nell'inserimento: "+dati[1]);
								return false;
			                }
			                var lnk2 = document.createElement("a");
							lnk2.setAttribute("href", "class_from.php?class_id="+dati[1]);
							lnk2.setAttribute("style", "text-decoration: none; font-weight: bold;");
							lnk2.appendChild(document.createTextNode(name));
							$('sp_'+school).appendChild(lnk2);
							win.close();
				        },
				        onFailure: function(){ alert("Si e' verificato un errore..."); }
				  });
	};
	</script>
	<style>
	td {border: 0}
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
			Elenco scuole e classi di provenienza
		</div>
		<div id="not1" class="notification"></div>
		<div style="width: 85%; margin: auto">
            <table id="sc_table" style="margin-top: 20px; width: 90%">
            <?php
            $x = 0;
            while(list($k, $school) = each($schools)){
            	list($desc, $code) = explode("#", $school[0]);
            ?>
            <tr id="tr<?php print $k ?>" style="border-bottom: 1px solid rgba(211, 222, 199, 0.6);">
            	<td style="width: 50%">
            		<a id="sc<?php print $k ?>" href='dettaglio_scuola.php?id=<?php print $k ?>' style='font-size: 14px; font-weight: normal; margin-left: 0px; text-decoration: none'>
					<?php print $desc ?>
					</a>
				</td>
				<td style="width: 50%" id="tr_2_<?php print $k ?>">
				<span id="sp_<?php print $k ?>">
			<?php
				foreach($school[1] as $s){
			?>
            	<a href="class_from.php?class_id=<?php print $s['class_id'] ?>" style="text-decoration: none; font-weight: bold;"><?php print $s['class'] ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php 
            	}
            ?>
            	</span>
            	(<a href="#" id="last<?php print $k ?>" onclick="add_class(<?php print $k ?>)" style="text-decoration: none; font-weight: bold;">+</a>)&nbsp;&nbsp;&nbsp;&nbsp;
            	</td>
            </tr>
            <?php
            	$x++;
            }
            ?>
            </table>
		</div>
		<div style="width: 85%; margin: 40px auto 0 auto; text-align: right">
		<a href="dettaglio_scuola.php?id=0" class="standard_link nav_link">Nuova scuola</a>
		</div>
    </div>
	<div class="clear"></div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
</body>
</html>