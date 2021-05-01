<?php
namespace Transport_Calc;

$transportCalcInstallUnstall = new TransportCalcInstallUnstall();

class TransportCalcInstallUnstall
{
	
	function __construct()
	{
		register_activation_hook( __FILE__, [&$this, 'create_plugin_tables'] );
		register_uninstall_hook( __FILE__, [&$this, 'drop_plugin_tables']);
	}



	function create_plugin_tables()
	{
	  global $wpdb;
		 $table_name = $wpdb->prefix . 'price';
		 $sql = "CREATE TABLE $table_name ( `id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(100) NOT NULL , `description` TINYTEXT NOT NULL , `weight` FLOAT UNSIGNED ZEROFILL NOT NULL , `volume` FLOAT UNSIGNED ZEROFILL NOT NULL , `price` DECIMAL UNSIGNED ZEROFILL NOT NULL , `msg` TINYTEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
		 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		 dbDelta( $sql );
	}

	function drop_plugin_tables()
	{
	 global $wpdb;
	 $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'price' );
	}
}