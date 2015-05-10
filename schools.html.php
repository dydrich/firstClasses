<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime scuola secondaria</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});

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
					var _cls = json.classi;
					var max = json.max;
					var _arr_cls = _cls.split(",");
					if (_arr_cls.length == 1){
						var lnk2 = document.createElement("a");
						lnk2.setAttribute("href", "class_from.php?class_id="+max);
						lnk2.setAttribute("style", "text-decoration: none; font-weight: bold;");
						lnk2.appendChild(document.createTextNode(_cls));
						$('#sp_'+school).append(lnk2);
					}
					else {
						start = max - _arr_cls.length + 1;
						for (var i = 0; i < _arr_cls.length; i++){
							var lnk2 = document.createElement("a");
							lnk2.setAttribute("href", "class_from.php?class_id="+start);
							lnk2.setAttribute("style", "text-decoration: none; font-weight: bold; margin-left: 10px");
							lnk2.appendChild(document.createTextNode(_arr_cls[i]));
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
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: 5px" class="rb_button">
			<a href="dettaglio_scuola.php?id=0">
				<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
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
    </div>
	<div class="clear"></div>
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
