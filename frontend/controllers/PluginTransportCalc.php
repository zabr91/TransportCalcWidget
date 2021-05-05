<?php

namespace TransportCalc;

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
    
    wp_enqueue_script( 'elementor-transport-jconfirm', TCW_PLUGIN_URL.'frontend/assets/dist/jquery-confirm/jquery-confirm.min.js', ['jquery'], false, true );

    wp_register_script( 'elementor-transport-calc', TCW_PLUGIN_URL.'frontend/assets/js/claculator.js', [ 'jquery', 'ymaps', 'elementor-transport-jconfirm' ], false, true );

    wp_enqueue_script( 'elementor-transport-calc' );

    wp_localize_script( 'elementor-transport-calc', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		wp_register_style( 'elementor-transport-calc',  TCW_PLUGIN_URL.'frontend/assets/css/style.css'  );
		wp_enqueue_style( 'elementor-transport-calc' );

    wp_register_style( 'elementor-transport-calc-swh',  TCW_PLUGIN_URL.'frontend/assets/css/switch.css'  );
    wp_enqueue_style( 'elementor-transport-calc-swh' );

    wp_register_style( 'elementor-transport-calc-jconfirm',  TCW_PLUGIN_URL.'frontend/assets/dist/jquery-confirm/jquery-confirm.min.css'  );
    wp_enqueue_style( 'elementor-transport-calc-jconfirm' );
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
    require_once( TCW_PLUGIN_DIR.'frontend/widgets/transport_calc_widget.php' );

    //add to beta version 
   /* require_once( __DIR__. '/widgets/transport_calc_map_widget.php' );
    require_once( __DIR__. '/widgets/transport_calc_field_from_widget.php' );
    require_once( __DIR__. '/widgets/transport_calc_field_to_widget.php' );
    require_once( __DIR__. '/widgets/transport_calc_field_size_widget.php' );
    require_once( __DIR__. '/widgets/transport_calc_field_mass_widget.php' );*/
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
  /*  Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_map_widget() );
    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_from_widget() );
    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_to_widget() );
    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_size_widget() );
    Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_mass_widget() );*/
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

    $distance = isset($_REQUEST['distance']) ? (float)$_REQUEST['distance'] : 0;
    $weight =   isset($_REQUEST['weight']) ? (float)$_REQUEST['weight'] : 0;
    $volume =   isset($_REQUEST['volume']) ? (float)$_REQUEST['volume'] : 0;
    $options =  $_REQUEST['options'];

    $options = preg_replace("/[\r\n]+/", " ", $options);
    $options = utf8_encode($options);
    $options = stripslashes(trim($options,'"'));
    $options = json_decode($options, true);

    //preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $_REQUEST['options'])

    //Math::calculate(0, 0, 0);

    //TransportCalcMath::
    
    $result = [
      'status' => 'OK', // $distance = 0, $weight = 0, $volume = 0, $parms = null
      'result' => TransportCalcMath::calculate($distance, $weight, $volume, $options),
   //  'options' =>  $options[0]["persent"] 
    ];

    echo json_encode( $result );

    wp_die();
  }

  public function ajax_send_data() {

    $data =[
    'name' =>  strip_tags($_REQUEST['name']),
    'phone' =>  strip_tags($_REQUEST['phone']),
    'email' =>  strip_tags($_REQUEST['email'])
    ];
    
   $formTo = get_option('TransportCalc')['yandex_api'];

    if(isset($formTo)) {

    $formSubject = "Новое сообщение с сайта";
    $formMessage = 
    "<p>Имя ".$data['name']."</p>".
    "<p>Телефон ".$data['phone']."</p>".
    "<p>email ".$data['email']."</p>";

    
    wp_mail( $formTo, $formSubject, $formMessage );
    }

   $messages = new BaseCustomData('tc_messages');
   $messages->insert($data);
   unset($messages);

   echo "OK";
   

    wp_die();
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

    self::$yandex_maps_api = get_option('TransportCalc')['yandex_api'];

    //Register category
    add_action( 'elementor/elements/categories_registered',  [ $this, 'register_categories' ]);
 
    // Register widget scripts
    add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
 
    // Register widgets
    add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

    add_action( 'wp_ajax_get_price',        [ $this, 'ajax_get_price'] );
    
    add_action( 'wp_ajax_nopriv_get_price', [ $this, 'ajax_get_price'] );

    add_action( 'wp_ajax_send_data',        [ $this, 'ajax_send_data'] );
    
    add_action( 'wp_ajax_nopriv_send_data', [ $this, 'ajax_send_data'] );

    }
}