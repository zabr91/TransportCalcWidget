<?
namespace Transport_Calc;

use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Transport_calc_field_to_widget extends Widget_Base {

	

	public static $slug = 'elementor-transport_calc_field_to_widget';

	public function get_name() { return self::$slug; }

	public function get_title() { return __('End transport', self::$slug); }

	public function get_icon() { return 'fas fa-edit'; }

	public function get_categories() { return [ 'transport-calc' ]; }

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Options', self::$slug ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Use the repeater to define one one set of the items we want to repeat look like
		$repeater = new Repeater();

		$repeater->add_control(
			'option_value',
			[
				'label' => __( 'Option Value', self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( "The Option's Value", self::$slug ),
				'placeholder' => __( 'Value Attribute', self::$slug ),
			]
		);

		$repeater->add_control(
			'option_contents',
			[
				'label' => __( 'Option Contents', self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( "The Option's Contents", self::$slug ),
				'placeholder' => __( 'Option Contents', self::$slug ),
			]
		);

		// Add the
		$this->add_control(
			'options_list',
			[
				'label' => __( 'Repeater List', self::$slug ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[]
				],
				'title_field' => '{{{ option_contents }}}'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
	echo '		<div class="calculate-from-position__to">
		<input type="text" placeholder="Куда" id="mapTo">
	</div>
	';
		
	}
}