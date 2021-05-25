<?php
//namespace Transport_Calc;

$transportCalcInstallUnstall = new TransportCalcInstallUnstall(TCW_FILE);

class TransportCalcInstallUnstall
{
	public $file;

	function __construct($file)
	{
		$this->file = $file;

		register_activation_hook( $this->file, [&$this, 'install'] );
		//register_uninstall_hook( $this->file, [&$this, 'unistall']);

 
		add_action( 'plugins_loaded', [&$this, 'textdomain_init'] );
	}


	function textdomain_init() {

		 $mo_file_path = TCW_PLUGIN_DIR . '/languages/'.TCW_TEXT_DOMAIN.'-'.determine_locale() . '.mo';

	     load_textdomain( TCW_TEXT_DOMAIN, $mo_file_path );
	}




	function install()
	{//Distance
	  global $wpdb;
		 $table_price = $wpdb->prefix . 'tc_price';
		 $table_messages = $wpdb->prefix . 'tc_messages';
		 $sql = "

		 CREATE TABLE IF NOT EXISTS $table_price ( `id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(100) NOT NULL , `description` TINYTEXT NOT NULL , `weight` FLOAT  NOT NULL , `distance` INT  NOT NULL , `volume` FLOAT NOT NULL , `price` DECIMAL NOT NULL , `msg` TINYTEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;


        CREATE TABLE IF NOT EXISTS $table_messages ( `id` INT NOT NULL , `send` TIMESTAMP NOT NULL , `name` VARCHAR(100) NOT NULL , `phone` VARCHAR(20) NOT NULL , `from` VARCHAR(100) NULL , `to` VARCHAR(100) NULL , `distance` INT NULL , `volume` FLOAT  NULL , `weight` INT NULL , `options` TEXT NULL , `email` VARCHAR(255) NULL , `price` INT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;


		 ";
		 

		 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		 dbDelta( $sql );

		 //$price = new BaseCustomData('tc_price');

		 $item1 = ['weight' => 1.5, 'volume' => 10,	'price' => 22];
         $item2 = ['weight' =>  3.5,'volume' => 20, 'price' => 27];
		 $item3 = ['weight' =>  5.5,'volume' =>	35,	'price' => 32];
		 $item4 = ['weight' =>  9.5,'volume' => 50,	'price' => 55];
		 $item5 = ['weight' =>  20,	'volume' => 82,	'price' => 65];
		


		/* $price->insert( $item1 );
		 $price->insert( $item2 );
		 $price->insert( $item3 );
		 $price->insert( $item4 );
		 $price->insert( $item5 );*/
		
	}

	function unistall()
	{

		/*echo "work";
		wp_die();*/



	// global $wpdb;
	// $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'tc_price' );
	}
}


