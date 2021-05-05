<div class="wrap">
	<h2><?php echo get_admin_page_title() ?></h2>

	<a href="?page=<?= $_GET['page'] ?>&action=create"  class="btn">Создать</a>	

	<?php
	// выводим таблицу на экран где нужно
	echo '<form action="" method="POST">';
	if($GLOBALS['Example_List_Table']) $GLOBALS['Example_List_Table']->display();
	echo '</form>';
	?>
</div>