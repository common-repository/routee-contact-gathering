<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//================== form class ======================================//
if(!class_exists('rcg_form')){
	class rcg_form{
		public static $instance = null;
		private $db;
		public function __construct(){
			global $wpdb;
			$this->db = $wpdb;
		}
		public static function getInstance(){
			if(!self::$instance){ self::$instance = new self(); }
			return self::$instance;
		}
		/*** create form ********/
		public function createForm($formData){
			$q = $this->db->insert($this->db->prefix.'rcg_forms',$formData);
			if($q){ return $this->db->insert_id; }
			return false;
		}
		public function checkFormListExist($formId,$appId){
			return $this->db->get_var("SELECT COUNT(*) FROM ".$this->db->prefix."rcg_form_list WHERE form_id='".$formId."' AND api_id='".$appId."'");
		}
		public function saveFormList($data){
			$q = $this->db->insert($this->db->prefix.'rcg_form_list',$data);
			if($q){ return $this->db->insert_id; }
			return false;
		}
		public function updateFormList($data,$where){
			 $this->db->update($this->db->prefix.'rcg_form_list',$data,$where);
		}
		public function getFormList($formId,$appId){
			return $this->db->get_var("SELECT list FROM ".$this->db->prefix."rcg_form_list WHERE form_id='".$formId."' AND api_id='".$appId."'");
		}
		/******* clear previous field data  ***********/
		public function clearPreviousField($formId){
			$this->db->delete($this->db->prefix.'rcg_form_fields',array('form_id'=>$formId));	
		}
		/**** create form fields *****/
		public function createFormField($fieldData){
			$q = $this->db->insert($this->db->prefix.'rcg_form_fields',$fieldData);
			if($q){ return $this->db->insert_id; }
			return false;						
		}
		/******* edit form *************/
		public function editForm($formData,$formId){
			$q = $this->db->update($this->db->prefix.'rcg_forms',$formData,array('id'=>$formId));
			if($q){
				return true;
			}		
			return false;
		}
		/**** creating custom field ****/
		public function createCustomFields($fieldData){
			$q = $this->db->insert($this->db->prefix.'rcg_form_custom_fields',$fieldData);
			if($q){ return $this->db->insert_id; }
			return false;		
		}
		public function deleteCustomField($fieldId){
			$this->db->delete($this->db->prefix.'rcg_form_custom_fields',array('id'=>$fieldId));	
		}
		public function checkCustomField($apiId,$fieldName){
			return $this->db->get_var("SELECT COUNT(*) FROM ".$this->db->prefix."rcg_form_custom_fields WHERE field_name='".$fieldName."' AND api_id='".$apiId."'");
		}
		public function getCustomFieldDetails($cols = array(),$where = array()){
			$sql  = "SELECT ";
			$s = '';
			if($cols){
				foreach($cols as $col){ $s .=  $col.', '; }
				$s = rtrim($s,', ');
			}else{
				$s = ' * ';
			}
			$sql = $sql.$s." FROM ".$this->db->prefix."rcg_form_custom_fields WHERE ";
			$whr = '';
			if($where){
				foreach($where as $colName=>$wh){
					if($whr !=''){ $whr .= ' AND '; }
					   $whr .= $colName.'="'.$wh.'"';
				}
			}else{
				$whr = '1' ;
			}
			$sql = $sql.$whr;
			$row = $this->db->get_row($sql, ARRAY_A);
			if($row){
				return $row;
			}
			return false;
		}
		public function getCustomRouteeFieldList($where = array() , $cols = array()){
			$sql  = "SELECT ";
			$s = '';
			if($cols){
				foreach($cols as $col){ $s .=  $col.', '; }
				$s = rtrim($s,', ');
			}else{
				$s = ' * ';
			}
			$sql = $sql.$s." FROM ".$this->db->prefix."rcg_form_custom_fields WHERE ";
			$whr = '';
			if($where){
				foreach($where as $colName=>$wh){
					if($whr !=''){ $whr .= ' AND '; }
					   $whr .= $colName.'="'.$wh.'"';
				}
			}else{
				$whr = '1' ;
			}
			$sql = $sql.$whr;
			$row = $this->db->get_results($sql, ARRAY_A);
			if($row){
				return $row;
			}
			return false;
		}
		public function updateCustomFieldDetails($colVal = array(),$where = array()){
			$q = $this->db->update($this->db->prefix.'rcg_form_custom_fields',$colVal,$where);
			if($q){
				return true;
			}		
			return false;
			
		}
		/*** get form list ******/
		public function getForms(){
			 $result = $this->db->get_results(" SELECT *   FROM ".$this->db->prefix."rcg_forms  ORDER BY id DESC " , ARRAY_A);
			if($result){
				return $result;
			}
			return false;							 
		}
		/**** get form details ****/
		public function getForm($formId){	
			 $row = $this->db->get_row(" SELECT *   FROM ".$this->db->prefix."rcg_forms  WHERE id ='".$formId."'" , ARRAY_A);
			if($row){
				return $row;
			}
			return false;
		}
		/**** get form field details ****/
		public function getFormFields($formId){
			 $result = $this->db->get_results(" SELECT *  FROM ".$this->db->prefix."rcg_form_fields   WHERE form_id ='".$formId."' ORDER BY id ASC " , ARRAY_A);
			if($result){
				return $result;
			}
			return false;
		}
	}
}