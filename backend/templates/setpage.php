<div class="wrap">
	<h2><?php echo get_admin_page_title() ?></h2>

	<form action="options.php" method="POST">
			<?php
				settings_fields( 'TransportCalc' );  
				do_settings_sections( 'TransportCalc' );
				submit_button();
			?>
		</form>

    <h3>Таблица стоимости доставки груза</h3>
	<a href="?page=<?= $_GET['page'] ?>&action=create"  class="btn">Создать новую стоимость</a>	
	
	<?php
	// выводим таблицу на экран где нужно
	echo '<form action="" method="POST">';
	if($GLOBALS['Example_List_Table']) $GLOBALS['Example_List_Table']->display();
	echo '</form>';
	?>
</div>