<?php
namespace TransportCalc;

/**
 * Настройки плагина
 */
class Settings
{
	
	function __construct()
	{
		$this->delete();

		$this->save();


		

		// WP 5.4.2. Cохранение опции экрана per_page. Нужно вызывать до события 'admin_menu'
		add_filter( 'set_screen_option_'.'lisense_table_per_page', function( $status, $option, $value ){
			return (int) $value;
		}, 10, 3 );

		// WP < 5.4.2. сохранение опции экрана per_page. Нужно вызывать рано до события 'admin_menu'
		add_filter( 'set-screen-option', function( $status, $option, $value ){
			return ( $option == 'lisense_table_per_page' ) ? (int) $value : $status;
		}, 10, 3 );

		// создаем страницу в меню, куда выводим таблицу
		add_action( 'admin_menu', function(){//page-slug
			$hook = add_menu_page( 'Настройки плагина TransportCalc', 'TransportCalc', 'manage_options', 'transportcalc-settings', 
				[&$this, 'wiev'], 'dashicons-calculator', 100 );

			/*if(isset($_GET['action'])) {
				if(!$_GET['action']) {*/
					add_action( "load-$hook", [&$this, 'page_load'] );
			/*	}
		    }*/
			
		} );

		add_action('admin_init', [&$this, 'addControls']);
	}

	function page_load(){
	
		require_once TCW_PLUGIN_DIR . 'backend/controllers/TablePrice.php'; // тут находится класс Example_List_Table...
	
		$GLOBALS['Example_List_Table'] = new TablePrice();// создаем экземпляр и сохраним его дальше выведем

		}


	function wiev() {
		if(isset($_GET['action']))
		{
			if($_GET['action'] == 'edit')
			{
				$price = new BaseCustomData('tc_price');
			    $values = $price->get_by(['id' => $_GET['id']]);
				require_once TCW_PLUGIN_DIR . 'backend/templates/_form_price.php';
			}
			elseif ($_GET['action'] == 'create') {
				require_once TCW_PLUGIN_DIR . 'backend/templates/_form_price.php';
			}		
	   } 
	   else {
			require_once TCW_PLUGIN_DIR . 'backend/templates/setpage.php'; 		
		}
	}

	private function save(){
		
       	if($_POST){

			$price = new BaseCustomData('tc_price');

			$id =$_POST['id'];
			unset($_POST['id']);
			unset($_POST['submit']);
			

			if($id) {
				$price->update($_POST, ['id' => $id]);
			}
			else{
				$price->insert($_POST);
			}

		}
	}

	private function delete()
	{
		if(isset($_GET['action'])) {
	    if($_GET['action'] == 'delete')
		{
			$price = new BaseCustomData('tc_price');
		   
		    $price->delete(['id' => intval($_GET['id']) ]);

		}}
	}

	public function addControls(){
	register_setting( 'TransportCalc', 'TransportCalc', 'sanitize_callback' );


	add_settings_section( 'section_id', 'Основные настройки', '', 'TransportCalc' ); 

	add_settings_field('yandex_api', 'Яндекс API', [&$this, 'fill_yandex_api'], 'TransportCalc', 'section_id' );

	add_settings_field('email', 'email менеджера', [&$this, 'fill_email'],       'TransportCalc', 'section_id' );
   }

   function fill_yandex_api(){
	$val = get_option('TransportCalc');
	$val = $val ? $val['yandex_api'] : null;
		?>
		<input type="text" name="TransportCalc[yandex_api]" value="<?php echo esc_attr( $val ) ?>" />
		<?php
	}

	function fill_email(){
	$val = get_option('TransportCalc');
	$val = isset($val['email']) ? $val['email'] : null;
		?>
		<input type="text" name="TransportCalc[email]" value="<?php echo esc_attr( $val ) ?>" />
		<?php
	}

	/*
	function fill_primer_field2(){
		$val = get_option('TransportCalc');
		$val = $val ? $val['checkbox'] : null;
		?>
		<label><input type="checkbox" name="TransportCalc[checkbox]" value="1" <?php checked( 1, $val ) ?> /> отметить</label>
		<?php
	}*/

	## Очистка данных
	function sanitize_callback( $options ){ 
		// очищаем
		foreach( $options as $name => & $val ){
			if( $name == 'input' )
				$val = strip_tags( $val );

			if( $name == 'checkbox' )
				$val = intval( $val );
		}

		return $options;
	}
}

$settings = new Settings();