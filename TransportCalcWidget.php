<?php
/**
 * Plugin Name: Elementor Transport Calculate Plugin
 * Description: A simple Elementor Widget that creates an calc
 * Version:     1.1.0
 * Author:      Ivan Zabroda
 * Author URI:  https://pajtai.github.io/
 * Text Domain: elementor-transport_calc_widget
 */
namespace TransportCalc;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( 'TCW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TCW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TCW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'TCW_FILE',  __FILE__  );


include TCW_PLUGIN_DIR.'base/abstract/BaseCustomData.php';;
// admin panel
include_once TCW_PLUGIN_DIR.'backend/controllers/Settings.php';
include_once TCW_PLUGIN_DIR.'backend/controllers/Messages.php';

include_once TCW_PLUGIN_DIR.'base/InstallUnstall.php';

// Math operations
include_once TCW_PLUGIN_DIR.'base/TransportCalcMath.php';

// front end
include_once TCW_PLUGIN_DIR.'frontend/controllers/PluginTransportCalc.php';


function get_frontend_template($template){
	return include_once(TCW_PLUGIN_DIR. 'frontend/templates/'.$template.'.php');
}

