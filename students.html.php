<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: classi prime scuola secondaria</title>
	<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="theme/style.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
	var colors_and_classes = new Array();
	var delete_on_assign = <?php if($_REQUEST['q'] == "not_assigned") print("true"); else print("false") ?>;
	<?php
	foreach($classes_and_colors as $a){
	?>
	colors_and_classes[<?php print $a['id'] ?>] = {id: "<?php print $a['id'] ?>", cls: "<?php print $a['name'] ?>", color: "<?php print $a['color'] ?>" };
	<?php } ?>
	var win;

	var update_class = function(id, sel){
		cl = sel.value;
		var req = new Ajax.Request('upd_class.php',
				  {
				        method:'post',
				        parameters: {std: id, cl: cl},
				        onSuccess: function(transport){
				            var response = transport.responseText || "no response text";
				            //alert(response);
				            var dati = response.split("|");
			                if(dati[0] == "ko"){
								alert("Errore nell'aggiornamento della classe: "+dati[1]);
								return false;
			                }
			                if(cl == "0"){
								$('tr'+dati[1]).style.backgroundColor = "";
								return false;
							}

							obj = colors_and_classes[cl];
							color = obj.color;
							if(delete_on_assign)
								$('tr'+dati[1]).style.display = "none";
							else
								$('tr'+dati[1]).style.backgroundColor = "#"+color;
				        },
				        onFailure: function(){ alert("Si e' verificato un errore..."); }
				  });
	};

	var _student = function(id){
		url = "student.php?stid="+id+"&order=<?php print $_REQUEST['order'] ?>&q=<?php print $_REQUEST['q'] ?>";
		if(id == 0){
			// new student
			if(confirm("L'alunno e' un ripetente?"))
				url = "student.php?stid=0&rip=1";
			else
				url = "student.php?stid=0";
		}
		else{
			// update student

		}

		win = new Window({className: "mac_os_x", url: url, top:100, left:100,  width:400, height:400, zIndex: 100, resizable: true, title: "Dettaglio alunno", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
		win.showCenter(false);
	};

	var del_std = function(stid){
		var req = new Ajax.Request('manage_student.php',
				  {
				        method:'post',
				        parameters: {stid: stid, action: 3},
				        onSuccess: function(transport){
				            var response = transport.responseText || "no response text";
				            //alert(response);
				            var dati = response.split("|");
			                if(dati[0] == "ko"){
								alert("Errore nell'aggiornamento della classe: "+dati[1]);
								return false;
			                }
			                $('tr'+stid).style.display = "none";
				        },
				        onFailure: function(){ alert("Si e' verificato un errore..."); }
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
		<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
			Alunni classi prime
		</div>
		<div id="not1" class="notification"></div>
		<form id="my_form" style="border: 1px solid #666666; border-radius: 10px; margin-top: 20px; text-align: left; width: 80%; margin-left: auto; margin-right: auto" method="post">
	 	    <?php if($n_std < 1){ ?>
	 	    <p style="margin-top: 20px; margin-bottom: 50px" class="_center _bold">Non hai ancora inserito nessun alunno.</p>
	 	    <div style="width: 90%; text-align: right">
		        <a href="#" onclick="_student(0)" class="standard_link">Inserisci alunno</a>
			</div>
	 	    <?php } 
			else{	 	    
	 	    ?>	
	 	    <table style="border-collapse: collapse; width: 100%; margin-top: 30px">
	 	    	<thead>
	 	    	<tr><td style="text-align: right; padding-bottom: 20px" colspan="9"><a href="#" style="float: left" onclick="_student(0)">Aggiungi alunno</a><a href="students.php?q=assigned&order=<?php print $_REQUEST['order'] ?>">Solo alunni gi&agrave; assegnati</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="students.php?q=not_assigned&order=<?php print $_REQUEST['order'] ?>">Solo alunni non assegnati</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="students.php?order=<?php print $_REQUEST['order'] ?>">Tutti</a></td></tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 25%; border-bottom: 1px solid #cccccc"><a href="students.php?q=<?php print $_REQUEST['q'] ?>">Cognome e nome</a></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=rip&q=<?php print $_REQUEST['q'] ?>">Ripetente</a></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=h&q=<?php print $_REQUEST['q'] ?>">H / DSA</a></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=sex&q=<?php print $_REQUEST['q'] ?>">Sesso</a></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=grade&q=<?php print $_REQUEST['q'] ?>">Voto</a></td>
					<td style="width: 11%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=from&q=<?php print $_REQUEST['q'] ?>">Provenienza</a></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc"><a href="#">Note</a></td>
					<td style="width: 11%; text-align: center; border-bottom: 1px solid #cccccc"><a href="students.php?order=cls&q=<?php print $_REQUEST['q'] ?>">Classe</a></td>
					<td style="width: 3%; text-align: center; border-bottom: 1px solid #cccccc"></td>	 	    	
	 	    	</tr>
	 	    	</thead>
	 	    	<tbody>
	 	    	<?php
	 	    	while($st = $res_students->fetch_assoc()) {
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
	 	    	<tr id="tr<?php print $st['id_alunno'] ?>" style="<?php if($st['id_classe'] != "") print("background-color: #".$classes_and_colors[$st['id_classe']]['color']) ?>">
					<td style="width: 25%; border-bottom: 1px solid #cccccc;<?php print $bck ?>"><a href="#" onclick="_student(<?php print $st['id_alunno'] ?>)"><?php print $st['name'] ?></a></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;<?php print $bck ?>"><?php print $ripetente ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;<?php print $bck ?>"><?php print $sost ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;<?php print $bck ?>"><?php print $st['sesso'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;<?php print $bck ?>"><?php print $st['voto'] ?></td>
					<td style="width: 11%; text-align: center; border-bottom: 1px solid #cccccc;<?php print $bck ?>"><?php print $st['class_from'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;<?php print $bck ?>"><?php print utf8_decode($st['note']) ?></td>
					<td style="width: 11%; text-align: center; border-bottom: 1px solid #cccccc;<?php print $bck ?>">
						<select id="n_class" id="n_class" style="border: 1px solid; width: 90%; font-size: 11px" onchange="update_class(<?php print $st['id_alunno'] ?>, this)">
							<option value="0">.</option>
							<?php 
							foreach($classes_and_colors as $c){
							?>
							<option value="<?php print $c['id'] ?>" <?php if($c['id'] == $st['id_classe']) print("selected='selected'"); ?>><?php print $c['name'] ?></option>
							<?php } ?>
						</select>
					</td>
					<td style="width: 3%; font-weight: bold; text-align: center; border-bottom: 1px solid #cccccc;<?php print $bck ?>"><a style="color: red; font-weight: bold" href="#" onclick="del_std(<?php print $st['id_alunno'] ?>)">x</a></td>	 	    	
	 	    	</tr>
	 	    	<?php } ?>
	 	    	</tbody>
	 	    	<tfoot>
	 	    	</tfoot>
	 	    </table>
	 	    <?php } ?>

			<!-- END CONTENT -->
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/{$_SESSION['__mod_area__']}/footer.php" ?>
</body>
</html>
