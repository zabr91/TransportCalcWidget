<?
namespace Transport_Calc;

use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Transport_calc_widget extends Widget_Base {

	

	public static $slug = 'elementor-transport_calc_widget';

	public function get_name() { return self::$slug; }

	public function get_title() { return __('Elementor Transport Calc', self::$slug); }

	public function get_icon() { return 'fas fa-truck'; }

	public function get_categories() { return [ 'transport-calc' ]; }

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Settings', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'Yandex_api',
			[
				'label' => __( 'Yandex api', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'plugin-domain' ),
				'placeholder' => __( 'Type your title here', 'plugin-domain' ),
			]
		);
		/*Repeater main*/
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title', [
				'label' => __( 'Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'plugin-domain' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_selector', [
				'label' => __( 'Selector', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '.class' , 'plugin-domain' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'floor_switcher',
			[
				'label' => __( 'Floor', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		for ($i=0; $i < 3 ; $i++) { 
			$repeater->add_control(
			('name').$i, [
				'label' => __( 'Name'.$i, 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Name' , 'plugin-domain' ),
				'label_block' => true,
			]);
		    $repeater ->add_control(
			('value').$i, [
				'label' => __( 'Value'.$i, 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Value' , 'plugin-domain' ),
				'label_block' => true,
			]);
			$repeater ->add_control(
			('min_price').$i, [
				'label' => __( 'min_price'.$i, 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'min_price' , 'plugin-domain' ),
				'label_block' => true,
			]);
			$repeater ->add_control(			
			('present').$i, [
				'label' => __( 'present'.$i, 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'present' , 'plugin-domain' ),
				'label_block' => true,
			]);
		}



		$this->add_control(
			'list',
			[
				'label' => __( 'Options', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'Loading into', 'plugin-domain' ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', 'plugin-domain' ),
						'floor_switcher' => 'yes',
					//	'repeater_options' => $repeater_options,
					],
					[
						'list_title' => __( 'Unloading', 'plugin-domain' ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', 'plugin-domain' ),
						'floor_switcher' => 'yes',
					//	'repeater_options' => $repeater_options,
					],					
					[
						'list_title' => __( 'Pacacing', 'plugin-domain' ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', 'plugin-domain' ),
						'floor_switcher' => 'yes',
					//	'repeater_options' => $repeater_options,
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();		
}
	protected function render() {
	echo self::htmlbilder_map().
		 self::htmlbilder_input('position__from', 'mapFrom', 'Откуда').
		 self::htmlbilder_input('position__to', 'mapTo', 'Куда').
		// self::htmlbilder_input('data-one__size', 'mapTo', 'Куда').
	'

	<!-- CONTROLS -->
	
	<div class="calculate-from-data-one__size">
		<label for="size">Объем, м3</label>
		<input type="number" id="size" name="size" pattern="[0-9]+([\.,][0-9]+)?" min="0" placeholder="10">
	</div>
	<div class="calculate-from-data-one__mass">
		<label for="mass">Вес, кг</label>
		<input type="number" id="mass" name="mass" pattern="[0-9]+([\.,][0-9]+)?" min="0" placeholder="1750">
	</div>

	<div class="calculate-from-data-two-button">
		<button class="big-button send-calc-result">
			<div class="button-price">0 <span>₽</span></div>
			Отправить заявку
		</button>
	</div>'. $this->detalis().'
	<style type="text/css">
		#map {min-width: 100%; height: 500px;}
	</style>';		
	}

	public static $html_class_prefix = 'calculate-from-';

	public static function htmlbilder_map($class = 'calculate-map' , $id = 'map'){
		return '<div class="'.$class.'"><div id="'.$id.'"></div></div>';
	}
	public static function htmlbilder_input($class, $id, $placeholder) {
		return '<div class="'.self::$html_class_prefix.$class.'">
		<input type="text" placeholder="'.$placeholder.'" id="'.$id.'">
	    </div>';
	}

	public static function htmlbilder_input_number($class, $id, $placeholder, $label_block,  $pattern){
		return '	<div class="calculate-from-data-one__size">
		<label for="size">Объем, м3</label>
		<input type="number" id="size" name="size" pattern="[0-9]+([\.,][0-9]+)?" min="0" placeholder="10">
	</div>';
	}

	public static function htmlbilder_select($class = null){
		$html = "<select ".($class ? "class = '". $class ."'" : " "). ">";
		for ($i=1; $i < 10 ; $i++) { 
			$html .= '<option>'.$i.'</option>';
		}
		$html .= "</select>";
		return $html;
	}
	

	private function detalis()
	{
		return '
		<div class="calculate-from_bottom">
						<div class="calculate-from-detailed__title">
							подробный расчет
						</div>
						<div class="calculate-from_bottom-wrap">
							<div class="loding-block calculate-from-detailed-block">
								<div class="calculate-from-detailed-block-wrap">
									<div class="calculate-from-detailed-block__price">
										0 <span class="currency-symbol">Р</span>
									</div>
									<div class="calculate-from-detailed-block__check">
										<div class="squaredFour">
											<input type="checkbox" class="lading-discharging" id="lading" name="lading" />
											<label for="lading"></label>
										</div>
										<p>погрузка</p>
									</div>
									<div class="calculate-from-detailed-block-total">
										<div class="calculate-from-detailed-block-total__porter d-none">
											Грузчики
'.self::htmlbilder_select().'
										</div>
										<div class="calculate-from-detailed-block-total__floor">
											Этаж
'.self::htmlbilder_select('floors').'
										</div>
									</div>
									<div class="calculate-from-detailed-block-list">
										<div class="calculate-from-detailed-block-list-item">
											<input type="radio" name="loding" class="default d-none" value="без лифта" checked>
											<input type="radio" id="check1" name="loding" class="oversized" value="негабарит">
											<label for="check1">
												негабаритный
												груз
											</label>
											<button type="button" class="tooltip-btn" data-toggle="tooltip" data-placement="top" title="Рассчитывается нашим менеджером">?</button>
										</div>

										<div class="calculate-from-detailed-block-list-item">
											<input type="radio" id="check2" name="loding" class="p-lift" value="пасажирский лифт">
											<label for="check2">
												пассажирский
												лифт
											</label>
										</div>

										<div class="calculate-from-detailed-block-list-item">
											<input type="radio" id="check3" name="loding" class="s-lift" value="грузовой лифт">
											<label for="check3">
												грузовой
												лифт
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="discharging-block calculate-from-detailed-block calculate-disabled">
								<div class="calculate-from-detailed-block-wrap">
									<div class="calculate-from-detailed-block__price">
										 0 <span class="currency-symbol">Р</span>
									</div>
									<div class="calculate-from-detailed-block__check">
										<div class="squaredFive">
											<input type="checkbox" class="lading-discharging" id="discharging" name="discharging">
											<label for="discharging"></label>
										</div>
										<p>разгрузка</p>
									</div>
									<div class="calculate-from-detailed-block-total">
										<div class="calculate-from-detailed-block-total__porter d-none">
											Грузчики
'.self::htmlbilder_select().'
										</div>
										<div class="calculate-from-detailed-block-total__floor">
											Этаж
'.self::htmlbilder_select('floors').'
										</div>
									</div>
									<div class="calculate-from-detailed-block-list">
										<div class="calculate-from-detailed-block-list-item">
											<input type="radio" name="discharging" class="default d-none" value="без лифта" checked>
											<input type="radio" id="check33" name="discharging" class="oversized" value="негабарит">
											<label for="check33">
												негабаритный
												груз
											</label>
											<button type="button" class="tooltip-btn" data-toggle="tooltip" data-placement="top" title="Рассчитывается нашим менеджером">?</button>
										</div>

										<div class="calculate-from-detailed-block-list-item">
											<input type="radio" id="check44" name="discharging" class="p-lift" value="пасажирский лифт">
											<label for="check44">
												пассажирский
												лифт
											</label>
										</div>

										<div class="calculate-from-detailed-block-list-item">
											<input type="radio" id="check55" name="discharging" class="s-lift" value="грузовой лифт">
											<label for="check55">
												грузовой
												лифт
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="packing-block calculate-from-detailed-block">
								<div class="calculate-from-detailed-block-wrap">
									<div class="calculate-from-detailed-block__price">
										0 <span class="currency-symbol">Р</span>
									</div>
									<div class="calculate-from-detailed-block__check">
										<div class="squaredSix">
											<input type="checkbox" id="packing" class="lading-discharging" name="packing"/>
											<label for="packing"></label>
										</div>
										<p>упаковка</p>
									</div>
									<div class="calculate-from-detailed-block-list">
										<div class="calculate-from-detailed-block__checktwo">
											<div class="roundedOne">
												<input type="radio" name="packing-value" class="default d-none" value="упаковка не выбрана" checked>
												<input type="radio" id="roundedOne" name="packing-value" value="эконом">
												<label for="roundedOne"></label>
											</div>
											<p>
												эконом <br><span>Стрейч-плека,
													амортизационная пленка,<br>
													фиксация в кузове.</span>
											</p>
										</div>
										<div class="calculate-from-detailed-block__checkthree">
											<div class="roundedTwo">
												<input type="radio" id="roundedTwo" name="packing-value" value="стандарт">
												<label for="roundedTwo"></label>
											</div>
											<p>
												стандарт
											</p>
										</div>
										<div class="calculate-from-detailed-block__checkfour">
											<div class="roundedThree">
												<input type="radio" id="roundedThree" name="packing-value" value="vip">
												<label for="roundedThree"></label>
											</div>
											<p>
												VIP
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
	}
}