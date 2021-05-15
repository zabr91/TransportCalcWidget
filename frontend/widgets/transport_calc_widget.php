<?
namespace TransportCalc;

use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Transport_calc_widget extends Widget_Base {

	

	public static $slug = TCW_TEXT_DOMAIN;

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
        self::htmlbuilder_input_number('mass', 'mass', 0.5, 'Вес, тонны',  '[0-9]+([\.,][0-9]+)?').
		self::htmlbuilder_input_number('size', 'size', 1, 'Объем, м3',     '[0-9]+([\.,][0-9]+)?').

		'</div><div class="calculate-from-data-two flex">'.
		'<p id="passingcargo"></p>'.
	//	self::htmlbuilder_switch().
		self::htmlbuilder_button().

	'<div class="calculate-from-data-two__text calculate-center flex">Полный расчет стоимости переезда происходит во время погрузки</div>
	</div>
	</div>

	</div>'.
'</div></form>'.
	 $this->detalis(); //self::htmlbuilder_popupform();		
	}	
		

		

	public static function htmlbuilder_switch(){
		return '<div class="flex">Наличныe <label class="switch">
  
  <input type="checkbox" checked>
  <span class="slider round"></span>
  
</label>Безналичные</div>';
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
		<input type="number" id="'.$id.'" name="'.$id.'" pattern="'.$pattern.'" min="'.$placeholder.'" placeholder="'.$placeholder.'">
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
		$html = '
	 
		<div class="calculate-from-data-two-button">
	<button class="big-button calculate-center" id="send-calc-result">
			<div class="button-price"><span id="calc-price">0</span> <span>₽ </span></div>
			Отправить заявку
	</button>
</div>';
		return $html;
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

		return $html.'</div>';
	}

	public static function parmsBuilder($block, $i){
		return ' data-persent= "'.	(isset($block['present' .$i])   ? $block['present'.$i] : '0').'" '.
        	   ' data-minprice= "'. (isset($block['min_price'.$i]) ? $block['min_price'.$i] : '0').'" ';
	}

	public static function htmlbuilder_block_radio_list($block)
	{
		$html = '<div class="calculate-from-detailed-block-list-item">
				<input type="radio" name="loding" class="default d-none" value="без лифта" checked>';

		
		for ($i = 0; $i < 3 ; $i++) { 
		if($block['item_switcher'.$i] === 'yes')	
		$html .='<input type="radio" id="check1" name="loding" class="oversized" value="'.$block['value'.$i].'" '.
	self::parmsBuilder($block, $i).'>
					<label for="check1">'.$block['name'.$i].'</label>';

		if($block['tooltip'.$i] === 'yes')
		$html .='<button type="button" class="tooltip-btn" data-toggle="tooltip" data-placement="top" title="Рассчитывается нашим менеджером">?</button>';
		}

		$html .= '</div>';
		return $html;
	}

		public static function htmlbuilder_block_check_list($block)
		{
			$html = '<div class="calculate-from-detailed-block-list-item chk-label">';

			for ($i = 0; $i < 3 ; $i++) { 
				if($block['item_switcher'.$i] === 'yes'){
		$html .=
  '<input type="checkbox" class="oversized calc-options" name="option'.$i.'" value="'.$block['value'.$i].'" '.
  self::parmsBuilder($block, $i).' >
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
   		<h4 class="text-center">Отправить заявку</h4>
   			<form class="tcw-form">
   			   <div>
   			   	<label>Имя</label>
   			   	<input type="text" size="40"  value="" name="name"/>
   			   </div>
			   <div>
			   	<label>Телефон</label>
			   	 <input type="tel" size="40"  value="" name="phone"  placeholder="+7(__)__-__-__" />
			   </div>
			   <div>
			   	<label>e-mail</label>
			   	<input type="email" size="40" value="" name="email" placeholder="Почта"/>
			   </div>
			   
			   	<label><input type="checkbox"/> Заполняя форму обратной связи на сайте '. get_site_url().'
			   <br>я даю согласие на обработку своих персональных данных</label>
			   
   				<div><input type="submit" value="Отправить" id="sendForm" /></div>
            <form>
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
					
		$html .='</div>
		</div>';

		return $html;
	}
}