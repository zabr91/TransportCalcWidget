<div class="wrap">
	<h2><?php echo get_admin_page_title() ?></h2>

	
	<?php
	// выводим таблицу на экран где нужно
	echo '<form action="" method="POST">';
	if($GLOBALS['Example_List_Table']) $GLOBALS['Example_List_Table']->display();
	echo '</form>';
	?>
</div>