<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Classi prime scuola secondaria</title>
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
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

            $('.label1').on('click', function (event) {
                var stid = $(this).data('stid');
                get_teachers(stid);
            });

            $('.label3').on('click', function (event) {
                var stid = $(this).data('stid');
                get_other(stid);
            });

            $('.label4').on('click', function (event) {
                var stid = $(this).data('stid');
                get_note(stid);
            });

            $('.label5').on('click', function (event) {
                var stid = $(this).data('stid');
                get_students(stid);
            });

            $('.del_btn').on('click', function (event) {
                event.preventDefault();
                stid = $(this).data("stid");
                j_alert("confirm", "Eliminare l'alunno?");
            });

            $('#okbutton').on('click', function (event) {
                event.preventDefault();
                upd_cls(stid);
            });
		});

        var get_teachers = function(stid){
            $.ajax({
                type: "POST",
                url: "manage_student.php",
                data:  {stid: stid, action: "get_teachers"},
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
                        j_alert("error", json.query);
                        //j_alert("error", "Si e' verificato un errore");
                        return;
                    }
                    else {
                        $('#teachers_list').text(json.string).dialog({
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
                            width: 250,
                            title: 'Elenco docenti',
                            open: function(event, ui){

                            }
                        });
                    }
                }
            });
        };

        var get_other = function(stid){
            $.ajax({
                type: "POST",
                url: "manage_student.php",
                data:  {stid: stid, action: "get_other"},
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
                        j_alert("error", json.query);
                        //j_alert("error", "Si e' verificato un errore");
                        return;
                    }
                    else {
                        $('#teachers_list').text(json.string).dialog({
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
                            width: 250,
                            title: 'Altre richieste',
                            open: function(event, ui){

                            }
                        });
                    }
                }
            });
        };

        var get_note = function(stid){
            $.ajax({
                type: "POST",
                url: "manage_student.php",
                data:  {stid: stid, action: "get_note"},
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
                        j_alert("error", json.query);
                        //j_alert("error", "Si e' verificato un errore");
                        return;
                    }
                    else {
                        $('#teachers_list').text(json.string).dialog({
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
                            width: 250,
                            title: 'Note docenti',
                            open: function(event, ui){

                            }
                        });
                    }
                }
            });
        };

        var get_students = function(stid){
            $.ajax({
                type: "POST",
                url: "manage_student.php",
                data:  {stid: stid, action: "get_students"},
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
                        j_alert("error", json.query);
                        //j_alert("error", "Si e' verificato un errore");
                        return;
                    }
                    else {
                        $('#teachers_list').text(json.string).dialog({
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
                            width: 250,
                            title: 'Elenco compagni',
                            open: function(event, ui){

                            }
                        });
                    }
                }
            });
        };

		var colors_and_classes = new Array();
		var delete_on_assign = <?php if(isset($_REQUEST['q']) && $_REQUEST['q'] == "not_assigned") print("true"); else print("false") ?>;
		<?php
		foreach($classes_and_colors as $a){
		?>
		colors_and_classes[<?php print $a['id'] ?>] = {id: "<?php print $a['id'] ?>", cls: "<?php print $a['name'] ?>", color: "<?php print $a['color'] ?>" };
		<?php } ?>

		var update_class = function(id, sel){
			cl = sel.value;

			$.ajax({
				type: "POST",
				url: "upd_class.php",
				data:  {std: id, cl: cl},
				dataType: 'json',
				error: function(data, status, errore) {
					j_alert("error", "Si e' verificato un errore");
					return false;
				},
				succes: function(result) {
					j_alert("alert", "ok");
				},
				complete: function(data, status){
					r = data.responseText;
					var json = $.parseJSON(r);
					if(json.status == "kosql"){
						j_alert("error", "Errore SQL");
						return;
					}
					else {
						//j_alert("alert", json.message);
						if(cl == "0"){
							$('#tr'+json.id).style.backgroundColor = "";
							return false;
						}

						obj = colors_and_classes[cl];
						color = obj.color;
						if(delete_on_assign){
							$('#tr'+json.id).hide();
						}
						else {
							$('#tr'+json.id).css({'backgroundColor': "#"+color});
						}
					}
				}
			});
		};

		var _student = function(id){
			url = "student.php?stid="+id;
			document.location.href = url;
		};

		var del_std = function(stid){

			$.ajax({
				type: "POST",
				url: "manage_student.php",
				data:  {stid: stid, action: 3},
				dataType: 'json',
				error: function(data, status, errore) {
					j_alert("error", "Si e' verificato un errore");
					return false;
				},
				succes: function(result) {
					j_alert("alert", "ok");
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
						$('#tr'+stid).hide();
					}
				}
			});
		};

	</script>
    <style>
        TR {
            height: 35px;
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
		<div id="not1" class="notification"></div>
		<form id="my_form" style="border: 1px solid #666666; border-radius: 10px; margin-top: 20px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post">
			<table style="border-collapse: collapse; width: 95%; margin: 30px auto 20px auto">
				<thead>
				<tr><td style="text-align: right; padding-bottom: 20px" colspan="9">
						<a href="insert_students.php" style="float: left" class="material_link">Aggiungi alunno</a>
						<a href="students.php?q=assigned&order=<?php echo $req_order ?>" class="material_link nav_link_first">Solo alunni gi&agrave; assegnati</a>
						<a href="students.php?q=not_assigned&order=<?php echo $req_order ?>" class="material_link nav_link">Solo alunni non assegnati</a>
						<a href="students.php?order=<?php echo $req_order ?>" class="material_link nav_link_last">Tutti</a>
					</td>
				</tr>
				<tr style="font-weight: bold; height: 30px">
					<td style="width: 25%; border-bottom: 1px solid #cccccc"><a href="students.php?q=<?php echo $q ?>">Cognome e nome</a></td>
					<td style="width: 5%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=rip&q=<?php echo $q ?>">Rip</a></td>
					<td style="width: 5%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=h&q=<?php echo $q ?>">BES</a></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=sex&q=<?php echo $q ?>">Sesso</a></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=grade&q=<?php echo $q ?>">Voto</a></td>
					<td style="width: 11%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=from&q=<?php echo $q ?>">Provenienza</a></td>
					<td style="width: 20%; text-align: center; border-bottom: 1px solid #cccccc"><a href="#">Note</a></td>
					<td style="width: 11%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=cls&q=<?php echo $q ?>">Classe</a></td>
					<td style="width: 3%; text-align: center; border-bottom: 1px solid #cccccc"></td>
				</tr>
				</thead>
				<tbody>
	 	    <?php
	        if($n_std < 1){ ?>
		        <tr>
			        <td style="padding: 20px 0;" colspan="9" class="_center _bold">Non hai ancora inserito nessun alunno.</td>
		        </tr>
	 	    <?php
	        } else{
				foreach ($students as $k => $st) {
	 	    		$ripetente = ($st['ripetente'] == 1) ? "SI" : "NO";
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
	 	    	<tr id="tr<?php print $st['id_alunno'] ?>" class="bottom_decoration" style="<?php if($st['id_classe'] != "") print("background-color: #".$classes_and_colors[$st['id_classe']]['color']) ?>">
					<td style="width: 25%"><a href="#" onclick="_student(<?php print $st['id_alunno'] ?>)"><?php print $st['name'] ?></a></td>
					<td style="width: 5%; text-align: center"><?php print $ripetente ?></td>
					<td style="width: 5%; text-align: center"><?php print $sost ?></td>
					<td style="width: 10%; text-align: center"><?php print $st['sesso'] ?></td>
					<td style="width: 10%; text-align: center"><?php print $st['voto'] ?></td>
					<td style="width: 11%; text-align: center"><?php print $st['class_from'] ?></td>
					<td style="width: 20%; text-align: center">
                        <div style="width: 90%; margin-top: auto; display: flex; align-content: center; align-items: center; justify-content: center">
							<?php
							if (count($st['preferenze']) > 0) {
								$labels = [];
								foreach ($st['preferenze'] as $pref) {
									if ($pref == 1) {
										$labels[] = 'T';
									}
									else if ($pref == 2) {
										$labels[] = $st['sect'];
									}
									else if ($pref == 3) {
										$labels[] = 'R';
									}
									else if ($pref == 4) {
										$labels[] = 'N';
									}
									else if ($pref == 5) {
										$labels[] = 'S';
									}
								}
								$x = 1;
								foreach ($labels as $label) {
									?>
                                    <div class="round_minilabel<?php if ($label == 'T') echo " label1"; else if ($label == 'R') echo " label3"; else if ($label == 'N') echo " label4"; else if ($label == 'S') echo " label5"; ?>" style="order: <?php echo $x ?>; padding: 0" data-stid="<?php echo $k ?>" >
                                        <div><?php echo $label ?></div>
                                    </div>
									<?php
									$x++;
								}
							}
							?>
                        </div>
                    </td>
					<td style="width: 11%; text-align: center">
						<select id="n_class" name="n_class" class="form_input" style="width: 90%" onchange="update_class(<?php print $st['id_alunno'] ?>, this)">
							<option value="0">.</option>
							<?php 
							foreach($classes_and_colors as $c){
							?>
							<option value="<?php print $c['id'] ?>" <?php if($c['id'] == $st['id_classe']) print("selected='selected'"); ?>><?php print $c['name'] ?></option>
							<?php } ?>
						</select>
					</td>
					<td style="width: 3%; font-weight: bold; text-align: center">
                        <a style="color: red; font-weight: bold" href="#" onclick="del_std(<?php print $st['id_alunno'] ?>)">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
	 	    	</tr>
	 	    	<?php } ?>

	 	    <?php } ?>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<!-- END CONTENT -->
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
<div id="teachers_list" style="display: none"></div>
</body>
</html>
