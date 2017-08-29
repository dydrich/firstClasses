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
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
        var id_alunno = <?php echo $_REQUEST['stid'] ?>;
        var id_other = <?php if (isset($student[3])) {$ak = array_keys($student[3]); echo $ak[0]; } else echo 0; ?>;
        var id_note = <?php if (isset($student[4])) {$ak = array_keys($student[4]); echo $ak[0]; } else echo 0; ?>;

        $(function(){
            load_jalert();
            setOverlayEvent();
            $('.show_list').click(function(event){
                event.preventDefault();
                strs = this.id.split('_');
                id_alunno = strs[2];
                show_students();
            });
            $('.sect').on('change', function(event){
                event.preventDefault();
                var sect = $("input[name=sect]:checked").val();
                save_pref(2, sect);
            });

            $('.add_t').on('click', function (event) {
                event.preventDefault();
                tid = $(this).data('t');
                man_teachers(tid, 'add_teacher');
            });

            $('.del_t').on('click', function (event) {
                event.preventDefault();
                tid = $(this).data('t');
                man_teachers(tid, 'del_teacher');
            });

            $('#oth').on('click', function (event) {
                event.preventDefault();
                var text = $('#other').val();
                save_other(text);
            });

            $('#del_oth').on('click', function (event) {
                event.preventDefault();
                save_other('');
            });

            $('#note').on('click', function (event) {
                event.preventDefault();
                var text = $('#notes').val();
                save_teachers_notes(text);
            });

            $('#del_note').on('click', function (event) {
                event.preventDefault();
                save_teachers_notes('');
            });

            $('#buttonset').buttonset();
        });

        var save_pref = function(id_pref, val){
            $.ajax({
                type: "POST",
                url: "manage_student.php",
                data:  {stid: id_alunno, pref: id_pref, value: val, action: 'upd_section'},
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
                        j_alert("error", json.query);
                        return false;
                    }
                    else {
                        //j_alert("alert", json.message);
                    }

                }
            });
        };

        var man_teachers = function(tid, action){
            $.ajax({
                type: "POST",
                url: "manage_student.php",
                data:  {stid: id_alunno, tid: tid, action: action},
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
                        j_alert("error", json.query);
                        return false;
                    }
                    else {
                        document.location.href = 'scelta_preferenze.php?stid='+id_alunno;
                    }

                }
            });
        };

        var save_other = function(val){
            action = 'upd_other';
            if (val === '') {
                action = 'del_other';
            }
            $.ajax({
                type: "POST",
                url: "manage_student.php",
                data:  {stid: id_alunno, value: val, action: action, id_other: id_other},
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
                        j_alert("error", json.query);
                        return false;
                    }
                    else {
                        j_alert("alert", json.message);
                    }

                }
            });
        };

        var save_teachers_notes = function(val){
            action = 'upd_note';
            if (val === '') {
                action = 'del_note';
            }
            $.ajax({
                type: "POST",
                url: "manage_student.php",
                data:  {stid: id_alunno, value: val, action: action, id_note: id_other},
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
                        j_alert("error", json.query);
                        return false;
                    }
                    else {
                        j_alert("alert", json.message);
                    }

                }
            });
        };

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
		<form id="my_form" style="margin-top: 20px; text-align: left; width: 90%; margin-left: auto; margin-right: auto" method="post" class="no_border">
			<fieldset style="width: 90%; margin: auto">
				<legend>Sezione</legend>
                <div id="buttonset" style="width: 80%; margin: auto">
                    <input name="sect" class="sect" id="s0" type="radio" value="<?php echo 0 ?>" <?php if (!isset($student['2'])) echo 'checked' ?> />
                    <label for="s0" style="width: 95px">Nessuna</label>
					<?php
					foreach ($sezioni as $k => $item) {
						?>
                        <input name="sect" class="sect" id="s<?php echo $k ?>" type="radio" value="<?php echo $item ?>" <?php if (isset($student['2']) && intval($student['2']) == $k) echo 'checked' ?> />
                        <label for="s<?php echo $k ?>" style="width: 65px"><?php echo $item ?></label>
						<?php
					}
					?>
                </div>
			</fieldset>
            <fieldset style="width: 90%; margin: auto">
                <legend>Docenti</legend>
                <div style="width: 90%; margin: auto">
                    <?php foreach ($student[1] as $tid => $pref) {
                        ?>
                    <div style="width: 50%; margin-left: 10%; height: 20px" class="_bold bottom_decoration">
                        <?php echo $pref['cognome']." ".$pref['nome']; ?>
                        <div class="fright" style="margin-right: 20px">
                            <a href="#" class="del_t" data-t="<?php echo $tid ?>">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <p class="_bold normal" style="margin-top: 25px">Aggiungi un docente</p>
                    <table style="width: 100%">
                        <?php
						$cells = 0;
						foreach ($teachers as $tid => $teacher) {
                            if ($cells == 0) {
                            ?>
                            <tr class="bottom_decoration">
                                <td style="width: 33%">
                                    <a href="#" class="add_t" data-t="<?php echo $tid ?>">
										<?php echo $teacher['cognome']." ".$teacher['nome'] ?>
                                    </a>
                                </td>
                            <?php
                            }
                            else {
                                ?>
                                <td style="width: 33%">
                                    <a href="#" class="add_t" data-t="<?php echo $tid ?>">
										<?php echo $teacher['cognome']." ".$teacher['nome'] ?>
                                    </a>
                                </td>
                                <?php
                            }
                            $cells++;
                            if ($cells == 3) {
                                $cells = 0;
                                echo "</tr>";
                            }
                        }

                        ?>
                    </table>
                </div>
            </fieldset>
            <fieldset style="width: 90%; margin: auto">
                <legend>Altre richieste</legend>
                <div style="width: 80%; margin: auto">
                    <label for="other">Altro</label>
					<textarea style="width: 95%; height: 55px" id="other" name="other"><?php if (isset($student[3])) {$txt = implode(". ", $student[3]); echo $txt;} ?>
                    </textarea>
                    <div style="width: 95%; text-align: right; margin-top: 20px">
                        <a href="#" class="oth material_link" id="oth">Registra</a>
                        <a href="#" class="material_link" id="del_oth" style="margin-left: 20px">Cancella</a>
                    </div>

                </div>
            </fieldset>
            <fieldset style="width: 90%; margin: auto">
                <legend>Note dei docenti</legend>
                <div style="width: 80%; margin: auto">
                    <label for="notes">Note</label>
                    <textarea style="width: 95%; height: 55px" id="notes" name="notes"><?php if (isset($student[4])) {$txt = implode(". ", $student[4]); echo $txt;} ?></textarea>
                    <div style="width: 95%; text-align: right; margin-top: 20px">
                        <a href="#" class="note material_link" id="note">Registra</a>
                        <a href="#" class="material_link" id="del_note" style="margin-left: 20px">Cancella</a>
                    </div>

                </div>
            </fieldset>
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
</body>
</html>
