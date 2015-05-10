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
	var students = <?php print $res_students->num_rows ?>;
	var win;
	var win2;

	var upd_cls = function(id){
		if(!confirm("Sei sicuro di voler togliere l'alunno dalla classe?")){
			return false;
		}

		$.ajax({
			type: "POST",
			url: "upd_class.php",
			data:  {std: id, cl: "0"},
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
					$('#tr'+json.id).hide();
					upd_summary(<?php print $_REQUEST['id_classe'] ?>);
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

	var update_class = function(id, cl){
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
			                $('p'+id).style.display = "none";

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
		<div style="width: 90%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
			<span style="border-bottom: 1px solid; float: right; font-weight: bold; font-size: 12px">Media generale: <?php print $mv ?></span>
		</div>
		<form id="my_form" style="margin-top: 20px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post">
	 	    <table style="border-collapse: collapse; width: 90%; margin: 0 auto 10px auto">
	 	    <thead>
	 	    	<tr>
	 	    		<td colspan="6" style="text-align: center; font-weight: bold; border: 0; height: 30px">Riepilogo</td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 16%; border-top: 1px solid #cccccc; border-left: 1px solid #cccccc">Alunni: </td>
					<td id="nmb_st" style="width: 16%; border-top: 1px solid #cccccc"><?php print $n_std ?></td>
					<td style="width: 16%; border-top: 1px solid #cccccc">Maschi: </td>
					<td id="nmb_male" style="width: 16%; border-top: 1px solid #cccccc"><?php print $male ?></td>
					<td style="width: 16%; border-top: 1px solid #cccccc">H/DSA: </td>
					<td id="nmb_h" style="width: 16%; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc"><?php print ($h." / ".$dsa." (".($res_h->num_rows).")") ?></td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 16%; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc">Ripetenti: </td>
					<td id="nmb_rip" style="width: 16%; border-bottom: 1px solid #cccccc"><?php print $ripetenti ?></td>
					<td style="width: 16%; border-bottom: 1px solid #cccccc">Femmine: </td>
					<td id="nmb_female" style="width: 16%; border-bottom: 1px solid #cccccc"><?php print $female ?></td>
					<td style="width: 16%; border-bottom: 1px solid #cccccc">Media: </td>
					<td id="avg" style="width: 16%; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc"><?php print $avg ?></td>
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
	 	    	<tr style="font-weight: bold; height: 30px">
					<td style="width: 27%; border-bottom: 1px solid #cccccc">Cognome e nome</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Ripetente</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">BES</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Sesso</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Voto</td>
					<td style="width: 30%; text-align: center; border-bottom: 1px solid #cccccc">Note</td>
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
	 	    	<tr id="tr<?php print $st['id_alunno'] ?>" style="<?php if($st['school'] != "5") print("background-color: #".$colors_from[$st['classe_provenienza']]['color']) ?>">
					<td style="width: 27%; border-bottom: 1px solid #cccccc; padding-left: 10px"><?php print $st['name'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $ripetente ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $sost ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['sesso'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['voto'] ?></td>
					<td style="width: 30%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['note'] ?></td>
					<td style="width: 3%; font-weight: bold; text-align: center; border-bottom: 1px solid #cccccc;"><a style="color: red; font-weight: bold" href="#" onclick="upd_cls(<?php print $st['id_alunno'] ?>)">x</a></td>	 	    	
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
</body>
</html>
