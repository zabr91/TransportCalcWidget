<?php
namespace Transport_Calc;

$TransportCalctings = new TransportCalcSettings();

//https://oiplug.com/blog/wordpress/4143/

class TransportCalcSettings
{
	
	function __construct()
	{
		add_action( 'admin_menu', [&$this, 'add_page'] );
		add_action('admin_init', [&$this, 'settings']);
	}

	public function add_page(){
		add_options_page( 'Настройки TransportCalc', 'TransportCalc', 'manage_options', 'primer_slug', [&$this, 'wiev'] );
	}

	public function wiev(){ ?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>
				<form action="options.php" method="POST">
			<?php
				settings_fields( 'TransportCalc' );  
				do_settings_sections( 'TransportCalc' );
				submit_button();
			?>
		</form>
	</div>
	<? }

	public function settings(){
	register_setting( 'TransportCalc', 'TransportCalc', 'sanitize_callback' );


	add_settings_section( 'section_id', 'Основные настройки', '', 'TransportCalc' ); 

	add_settings_field('yandex_api', 'Яндекс API', [&$this, 'fill_yandex_api'], 'TransportCalc', 'section_id' );
   }

   function fill_yandex_api(){
	$val = get_option('TransportCalc');
	$val = $val ? $val['yandex_api'] : null;
		?>
		<input type="text" name="TransportCalc[yandex_api]" value="<?php echo esc_attr( $val ) ?>" />
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