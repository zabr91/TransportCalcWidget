<?php
/**
 * Plugin Name: Elementor Transport Calculate Plugin
 * Description: A simple Elementor Widget that creates an calc
 * Plugin URI:  https://www.soliddigital.com/blog/...
 * Version:     1.0.0
 * Author:      Ivan Zabroda
 * Author URI:  https://pajtai.github.io/
 * Text Domain: elementor-calcplugin
 */
namespace Transport_Calc;

include_once 'TransportCalcMath.php';

use Elementor\Plugin;

$plugin = new PluginTransportCalc();

class PluginTransportCalc {
 
  /**
   * Instance
   *
   * @since 1.0.0
   * @access private
   * @static
   *
   * @var Plugin The single instance of the class.
   */
  private static $_instance = null;

  public static $yandex_maps_api = 'b747871b-9cd7-4287-b762-40d401ff1a79';
 
  /**
   * Instance
   *
   * Ensures only one instance of the class is loaded or can be loaded.
   *
   * @since 1.2.0
   * @access public
   *
   * @return Plugin An instance of the class.
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
 
    return self::$_instance;
  }
 
  /**
   * widget_scripts
   *
   * Load required plugin core files.
   *
   * @since 1.2.0
   * @access public
   */
  public function widget_scripts() {
    wp_enqueue_script( 'ymaps', 'https://api-maps.yandex.ru/2.1/?apikey='.self::$yandex_maps_api.'&lang=ru_RU', null, false, true );
    wp_register_script( 'elementor-transport-calc', plugins_url( '/assets/js/claculator.js', __FILE__ ), [ 'jquery', 'ymaps' ], false, true );

    wp_enqueue_script( 'elementor-transport-calc' );

    wp_localize_script( 'elementor-transport-calc', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		wp_register_style( 'elementor-transport-calc',  plugins_url('/assets/css/style.css', __FILE__ )  );
		wp_enqueue_style( 'elementor-transport-calc' );
  }
 
  /**
   * Include Widgets files
   *
   * Load widgets files
   *
   * @since 1.2.0
   * @access private
   */
  private function include_widgets_files() {
    require_once( __DIR__. '/widgets/transport_calc_widget.php' );
    require_once( __DIR__. '/widgets/transport_calc_map_widget.php' );
    require_once( __DIR__. '/widgets/transport_calc_field_from_widget.php' );
    require_once( __DIR__. '/widgets/transport_calc_field_to_widget.php' );
    require_once( __DIR__. '/widgets/transport_calc_field_size_widget.php' );
    require_once( __DIR__. '/widgets/transport_calc_field_mass_widget.php' );
  }
 
  /**
   * Register Widgets
   *
   * Register new Elementor widgets.
   *
   * @since 1.2.0
   * @access public
   */
  public function register_widgets() {
    // Its is now safe to include Widgets files
    $this->include_widgets_files();    

    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_widget() );
    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_map_widget() );
    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_from_widget() );
    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_to_widget() );
    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_size_widget() );
    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_mass_widget() );
  }

  function register_categories( $elements_manager ) {

  $elements_manager->add_category(
    'transport-calc',
    [
      'title' => __( 'Transport Calc', 'TransportCalcWidget' ),
      'icon' => 'fa fa-truck',
    ]
  );
  }

  public function ajax_get_price() {
    $distance = (float)$_REQUEST['distance'];
    $weight = (float)$_REQUEST['weight'];
    $volume = (float)$_REQUEST['volume'];
    
    $result = [
      'status' => 'OK',
      'result' => TransportCalcMath::calculate($distance, $weight, $volume),
    //  'var_dump' => 'D: '.$distance . ' W:'. $weight . ' V:' . $volume
    ];

    echo json_encode( $result );

    wp_die();
  }

  public function ajax_scripts() {
   
    wp_enqueue_script(  'ajaxHandle');
}

 
  /**
   *  Plugin class constructor
   *
   * Register plugin action hooks and filters
   *
   * @since 1.2.0
   * @access public
   */
  public function __construct() {     

   //  add_action('wp_enqueue_scripts', [$this, 'ajax_scripts']);
    //Register category
    add_action( 'elementor/elements/categories_registered',  [ $this, 'register_categories' ]);
 
    // Register widget scripts
    add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
 
    // Register widgets
    add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

    // wp-admin/admin-ajax.php?action=get_price&distance=196&weight=&volume=9
    /*
    * 196, 1.2, 9
    */
    add_action( 'wp_ajax_get_price',        [ $this, 'ajax_get_price'] );
    
    add_action( 'wp_ajax_nopriv_get_price', [ $this, 'ajax_get_price'] );
  }
}