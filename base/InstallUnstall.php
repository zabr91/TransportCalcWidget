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
	}



	function install()
	{//Distance
	  global $wpdb;
		 $table_price = $wpdb->prefix . 'tc_price';
		 $table_messages = $wpdb->prefix . 'tc_messages';
		 $sql = "


		 CREATE TABLE IF NOT EXISTS $table_price ( `id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(100) NOT NULL , `description` TINYTEXT NOT NULL , `weight` FLOAT  NOT NULL , `distance` INT  NOT NULL , `volume` FLOAT NOT NULL , `price` DECIMAL NOT NULL , `msg` TINYTEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS $table_messages ( `id` INT NULL , `send` TIMESTAMP NOT NULL , `name` VARCHAR(100) NOT NULL , `phone` VARCHAR(20) NOT NULL , `pointa` VARCHAR(100) NULL , `pointb` VARCHAR(100) NULL , `distance` INT NULL , `volume` FLOAT  NULL , `weight` INT NULL , `options` JSON NULL , `email` VARCHAR(255) NULL , `price` INT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;


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

/*
Notice: Функция register_uninstall_hook вызвана неправильно. Для удаления можно использовать только статический метод класса или функцию. Дополнительную информацию можно найти на странице «Отладка в WordPress». (Это сообщение было добавлено в версии 3.1.0.) in /opt/lampp/htdocs/transport.localhost/wordpress/wp-includes/functions.php on line 5313

Fatal error: Uncaught Error: Class 'Transport_Calc\BaseCustomData' not found in /opt/lampp/htdocs/transport.localhost/wordpress/wp-content/plugins/TransportCalcWidget/base/InstallUnstall.php:28 Stack trace: #0 /opt/lampp/htdocs/transport.localhost/wordpress/wp-includes/class-wp-hook.php(292): Transport_Calc\TransportCalcInstallUnstall->install('') #1 /opt/lampp/htdocs/transport.localhost/wordpress/wp-includes/class-wp-hook.php(316): WP_Hook->apply_filters('', Array) #2 /opt/lampp/htdocs/transport.localhost/wordpress/wp-includes/plugin.php(484): WP_Hook->do_action(Array) #3 /opt/lampp/htdocs/transport.localhost/wordpress/wp-admin/plugins.php(193): do_action('activate_Transp...') #4 {main} thrown in /opt/lampp/htdocs/transport.localhost/wordpress/wp-content/plugins/TransportCalcWidget/base/InstallUnstall.php on line 28


*/