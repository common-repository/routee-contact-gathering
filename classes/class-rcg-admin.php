<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//this class contain every functionality of admin
if(!class_exists('rcg_admin')){
	class rcg_admin{
		public static $instance = null;
		public function __construct(){ 
			  global $pagenow;
			//this action for add admin menu in wp-admin panel
			  add_action('admin_menu',array(&$this,'init_admin_menu'));	
			  //this action for add admin notices for admin panel
			  add_action('admin_notices',array(&$this,'init_admin_notices'));
			  //this action add javascript script and stylesheet in admin panel
			  add_action('admin_enqueue_scripts',array(&$this,'init_admin_scripts'));
			  //plugin action
			  if ( 'plugins.php' === $pagenow ) {
				  add_action( 'admin_footer', array(&$this, 'add_deactivation_dialog' ) );
			  }
		}
		//instance for admin class
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new self();
			}
			return self::$instance;
		}
		//imit admin scripta
		public function init_admin_scripts(){
			//register css here
			wp_register_style('wrAdmin', RCG_CSS .'wrAdmin.css', null, RCG_ASSET_VERSION,false);
			wp_register_style('wrFormBuilder', RCG_CSS .'wrFormBuilder.css', null, RCG_ASSET_VERSION,false);
			wp_register_style('wrMultipleSelect', RCG_CSS .'wrMultipleSelect.css', null, RCG_ASSET_VERSION,false);
			//register javascript here
			wp_register_script('wrAdminJs', RCG_JS .'wrAdmin.js', array('jquery'), RCG_ASSET_VERSION, true);
			wp_register_script('jquerytouch', RCG_JS .'jquery.ui.touch-punch.min.js', array('jquery','jquery-ui-core'), RCG_ASSET_VERSION, true);
			wp_register_script('wrHighlight', RCG_JS .'wrHighlight.min.js', array('jquery','jquery-ui-core'), RCG_ASSET_VERSION, true);
			wp_register_script('wrFormBuilder', RCG_JS .'wrFormBuilder.js', array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable','jquery-ui-selectable','jquerytouch'), RCG_ASSET_VERSION, true);
			wp_register_script('wrDropdown', RCG_JS .'wrDropdown.js', array('jquery'), RCG_ASSET_VERSION, true);
			wp_register_script('wrMultipleSelect', RCG_JS .'wrMultipleSelect.js', array('jquery'),  RCG_ASSET_VERSION, true);
			 
			if(isset($_GET['page']) && $_GET['page'] == 'wr-form-generator'){
				 $wr_routee = rcg_routee::getInstance();
				 $listing   = $wr_routee->retrieve_account_lists();
				 $data = array();
				 if( $listing){ $data = $listing; }
				 //routee list js variable
				 wp_localize_script( 'wrAdminJs', 'routeeLists', $data );
				 //js checking for api access is valid or not
				 wp_localize_script( 'wrAdminJs', 'accessAllowed', array('value'=>rcg_routee::$accessAllowed));
			}
			$wrForm = array();
			$wrForm['RCG_URL'] = RCG_URL;
			$wrForm['RCG_IMAGES'] = RCG_IMAGES;
			$wrForm['RCG_TD'] = RCG_TD;
			if(isset( $_GET['page'] ) && $_GET['page'] =='wr-form-generator'){
				$wrForm['ID'] = 0;
				if(isset( $_GET['frmID'] )){	
					  $frmID = $_GET['frmID'];
					  $wr_form = rcg_form::getInstance();
					  $getForm = $wr_form->getForm($frmID);
					  if($getForm){  
						  $getFormFields = $wr_form->getFormFields((int)$frmID);
						  $fields = array();
						  if($getFormFields){
							  foreach($getFormFields as $formFields){
								 $fields[] =  array(
									 'attributes'=>maybe_unserialize($formFields['attributes']),
									 'options'=>maybe_unserialize($formFields['options'])
									);
							  }
						  }
						  $wrForm['ID']         = $getForm['id'];
						  $wrForm['FORM_NAME']  = stripslashes($getForm['form_name']);	
						  $wrForm['SHOW_NAME']  = (int)$getForm['show_name'];	
						  $wrForm['LIST'] = array();
						  $wr_config = get_option('wr_config');
						  if($wr_config['routee_app_id']){
							   $getFormList =  $wr_form->getFormList($frmID,$wr_config['routee_app_id']); 
							   if($getFormList!=''){
								   $wrForm['LIST'] = maybe_unserialize($getFormList);
							   }
						  }
						  $wrForm['FIELDS'] = wp_json_encode($fields);
					} 	  	  	  
				}
			}
			wp_localize_script( 'wrAdminJs', 'wrForm', $wrForm );
			wp_localize_script( 'wrAdminJs', 'jsLang',  __rcg_js_lang());
		}
		//initialize admin menu
		public function init_admin_menu(){
			  if( !current_user_can( 'manage_options' )){  wp_die( __rcg_lang('notPermission') ); }
			   add_menu_page( __rcg_lang('Routee'), __rcg_lang('Routee'), 'manage_options', 'wr-settings', array($this,'wr_settings'),RCG_IMAGES.'r-routee-logo.png');
			   add_submenu_page( 'wr-settings', __rcg_lang('routeeSettings'), __rcg_lang('routeeSettings'), 'manage_options', 'wr-settings', array($this,'wr_settings')); 
			   add_submenu_page( 'wr-settings', __rcg_lang('FormGenerator'), __rcg_lang('FormGenerator'), 'manage_options', 'wr-form-generator', array($this,'wr_form_generator')); 
			   add_submenu_page( 'wr-settings', __rcg_lang('AllForms'), __rcg_lang('AllForms'), 'manage_options', 'wr-forms-list', array($this,'wr_form_list'));  
		}
		//initialize admin notices
		public function init_admin_notices(){
			$wr_config = get_option('wr_config');
			$routee_app_id = (($wr_config['routee_app_id']) ? $wr_config['routee_app_id']   : false);
			$routee_app_secret =  (($wr_config['routee_app_secret']) ? $wr_config['routee_app_secret']   : false);
			if(!$routee_app_id  || !$routee_app_secret){  
			  require(RCG_DIR.'/template/init_admin_notices.php');
			}
		}
		//for wr setting page 
		public function wr_settings(){ 
		if( !current_user_can( 'manage_options' )){  wp_die( __rcg_lang('notPermission') ); }
		/**
		* Saving app data 
		*/
		 if( !empty( $_POST) && isset($_POST['get_access_token']) && wp_verify_nonce( $_POST['_wr_settings'], 'wr_settings') ){
				 update_option('wr_config', $_POST['wr']);
				 //checking provided details ok or not
				 $wr_routee = rcg_routee::getInstance();
				 $wr_routee->setAccessToken( $_POST['wr']['routee_app_id'], $_POST['wr']['routee_app_secret']);
				 $message = rcg_routee::$accessAllowed ? 'success' : 'error';
				 header('location: '.admin_url('admin.php?page='.$_GET['page'].'&message='.$message));
				 exit;
		 }
		 $wr_config = get_option('wr_config'); 
		 require(RCG_DIR.'/template/wr_settings.php');
	   }
		//form generator 
	   public function wr_form_generator(){ 
		//======= loading register css and javascript for the admin ===============//
		   wp_enqueue_style('wrAdmin');
		   wp_enqueue_style('wrFormBuilder');
		   wp_enqueue_style('wrMultipleSelect');
		 
		   wp_enqueue_script('wrAdminJs');
		   wp_enqueue_script('jquerytouch');
		   wp_enqueue_script('wrHighlight');
		   wp_enqueue_script('wrFormBuilder');
		   wp_enqueue_script('wrDropdown');
		   wp_enqueue_script('wrMultipleSelect');
	   
		   $wr_routee = rcg_routee::getInstance();
		   $listing   = $wr_routee->retrieve_account_lists();
		   require(RCG_DIR.'/template/wr_form_generator.php');
		 }
	   //=== listing of wr form list
	   public function wr_form_list(){
		global $wpdb;
		 $p = array(
				'singular'=>'form',
				'plural'=>'forms',
				'table_name'=>$wpdb->prefix.'wr_forms',
				'page'=>'wr-forms-list',
		);
		$listObject = new rcg_admin_listing($p);
		$wr_form = rcg_form::getInstance();
		$getForms = $wr_form->getForms();
		$list = array();
		if($getForms){
			foreach($getForms as $form){
				$list[] = array(
						'ID' => $form['id'],
						'NAME' => $form['form_name'],
						'SHORTCODE' => '[wrForm id="'.$form['id'].'"]<br/><br/>[wrForm id="'.$form['id'].'" width="100%" height="100%" background="#eee"]',
						'DATE' => date('d/m/Y',strtotime($form['date'])),
						'EDIT' => '<a href="'.get_bloginfo('siteurl').'/wp-admin/admin.php?page=wr-form-generator&frmID='.$form['id'].'">'.__rcg_lang('Edit').'</a>',
					);
			}
		}
		$listObject->prepare_items($list);
		require(RCG_DIR.'/template/wr_form_list.php');	
	}
	   public function add_deactivation_dialog(){
		wp_enqueue_style('wrDeactivation', RCG_CSS .'wrDeactivation.css', null, false,false);
		$points  = array(
			   'delete_tables'=>__rcg_lang('DeleteTableAndDataBoth'),
			   'clear_data'=>__rcg_lang('DeleteDataOnlyAndKeepTable'),
			   'delete_api'=>__rcg_lang('DeleteApiCredentialsDetails'),
		);
		require(RCG_DIR.'/template/uninstall.php');
	}
	   }
}
?>
