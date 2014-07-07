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
	<script type="text/javascript">
	var _classes = function(){
		var _cls = prompt("Inserisci le sezioni che vuoi creare, separate da una virgola");
		if(trim(_cls) == ""){
			alert("Non hai inserito nessuna sezione");
			return false;
		}
		document.forms[0].cls.value = _cls;
		document.forms[0].submit();
	};

	var del_cls = function(id){
		if(!confirm("Sei sicuro di voler cancellare questa classe?"))
			return false;

		var req = new Ajax.Request('del_cls.php',
				  {
				        method:'post',
				        parameters: {id: id},
				        onSuccess: function(transport){
				            var response = transport.responseText || "no response text";
				            //alert(response);
				            var dati = response.split(";");
			                if(dati[0] == "ko"){
								alert("Errore nella cancellazione della classe: "+dati[1]);
								return false;
			                }
			                $('tr'+dati[1]).style.display = "none";
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
	 	    <?php if($n_cls < 1){ ?>
	 	    <p style="margin-top: 20px; margin-bottom: 50px" class="_bold _center">Non hai ancora inserito nessuna classe.</p>
	 	    <div style="width: 90%; text-align: right">
		        <a href="#" onclick="_classes()" class="standard_link">Inserisci classi</a>
		        <input type="hidden" name="cls" id="cls" />
	 	    </div>
	 	    <?php
	        } else{
	 	    ?>	
	 	    <table style="border-collapse: collapse; width: 95%; margin-top: 30px">
	 	    	<thead>
	 	    	<tr style="font-weight: bold">
					<td style="width: 13%; border-bottom: 1px solid #cccccc">Classe</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Alunni</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Ripetenti</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Maschi</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Femmine</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">H / DSA</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Media</td>
					<td style="width: 9%; text-align: center; border-bottom: 1px solid #cccccc">Elimina</td>	 	    	
	 	    	</tr>
	 	    	</thead>
	 	    	<tbody>
	 	    	<?php
	 	    	while($cl = $res_classes->fetch_assoc()) {
	 	    		$sel_sex = "SELECT sesso, COUNT(sesso) AS count FROM fc_alunni WHERE id_classe = ".$cl['id']." GROUP BY sesso";
	 	    		$res_sex = $db->executeQuery($sel_sex);
	 	    		$male = $female = 0;
	 	    		while($sx = $res_sex->fetch_assoc()){
						if($sx['sesso'] == 'M')
							$male = $sx['count'];
						else
							$female = $sx['count'];
	 	    		}
	 	    		$sel_rip = "SELECT COUNT(id_alunno) FROM fc_alunni WHERE id_classe = ".$cl['id']." AND ripetente = 1";
	 	    		$ripetenti = $db->executeCount($sel_rip); 
	 	    		
	 	    		$sel_h = "SELECT H FROM fc_alunni WHERE id_classe = ".$cl['id']." AND H IS NOT NULL AND H <> 0";
	 	    		$res_h = $db->executeQuery($sel_h);
	 	    		$h = $dsa = 0;
	 	    		while($al = $res_h->fetch_assoc()){
	 	    			if($al['H'] < 4)
	 	    				$dsa++;
	 	    			if($al['H'] > 1)
	 	    				$h++;	
	 	    		}
	 	    		$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM fc_alunni WHERE id_classe = ".$cl['id'];
	 	    		$avg = $db->executeCount($sel_avg);
	 	    	?>
	 	    	<tr id="tr<?php print $cl['id'] ?>">
					<td style="width: 13%; border-bottom: 1px solid #cccccc"><a href="class.php?id_classe=<?php print $cl['id'] ?>"><?php print $cl['descrizione'] ?></a></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $cl['alunni'] ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $ripetenti ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $male ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $female ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print ($h." / ".$dsa." (".($res_h->num_rows).")") ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $avg ?></td>
					<td style="width: 9%; font-weight: bold; text-align: center; border-bottom: 1px solid #cccccc"><a style="color: red; font-weight: bold" href="#" onclick="del_cls(<?php print $cl['id'] ?>)">x</a></td>	 	    	
	 	    	</tr>
	 	    	<?php } ?>
	 	    	</tbody>
	 	    	<tfoot>
	 	   		<tr>
	 				<td colspan="8" style="text-align: right; margin-right: 10px; padding-top: 30px">
						<a href="#" style="" onclick="_classes()">Aggiungi classi</a>
						<input type="hidden" name="cls" id="cls" /> 				
	 				</td>    		
	 	   		</tr>
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