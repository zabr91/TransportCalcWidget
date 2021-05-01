<?php
// WP 5.4.2. Cохранение опции экрана per_page. Нужно вызывать до события 'admin_menu'
add_filter( 'set_screen_option_'.'lisense_table_per_page', function( $status, $option, $value ){
	return (int) $value;
}, 10, 3 );

// WP < 5.4.2. сохранение опции экрана per_page. Нужно вызывать рано до события 'admin_menu'
add_filter( 'set-screen-option', function( $status, $option, $value ){
	return ( $option == 'lisense_table_per_page' ) ? (int) $value : $status;
}, 10, 3 );

// создаем страницу в меню, куда выводим таблицу
add_action( 'admin_menu', function(){
	$hook = add_menu_page( 'Заголовок', 'Имя в меню', 'manage_options', 'page-slug', 'example_table_page', 'dashicons-products', 100 );

	add_action( "load-$hook", 'example_table_page_load' );
} );

function example_table_page_load(){
	require_once __DIR__ . '/Example_List_Table.php'; // тут находится класс Example_List_Table...

	// создаем экземпляр и сохраним его дальше выведем
	$GLOBALS['Example_List_Table'] = new Example_List_Table();
}

function example_table_page(){
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<?php
		// выводим таблицу на экран где нужно
		echo '<form action="" method="POST">';
		$GLOBALS['Example_List_Table']->display();
		echo '</form>';
		?>

	</div>
	<?php
}