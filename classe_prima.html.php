<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime</title>
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" /><script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var students = <?php print $res_students->num_rows ?>;
		var stid = 0;
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

        var upd_cls = function(id){
            $('#confirm').fadeOut(10);
            $('#overlay').fadeOut(10);
            $.ajax({
                type: "POST",
                url: "upd_class.php",
                data:  {std: id, cl: "0"},
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
                    if(json.status === "kosql"){
                        j_alert("error", "Si e' verificato un errore");
                        return false;
                    }
                    else {
                        $('#tr'+json.id).hide();
                        upd_summary(<?php print $_REQUEST['id_classe'] ?>);
                    }
                }
            });
        };

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

		var upd_summary = function(class_id){
			$.ajax({
				type: "POST",
				url: "get_class_summary.php",
				data:  {cl: class_id},
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
					if(json.status === "kosql"){
						j_alert("error", "Errore SQL");
						return false;
					}
					else {
						data = json.data;
						$('#nmb_st').text(data.male + data.female);
						$('#nmb_male').text(data.male);
						$('#nmb_female').text(data.female);
						$('#nmb_rip').text(data.rip);
						$('#nmb_h').text(data.h+" / "+(data.dsa + data.des)+"("+data.sos+")");
						$('#avg').text(data.avg);
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
		<div style="width: 90%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
			<span style="border-bottom: 1px solid; float: right; font-weight: bold; font-size: 12px" class="material_label">Media generale: <?php print $mv ?></span>
		</div>
		<form id="my_form" style="margin-top: 5px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post">
	 	    <table style="border-collapse: collapse; width: 90%; margin: 0 auto 10px auto">
	 	    <thead>
	 	    	<tr>
	 	    		<td colspan="6" style="text-align: center; font-weight: bold; border: 0; height: 30px">Riepilogo</td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 16%; border-top: 1px solid #cccccc">Alunni: </td>
					<td id="nmb_st" style="width: 16%; border-top: 1px solid #cccccc"><?php print $n_std ?></td>
					<td style="width: 16%; border-top: 1px solid #cccccc">Maschi: </td>
					<td id="nmb_male" style="width: 16%; border-top: 1px solid #cccccc"><?php print $male ?></td>
					<td style="width: 16%; border-top: 1px solid #cccccc">H/DSA: </td>
					<td id="nmb_h" style="width: 16%; border-top: 1px solid #cccccc"><?php print ($h." / ".$dsa." (".($res_h->num_rows).")") ?></td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 16%; border-bottom: 1px solid #cccccc">Ripetenti: </td>
					<td id="nmb_rip" style="width: 16%; border-bottom: 1px solid #cccccc"><?php print $ripetenti ?></td>
					<td style="width: 16%; border-bottom: 1px solid #cccccc">Femmine: </td>
					<td id="nmb_female" style="width: 16%; border-bottom: 1px solid #cccccc"><?php print $female ?></td>
					<td style="width: 16%; border-bottom: 1px solid #cccccc">Media: </td>
					<td id="avg" style="width: 16%; border-bottom: 1px solid #cccccc"><?php print $avg ?></td>
	 	    	</tr>
		        <tr>
			        <td colspan="6" style="text-align: center; font-weight: bold; border: 0; padding-top: 20px; height: 30px">Classi di provenienza</td>
		        </tr>
		        <tr style="font-weight: bold">
			        <?php
			        $index = 0;
			        foreach($colors_from as $color){
				        if($index > 5){
					        break;
				        }
				        if($index == 0){
					?>
					 <td style="width: 5%; text-align: center; border-top: 1px solid #cccccc; border-left: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
				    <?php
				        }
				        else if($index == 5){
					?>
					<td style="width: 5%; text-align: center; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
				    <?php
				        }
				        else{
					?>
					<td style="width: 5%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
				    <?php
				        }
				        $index++;
			        }
			        ?>
		        </tr>
		        <tr style="font-weight: bold">
			        <?php
			        $ar = array_slice($colors_from, 6);
			        $index = 0;
			        foreach($ar as $color){
				        if($index > 5){
					        break;
				        }
				        if($index == 0){
					?>
					<td style="width: 5%; text-align: center; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
					<?php
				        }
				        else if($index == 5){
					?>
					<td style="width: 5%; text-align: center; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
					<?php
				        }
				        else{
					?>
					<td style="width: 5%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
					<?php
				        }
				        $index++;
			        }
			        ?>
		        </tr>
			</thead>
	 	    </table>
	 	    <p></p>
	 	    <table style="border-collapse: collapse; width: 95%; margin: 30px auto 20px auto">
	 	    	<thead>
	 	    	<tr>
	 	    		<td colspan="7" style="text-align: right; padding-right: 20px; height: 30px"><a href="classe_prima.php?id_classe=<?php print $_REQUEST['id_classe'] ?>" style="float: left">Ordina per cognome</a><span style="float: left">&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="classe_prima.php?id_classe=<?php print $_REQUEST['id_classe'] ?>&order=from" style="float: left">Ordina per provenienza</a></td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold; height: 30px" class="bottom_decoration">
					<td style="width: 27%">Cognome e nome</td>
					<td style="width: 10%; text-align: center">Ripetente</td>
					<td style="width: 10%; text-align: center">BES</td>
					<td style="width: 10%; text-align: center">Sesso</td>
					<td style="width: 10%; text-align: center">Voto</td>
					<td style="width: 30%; text-align: center">Note</td>
					<td style="width: 3%; text-align: center"></td>
	 	    	</tr>
	 	    	</thead>
	 	    	<tbody>
	 	    	<?php
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
	 	    	<tr id="tr<?php print $st['id_alunno'] ?>" class="bottom_decoration" style="<?php if($st['school'] != "5") print("background-color: #".$colors_from[$st['classe_provenienza']]['color']) ?>">
					<td style="width: 27%; padding-left: 10px"><?php print $st['name'] ?></td>
					<td style="width: 10%; text-align: center"><?php print $ripetente ?></td>
					<td style="width: 10%; text-align: center"><?php print $sost ?></td>
					<td style="width: 10%; text-align: center"><?php print $st['sesso'] ?></td>
					<td style="width: 10%; text-align: center"><?php print $st['voto'] ?></td>
					<td style="width: 30%; text-align: center">
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
                                <div class="round_label<?php if ($label == 'T') echo " label1"; else if ($label == 'R') echo " label3"; else if ($label == 'N') echo " label4"; else if ($label == 'S') echo " label5"; ?>" style="order: <?php echo $x ?>; padding: 0" data-stid="<?php echo $k ?>" >
                                    <div><?php echo $label ?></div>
                                </div>
                        <?php
                                $x++;
                            }
                        }
                        ?>
                        </div>
                    </td>
					<td style="width: 3%; font-weight: bold; text-align: center">
                        <a style="font-weight: bold" href="#" data-stid="<?php print $st['id_alunno'] ?>" class="del_btn">
                            <i class="fa fa-trash normal"></i>
                        </a>
                    </td>
	 	    	</tr>
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
