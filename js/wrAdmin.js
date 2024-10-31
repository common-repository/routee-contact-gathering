// JavaScript Document

var wrAdmin = function(){
	 
	return {
	  init:function(){
		  if(jQuery('#fb-template').length > 0){ this.initFormBuilder(); }
		  if(jQuery('.cb-wrap').length > 0){ this.addRouteeLists();  }
	  },
	}
}();
wrAdmin.addRouteeLists = function(){
	if(accessAllowed.value){ //if the api key is valid
	                 var editSegment = false;
					 var selectedList = [];
					 if(wrForm.LIST){
						 
						var selectedList = wrForm.LIST;
						if(selectedList.length > 0){
						    editSegment = true;
						}
					 }
					if(editSegment == true){
					    var segmentInnerHtml = jQuery('<li/>').html('<h3>'+jsLang.editSelectedSegments+'</h3>'); //Assign to existing segments
					}else{
						var segmentInnerHtml = jQuery('<li/>').html('<h3>'+jsLang.assignToExistingSegments+'</h3>'); //Assign to existing segments
					}
					var checkBoxHtml = '';
					if(editSegment == false){
							 var checkBoxHtml = jQuery('<li/>').append( jQuery('<div/>',{style:"float:left;padding:6px"}).append(jQuery('<input/>',{
										type:'radio',
										name : 'assign_list',
										checked : 'checked',
										value : 1
								 })).append(jsLang.yes)).append( jQuery('<div/>',{style:"float:left;padding:6px"}).append(jQuery('<input/>',{
										type:'radio',
										name : 'assign_list',
										value : 0,
								 })).append(jsLang.no));
					}
					 //text field  and select box field or seletct texta 	 	
					 var listHtmlOne =  jQuery('<li/>', { 'class' : 'listItem' }).css({ 'display': ((editSegment == true) ? 'block' : 'none' ), 'float' : 'left' }).append('<strong>'+((editSegment == true) ? jsLang.editWithNewSegments   : jsLang.editSelectedSegments)+':</strong>').append(jQuery('<input/>',{type:'text',class:'list_field',value:''}).attr('name','n_list').css({'width':'100%'})).append(jsLang.addMultipleListSeparateByComma);
					 var listHtmlTwo  =  jQuery('<li/>', { 'class' : 'listItem' }).css({ 'display': ((editSegment == true) ? 'block' : 'none'),'position':'relative','width':'100%', 'float' : 'left' }).append('<strong>'+((editSegment == true) ? jsLang.yourSelectedSegments : jsLang.chooseList)+':</strong>');
					 var listselect = jQuery('<select/>',{ id:'multiSelectList', multiple:'multiple' }).attr('name','e_list').addClass('list_field').css({'width':'100%'});
					 if(routeeLists.length > 0){
						 jQuery.each(routeeLists,function(index,value){ 
						    if(value.name !='All' && value.name !='NotListed'){
								var optList = jQuery('<option/>',{value:value.name}).html(value.name);
							  	 if(editSegment == true && jQuery.inArray(value.name,selectedList)!= -1){
									optList.attr({'selected':'selected'});
								 }
							     listselect.append(optList);   
							  } 
							 });
					 }
					 listHtmlTwo = listHtmlTwo.append(listselect);	
					 var segmentHtml = jQuery('<ul/>').append(segmentInnerHtml,checkBoxHtml,listHtmlTwo,listHtmlOne);	
	}else{ //showing the message from applicatio id and secret is not provide.
		 segmentHtml = '<h4>'+jsLang.loginWithApp+' <a href="admin.php?page=wr-settings">'+jsLang.clickHere+'<a></h4>'; 
	}
	 jQuery('.wr-wrform-attr-panel').append("<h2>"+jsLang.routeeSegment+"</h2>").append(segmentHtml);
	 if(accessAllowed.value){ this.callMultiSelectFunc();  this.showFieldFormList();  }
},

