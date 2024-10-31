<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//all ajax request process will dosne in this class
if(!class_exists('rcg_ajax')){
class rcg_ajax{
	public static $instance = null;
	public function __construct(){
	  if(is_admin()){
		 //ajax function for the admin user here
	      add_action( 'wp_ajax_get_routee_list', array(&$this,get_routee_list ));
		  add_action( 'wp_ajax_delete_routee_list', array(&$this,delete_routee_list ));
		  add_action( 'wp_ajax_contact_list', array(&$this,contact_list )); 
		  add_action( 'wp_ajax_delete_contact', array(&$this,delete_contact )); 
		  add_action( 'wp_ajax_form_generation', array(&$this,form_generation ));
		  add_action( 'wp_ajax_get_custom_field_list', array(&$this,get_custom_field_list ));
		  add_action( 'wp_ajax_set_routee_field', array(&$this,set_routee_field ));
		  add_action( 'wp_ajax_get_routee_custom_field_list', array(&$this,get_routee_custom_field_list ));
		  add_action( 'wp_ajax_deactivate_apply', array(&$this,deactivate_apply ));
	  }
		  //ajax function for frontend user
		  add_action( 'wp_ajax_createContact', array(&$this,createContact ));
          add_action( 'wp_ajax_nopriv_createContact', array(&$this,createContact ));
	}
	//create contact routee api
	public function createContact(){
		$json = array();
		if(isset($_GET['frmbID']) && isset($_POST['mobile']) && isset($_POST['_wr_contact'])){ //checking form id and mobile number
			$frmbID = $_GET['frmbID']; 
			if(wp_verify_nonce( $_POST['_wr_contact'], 'wrForm_'.$frmbID)){ // verify wp nonce
			   $mobile = $_POST['mobile'];
			   $data = $_POST;
			   unset($data['mobile']);
			   unset($data['_wr_contact']);
			   unset($data['_wp_http_referer']);
			   //=== get all required field name from the form field record ============//
			    $acceptedFields = array(); //exists field of routee will insert in this array
			    $acceptedFields['mobile'] = $mobile;
				$wr_routee = rcg_routee::getInstance(); 
				//checking for invalid api
				if(!rcg_routee::$accessAllowed){ $json['formError'] = array('errortext'=> __rcg_lang('unableSendContactDetails'));  echo wp_json_encode($json); die;	  }
				//checking mobile number exists or not
				$isRouteeContactUpdate = ((isset($_POST['isRouteeContactUpdate'])) ?  $_POST['isRouteeContactUpdate'] : 0);
				if($isRouteeContactUpdate == 0){ 
					 if($wr_routee->check_contact_exists($acceptedFields['mobile'])){
							$json['MobileExistsformError'] = array('errortext'=>__rcg_lang('mobileVumberAlreadyExists'));
							echo wp_json_encode($json); die;	  
					 }
				 }
				 $mainFields = array('country','email','firstName','lastName','mobile');
				 $apiContactFields = $wr_routee->retrieve_the_account_contact_fields();
				 $wr_form = rcg_form::getInstance();
			     $getFormFields = $wr_form->getFormFields($frmbID);
			   foreach($getFormFields as $formField){
				    $attributes = maybe_unserialize($formField['attributes']);
					if($attributes['type']!='mobile' && $attributes['type']!='submit'){
						//checking field value and field existance which is exists
						if(isset($attributes['required'])){
							if(!isset($data[$attributes['name']])){
								 $json['formError'] = array('errortext'=> '('.__rcg_lang('Required').') '.$attributes['label']);
								 echo wp_json_encode($json); die;	 
							}elseif(empty($data[$attributes['name']])){
								 $json['formError'] = array('errortext'=>(($attributes['errortext']!='') ?  $attributes['errortext'] : '('.__rcg_lang('Required').') '.$attributes['label']));
								 echo wp_json_encode($json); die;	
							}
						}
						//antispam checking
						if( $attributes['type']== 'antispam'){
							 if(isset($attributes['required'])){
								 $mainanswer = trim(strtolower($attributes['answer']));
								 $useranser = trim(strtolower($data[$attributes['name']]));
								 if($mainanswer!=$useranser){
									 $json['formError'] = array('errortext'=>(($attributes['errortext']!='') ?  $attributes['errortext'] : __rcg_lang('yourAnswerIsIncorrect')));
									 echo wp_json_encode($json); die;	 
								 }
							 }
							 if(isset($data[$attributes['name']])){  unset($data[$attributes['name']]);  }
						}
						//filtering valid field
						if(isset($data[$attributes['name']])  && $data[$attributes['name']]!=''){
						  if(in_array($attributes['name'],$mainFields)){  
							$acceptedFields[$attributes['name']] = (is_array($data[$attributes['name']]) ? implode(', ',$data[$attributes['name']]) : ((strtolower($attributes['type'])== 'number') ? (int)$data[$attributes['name']] : $data[$attributes['name']])   ) ;
						  }else{
							  if(!array_key_exists($attributes['name'],$apiContactFields)){ 
							       $wr_routee->create_custom_fields(array('name'=>$attributes['name'],'type'=>((strtolower($attributes['type'])== 'number') ? 'Number' : 'Text')));
							  }
							  $acceptedFields['labels'][] = array('name'=>$attributes['name'],'value'=>(is_array($data[$attributes['name']]) ? implode(', ',$data[$attributes['name']]) : ((strtolower($attributes['type'])== 'number') ? (int)$data[$attributes['name']] : $data[$attributes['name']]))) ;
						  }
						}
					}
			   }
			   //===========  inserting in new contact ==========//
			   $result = $wr_routee->create_new_contact($acceptedFields);
			   if(isset($result['id'])){
					 $contactID = $result['id'];
					 $wr_config = get_option('wr_config'); 
					 $api_id = $wr_config['routee_app_id'];
					//============= assing in  list will here =====================//
					  $getFormList = $wr_form->getFormList($frmbID,$api_id);
					  $getFormList = maybe_unserialize($getFormList);
					  if($getFormList){
						  $c[]= $contactID;
						  foreach($getFormList as $List){
							   $wr_routee->add_contacts_to_specified_list($List,$c);
						  }
					  }
					  
					  $json['Success'] = array('message'=>(($isRouteeContactUpdate==0)? __rcg_lang('contactSuccessfullyCreated') :  __rcg_lang('contactSuccessfullyUpdated')));  echo wp_json_encode($json); die;	
			   }
			}
		}
		$json['formError'] = array('errortext'=>__rcg_lang('failedToCreateContact'));  echo wp_json_encode($json); die;	
	}
	//admin ajax Form generation 
	public function form_generation(){
		//print_r($_POST); die;
		$json = array();
		$successMessages = array();
		$errorMessages = array();
		$authorID =  get_current_user_id();
		$frmbID = 0;
		$wr_config = get_option('wr_config');
		if(isset($_POST['frmbID'])){
			 $frmbID = explode('frmb-',$_POST['frmbID']);
		     $frmbID = (int)$frmbID[1];
		}
		if(isset($_POST['form_name']) && isset($_POST['fields'])){   
		   $fields = $_POST['fields']; 
		   $e_list = (isset($_POST['e_list']) ? $_POST['e_list'] : array()); //exist list
		   $n_list = (isset($_POST['n_list']) ? $_POST['n_list'] : array()); //new list
		   $selectedList  = ''; //total selected list
		   $n_listing = array(); //success created new list will assign here
					   if(!empty($n_list)){ 
							   $wr_routee = rcg_routee::getInstance();
							   foreach($n_list as $list){
								   $result =  $wr_routee->create_new_list($list);
								   if($result){
										 $n_listing[] = $list;
										 $successMessages[] = $list.' '.__rcg_lang('listSuccessfullyCreated'); 
								   }else{
										 $errorMessages[] = __rcg_lang('listSuccessfullyCreated').' '.$list;
								   }
							   }
					   }
					   if(!empty($e_list) && !empty($n_listing)){
					     $selectedList = array_merge($e_list,$n_listing);
						 $selectedList = maybe_serialize($selectedList);
					   }elseif(!empty($e_list)){
						   $selectedList = maybe_serialize($e_list);
					   }elseif(!empty($n_listing)){
						   $selectedList = maybe_serialize($n_listing);
					   }
		             //============== form saving  data ===========//
			          $wr_form = rcg_form::getInstance();
					  $formData = array(
						   'form_name'=>sanitize_text_field($_POST['form_name']),
						   'show_name'=>(int)$_POST['show_name'],
						   'author'=>(int)$authorID,
						   'date'=> date('Y-m-d H:i:s')
					   );
					   if($frmbID > 0){
						    $sM = __rcg_lang('formSuccessfullyUpdated');
						    unset($formData['date']);
							unset($formData['author']);
							$formData['update_date'] = date('Y-m-d H:i:s');
							$c = $wr_form->editForm($formData,$frmbID);
							if(!$c){ //form uodate failed 
								$errorMessages[] = __rcg_lang('formUpdationFailed'); 
								set_transient('formMessage', wp_json_encode( array( 'successMessages'=>$successMessages, 'errorMessages'=>$errorMessages )));		
								$json =  array('Failed'=>1, 'RedirectUrl'=>admin_url('admin.php?page=wr-form-generator&frmID='.$frmbID));	
							    echo wp_json_encode($json); die;
							}
					   }else{
						    $sM = __rcg_lang('formSuccessfullyCreated');
						   $frmbID = $wr_form->createForm($formData);
						   if(!$frmbID){ //form create failed
							   $errorMessages[] = __rcg_lang('formCreationFailed');
							   set_transient('formMessage', wp_json_encode( array( 'successMessages'=>$successMessages, 'errorMessages'=>$errorMessages )));
							   $json =  array('Failed'=>1, 'RedirectUrl'=>admin_url('admin.php?page=wr-form-generator'));	
							   echo wp_json_encode($json); die;
						   }
					   }
					   //============ saving  routee list in table ======================//
					    
						if($wr_config['routee_app_id']){
							  //checking list already exist for that app id or not
						      $checkFormListExist = $wr_form->checkFormListExist($frmbID,$wr_config['routee_app_id']);
							  if($checkFormListExist == 0){
								  $saveFormList = $wr_form->saveFormList( array('api_id'=>$wr_config['routee_app_id'],'form_id'=>$frmbID,'list'=>$selectedList));
								  if(!$saveFormList){
									  $errorMessages[] = __rcg_lang('failedSavingListForForm');
								  }
							  }else{
								  $updateFormList = $wr_form->updateFormList( array('list'=>$selectedList), array('api_id'=>$wr_config['routee_app_id'],'form_id'=>$frmbID));
							  }
						}
						if(!empty($fields)){
							if($frmbID > 0){ $wr_form->clearPreviousField($frmbID); }
							foreach($fields as $field){
								//=================== field saving  data ==================//
								
								//field attributes data here 
								$fieldAttrs =  array();
								foreach($field['attributes'] as $attribute){ $fieldAttrs[$attribute['name']] = $attribute['value'];  }
								//field option data here
								$fieldOptions =  array();
								if(isset($field['option'])){
									foreach($field['option'] as $option){
									   $optionAttr =  array();
									   foreach($option['attributes'] as $optAttribute){ $optionAttr[$optAttribute['name']] = $optAttribute['value'];  }
										$fieldOptions[] =  array('text'=>$option['text'],'attributes'=>$optionAttr);
									}
								}
								//insert or update table data 
								$fieldData =  array(
										  'form_id'          => $frmbID,
										  'attributes'       => maybe_serialize($fieldAttrs),
										  'options'          => (($fieldOptions) ?  maybe_serialize($fieldOptions) : ''),
										  'is_routee'        => (int)$field['is_routee'],
										  'label_text'       => sanitize_text_field($field['label_text']),
										  'label_position'   => sanitize_text_field($field['label_position']),
										  'field_name'       => sanitize_text_field($field['field_name']),
										  'field_type'       => sanitize_text_field($field['field_type']), 
										  'field_description'=> sanitize_text_field($field['field_description']),
								);
								//===== saving data in fields table ========//
								$fieldC = $wr_form->createFormField($fieldData);
								//==== create new custom field in rouree api and web database =====//
								  if($wr_config['routee_app_id']){
										if($fieldC && (int)$fieldData['is_routee'] == 0 && $fieldData['field_type']!='submit' && $fieldData['field_type']!='antispam' && $wr_form->checkCustomField($wr_config['routee_app_id'],$fieldData['field_name']) == 0){
												 unset($fieldData['form_id']);
												 $fieldData['api_id'] = $wr_config['routee_app_id'];
												 $cField = $wr_form->createCustomFields($fieldData); //creating custom field
												 if($cField){
													 $wr_routee = rcg_routee::getInstance();
													 $routeeFields = $wr_routee->retrieve_the_account_contact_fields();
														if (!array_key_exists($field['field_name'], $routeeFields)) {
																 $apiType = (($fieldData['field_type'] == 'number') ? 'Number' : 'Text' );
																 $apiData =  array('name'=>$fieldData['field_name'],'type'=>$apiType);
																 $create_custom_fields = $wr_routee->create_custom_fields($apiData);
																	 if(!$create_custom_fields){
																		 $wr_form->deleteCustomField($cField);
																		 $errorMessages[] = __rcg_lang('failedCreateCustomFieldName'). " ".$fieldData['field_name'];
																		 
																	  }else{
																		 $successMessages[] = __rcg_lang('successfullyCreatedCustomFielName'). " ".$fieldData['field_name'];
																      }
														}
												 }  
										}
							      }
								  if(!$fieldC){ $errorMessages[] = __rcg_lang('failedToCreateField'). " ".$fieldData['label_text']; }
							}
						 }
				         $successMessages[] = $sM;
						 set_transient('formMessage', wp_json_encode( array( 'successMessages'=>$successMessages, 'errorMessages'=>$errorMessages )));	
						 $json['RedirectUrl'] = admin_url('admin.php?page=wr-form-generator&frmID='.$frmbID);			
				         echo wp_json_encode($json); die;
		}
		$errorMessages[] = __rcg_lang('operationFailed');
		set_transient('formMessage', wp_json_encode( array( 'successMessages'=>$successMessages, 'errorMessages'=>$errorMessages )));
		if($frmbID > 0){ $json =  array('Failed'=>1, 'RedirectUrl'=>admin_url('admin.php?page=wr-form-generator&frmID='.$frmbID));	
		}else{ $json =  array('Failed'=>1, 'RedirectUrl'=>admin_url('admin.php?page=wr-form-generator'));	
		}
		echo wp_json_encode($json); die;	 
	}
	//get custom field list 
	public function get_custom_field_list(){
		$json  = array();
		$wr_config = get_option('wr_config');
		 if($wr_config['routee_app_id']){
				$wr_routee = rcg_routee::getInstance();
				$apiCustomField = $wr_routee->retrieve_the_account_contact_fields();
				if($apiCustomField){
					$wr_form = rcg_form::getInstance();
					foreach($apiCustomField as $cusField=>$type){
						$getCustomFieldDetails  = $wr_form->getCustomFieldDetails( array('id','label_text','field_type','is_routee'), array('field_name'=>$cusField,'api_id'=>$wr_config['routee_app_id']));
							  if($cusField!='firstName' && $cusField!='lastName' && $cusField!='mobile' && $cusField!='country' && $cusField!='email'){	
									if($getCustomFieldDetails){
										$json['customFields'][] = array(  //custom field exists in database
																				'name'=>  sanitize_text_field($cusField),
																				'label'=> sanitize_text_field($getCustomFieldDetails['label_text']),
																				'type'=>  sanitize_text_field($getCustomFieldDetails['field_type']), 
																				'is_routee'=> (int)$getCustomFieldDetails['is_routee'], 
																  );
									}else{
										$json['customFields'][] = array( //custom field not exists in database
																				'name'=>  sanitize_text_field($cusField),
																				'label'=> sanitize_text_field($cusField),
																				'type'=>  strtolower(sanitize_text_field($type)), 
																				'is_routee'=> 0, 
																  );
									}
							  }
					}
				}
		}
		echo wp_json_encode($json); die;
	}
	//set custom field as routee field
	public function set_routee_field(){
		$json =  array();
		if(isset($_POST['cusField']) && isset($_POST['isRoutee']) && isset($_POST['type'])){
			$wr_config = get_option('wr_config');
				 if($wr_config['routee_app_id']){
							$wr_form = rcg_form::getInstance();
							$checkCustomField =  $wr_form->checkCustomField($wr_config['routee_app_id'],$_POST['cusField']);
							if($checkCustomField > 0){ //if custom field exists and just set as routee field
								$updateCustomFieldDetails = $wr_form->updateCustomFieldDetails( array('is_routee'=>$_POST['isRoutee']), array('field_name'=>$_POST['cusField']));
								if($updateCustomFieldDetails){
								   $json['Success'] = 1;
							     }
							}else{ //if not exist will insert first
										 $newCustomFieldData =  array(
													'api_id'=>$wr_config['routee_app_id'],
													'attributes'=>maybe_serialize( array(
																					 'isroutee'=>true,
																					 'type'=>'text',
																					 'class'=>'text-input',
																					 'name'=>$_POST['cusField'],
																				   )),
													'options'          => '',
													'is_routee'        => (int)$_POST['isRoutee'],
													'label_text'       => sanitize_text_field($_POST['cusField']),
													'label_position'   => '',
													'field_name'       => sanitize_text_field($_POST['cusField']),
													'field_type'       => sanitize_text_field($_POST['type']),
													'field_description'=> '',
										 );
										 $createCustomFields = $wr_form->createCustomFields($newCustomFieldData);
										 if($updateCustomFieldDetails){
								              $json['Success'] = 1;
							             }
					 
				            }
							
				 }
		}
		echo wp_json_encode($json); die;
	}
	public function get_routee_custom_field_list(){
		$json =  array();
		$json['fields'] =  array();
		$wr_config = get_option('wr_config');
	  if($wr_config['routee_app_id']){
		$wr_form = rcg_form::getInstance();
		$getCustomRouteeFieldList = $wr_form->getCustomRouteeFieldList( array('is_routee'=>1,'api_id'=>$wr_config['routee_app_id']));
		if(!empty($getCustomRouteeFieldList)){
			foreach($getCustomRouteeFieldList as $routeeFields){
				 $attributes =  maybe_unserialize($routeeFields['attributes']);
				 if(!empty($attributes)){
					$fld =  array( 'attrs'=>$attributes, 'isroutee'=>(($routeeFields['is_routee']==1) ? true : false),'label'=>$routeeFields['field_name']);
								 if($routeeFields['options']!=''){
											$foptions = maybe_unserialize($routeeFields['options']);
											 if(!empty($foptions)){
													$opts =  array();
													 foreach($foptions as $foption){
														$opt['label'] = $foption['text'];
														 if($foption['attributes']){
															$opt = array_merge($opt,$foption['attributes']);
														  }
														  $opts[] = $opt;
													 }
												    $fld['values'] = $opts;
											 }	 
								}
					$json['fields'][] = $fld;			
				 }
			}
		}
	  }
		echo wp_json_encode($json); die;
	}
	//instance for ajax class
	public static function getInstance(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}
	//get all list of routee account
	public function get_routee_list(){
		$wr_routee = rcg_routee::getInstance();
		$listing = $wr_routee->retrieve_account_lists();
		$data =  array();
		if($listing){
			$count=1;
			foreach($listing as $list){ 
			    $data[] =  array(
				          $count,
						  $list['name'],
						  $list['size'],
						  (($list['name']!='All' && $list['name']!='NotListed') ?'<a href="javascript:void(0);" data-list="'.$list['name'].'" class="deleteList">'.__rcg_lang('Delete').'</a>' : '' ),
					   );
					   $count++;
			}
		}
		echo json_encode(array( 'recordsTotal'=>count($data),  'recordsFiltered'=>count($data), 'data'=>$data));
        die;
	}
	//delete routee list
	public function delete_routee_list(){
		$json =  array();
		if(isset($_POST['list'])){
			$wr_routee = rcg_routee::getInstance();
			$delete = $wr_routee->delete_lists($_POST['list']);
			if($delete){ $json['success'] = __rcg_lang('dataSuccessfullyDeleted');
			}else{
				if($wr_routee->getErrors()){  $getErrors = $wr_routee->getErrors(); $json['delete'] = $getErrors[0];
				}else{  $json['delete'] =  __rcg_lang('UnableToDeleteData');
				}
			}
		}else{ $json['delete'] = __rcg_lang('UnableToDeleteData');
		}
		echo json_encode($json);
		die;
	}
	//get contacts by list name
	public function contact_list(){
		 $list = (isset($_GET['postFilter']['list']) ? $_GET['postFilter']['list'] : 'All' );
		 $wr_routee = rcg_routee::getInstance();
		 $size      = ((isset($_GET['iDisplayLength'])) ?  $_GET['iDisplayLength'] : 10);
         $page      =  ((isset($_GET['iDisplayStart'])) ? $_GET['iDisplayStart'] : 0);
		 $listing   = $wr_routee->view_the_contacts_specified_list($list,$page,$size);
		 $data = array();
		 $totalPages = $listing['totalPages'];
		 if($listing['content']){
			 foreach($listing['content'] as $list){
				 $data[] = array( 
				   $list['id'], 
				   $list['firstName']." ".$list['lastName'],  
				   $list['email'], 
				   $list['mobile'], 
				   $list['country'],  
				   implode(',',$list['listTags']),
				  '<a href="javascript:void(0);" data-id="'.$list['id'].'" class="deleteContact">'.__rcg_lang('Delete').'</a>' ,
				   ''  
				 );
			 }
		 }
		echo json_encode(array( 'recordsTotal'=>$totalPages,  'recordsFiltered'=>$totalPages, 'data'=>$data));
        die;
	}
	//delete contact from the list
	public function delete_contact(){
		$json = array();
		if(isset($_POST['id'])){
			$wr_routee = rcg_routee::getInstance();
			$delete = $wr_routee->delete_contact((int)$_POST['id']);
			if($delete){  $json['success'] =  __rcg_lang('dataSuccessfullyDeleted');
			}else{
				if($wr_routee->getErrors()){  $getErrors = $wr_routee->getErrors(); $json['delete'] = $getErrors[0];
				}else{  $json['delete'] =  __rcg_lang('UnableToDeleteData');
				}
			}
		}else{ $json['delete'] = __rcg_lang('UnableToDeleteData');
		}
		echo json_encode($json);
		die;
	}
	public function deactivate_apply(){
		update_option('wr_uninstall_option', $_POST['points']);
		echo wp_json_encode(array('success'=>1)); die;
	}
}
}