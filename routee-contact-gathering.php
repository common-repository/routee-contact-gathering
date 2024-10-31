<?php
/**
 * @package routee-contact-gathering
 * @version 1.0
 */
/*
Plugin Name: Routee Contact Gathering
Plugin URI: http://www.routee.net
Description: Collect contact details from websites, based on Wordpress, and incorporate them in Routee platform. 
Version: 1.0
Author URI: http://www.routee.net
*/
if ( ! defined( 'ABSPATH' ) ) exit; 
/**
 * load the application configuration
*/
if(!defined('RCG_FILE_PATH')){ define('RCG_FILE_PATH',__FILE__); }
if(!defined('RCG_URL')){ define('RCG_URL', plugins_url('', __FILE__) .'/'); }
if(!defined('RCG_DIR')){ define('RCG_DIR', plugin_dir_path( __FILE__ )); }
if(!defined('RCG_CSS')){ define('RCG_CSS', RCG_URL . 'css/'); }
if(!defined('RCG_JS')){ define('RCG_JS', RCG_URL . 'js/'); }
if(!defined('RCG_LIB')){ define('RCG_LIB', RCG_URL . 'lib/'); }
if(!defined('RCG_IMAGES')){ define('RCG_IMAGES', RCG_URL . 'images/'); }
if(!defined('RCG_TD')){ define('RCG_TD', 'wproutee'); }
if(!defined('RCG_ASSET_VERSION')){ define('RCG_ASSET_VERSION', '2.0'); }
if(!defined('RCG_REQUIRED_WP_VERSION')){ define('RCG_REQUIRED_WP_VERSION', '4.0'); }
if(!defined('RCG_REQUIRED_PHP_VERSION')){ define('RCG_REQUIRED_PHP_VERSION', '5.2'); }
/**
 * minimum configuration
*/
/**
* Loading all classes related this plugin from the class folder
*/
spl_autoload_register( function( $class_name ){
	$file_name = strtolower( str_replace( array('_'), array('-'), $class_name ) );
	$file_name = RCG_DIR.'classes/class-'.$file_name.'.php';
	if(file_exists($file_name)){
		require_once($file_name);
	}
});
/**
* language file and function added here
*/
require_once(RCG_DIR.'languages/language.php');
add_action( 'plugins_loaded', 'rcg_load_textdomain' );
if(!function_exists('rcg_load_textdomain')){
	function rcg_load_textdomain(){
		 load_plugin_textdomain( RCG_TD, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
}
/**
 * handle the installation.this action will call during install of the plugin.
 */
register_activation_hook( __FILE__, function(){
	    global $wp_version;
	/**
			 * while installing 
			 * we are doing various check 
			 * that is needded for our plugin
			 * 1 wp_version
			 * 2 php version
			 * 3 curl enabled
	 */
	 $compaitable = true;
	 #1 wp_version checking
	if( version_compare( $wp_version,RCG_REQUIRED_WP_VERSION, '<') ){  wp_die(__rcg_lang('installMsg1').' <a href="'.admin_url('plugins.php').'">'.__rcg_lang('Back').'</a>'); }
	#2 php version checking 
	if( version_compare( phpversion(), RCG_REQUIRED_PHP_VERSION, '<')){  wp_die(__rcg_lang('installMsg2').' <a href="'.admin_url('plugins.php').'">'.__rcg_lang('Back').'</a>'); }
	#3 curl enabled checking 
	if( !function_exists( 'curl_init' ) ){ wp_die(__rcg_lang('installMsg3').' <a href="'.admin_url('plugins.php').'">'.__rcg_lang('Back').'</a>'); } 
	/**
	 * now the code is executed upto this
	 * it means that the plugin is compaitable
	 * so install the table	 
	 */
	 global $wpdb;
	 $sql = sprintf(  "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}rcg_forms` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `form_name` varchar(500) CHARACTER SET utf8 NOT NULL,
					  `show_name` int(11) NOT NULL DEFAULT '0',
					  `author` int(11) NOT NULL,
					  `date` datetime NOT NULL,
					  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
				 );
	  $wr_forms = $wpdb->query( $sql);
	  if(!$wr_forms){  wp_die(__rcg_lang('installMsg4').' <a href="'.admin_url('plugins.php').'">'.__rcg_lang('Back').'</a>');  }
	  
	  $sql = sprintf(  "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}rcg_form_custom_fields` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `api_id` varchar(500) NOT NULL,
					  `attributes` text CHARACTER SET utf8 NOT NULL,
					  `options` text CHARACTER SET utf8 NOT NULL,
					  `is_routee` int(11) NOT NULL,
					  `label_text` varchar(500) CHARACTER SET utf8 NOT NULL,
					  `label_position` varchar(20) NOT NULL,
					  `field_name` varchar(100) CHARACTER SET utf8 NOT NULL,
					  `field_type` varchar(100) NOT NULL,
					  `field_description` text CHARACTER SET utf8 NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
				 );
	$wr_form_custom_fields = $wpdb->query( $sql);
	if(!$wr_form_custom_fields){
			$wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_forms`");	
		     wp_die(__rcg_lang('installMsg4').' <a href="'.admin_url('plugins.php').'">'.__rcg_lang('Back').'</a>');
	  }
	$sql = sprintf(  "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}rcg_form_fields` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `form_id` int(11) NOT NULL,
					  `attributes` text CHARACTER SET utf8 NOT NULL,
					  `options` text CHARACTER SET utf8 NOT NULL,
					  `is_routee` int(11) NOT NULL,
					  `label_text` varchar(500) CHARACTER SET utf8 NOT NULL,
					  `label_position` varchar(20) NOT NULL,
					  `field_name` varchar(100) CHARACTER SET utf8 NOT NULL,
					  `field_type` varchar(100) NOT NULL,
					  `field_description` text CHARACTER SET utf8 NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
				 );
	$wr_form_fields = $wpdb->query( $sql);
	if(!$wr_form_fields){
			$wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_forms`"); 
			$wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_form_custom_fields`"); 	
		     wp_die(__rcg_lang('installMsg4').' <a href="'.admin_url('plugins.php').'">'.__rcg_lang('Back').'</a>');
	  }
	$sql = sprintf(  "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}rcg_form_list` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `api_id` varchar(500) NOT NULL,
					  `form_id` int(11) NOT NULL,
					  `list` text CHARACTER SET utf8 NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
				 );
	$wr_form_list = $wpdb->query( $sql);
	if(!$wr_form_list){
			$wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_forms`"); 
		    $wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_form_custom_fields`"); 
			$wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_form_fields`");
		     wp_die(__rcg_lang('installMsg4').' <a href="'.admin_url('plugins.php').'">'.__rcg_lang('Back').'</a>');
	  }
});
register_deactivation_hook( __FILE__, function(){
	  global $wpdb;
	  $wr_uninstall_option = get_option('wr_uninstall_option');
	  if(in_array('delete_tables',$wr_uninstall_option)){ //delete all tables
				  $wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_forms`"); 
				  $wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_form_custom_fields`"); 
				  $wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_form_fields`");
				  $wpdb->query("DROP TABLE  `{$wpdb->prefix}rcg_form_list`");
		  }elseif(in_array('clear_data',$wr_uninstall_option)){ //keep  table clear data 
				  $wpdb->query("TRUNCATE TABLE  `{$wpdb->prefix}rcg_forms`"); 
				  $wpdb->query("TRUNCATE TABLE  `{$wpdb->prefix}rcg_form_custom_fields`"); 
				  $wpdb->query("TRUNCATE TABLE  `{$wpdb->prefix}rcg_form_fields`");
				  $wpdb->query("TRUNCATE TABLE  `{$wpdb->prefix}rcg_form_list`");
		  }
		  if(in_array('delete_api',$wr_uninstall_option)){ //delete api details here
			       delete_option( 'wr_config' );
		  }
		  delete_option( 'wr_uninstall_option' );
		  delete_transient('_wr_accesstoken');
});
//==== identify deactivation link from plugin list in plugin page =======// 
add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ), '_wr_modify_plugin_action_links_hook', 10, 2 );
add_filter( 'network_admin_plugin_action_links_'.plugin_basename( __FILE__ ), '_wr_modify_plugin_action_links_hook', 10, 2 );
function _wr_modify_plugin_action_links_hook($links, $file ){
	        $passed_deactivate = false;
			$deactivate_link   = '';
			$before_deactivate = array();
			$after_deactivate  = array();
			foreach ( $links as $key => $link ) {
				if ( 'deactivate' === $key ) {
					$deactivate_link   = $link;
					$passed_deactivate = true;
					continue;
				}
				if ( ! $passed_deactivate ) {
					$before_deactivate[ $key ] = $link;
				} else {
					$after_deactivate[ $key ] = $link;
				}
			}
	        if ( ! empty( $deactivate_link ) ) {
			     $deactivate_link .= '<i class="wr-slug" data-slug="'.RCG_TD.'"></i>';
				 $before_deactivate['deactivate'] = $deactivate_link;
			}
			return array_merge( $before_deactivate, $after_deactivate );		
}
if(is_admin()){  rcg_admin::getInstance(); }
@rcg_ajax::getInstance();	
//register plugin js
function rcg_enqueue_script(){
	   wp_register_style('wrFormRender', RCG_CSS .'wrFormRender.css', null, RCG_ASSET_VERSION,false);
	   wp_register_style('intlTelInput', RCG_LIB .'telbuild/css/intlTelInput.css', null, RCG_ASSET_VERSION,false);
	   wp_register_style('wrMultipleSelect2Css', RCG_CSS .'wrMultipleSelect2.css', null, RCG_ASSET_VERSION,false);
	   wp_register_style('datepickerCss', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css', null, RCG_ASSET_VERSION,false);	  
	   
	   wp_register_script('wrMultipleSelectJs', RCG_JS .'wrMultipleSelect.js', array('jquery'),  RCG_ASSET_VERSION, true);
	   wp_register_script('wrFormJs', RCG_JS .'wrForm.js', array('jquery','jquery-ui-core','jquery-ui-datepicker'),  RCG_ASSET_VERSION, true);
	   wp_register_script('intlTelInput', RCG_LIB .'telbuild/js/intlTelInput.min.js', array('jquery'),  RCG_ASSET_VERSION, true);   
	   wp_register_script('jqueryValidate', RCG_JS .'jquery.validate.min.js', array('jquery'),  RCG_ASSET_VERSION, true);
	  
	   wp_localize_script( 'wrMultipleSelectJs', 'jsLang',  __rcg_js_lang_font());
	   wp_localize_script( 'wrFormJs', 'wrFront', array('ajaxUrl'=>admin_url('admin-ajax.php'),  'ajaxLoader'=>RCG_IMAGES.'form-ajax-loader.gif','plugUrl'=>RCG_URL));  
	     
}
add_action( 'wp_enqueue_scripts', 'rcg_enqueue_script' );

if(!function_exists('wp_json_encode')){
	function wp_json_encode($data){
		return json_encode($data);
	}
}
if(!function_exists('wrForm_generate_func')){
	function wrForm_generate_func($atts){
		   wp_enqueue_style('wrFormRender');
		   wp_enqueue_style('datepickerCss');
		   wp_enqueue_style('wrMultipleSelect2Css');
		   wp_enqueue_style('intlTelInput');
		   
		   wp_enqueue_script('wrMultipleSelectJs');
		   wp_enqueue_script('wrFormJs');
		   wp_enqueue_script('intlTelInput');
		   wp_enqueue_script('jqueryValidate');
		   wp_enqueue_script('wrMultipleSelect');
		   $a = shortcode_atts( array(
				'id' => 0,
				'width'=>'',
				'height'=>'',
				'background' => '#eee',
			), $atts );
			$formDivHtml = '<div class="build-form" id="build-form-'.$a['id'].'"  style="'.(($a['width']) ? 'width:'.$a['width'].';' : '' ).(($a['height']) ? 'height:'.$a['height'].';' : '' ).'background:'.$a['background'].'">';
			$formDivHtml .='<div class="rendered-form">';
			$formHtml  ='';
			$formId = $a['id'];
			$wr_form = rcg_form::getInstance();
			$getForm = $wr_form->getForm($formId);
			if($getForm){
				$getFormFields = $wr_form->getFormFields($formId);
			  if($getFormFields){	
				   $formHtml .='<form class="routeeContactForm" data-form-id="'.$a['id'].'" action="" method="post">';
				   if($getForm['show_name']==1){
					$formHtml .='<h2>'.$getForm['form_name'].'</h2>';
				   }
				   $btnHtml  ='';
				   $rcg_fields = rcg_fields::getInstance();
				   $hasMobile = false;
				   $hasSubmitButton = false;
							   foreach($getFormFields as $FormField){
								  if($FormField['field_type'] =='mobile'){  $hasMobile = true;  }
								  if($FormField['field_type'] =='submit'){
									   $hasSubmitButton = true;
									   $btnHtml .= wp_nonce_field('wrForm_'.$formId, '_wr_contact');
									   $btnHtml .= '<input type="hidden" name="isRouteeContactUpdate" value="0">' ;
									   $btnHtml .='<div class="wrMobileMessage"></div>';
									   $btnHtml .= apply_filters( '_wr_'.$FormField['field_type'], $FormField['attributes'],  $FormField['options'],  $FormField['label_text'], $FormField['label_position'],  $FormField['field_name'],   $FormField['field_description'] ); 
								  }else{
									   $formHtml .= apply_filters( '_wr_'.$FormField['field_type'], $FormField['attributes'],  $FormField['options'],  $FormField['label_text'], $FormField['label_position'],  $FormField['field_name'],   $FormField['field_description'] );
								  }
							   }
					$btnHtml  .='<div class="wrFrmMessage"></div>';
					$formHtml .= $btnHtml;
					$formHtml .= '</form>';
					if(!$hasMobile){
						$formHtml = '';
						$formDivHtml .='<h2>'.__rcg_lang('FormShortcodeError1').'</h2>';
					}elseif(!$hasSubmitButton){
						$formHtml = '';
						$formDivHtml .='<h2>'.__rcg_lang('FormShortcodeError2').'</h2>';
					}else{
						$formDivHtml .= $formHtml;
					}
			  }else{
				  $formDivHtml .='<h2>'.__rcg_lang('FormNotAvailable').'</h2>';
			  }
			}else{
				$formDivHtml .='<h2>'.__rcg_lang('FormNotAvailable').'</h2>';
			}
			$formDivHtml .='</div>';
			$formDivHtml .='</div>';
			return $formDivHtml;
	}
}
add_shortcode( 'wrForm', 'wrForm_generate_func' );
?>