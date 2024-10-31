<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('WP_List_Table')){ require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ); }
if(!class_exists('rcg_admin_listing')){
	class rcg_admin_listing  extends WP_List_Table{
		  protected  $singular = '';
		  protected  $plural = '';
		  protected  $table_name = '';
		  protected  $page = '';
		  protected  $ajax = false;
		  protected  $delete = true;
		  function __construct($methods = array()){
				global $status, $page;  
				if(!empty($methods)){
					foreach($methods as $method=>$value){
						$this->$method =  $value;
					}
				}
				parent::__construct( array( 'singular'  => $this->singular, 'plural'=> $this->plural, 'ajax' => $this->ajax ));
		  }  
		 
		function column_default($item, $column_name){
			  if(isset($item[$column_name])){
				   return $item[$column_name];
			  }else{
				   return ''; 
			  }
		  }
		  function column_cb($item){ return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['ID'] ); }
		  function get_columns($data = array()){
			  $columns = array();
			if(!empty($data)){
				if($this->delete == true){
					$columns['cb'] = '<input type="checkbox" />';
				}
			  foreach($data as $val){
				  foreach($val as $col=>$v){
					  if($col!='ID'){
						$columns[$col] = str_replace('_', ' ',$col);
					  }
				  }
				  break;
			  }
			}
			   return $columns;
		  }
		  function get_bulk_actions() {
		  if($this->delete == true){
			  $actions = array( 'delete' => 'Delete' );
			  return $actions;
			}
		  }
		  function process_bulk_action() { 
			if('delete' == $this->current_action()){
				$cb = $_GET[$this->singular];
				if(count($cb) == 0){ $msg = '<div class="error"><p>Please check to delete item.</p></div>';  }
				else{
					 global $wpdb;
					 foreach($cb as $val){
						   $query = 'DELETE FROM '.$this->table_name.' WHERE ID ='.(int)$val;  
						   $wpdb->query($query);
					 }
					 $msg = '<div class="updated"><p>'.count($cb).'item deleted.</p></div>';
				}
				set_transient('items_del_msg',$msg,30);
				$redirect = get_bloginfo('url').'/wp-admin/admin.php?page='.$this->page;  
				echo "<script type='text/javascript'>location.href='".$redirect."'</script>";
				exit;
			}    
		}
		 function prepare_items($data) {
			$per_page = 200;
			$columns = $this->get_columns($data);
			$hidden = array();
			$this->_column_headers = array($columns, $hidden);
			$this->process_bulk_action();
			$current_page = $this->get_pagenum();
			$total_items = count($data);
			if($total_items >0){
				$data = array_slice($data,(($current_page-1)*$per_page),$per_page);  
			 }
			$this->items = $data;
			$this->set_pagination_args(
			array( 'total_items' => $total_items,  'per_page' => $per_page, 'total_pages' => ceil($total_items/$per_page)
			   )
		  );
		}
		
	}
}
?>