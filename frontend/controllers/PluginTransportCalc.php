<?php

namespace TransportCalc;

use Elementor\Plugin;

$plugin = new PluginTransportCalc();

class PluginTransportCalc
{

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
     * @return Plugin An instance of the class.
     * @since 1.2.0
     * @access public
     *
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
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
    public function widget_scripts()
    {
        wp_enqueue_script('ymaps', 'https://api-maps.yandex.ru/2.1/?apikey=' . self::$yandex_maps_api . '&lang=ru_RU', null, false, true);

        wp_enqueue_script('elementor-transport-jconfirm', TCW_PLUGIN_URL . 'frontend/assets/dist/jquery-confirm/jquery-confirm.min.js', ['jquery'], false, true);

        wp_register_script('elementor-transport-calc', TCW_PLUGIN_URL . 'frontend/assets/js/claculator.js', ['jquery', 'ymaps', 'elementor-transport-jconfirm'], false, true);

        wp_enqueue_script('elementor-transport-calc');

        wp_localize_script('elementor-transport-calc', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));

        wp_register_style('elementor-transport-calc', TCW_PLUGIN_URL . 'frontend/assets/css/style.css');
        wp_enqueue_style('elementor-transport-calc');

        wp_register_style('elementor-transport-calc-swh', TCW_PLUGIN_URL . 'frontend/assets/css/switch.css');
        wp_enqueue_style('elementor-transport-calc-swh');

        wp_register_style('elementor-transport-calc-jconfirm', TCW_PLUGIN_URL . 'frontend/assets/dist/jquery-confirm/jquery-confirm.min.css');
        wp_enqueue_style('elementor-transport-calc-jconfirm');
    }

    /**
     * Include Widgets files
     *
     * Load widgets files
     *
     * @since 1.2.0
     * @access private
     */
    private function include_widgets_files()
    {
        require_once(TCW_PLUGIN_DIR . 'frontend/widgets/transport_calc_widget.php');

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
    public function register_widgets()
    {
        // Its is now safe to include Widgets files
        $this->include_widgets_files();

        Plugin::instance()->widgets_manager->register_widget_type(new Transport_calc_widget());
        /*  Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_map_widget() );
          Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_from_widget() );
          Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_to_widget() );
          Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_size_widget() );
          Plugin::instance()->widgets_manager->register_widget_type( new Transport_calc_field_mass_widget() );*/
    }

    /**
     * Register catigories
     * @param $elements_manager
     */
    function register_categories($elements_manager)
    {
        $elements_manager->add_category(
            'transport-calc',
            [
                'title' => __('Transport Calc', 'TransportCalcWidget'),
                'icon' => 'fa fa-truck',
            ]
        );
    }

    /**
     * Get price dev
     */
    public function ajax_get_price()
    {

        $distance = isset($_REQUEST['distance']) ? (float)$_REQUEST['distance'] : 0;
        $weight = isset($_REQUEST['weight']) ? (float)$_REQUEST['weight'] : 0;
        $volume = isset($_REQUEST['volume']) ? (float)$_REQUEST['volume'] : 0;
        $options = $_REQUEST['options'];

        $options = preg_replace("/[\r\n]+/", " ", $options);
        $options = utf8_encode($options);
        $options = stripslashes(trim($options, '"'));
        $options = json_decode($options, true);

        $result = [
            'status' => 'OK', // $distance = 0, $weight = 0, $volume = 0, $parms = null
            'result' => TransportCalcMath::calculate($distance, $weight, $volume, $options),
            //  'options' =>  $options[0]["persent"]
        ];

        echo json_encode($result);

        wp_die();
    }

    /**
     * Insert data to database and send mail
     */
    public function ajax_send_data()
    {

        $dataClient = [
            'Имя' => strip_tags($_REQUEST['name']),
            'Телефон' => strip_tags($_REQUEST['phone']),
            'email' => strip_tags($_REQUEST['email']),
            //'from' => strip_tags($_REQUEST['from']),
            //'from' => strip_tags($_REQUEST['to']),
        ];

        $dataRoute = [
            'ПунктА' => strip_tags($_REQUEST['from']),
            'ПункиВ' => strip_tags($_REQUEST['to']),
            'Расстояние' => strip_tags($_REQUEST['distance']),
            'Объем' => strip_tags($_REQUEST['volume']),
            'Масса' => strip_tags($_REQUEST['weight']),
            'Цена' => strip_tags($_REQUEST['price']),
            'Форма оплаты' => strip_tags($_REQUEST['formpay']),
            'Опции' => strip_tags($_REQUEST['options']),
        ];

        $formTo = get_option('TransportCalc')['email'];

        if (isset($formTo)) {

            $dataTimeNow = date("Y-m-d H:i:s");

            $formSubject = "Новое сообщение с сайта " . get_site_url();
            ob_start();
            include(TCW_PLUGIN_DIR . 'frontend/assets/email/email-template.php');
            $emailContent = ob_get_contents();
            ob_end_clean();

            //   echo $emailContent;

            $headers = ['Content-Type: text/html; charset=UTF-8',
                'From: TransportCalc <' . get_option('TransportCalc')['fromemail'] . '>' . "\r\n"];

            if (wp_mail($formTo, $formSubject, $emailContent, $headers)) {
                echo "1";
            }
        }

        $data = [
            'name' => strip_tags($_REQUEST['name']), //1
            'phone' => strip_tags($_REQUEST['phone']), //2
            'from' => strip_tags($_REQUEST['from']), //3
            'to' => strip_tags($_REQUEST['to']), //4
            'distance' => strip_tags($_REQUEST['distance']), //5
            'volume' => strip_tags($_REQUEST['volume']), //6
            'weight' => strip_tags($_REQUEST['weight']), //7
            'options' => strip_tags($_REQUEST['options']), //8
            'email' => strip_tags($_REQUEST['email']), //9
            'price' => strip_tags($_REQUEST['price']) //10
        ];

        $messages = new BaseCustomData('tc_messages');
        $messages->insert($data);

        //echo $wpdb->show_errors;

        unset($messages);

        wp_die();
    }

    /**
     * Get table price
     * @return Array weight, volume for placeholder
     */
    public function ajax_get_table()
    {
        $pricetable = new BaseCustomData('tc_price');
        $table = $pricetable->get_all(null, 'weight, volume');

        $arr = [];
        $countRows = count($table);

        for ($i = 0; $i < $countRows; $i++) {
            $arr[$i][0] = $table[$i]->weight;
            $arr[$i][1] = $table[$i]->volume;
        }

        echo json_encode($arr);
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
    public function __construct()
    {

        self::$yandex_maps_api = get_option('TransportCalc')['yandex_api'];

        //Register category
        add_action('elementor/elements/categories_registered', [$this, 'register_categories']);

        // Register widget scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);

        // Register widgets
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);


        // Register ajax metods
        add_action('wp_ajax_get_price', [$this, 'ajax_get_price']);

        add_action('wp_ajax_nopriv_get_price', [$this, 'ajax_get_price']);

        add_action('wp_ajax_send_data', [$this, 'ajax_send_data']);

        add_action('wp_ajax_nopriv_send_data', [$this, 'ajax_send_data']);

        add_action('wp_ajax_get_table', [$this, 'ajax_get_table']);

        add_action('wp_ajax_nopriv_get_table', [$this, 'ajax_get_table']);

    }
}