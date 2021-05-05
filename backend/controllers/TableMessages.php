<?php

namespace TransportCalc;

use WP_List_Table;



class TableMessages extends WP_List_Table {

	function __construct(){
		parent::__construct(array(
			'singular' => 'log',
			'plural'   => 'logs',
			'ajax'     => false,
		));

		$this->bulk_action_handler();

		// screen option
		add_screen_option( 'per_page', array(
			'label'   => 'Показывать на странице',
			'default' => 20,
			'option'  => 'logs_per_page',
		) );

		$this->prepare_items();

		add_action( 'wp_print_scripts', [ __CLASS__, '_list_table_css' ] );
	}

	// создает элементы таблицы
	function prepare_items(){		

		$messages = new BaseCustomData('tc_messages');
		$this->items = $messages->get_all();
		unset($messages);

				
		//$items = new BaseCustomData('tc_price');

		// пагинация
		/*$per_page = get_user_meta( get_current_user_id(), get_current_screen()->get_option( 'per_page', 'option' ), true ) ?: 20;

		$this->set_pagination_args( array(
			'total_items' => 3,
			'per_page'    => $per_page,
		) );
		$cur_page = (int) $this->get_pagenum();*/ // желательно после set_pagination_args()

		// элементы таблицы
		// обычно элементы получаются из БД запросом
		// $this->items = get_posts();
}

	// колонки таблицы
	function get_columns(){
		return array(
			'cb'            => '<input type="checkbox" />',
			'actions'       =>   'Функции',
			'id'            => 'ID',
			'name' 	    	=> 'Имя',
			'phone' 		=> 'Телефон',
			'email' 		=> 'E-mail',
			/*'pointa' 		=> 'С пункта А',
			'pointb' 		=> 'В пункта B',
			'distance'   	=> 'Distance',
			'weight'   		=> 'Weight',
			'volume'  		=> 'Volume',
			'price'  		=> 'Price',
			'options'  		=> 'Опиции',*/
		);
	}

	// сортируемые колонки
	function get_sortable_columns(){
		return array(
			'title' => array( 'name', 'desc' ),
		);
	}

	protected function get_bulk_actions() {
		return array(
		//	'delete' => 'Delete',
		);
	}

	// Элементы управления таблицей. Расположены между групповыми действиями и панагией.
	function extra_tablenav( $which ){
		//echo '<div class="alignleft actions">HTML код полей формы (select). Внутри тега form...</div>';
	}

	// вывод каждой ячейки таблицы -------------

	static function _list_table_css(){
		?>
		<style>
			table.logs .column-id{ width:2em; }
			table.logs .column-license_key{ width:8em; }
			table.logs .column-title{ width:15%; }
		</style>
		<?php
	}

	// вывод каждой ячейки таблицы...
	function column_default( $item, $colname ){

		if( $colname === 'actions' ){
			// ссылки действия над элементом

			
			$actions = array();
			//$actions['edit'] = sprintf( '<a href="%s">%s</a>', '?page='.$_GET['page'].'&action=edit&id='.$item->id, __('edit','hb-users') );
			$actions['delete'] = sprintf( '<a href="%s">%s</a>', '?page='.$_GET['page'].'&action=delete&id='.$item->id, __('delete','hb-users') );

			return ( isset( $item->name ) ? esc_html( $item->name ) : " " ). $this->row_actions( $actions );
		}
		else {
			return isset($item->$colname) ? $item->$colname : print_r($item, 1);
		}

	}

	// заполнение колонки cb
	function column_cb( $item ){
		echo '<input type="checkbox" name="licids[]" id="cb-select-'. $item->id .'" value="'. $item->id .'" />';
	}

	// остальные методы, в частности вывод каждой ячейки таблицы...

	// helpers -------------

	private function bulk_action_handler(){
		if( empty($_POST['licids']) || empty($_POST['_wpnonce']) ) return;

		if ( ! $action = $this->current_action() ) return;

		if( ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) )
			wp_die('nonce error');

		// делает что-то...
		die( $action ); // delete
		die( print_r($_POST['licids']) );

	}

}