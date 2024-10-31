<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//fields will generate here in frontend based on on field type 
if(!class_exists('rcg_fields')){
	class rcg_fields {
		public static $instance = null;
		//instance for fields class
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new self();
			}
			return self::$instance;
		}
		public function __construct(){
			add_filter( '_wr_firstname', array($this,field_firstname ),10,6);
			add_filter( '_wr_lastname', array($this,field_lastname ),10,6);
			add_filter( '_wr_email', array($this,field_email ),10,6);
			add_filter( '_wr_phone', array($this,field_mobile ),10,6);
			add_filter( '_wr_mobile', array($this,field_mobile ),10,6);
			add_filter( '_wr_number', array($this,field_number ),10,6);
			add_filter( '_wr_text', array($this,field_text),10,6);
			add_filter( '_wr_textarea', array($this,field_textarea),10,6);
			add_filter( '_wr_date', array($this,field_date),10,6);
			add_filter( '_wr_select', array($this,field_select),10,6);
			add_filter( '_wr_checkbox-group', array($this,field_checkbox_group),10,6);
			add_filter( '_wr_radio-group', array($this,field_radio_group),10,6);
			add_filter( '_wr_antispam', array($this,field_antispam),10,6);	
			add_filter( '_wr_submit', array($this,field_submit),10,6);	
		}
		//field for firstname
		public function field_firstname($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
				  $field = '';
				  $isRequired = false;
				  $errorMessage = __rcg_lang('EnterYourFirstname');
				  $field.= '<input type="text" ';
				  $field.= 'name="firstName"';
				  $field.= 'id="firstName"';
				  if(!empty($attributes)){
					$attributes = maybe_unserialize($attributes);
					foreach( $attributes as $attrKey=>$attrValue){
						if($attrKey == 'errortext' && $attrValue!=''){  $errorMessage = $attrValue;  }
						if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description'  && $attrKey!='name' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $field .= 'data-rule-required = "true"'; 
								 }
							 }else{
								 $field .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}
				  }
				  if( $isRequired ){
					   $field .= 'data-msg-required = "'.$errorMessage.'"';
				  }
				 $field .=' >';
				 $html = '';
				 $html .='<div class="wrform-group field-firstName">';
				 if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="firstName">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="firstName">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="firstName" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="firstName">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
				 return $html;
		}
		//field for last name
		public function field_lastname($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
				  $field = '';
				  $isRequired = false;
				  $errorMessage = __rcg_lang('EnterYourLastname');
				  $field.= '<input type="text" ';
				  $field.= 'name="lastName"';
				  $field.= 'id="lastName"';
				  if(!empty($attributes)){
					$attributes = maybe_unserialize($attributes);
					foreach( $attributes as $attrKey=>$attrValue){
						if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
						if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description'  && $attrKey!='name' &&  $attrKey!='type' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $field .= 'data-rule-required = "true"';
									 
								 }
							 }else{
								 $field .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}
				  }
				  if( $isRequired ){
					   $field .= 'data-msg-required = "'.$errorMessage.'"';
				  }
				 $field .=' >';
				 $html = '';
				 $html .='<div class="wrform-group field-lastName">';
				 if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="lastName">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="lastName">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="lastName" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="lastName">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
				 return $html;
		}
		//field for email
		public function field_email($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){	
				  $field = '';
				  $isRequired = false;
				  $errorMessage = __rcg_lang('EnterCorrectEmailAddress');
				  $field.= '<input type="email" ';
				  $field.= 'name="email"';
				  $field.= 'id="email"';
				  if(!empty($attributes)){
					$attributes = maybe_unserialize($attributes);
					foreach( $attributes as $attrKey=>$attrValue){
						if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
						if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description'  && $attrKey!='name' &&  $attrKey!='type' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									 $isRequired = true;
									 $field .= 'data-rule-required = "true"';				  
								 }
							 }else{
								 $field .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}
				  }
				 $field .= 'data-rule-email = "true"';
				 $field .= 'data-msg-email="'.__rcg_lang('InvalidEmail').'"';
				 if( $isRequired ){  $field .= 'data-msg-required = "'.$errorMessage.'"';  }
				 $field .=' >';
				 $html = '';
				 $html .='<div class="wrform-group field-email">';
				 if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="email">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="email">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="email" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="email">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
				 return $html;
		}
		//field for  mobile
		public function field_mobile($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
				  $field = '';
				  $isRequired = false;
				  $errorMessage = __rcg_lang('EnterYourMobileNumber');
				  $invalidNumberMessage = __rcg_lang('invalidMobileNumber');
				  $field.= '<input type="text" ';
				  if(!empty($attributes)){
					$attributes = maybe_unserialize($attributes);
					foreach( $attributes as $attrKey=>$attrValue){
						if($attrKey=='type' && $attrValue =='phone'){  $errorMessage = __rcg_lang('EnterPhoneNumber');  $invalidNumberMessage = __rcg_lang('invalidPhoneNumber'); }
						if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
						if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description'  &&  $attrKey!='type' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $field .= 'data-rule-required = "true"';
									  
								 }
							 }elseif($attrKey =='class'){
								   $field .= $attrKey.'="'.$attrValue.' routeeInputNumberField"';
							 }else{
								if($attrKey =='name'){   $field .= 'id="'.$attrValue.'"'; }
								 $field .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}
				  }
				  $field .= 'data-rule-mobile = "true"';
				  $field .= 'data-msg-mobile = "'.$invalidNumberMessage.'"';
				  //$field .= 'data-msg-number="Must be numeric value"';
				  if( $isRequired ){
					   $field .= 'data-msg-required = "'.$errorMessage.'"';
				  }
				 $field .=' >';
				 $html = '';
				 $html .='<div class="wrform-group field-'.$field_name.'">';
				 if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="'.$field_name.'" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
				 return $html;
		}
		//field for number
		public function field_number($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
			if($field_name!=''){
				  $field = '';
				  $isRequired = false;
				  $errorMessage =  __rcg_lang('ThisFieldIsRequired');
				  $field .= '<input type="number" ';
				  if(!empty($attributes)){
					$attributes = maybe_unserialize($attributes);
					foreach( $attributes as $attrKey=>$attrValue){
						if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
						if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description' && $attrKey!='type' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $field .= 'data-rule-required = "true"';
									  
								 }
							 }else{
								  if($attrKey =='name'){   $field .= 'id="'.$attrValue.'"'; }
								  $field .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}
				  }
				  $field .= 'data-rule-number = "true"';
				  $field .= 'data-msg-number="'.__rcg_lang('MustNumericValue').'"';
				   if( $isRequired ){
					   $field .= 'data-msg-required = "'.$errorMessage.'"';
				  }
				 $field .=' >';
				 $html = '';
				 $html .='<div class="wrform-group field-'.$field_name.'">';
				 if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="'.$field_name.'" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
				 return $html;
			 }
		}
		//field for text field
		public function field_text($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
			 if($field_name!=''){
				  $field = '';
				  $isRequired = false;
				  $errorMessage =  __rcg_lang('ThisFieldIsRequired');
				  $field .= '<input type="text" ';
				  if(!empty($attributes)){
					$attributes = maybe_unserialize($attributes);
					foreach( $attributes as $attrKey=>$attrValue){
						if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
						if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description' && $attrKey!='type' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $field .= 'data-rule-required = "true"';
									  
								 }
							 }else{
								  if($attrKey =='name'){   $field .= 'id="'.$attrValue.'"'; }
								  $field .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}
				  }
				   if( $isRequired ){
					   $field .= 'data-msg-required = "'.$errorMessage.'"';
				  }
				 $field .=' >';
				 $html = '';
				 $html .='<div class="wrform-group field-'.$field_name.'">';
				 if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="'.$field_name.'" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
				 return $html;
			 }
		}
		//field for textarea
		public function field_textarea($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
			 if($field_name!=''){
				  $field = '';
				  $isRequired = false;
				  $errorMessage =  __rcg_lang('ThisFieldIsRequired');
				  $field .= '<textarea ';
				  if(!empty($attributes)){
					$attributes = maybe_unserialize($attributes);
					foreach( $attributes as $attrKey=>$attrValue){
						if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
						if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description' && $attrKey!='type' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $field .= 'data-rule-required = "true"';
									   
								 }
							 }else{
								  if($attrKey =='name'){   $field .= 'id="'.$attrValue.'"'; }
								  $field .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}
				  }
				   if( $isRequired ){
					   $field .= 'data-msg-required = "'.$errorMessage.'"';
				  }
				 $field .='></textarea>';
				 $html = '';
				 $html .='<div class="wrform-group field-'.$field_name.'">';
				 if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="'.$field_name.'" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
				 return $html;	
		}
		}
		//field for date
		public function field_date($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){ 
			if($field_name!=''){
				  $field = '';
				  $isRequired = false;
				  $errorMessage = __rcg_lang('EnterDate');
				  $field .= '<input type="text" ';
				  if(!empty($attributes)){
					$attributes = maybe_unserialize($attributes);
					foreach( $attributes as $attrKey=>$attrValue){
						if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
						if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description' && $attrKey!='type' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $field .= 'data-rule-required = "true"';
									  
								 }
							 }elseif($attrKey =='class'){
								   $field .= $attrKey.'="'.$attrValue.' dateField"';
							 }else{
								  if($attrKey =='name'){   $field .= 'id="'.$attrValue.'"'; }
								  $field .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}
				  }
				  
				   if( $isRequired ){
					   $field .= 'data-msg-required = "'.$errorMessage.'"';
				  }
				 $field .= 'data-rule-date = "true"';
				 $field .= 'data-msg-date="'.__rcg_lang('EnterDate').'"';
				 $field .=' >';
				 $html = '';
				 $html .='<div class="wrform-group field-'.$field_name.'">';
				 if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="'.$field_name.'" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
				 return $html;
			 }
		}
		//field for select
		public function field_select($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
		   if(!empty($options) && $field_name!=''){
			  $isRequired = false; 
			  $isMultiple = false;
			  $errorMessage = __rcg_lang('SelectAnyOption');
			  $optionField = '';
			  $mainAttr = '';
			  $fieldName = '';
			  if($attributes){
				  $attributes = maybe_unserialize($attributes);
				  foreach( $attributes as $attrKey=>$attrValue){
					  if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
					  if($attrKey =='name'){  $fieldName = $attrValue; $mainAttr .= 'id="'.$attrValue.'"'; }
					   if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' && $attrKey!='name' &&  $attrKey!='description'  && $attrKey!='type' && $attrKey!='errortext'){
							  if($attrKey =='multiple'){
								  if($attrValue == true){
									   $isMultiple = true;
								  }
							  }
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $mainAttr .= 'data-rule-required = "true"';
									  
								 }
							 }else{
								  $mainAttr .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}  
			  }
			  if( $isRequired ){
					   $mainAttr .= 'data-msg-required = "'.$errorMessage.'"';
			  }
			  if( $isMultiple ){
				   $mainAttr .= 'name = "'.$fieldName.'[]"';
			  }else{
				   $mainAttr .= 'name = "'.$fieldName.'"';
			  }
			  $options = maybe_unserialize($options);
			  foreach($options as $optionIndex=>$option){
				$optionText = $option['text'];
				$optionAttr = '';
				foreach($option['attributes'] as $opKey=>$opValue){
					$optionAttr .= $opKey.'="'.$opValue.'"';
				}
				$optionField.='<option '.$optionAttr.'>'.$optionText.'</option>';
			  } 
			  $field = '<select '.$mainAttr.'>'.$optionField.'</select>';
			  $html ='<div class="wrform-group field-'.$field_name.'" '.(($isMultiple) ? 'style="position:relative;"' : '' ).'>';
			   if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;'.(($isMultiple) ? 'height:40px;position:relative;' : '' ).'">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:70%;float:left;'.(($isMultiple) ? 'height:40px;position:relative;' : '' ).'">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="'.$field_name.'" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:70%;float:left;'.(($isMultiple) ? 'height:40px;position:relative;' : '' ).'">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;'.(($isMultiple) ? 'height:40px;position:relative;' : '' ).'">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
			  return $html;
		   }
		}
		//field for checkbox group
		public function field_checkbox_group($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
			if(!empty($options) && $field_name!=''){
			  $field = '';
			  $html = '';
			  $isRequired = false;
			  $errorMessage = __rcg_lang('CheckAnyOption');
			  $mainAttr = '';
			  $requiredAttr = '';
			  if($attributes){
				  $attributes = maybe_unserialize($attributes);
				  foreach( $attributes as $attrKey=>$attrValue){
					   if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
					   if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description' && $attrKey!='name' && $attrKey!='type' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $requiredAttr .= 'data-rule-required = "true"';
									 
								 }
							 }else{
								  $mainAttr .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}  
			  }
			  if( $isRequired ){ $requiredAttr .= 'data-msg-required = "'.$errorMessage.'"'; }
			  $options = maybe_unserialize($options);
			  foreach($options as $optionIndex=>$option){
				$optionText = $option['text'];
				$optionAttr = '';
				foreach($option['attributes'] as $opKey=>$opValue){
					$optionAttr .= $opKey.'="'.$opValue.'"';
				}
				$field.='<input type="checkbox" '.$mainAttr.' '.(( $optionIndex == 0 ) ? $requiredAttr : '').' '.$optionAttr.' id="'.$field_name.'-'.$optionIndex.'"  name="'.$field_name.'[]"> <label for="'.$field_name.'-'.$optionIndex.'">'.$optionText.'</label><br>';
			  }
			  $html .='<div class="wrform-group field-'.$field_name.'">';
			   if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;" class="checkbox-group">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;" class="checkbox-group">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="'.$field_name.'" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;" class="checkbox-group">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;" class="checkbox-group">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
			  
			  return $html;
			}
		}
		//field for radio group
		public function field_radio_group($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
			 if(!empty($options) && $field_name!=''){
			  $field = '';
			  $html = '';
			  $isRequired = false;
			  $errorMessage = __rcg_lang('CheckAnyOption');
			  $mainAttr = '';
			  $requiredAttr = '';
			  if($attributes){
				  $attributes = maybe_unserialize($attributes);
				  foreach( $attributes as $attrKey=>$attrValue){
					   if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; }
					   if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description' && $attrKey!='name' && $attrKey!='type' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $requiredAttr .= 'data-rule-required = "true"'; 
								 }
							 }else{
								  $mainAttr .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}  
			  }
			  if( $isRequired ){
					   $requiredAttr .= 'data-msg-required = "'.$errorMessage.'"';
			  }
			  $options = maybe_unserialize($options);
			  foreach($options as $optionIndex=>$option){
				$optionText = $option['text'];
				$optionAttr = '';
				foreach($option['attributes'] as $opKey=>$opValue){
					$optionAttr .= $opKey.'="'.$opValue.'"';
				}
				$field.='<input type="radio" '.$mainAttr.' '.(( $optionIndex == 0 ) ? $requiredAttr : '').' '.$optionAttr.' id="'.$field_name.'-'.$optionIndex.'"  name="'.$field_name.'"> <label for="'.$field_name.'-'.$optionIndex.'">'.$optionText.'</label><br>';
			  }
			  $html .='<div class="wrform-group field-'.$field_name.'">';
			  if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;" class="radio-group">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;" class="radio-group">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="'.$field_name.'" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;" class="radio-group">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;" class="radio-group">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
			
			  return $html;
			}
		}
		//field for anitspam
		public function field_antispam($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
			 if($field_name!=''){
				  $field = '';
				  $isRequired = false;
				  $errorMessage = __rcg_lang('EnterYourAnswer');
				  $field .= '<input type="text" ';
				  if(!empty($attributes)){
					$attributes = maybe_unserialize($attributes);
					foreach( $attributes as $attrKey=>$attrValue){
						if($attrKey == 'errortext' && $attrValue!=''){ $errorMessage = $attrValue; } 
						if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description' && $attrKey!='type' && $attrKey!='answer' && $attrKey!='errortext'){
							 if($attrKey =='required'){
								 if($attrValue == true){
									  $isRequired = true;
									  $field .= 'data-rule-required = "true"';
								 }
							 }else{
								  if($attrKey =='name'){   $field .= 'id="'.$attrValue.'"'; }
								  $field .= $attrKey.'="'.$attrValue.'"';
							 }
						}
					}
				  }
				 if( $isRequired ){  $requiredAttr .= 'data-msg-required = "'.$errorMessage.'"';  }
				 $field .=' >';
				 $html = '';
				 $html .='<div class="wrform-group field-'.$field_name.'">';
				 if($label_position == 'down'){ //for down label  position
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
				 }elseif($label_position == 'left'){ //for left label position
						 $html .='<div style="float:left;width:30%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }elseif($label_position == 'right'){ //for right label position
						 $html .='<div style="float:right;width:30%;"><label for="'.$field_name.'" style="float:right;">'.(($isRequired) ? '<span class="required">*</span> ' : '').$label_text.(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="float:left;width:70%;">'.$field.'<br style="clear:both"></div>';
				 }else{ //for up  label position
						 $html .='<div style="width:100%;"><label for="'.$field_name.'">'.$label_text.(($isRequired) ? '<span class="required">*</span>' : '').(($field_description!='') ? '<span tooltip="'.$field_description.'" class="tooltip-element">?</span>' : '' ).'</label></div>';
						 $html .= '<div style="width:100%;">'.$field.'<br style="clear:both"></div>';
				 }
				 $html .= '</div><div style="clear:both;margin-bottom:10px;"></div>';
				 return $html;
			 }
		}
		//field for submit 
		public function field_submit($attributes = '' ,$options = '', $label_text = '',$label_position = '',$field_name = '', $field_description = ''){
			 $mainAttr = '';
			 $html = '';
			  if($attributes){
				  $attributes = maybe_unserialize($attributes);
				  foreach( $attributes as $attrKey=>$attrValue){
					   if($attrKey!='' && $attrKey!='isroutee' && $attrKey!='labelposition'  && $attrKey!='label' &&  $attrKey!='description' && $attrKey!='name' && $attrKey!='type' && $attrKey!='required' && $attrKey!='errortext'){
							  $mainAttr .= $attrKey.'="'.$attrValue.'"';
						}
					}  
			  }
			 $html.='<div class="wrform-group field-'.$field_name.' routeeFormSubmitPanel"><button id="'.$field_name.'" '.$mainAttr.' name="'.$field_name.'" type="submit">'.$label_text.'</button></div>';
			 return $html;
		}
	}
}
?>