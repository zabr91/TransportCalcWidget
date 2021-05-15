<?php
namespace TransportCalc;

/**
 * Отправленые сообщения
 */

$messages = new Messages();

class Messages{
	function __construct()
	{
		$this->delete();

		//$this->save();
		add_action( 'admin_menu', [&$this, 'add_admin_menu'] );
	}

	public function add_admin_menu(){
		$hook = add_submenu_page( 'transportcalc-settings', 
			'Сообщения', 
			'Сообщения',
            'manage_options',
            'transportcalc-sub-settings',
        	[&$this, 'wiev']);

		add_action( "load-$hook", [&$this, 'page_load'] );
	}
	

	function page_load(){
	
		require_once TCW_PLUGIN_DIR . 'backend/controllers/TableMessages.php'; // тут находится класс Example_List_Table...
	
		$GLOBALS['Example_List_Table'] = new TableMessages();// создаем экземпляр и сохраним его дальше выведем

		}


	function wiev() {
		
		require_once TCW_PLUGIN_DIR . 'backend/templates/messagespage.php'; 		
		
	}

	private function save(){
		
     /*  	if($_POST){

			$price = new BaseCustomData('tc_price');

			$id =$_POST['id'];
			unset($_POST['id']);
			unset($_POST['submit']);
			

			if($id) {
				$price->update($_POST, ['id' => $id]);
			}
			else{
				$price->insert($_POST);
			}

		}*/
	}

	private function delete()
	{
		if(isset($_GET['action'])) {
	    if($_GET['action'] == 'deletemessage')
		{
			$price = new BaseCustomData('tc_messages');
		   
		    $price->delete(['id' => intval($_GET['id']) ]);

		}}
	}
}