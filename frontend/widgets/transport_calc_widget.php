<?
namespace Transport_Calc;

use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Transport_calc_widget extends Widget_Base {

	

	public static $slug = 'elementor-transport_calc_widget';

	//public static $text_domain = '';

	public function get_name() { return self::$slug; }

	public function get_title() { return __('Elementor Transport Calc', self::$slug); }

	public function get_icon() { return 'fas fa-truck'; }

	public function get_categories() { return [ 'transport-calc' ]; }

	public static $html_class_prefix = 'calculate-from-';

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Settings', self::$slug ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'Yandex_api',
			[
				'label' => __( 'Yandex api', self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', self::$slug ),
				'placeholder' => __( 'Type your title here', self::$slug ),
			]
		);
		/*Repeater main*/
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'block_title', [
				'label' => __( 'Title block', self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , self::$slug ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'block_selector', [
				'label' => __( 'Selector', self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '.class' , self::$slug ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'block_title_show', [
				'label' => __( 'Show title', self::$slug ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', self::$slug ),
				'label_off' => __( 'Hide', self::$slug ),
				'return_value' => 'yes',
				'default' => 'yes'
			]
		);

		$repeater->add_control(
			'block_price_show', [
				'label' => __( 'Show price', self::$slug ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', self::$slug ),
				'label_off' => __( 'Hide', self::$slug ),
				'return_value' => 'yes',
				'default' => 'yes'
			]
		);

		$repeater->add_control(
			'active_switcher',
			[
				'label' => __( 'Active on start', self::$slug ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', self::$slug ),
				'label_off' => __( 'Hide', self::$slug ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$repeater->add_control(
			'floor_switcher',
			[
				'label' => __( 'Floor', self::$slug ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', self::$slug ),
				'label_off' => __( 'Hide', self::$slug ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$repeater->add_control(
			'list_style',
			[
				'label' => __( 'List Style', ' self::$slug ' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'check'  => __( 'Check', self::$slug ),
					'radio' => __( 'Radio', self::$slug ),
				],
			]
		);

		for ($i=0; $i < 3 ; $i++) {

			$repeater->add_control(
			('item_switcher').$i,
			[
				'label' => __( 'Visible block ', self::$slug ) .($i + 1),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', self::$slug ),
				'label_off' => __( 'Hide', self::$slug ),
				'return_value' => 'yes',
				'default' => 'yes',
			]);
	
			$repeater->add_control(
			('name').$i, [
				'label' => __( 'Name', self::$slug ).($i + 1),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Name' , self::$slug ),
				'label_block' => true,
			]);
		    $repeater ->add_control(
			('value').$i, [
				'label' => __( 'Value', self::$slug ).($i + 1),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Value' , self::$slug ),
				'label_block' => true,
			]); 
		    $repeater ->add_control(
			('tooltip').$i, [
				'label' => __( 'Tooltip'.$i, self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Tooltip' , self::$slug ),
				'label_block' => true,
			]); 
			$repeater ->add_control(
			('min_price').$i, [
				'label' => __( 'min_price'.$i, self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'min_price' , self::$slug ),
				'label_block' => true,
			]);
			$repeater ->add_control(			
			('present').$i, [
				'label' => __( 'present'.$i, self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'present' , self::$slug ),
				'label_block' => true,
			]);
		}

		$this->add_control(
			'list',
			[
				'label' => __( 'Options', self::$slug ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'block_title' => __( 'Loading into', self::$slug ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', self::$slug ),
						'floor_switcher' => 'yes',
					//	'repeater_options' => $repeater_options,
					],
					[
						'block_title' => __( 'Unloading', self::$slug ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', self::$slug ),
						'floor_switcher' => 'yes',
					//	'repeater_options' => $repeater_options,
					],					
					[
						'block_title' => __( 'Pacacing', self::$slug ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', self::$slug ),
						'floor_switcher' => 'yes',
					//	'repeater_options' => $repeater_options,
					],
				],
				'title_field' => '{{{ block_title }}}',
			]
		);

		$this->end_controls_section();		
}
	protected function render() {
	echo '<div class="calculate-wrap-form">'.self::htmlbuilder_map().
	'<form action="#" class="calculate-from">
					<div class="calculate-from_top" id="calculate">
					<div class="calculate-from-position calculate-center flex">'.
		 self::htmlbuilder_input('position__from', 'mapFrom', 'Откуда').
		 '<div class="calculate-from-position__dist"><span id="distance"></span></div>'.
		 self::htmlbuilder_input('position__to', 'mapTo', 'Куда').
     '</div>'.
     	'<div class="calculate-from-data flex">'.
		'<div class="calculate-from-data-one flex">'.
		self::htmlbuilder_input_number('size', 'size', 1, 'Объем, м3',     '[0-9]+([\.,][0-9]+)?').
		self::htmlbuilder_input_number('mass', 'mass', 0.15, 'Вес, тонны',  '[0-9]+([\.,][0-9]+)?').
		'</div><div class="calculate-from-data-two flex">'.
		self::htmlbuilder_button().

	'<div class="calculate-from-data-two__text calculate-center flex">Полный расчет стоимости переезда происходит во время погрузки</div>
	</div>
	</div>

	</div>'.
'</div></form>'.
	 $this->detalis() . self::htmlbuilder_popupform();		
	}	

	public static function htmlbuilder_map($class = 'calculate-map' , $id = 'map'){
		return '<div class="'.$class.'"><div id="'.$id.'"></div></div>';
	}

	public static function htmlbuilder_input($class, $id, $placeholder , $flex = "flex") {
		return '<div class="'.self::$html_class_prefix.$class .' '.$flex.'">
		<input type="text" placeholder="'.$placeholder.'" id="'.$id.'">
	    </div>';
	}

	public static function htmlbuilder_input_number($class, $id, $placeholder, $label,  $pattern){
		return '	<div class="calculate-from-data-one__size flex">
		<label for="size">'.$label.'</label>
		<input type="number" id="'.$id.'" name="'.$id.'" pattern="'.$pattern.'" min="0" placeholder="'.$placeholder.'">
	</div>';
	}

	public static function htmlbuilder_select($class = null){
		$html = "<select ".($class ? "class = '". $class ."'" : " "). ">";
		for ($i=1; $i <= 10 ; $i++) { 
			$html .= '<option>'.($i).'</option>';
		}
		$html .= "</select>";
		return $html;
	}


	
	public static function htmlbuilder_button(){
		return '<div class="calculate-from-data-two-button">
		<button class="big-button calculate-center" id="send-calc-result">
			<div class="button-price"><span id="calc-price">0</span> <span>₽ </span></div>
			Отправить заявку
		</button>
	</div>';
	}

	public static function htmlbuilder_calculate_from_detailed_block($block, $count_colums ){

		$colums = "calc-col-3";
		if($count_colums == 1) $colums = "calc-col-1";
		elseif ($count_colums == 2 ) $colums = "calc-col-2";
		else $colums = "calc-col-3";

		$calculate_disabled = ('yes' != $block['active_switcher'] ? 'calculate-disabled' : '');
		$block_price_show = ('yes' === $block['block_price_show'] ? '<div class="calculate-from-detailed-block__price">0 <span class="currency-symbol">Р</span></div>' : '');
		$block_price_title = ('yes' === $block['block_title_show'] ? '<div class="calculate-from-detailed-block__check flex">
										<div class="squaredFour">
											<input type="checkbox" class="lading-discharging" id="lading" name="lading" />
											<label for="lading"></label>
										</div>
										<p>'.$block['block_title'].'</p>
									</div>' : '');
		$block_foor_show = ( 'yes' === $block['floor_switcher'] ) ? '<div class="calculate-from-detailed-block-total__floor flex">Этаж'.self::htmlbuilder_select('floors').'</div>' : '';


		$html = '<div class="'.$block['block_selector'].' calculate-from-detailed-block '.$colums .' '.
		$calculate_disabled.' flex calculate-center">
				<div class="calculate-from-detailed-block-wrap calculate-center" >'.$block_price_title.$block_price_show.
		'<div class="calculate-from-detailed-block-total">' . $block_foor_show;	


		if($block['list_style'] === 'radio')
		{
			$html .= '</div>'.self::htmlbuilder_block_radio_list($block).'</div>';
		}
		else{
			$html .= '</div>'.self::htmlbuilder_block_check_list($block).'</div>';
		}

		return $html;
	}

	public static function htmlbuilder_block_radio_list($block)
	{
		$html = '<div class="calculate-from-detailed-block-list-item">
				<input type="radio" name="loding" class="default d-none" value="без лифта" checked>';

		
		for ($i = 0; $i < 3 ; $i++) { 
		if($block['item_switcher'.$i] === 'yes')	
		$html .='<input type="radio" id="check1" name="loding" class="oversized" value="'.$block['value'.$i].'">
					<label for="check1">'.$block['name'.$i].'</label>';

		//if($block['tooltip'.$i] === 'yes')
		$html .='<button type="button" class="tooltip-btn" data-toggle="tooltip" data-placement="top" title="Рассчитывается нашим менеджером">?</button>';
		}

		$html .= '</div></div>';
		return $html;
	}

		public static function htmlbuilder_block_check_list($block)
		{
			$html = '<div class="calculate-from-detailed-block-list-item chk-label">';

			for ($i = 0; $i < 3 ; $i++) { 
				if($block['item_switcher'.$i] === 'yes'){
		$html .=
  '<input id="check1" type="checkbox" class="oversized" name="option'.$i.'" value="'.$block['value'.$i].'" 
  data-persent="'.$block['present'.$i].'" >
  <label for="check1">'.$block['name'.$i].'</label><br />';		
				}
			}


			$html .= '</div>';

			return $html;
		}

		public static function htmlbuilder_popupform() {
			$html = '<div id="transport_calc_popup" class="modal">
  						<div class="modal-content">
    						<span id="close-modal">&times;</span>
   							<div class="popup__from">
    <span class="wpcf7-form-control-wrap you-name"><input type="text" name="you-name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required popup__name" aria-required="true" aria-invalid="false" placeholder="Имя"></span><br>
    <span class="wpcf7-form-control-wrap your-tel"><input type="tel" name="your-tel" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-required wpcf7-validates-as-tel popup__tel" aria-required="true" aria-invalid="false" placeholder="+7(__)__-__-__"></span><br>
    <span class="wpcf7-form-control-wrap your-mail"><input type="email" name="your-mail" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-email popup__email" aria-invalid="false" placeholder="Почта"></span><br>
    <input type="submit" value="Отправить заявку" class="wpcf7-form-control wpcf7-submit" disabled=""><span class="ajax-loader"></span><p></p>
<input type="hidden" name="result" value="" class="wpcf7-form-control wpcf7-hidden" id="calcresult">
<div class="popup-check">
    <span class="wpcf7-form-control-wrap acceptance-761"><span class="wpcf7-form-control wpcf7-acceptance"><span class="wpcf7-list-item"><input type="checkbox" name="acceptance-761" value="1" aria-invalid="false"></span></span></span><br>
        <span><br>
            Заполняя форму обратной связи на сайте www.kangor.ru,<br>
            я даю согласие на обработку своих персональных данных<br>
        </span>
    </div>
</div>
  						</div>
					</div>';


			return $html;
		}



	private function detalis()
	{
		$html = '<div class="calculate-from_bottom">
					<div class="calculate-from_bottom-wrap flex">';

					$settings = $this->get_settings_for_display();

					$count_colums = count( $settings['list'] );

					if ( $settings['list'] ) {

						foreach ($settings['list'] as $block) {
							$html .= self::htmlbuilder_calculate_from_detailed_block( $block, $count_colums );
						}

					}
					
		$html .='</div>';

		return $html;
	}
}