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
	var win;

	var add_class = function(school){
		var cls = prompt("Inserisci le classi (nel formato 5A), separate da una virgola");
		if (cls == ""  || cls == null){
			return false;
		}
		_upd_class(school, cls);
	};

	var import_class = function(school){
		$.ajax({
			type: "POST",
			url: "manage_classes_from.php",
			data: {action: '4', school_id: school},
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
					var _cls = json.classi;
					var max = json.max;
					var _arr_cls = _cls.split(",");
					if (_arr_cls.length == 1){
						var lnk2 = document.createElement("a");
						lnk2.setAttribute("href", "class_from.php?class_id="+max);
						lnk2.setAttribute("style", "text-decoration: none; font-weight: bold;");
						lnk2.appendChild(document.createTextNode(""+_cls));
						$('#sp_'+school).append(lnk2);
					}
					else {
						start = max - _arr_cls.length + 1;
						for (var i = 0; i < _arr_cls.length; i++){
							var lnk2 = document.createElement("a");
							lnk2.setAttribute("href", "class_from.php?class_id="+start);
							lnk2.setAttribute("style", "text-decoration: none; font-weight: bold; margin-left: 10px");
							lnk2.appendChild(document.createTextNode(""+_arr_cls[i]));
							$('#sp_'+school).append(lnk2);
							start++;
						}
					}

					//document.location.href = document.location.href;
				}
			}
		});
	};

	var _upd_class = function(school, cls){
		$.ajax({
			type: "POST",
			url: "manage_classes_from.php",
			data: {action: '2', school_id: school, class_names: cls},
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
					var _cls = json.classi;
					var max = json.max;
					var _arr_cls = _cls.split(",");
					if (_arr_cls.length == 1){
						var lnk2 = document.createElement("a");
						lnk2.setAttribute("href", "class_from.php?class_id="+max);
						lnk2.setAttribute("style", "text-decoration: none; font-weight: bold;");
						lnk2.appendChild(document.createTextNode("5"+_cls));
						$('#sp_'+school).append(lnk2);
					}
					else {
						start = max - _arr_cls.length + 1;
						for (var i = 0; i < _arr_cls.length; i++){
							var lnk2 = document.createElement("a");
							lnk2.setAttribute("href", "class_from.php?class_id="+start);
							lnk2.setAttribute("style", "text-decoration: none; font-weight: bold; margin-left: 10px");
							lnk2.appendChild(document.createTextNode("5"+_arr_cls[i]));
							$('#sp_'+school).append(lnk2);
							start++;
						}
					}

					//document.location.href = document.location.href;
				}
			}
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
            ?>
            <tr id="tr<?php print $k ?>" style="border-bottom: 1px solid rgba(211, 222, 199, 0.6);">
            	<td style="width: 50%; padding: 2px" class="<?php if ($school['comprensivo'] == 1) echo "attention" ?>">
            		<a id="sc<?php print $k ?>" href='dettaglio_scuola.php?id=<?php print $k ?>' style='font-size: 13px; font-weight: normal; margin-left: 0px; text-decoration: none' class="<?php if ($school['comprensivo'] == 1) echo "attention" ?>">
					<?php print $school['descrizione'] ?>
					</a>
				</td>
				<td style="width: 50%" id="tr_2_<?php print $k ?>">
				<span id="sp_<?php print $k ?>">
			<?php
				foreach($school['classi'] as $s){
			?>
            	<a href="class_from.php?class_id=<?php print $s['class_id'] ?>" style="text-decoration: none; font-weight: bold;" class="<?php if ($school['comprensivo'] == 1) echo "attention" ?>"><?php print $s['class'] ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php 
            	}
            ?>
            	</span>
            	(<a href="#" id="last<?php print $k ?>" onclick="<?php if ($school['comprensivo'] != 1){ ?>add_class<?php } else { ?>import_class<?php } ?>(<?php print $k ?>)" style="text-decoration: none; font-weight: bold;">+</a>)&nbsp;&nbsp;&nbsp;&nbsp;
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