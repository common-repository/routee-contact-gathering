<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * this class contain all method related routee api 
*/
if(!class_exists('rcg_routee')){
	class rcg_routee{
		const AUTH_MESSAGE = 'Authorization has been denied for this request.';
		private static $_accessToken = null;
		private $_error = array();
		public static $accessAllowed = false;
		public static $instance = null;
		//api post  methods request urtls
		private static $_post_apiMethods = array(
					'authentication' => 'https://auth.routee.net/oauth/token',
					'create_new_contact' => 'https://connect.routee.net/contacts/my',
					'delete_multiple_contacts' => 'https://connect.routee.net/contacts/my/delete',
					'add_contacts_blacklist' => 'https://connect.routee.net/contacts/my/blacklist/{service}',
					'remove_specific_contact_from_service_blacklist'=>'https://connect.routee.net/contacts/my/blacklist/{service}/remove',
					'create_custom_fields'=>'https://connect.routee.net/contacts/labels/my',
					'create_new_list'=>'https://connect.routee.net/groups/my',
					'delete_lists'=>'https://connect.routee.net/groups/my/delete',
					'merge_multiple_lists'=>'https://connect.routee.net/groups/my/merge',
					'create_list_from_difference'=>'https://connect.routee.net/groups/my/difference',
					'add_contacts_to_specified_list'=>'https://connect.routee.net/groups/my/{name}/contacts',	
		);
		//pai post methods request urls
		private static $_get_apiMethods = array(
					'retrieve_details_about_contact'=>'https://connect.routee.net/contacts/my/{id}',
					'get_blacklisted_contacts_for_service'=>'https://connect.routee.net/contacts/my/blacklist/{service}',
					'retrieve_the_account_contact_fields'=>'https://connect.routee.net/contacts/labels/my',
					'retrieve_account_lists'=>'https://connect.routee.net/groups/my',
					'view_the_contacts_specified_list'=>'https://connect.routee.net/groups/my/{name}/contacts',
		);
		private static $_put_apiMethods = array(
					 'update_contact_details'=>'https://connect.routee.net/contacts/my/{id}',
		);
		private static $_delete_apiMethods = array(
					  'delete_contact'=>'https://connect.routee.net/contacts/my/{id}',
		);
		private static $_head_apiMethods = array(
					   'check_contact_exists'=>'https://connect.routee.net/contacts/my/mobile',
		);
		private $_application_id = '';
		private $_application_secret = '';
		  
		
		public function __construct(){
			  $this->checkAccessToken();
		}
		//get errors during api request
		public function getErrors(){
			 return $this->_error;
		}
		//create instance
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new self();
			}
			return self::$instance;
		}
		//set access token
		public function setAccessToken( $application_id ='', $application_secret = ''){
			if($application_id && $application_secret){
				$this->_application_id     = 	$application_id;
				$this->_application_secret = 	$application_secret;
				$this->_access();
			}
		}
		//Processing access token 
		public function _access(){
			  $curl = curl_init();
			  curl_setopt_array($curl, array(  CURLOPT_URL =>  self::$_post_apiMethods['authentication'], CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,  CURLOPT_CUSTOMREQUEST => "POST",  CURLOPT_POSTFIELDS => "grant_type=client_credentials",  CURLOPT_HTTPHEADER => array( "authorization: Basic ".base64_encode($this->_application_id.':'.$this->_application_secret), "content-type: application/x-www-form-urlencoded" )));
				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
				if( $err){ 
					  $this->_error[] = $err;  
				}
				//set access token accesstoken
				if(($result_json = json_decode($response, true) ) && isset($result_json['access_token'])){
						 self::$_accessToken = $result_json['access_token'];
						 set_transient('_wr_accesstoken', $result_json['access_token'], $result_json['expires_in']);
						 self::$accessAllowed = true;
				}else{
						 delete_transient('_wr_accesstoken');self::$accessAllowed = false;
				}
		}
		public function checkAccessToken(){
			if( !self::$_accessToken){
				self::$_accessToken = get_transient('_wr_accesstoken');
				if(self::$_accessToken){
					self::$accessAllowed = true;
				}
			}
			//auto update access token here
			if(!self::$accessAllowed){
				  $wr_config = get_option('wr_config');
				  if($wr_config['routee_app_id'] && $wr_config['routee_app_secret']){
					 $this->_application_id      = 	$wr_config['routee_app_id'];
					 $this->_application_secret  = 	$wr_config['routee_app_secret'];
					 $this->_access();
				  } 
			}
		}
		//create custom field
		public function create_custom_fields($data){
			 $apiUrl = self::$_post_apiMethods[__FUNCTION__];
			 $result = $this->_postApiMethod($apiUrl,array($data));
			 return $result;
		}
		//get list of custom field list 
		public function retrieve_the_account_contact_fields(){
			$apiUrl = self::$_get_apiMethods[__FUNCTION__]; 
			$result = $this->_getApiMethod($apiUrl);
			return $result;
		}
		//create a new list for contact 
		public function create_new_list($listName = ''){
			 $apiUrl = self::$_post_apiMethods[__FUNCTION__];
			 $data = array('name'=>$listName); 
			 $result = $this->_postApiMethod($apiUrl,$data);
			 return $result;
		}
		//delete list from data 
		public function delete_lists($listName = ''){
			$apiUrl = self::$_post_apiMethods[__FUNCTION__];
			$data[] = $listName; 
			$result = $this->_postApiMethod($apiUrl,$data);
			return $result;
		}
		//getting all list name of contact 
		public function retrieve_account_lists(){
			$apiUrl = self::$_get_apiMethods[__FUNCTION__]; 
			$result = $this->_getApiMethod($apiUrl);
			return $result;
		}
		//create new contact
		public function create_new_contact($data = array()){
			$apiUrl = self::$_post_apiMethods[__FUNCTION__]; 
			$result = $this->_postApiMethod($apiUrl,$data);
			return $result;
		}
		//add contacts to specific list
		public function add_contacts_to_specified_list($listName,$data){
			$apiUrl = self::$_post_apiMethods[__FUNCTION__];
			$apiUrl = str_replace('{name}',$listName,$apiUrl);
			$result = $this->_postApiMethod($apiUrl,$data);
			return $result;
		}
		//view contacts under any list
		public function view_the_contacts_specified_list($listName ='All',$page = 0 , $size = 10){
			$apiUrl = self::$_get_apiMethods[__FUNCTION__]; 
			$apiUrl = str_replace('{name}',$listName,$apiUrl);
			$apiUrl = $apiUrl.'?page='.$page.'&size='.$size;
			$result = $this->_getApiMethod($apiUrl);
			return $result;
		}
		//checking contact mobile number exists or not
		public function check_contact_exists($mobile =''){
			$apiUrl = self::$_head_apiMethods[__FUNCTION__];
			$apiUrl =  $apiUrl.'?value='.rawurlencode($mobile);
			return $this->_getHeadMethod($apiUrl);
		}
		//delete contact from list
		public function delete_contact($id = ''){
			$apiUrl = self::$_delete_apiMethods[__FUNCTION__];
			$apiUrl = str_replace('{id}',$id,$apiUrl);
			$result = $this->_deleteApiMethod($apiUrl);
			return $result;
		}
		//this function will execute all delete method of routee
		private function _deleteApiMethod($apiUrl = ''){
				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => $apiUrl, CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "DELETE",  CURLOPT_HTTPHEADER => array( "authorization: Bearer ".self::$_accessToken),
				));
				$response = curl_exec($curl);
				$info = curl_getinfo($curl);
				$err = curl_error($curl);
				curl_close($curl);
				if((int)$info['http_code'] == 200){  $response = json_decode($response,true); return $response; }
				$this->_error[]= $this->getHttpError($info['http_code']);
				return false;
		}
		//this function will execute all head method of routee
		private function _getHeadMethod($apiUrl = ''){
				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => $apiUrl, CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "HEAD",  CURLOPT_HTTPHEADER => array( "authorization: Bearer ".self::$_accessToken),
				));
				$response = curl_exec($curl);
				$info = curl_getinfo($curl);
				$err = curl_error($curl);
				curl_close($curl);
				if((int)$info['http_code'] == 200){  return true; }
				return false;
		}
		//this function will execute all get method of routee
		private function _getApiMethod($apiUrl = ''){
				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => $apiUrl, CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET",  CURLOPT_HTTPHEADER => array( "authorization: Bearer ".self::$_accessToken),
				));
				$response = curl_exec($curl);
				$info = curl_getinfo($curl);
				$err = curl_error($curl);
				curl_close($curl);
				if((int)$info['http_code'] == 200){  $response = json_decode($response,true); return $response; }
				$this->_error[]= $this->getHttpError($info['http_code']);
				return false;
		}
		//this function will excute all post method of routee
		private function _postApiMethod($apiUrl = '',$data = array()){
			$curl = curl_init();
			curl_setopt_array($curl, array( CURLOPT_URL => $apiUrl, CURLOPT_RETURNTRANSFER => true,  CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => wp_json_encode($data), CURLOPT_HTTPHEADER => array( "authorization: Bearer ".self::$_accessToken, "content-type: application/json")));
			   $response = curl_exec($curl);
			   $err = curl_error($curl);
			   $info = curl_getinfo($curl);
			   curl_close($curl);
			  
			   if((int)$info['http_code'] == 200){
					$response = json_decode($response,true);  
					return $response;  	
				}
				
				$this->_error[]= $this->getHttpError($info['http_code']);
				return false;
		}
		//this function is for get curl http error meaning
		private function getHttpError($http_code){
			$http_error = array(
			 '401'=> __('No access token provided or the access token has expired', RCG_TD),
			 '404'=> __("Something that was requested wasn't found. For example if we want to retrieve information about a specific entity with an id and the id doesn't exist. ", RCG_TD),
			 '409'=> __('On create/update operations when a property must have unique value and it already exists in the system. For example the name of an SMS campaign.', RCG_TD),
			 '400'=> __('You have provided a value that is considered invalid', RCG_TD),
			 '500'=> __('An internal error occured (our system is down or we have a bug)', RCG_TD)
			);
			return (isset($http_error[$http_code]) ? $http_error[$http_code] : 'Application not working properly try later.');
		}
	}
}
?>