wrAdmin.callMultiSelectFunc = function(){
	    jQuery('#multiSelectList').change(function() {
			 
        }).multipleSelect({
             width: '100%'
        });
},
wrAdmin.initFormBuilder = function(){
	if(wrForm.FORM_NAME){ jQuery('#form_name').val(wrForm.FORM_NAME); }
	if(wrForm.SHOW_NAME){ if(wrForm.SHOW_NAME == 1){ jQuery('#show_name').attr('checked',true);  }else{ jQuery('#show_name').attr('checked',false);  }   }
	var test  = false;
	//var test  = false;
	 var fbTemplate = document.getElementById('fb-template');
	 var xmlfrm = '';
	
	 if(wrForm.FIELDS){
		if(wrForm.FIELDS.length > 0){
		   //console.log(wrForm.FIELDS);	
		    var codeTag = jQuery('<code/>');
		    var templateTag = jQuery('<wrform-template/>'); //template tag
		    var fieldsTag   = jQuery('<fields/>'); //fields tag
            var jsonArr = JSON.parse(JSON.stringify(wrForm.FIELDS));
		    jQuery.each(jQuery.parseJSON(wrForm.FIELDS),function(i,el){
				var FieldTag = jQuery('<field/>'); //field tag start
				jQuery.each(this.attributes,function(attributeKey,attributeValue){
					        FieldTag[0].setAttribute(attributeKey,String(attributeValue));
				});
				if(this.options.length > 0){
					jQuery.each(this.options,function(){
						var optionTag = jQuery('<option/>');
						jQuery.each(this.attributes,function(optAttributeKey,optAttributeValue){ optionTag[0].setAttribute(optAttributeKey,String(optAttributeValue)); });
						optionTag.text(this.text).appendTo(FieldTag);
					});
				}
				FieldTag.appendTo(fieldsTag);
			    //console.log(this.attributes);
				//console.log(this.options);
			});
			fieldsTag.appendTo(templateTag);
			templateTag.appendTo(codeTag);
			xmlfrm = codeTag.html();
			
		}
	}
	 if(xmlfrm!=''){  fbTemplate.value = xmlfrm; }
	 
	
	
	var defaultFields =  [
			{  label: 'Mobile', name: 'mobile', required: 'true',  type: 'mobile',  isroutee:true, errortext: jsLang.EnterYourMobileNumber },
			{  label: 'Send', class: 'btn btn-default button-input',  type: 'submit', name:'submit', isroutee:true }
	 ];
	  var formBuilder = jQuery(fbTemplate).formBuilder({
		frmbID : wrForm.ID,
		controlPosition: 'left',
		controlOrder: [ 'autocomplete', 'firstname',  'lastname', 'mobile', 'email',  'number', 'text',  'textarea',  'select', 'checkbox', 'checkbox-group', 'date',  'file',  'header',  'hidden',  'paragraph',  'radio-group',  'button', 'date','antispam', 'phone' ],
		disableFields: ['autocomplete', 'hidden', 'file','header','paragraph','button','checkbox'],			  
		defaultFields: defaultFields,
   });
   
},
wrAdmin.associateArr = function(keys,values){
	 var index,   output = Object.create(null);
	 for(index = 0; index < values.length; index++){  output[keys[index]] = values[index]; }
	 return output; 
};
wrAdmin.showFieldFormList = function(){
		if(jQuery('input[name="assign_list"]').length > 0){
			s = jQuery('input[name="assign_list"]:checked').val();
			if(s == 1){
				if(!jQuery('.listItem').eq(0).is(":visible")){  jQuery('.listItem').eq(0).show(); }
				if(jQuery('.listItem').eq(1).is(":visible")){  jQuery('.listItem').eq(1).hide(); }
			}else{
				if(jQuery('.listItem').eq(0).is(":visible")){  jQuery('.listItem').eq(0).hide(); }
				if(!jQuery('.listItem').eq(1).is(":visible")){ jQuery('.listItem').eq(1).show(); }
			}
		}
};
wrAdmin.appendCustomFieldList = function(){
	jQuery.ajax({
		 url : ajaxurl+'?action=get_custom_field_list',
		 data: {},
		 type:'POST',
		 dataType:"JSON",
		 success: function(json){
			if(json.customFields){
				 var customFieldUl = jQuery('<ul/>');
				jQuery.each(json.customFields,function(indx,ele){
					var customFieldLi = jQuery('<li/>');
					if(parseInt(ele.is_routee) == 1 ){
					    jQuery('<input/>',{ 'type': 'checkbox','value':ele.name, 'data-type': ele.type, 'class':'setRouteeField','checked':'checked'}).appendTo(customFieldLi).after(ele.name);
					}else{
						jQuery('<input/>',{ 'type': 'checkbox','class':'setRouteeField','value' : ele.name, 'data-type': ele.type } ).appendTo(customFieldLi).after(ele.name);
					}
					customFieldLi.appendTo(customFieldUl);
					
				});
				customFieldUl.appendTo($('.wrform-customFieldPopUp'));
			}else{
				var customFieldLi = jQuery('<li/>');
				jQuery('<h5/>').appendTo($('.wrform-customFieldPopUp')).html(jsLang.CustomFieldNotAvailable);
			}
		 },
		 error: function(jqXHR, textStatus, errorThrown) {
			 var con = confirm(jsLang.problemServer);
			 if(con == true){ window.location.reload();  }
		 },
	 });
};
jQuery(document).ready(function(e) {
   wrAdmin.init();
}).on('click','.wrform-customFieldOpenpopup',function(e){
	if(jQuery('.wrform-customFieldPopUp').is(":visible") == true){
    	jQuery('.wrform-customFieldPopUp').hide();
	}else{
		jQuery('.wrform-customFieldPopUp').show();
	}
}).on('click','input[name="assign_list"]',function(){
	 wrAdmin.showFieldFormList(); 
}).on('click','.setRouteeField',function(){ 
    var Me       = jQuery(this);
	var isRoutee = (( Me.is(':checked') == true ) ? 1 : 0 );
	var type     = Me.data('type');
	jQuery.ajax({
		 url : ajaxurl+'?action=set_routee_field',
		 data: { cusField : Me.val(),  'isRoutee' : isRoutee,  'type' : type },
		 type:'POST',
		 dataType:"JSON",
		 beforeSend:function(){   $('#FormBuilderPanel').append('<div id="formBuliderOverlay">' +'<h1 id="formBuliderLoading">'+jsLang.UpdatingRouteeFieldPanel+'...<h1>' +'</div>'); $('html, body').animate({ scrollTop: $('#formBuliderLoading').offset().top - 200 }, 1000);  },
		 complete:function(){ $('#formBuliderOverlay').remove(); },
		 success:function(){ location.reload(); },
		 error: function(jqXHR, textStatus, errorThrown) {
			 var con = confirm(jsLang.problemServer);
			 if(con == true){ window.location.reload();  } 
		 },
	 }); 
});
