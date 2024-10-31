// JavaScript Document
var wrForm = function(){
	return {
	  init:function(){
		    this.callMultiSelectFunc();
			this.callDatepicker();
			this.callValidationForm();
	  },
	}
}();
//calling date mask function
wrForm.callValidationForm = function(){
	jQuery.validator.addMethod("mobile", function(value, element) {
		          var extension = jQuery(element).intlTelInput("getSelectedCountryData");
			      if(typeof jQuery(element).attr('data-rule-required')!='undefined'){ 
				        return jQuery(element).intlTelInput("isValidNumber"); 
			        }else{ 
						 if( jQuery.trim(jQuery(element).val()) !=''){
							  return jQuery(element).intlTelInput("isValidNumber"); 
						 }else{
						      if( jQuery.trim(jQuery(element).val()) =='+' || jQuery.trim(jQuery(element).val()) =='+'+extension.dialCode){
						        jQuery(element).val('');
							  }
						      return true; 
						 }
				  }
			    }, jsLang.invalidMobileNumber);		
	if(jQuery(".routeeContactForm").length > 0){
		
		jQuery(".routeeContactForm").each(function() {
			   var curForm = jQuery(this);
			   var btnText = curForm.find('button[type="submit"]').text();
			   jQuery('.routeeInputNumberField',this).each(function() {
                 jQuery(this).intlTelInput({ autoPlaceholder: false, nationalMode: false, numberType: "MOBILE",  utilsScript: wrFront.plugUrl+"lib/telbuild/js/utils.js" });
			     jQuery(this).on('keyup',function(){ if(jQuery.trim(jQuery(this).val()) == ''){ jQuery(this).val('+');  } })
               });
			   var validate = curForm.validate({
									errorPlacement: function(error, element) { jQuery( element ).parent().append( error ); },
									errorElement: "span",
									submitHandler: function(form) {
										curForm.find('div.wrFrmMessage').removeClass('wrFormError').removeClass('wrFormSuccess').html(''); 
										curForm.find('div.wrMobileMessage').html(''); 
										var postData = jQuery(form).serializeArray();
										var frmbID = curForm.data('form-id');
										jQuery.ajax({
												 url : wrFront.ajaxUrl+'?action=createContact&frmbID='+frmbID,
												 data : postData,
												 type:'POST',
												 dataType:"JSON",
												 beforeSend:function(){ curForm.find('button[type="submit"]').attr('disabled',true);  curForm.find('button[type="submit"]').html('<img src="'+wrFront.ajaxLoader+'">'); },
												 complete:function(){   curForm.find('button[type="submit"]').attr('disabled',false);  },
												 success:function(jsonData){  
												    if(jsonData.Success){ //json success data
															curForm.find('div.wrFrmMessage').removeClass('wrFormError').addClass('wrFormSuccess').html(jsonData.Success.message);
															if(curForm.find('input[name="isRouteeContactUpdate"]').val() == 1){ curForm.find('input[name="isRouteeContactUpdate"]').val(0); }
															//success message will remove after 5 seconds
															setTimeout(function(){  curForm.find('div.wrFrmMessage').removeClass('wrFormSuccess').html(''); }, 10000);
															curForm.find('button[type="submit"]').html(btnText);
															curForm.find('.cancelUpdate').remove();
															curForm.find('div.wrMobileMessage').html(''); 
													}
													if(jsonData.formError){
																curForm.find('div.wrFrmMessage').removeClass('wrFormSuccess').addClass('wrFormError').html(jsonData.formError.errortext);
																setTimeout(function(){  curForm.find('div.wrFrmMessage').removeClass('wrFormSuccess').html(''); }, 10000);
																curForm.find('button[type="submit"]').html(btnText);
													}
													if(jsonData.MobileExistsformError){
																 curForm.find('div.wrMobileMessage').html(jsonData.MobileExistsformError.errortext);
																 curForm.find('input[name="isRouteeContactUpdate"]').val(1);
																 curForm.find('button[type="submit"]').html('Update contact');
																 var cancelBtn  = jQuery('<button/>', { type: "button",class: curForm.find('button[type="submit"]').attr('class')+' cancelUpdate'});
																 curForm.find('.routeeFormSubmitPanel').append(cancelBtn);
																 cancelBtn.html('Cancel');
																 cancelBtn.click(function(e){
																	 e.preventDefault();
																	 if(curForm.find('input[name="isRouteeContactUpdate"]').val() == 1){ curForm.find('input[name="isRouteeContactUpdate"]').val(0); }
																	 curForm.find('button[type="submit"]').html(btnText);
																	 jQuery(this).remove();	
																	 curForm.find('div.wrMobileMessage').html(''); 
																 });
													}
												 },
												 error: function(jqXHR, textStatus, errorThrown) {  var con = confirm(jsLang.problemServer); if(con == true){ window.location.reload();  }  },
				                       });
										
										return false;
									},
							   });
		});
	}	
};
wrForm.setNumericField = function(evt){
	 evt.value=evt.value.replace(/[^\d]/,'')
};
wrForm.callDatepicker = function(){
   if(jQuery('.dateField').length > 0){ jQuery('.dateField').datepicker(); }
};
//calling multiselect function if there is any multiselect dropdown
wrForm.callMultiSelectFunc = function(){
	if(jQuery('.build-form select[multiple="true"]').length > 0){  jQuery('.build-form select[multiple="true"]').multipleSelect({   width: '100%' }); }
};
jQuery(document).ready(function(e) {
   wrForm.init();
}).on('keyup','input[type="number"]',function(e){
	 wrForm.setNumericField(this);
});