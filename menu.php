			<div class="smallbox" id="working">
				<p class="menu_label act_icon">Nuove classi</p>
				<ul class="menublock" style="" dir="rtl">
					<li><a href="index.php">Home</a></li>
					<li><a href="schools.php">Scuole di provenienza</a></li>
					<li><a href="classi.php">Classi prime</a></li>
				</ul>
				<p class="menu_label act_icon">Alunni</p>
				<ul class="menublock" style="" dir="rtl">
					<li><a href="insert_students.php">Inserimento</a></li>
					<li><a href="students.php">Elenco </a></li>
					<li><a href="preferenze_compagni.php">Compagni </a></li>
                    <li><a href="preferenze_didattica.php">Preferenze </a></li>
					<?php if ($_SESSION['__school_order__'] == 1): ?>
					<li><a href="import_students.php">Importazione</a></li>
					<?php endif; ?>
					<li><a href="ripetenti.php">Ripetenti</a></li>
				</ul>
			</div>
