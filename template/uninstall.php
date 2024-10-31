<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<script type="text/javascript">
		(function( $ ) {
			<?php $pointa_list_items_html = '';?>
			<?php foreach ( $points as $pKey=>$point ) {
				  $pointa_list_items_html .= '<li><label><span><input type="checkbox" class="deactivate_points" value="' . $pKey . '"/></span><span>' . $point . '</span></label></li>';
			}?>
			   var reasonsHtml = <?php echo json_encode( $pointa_list_items_html ); ?>,
				modalHtml	=
				'<div class="wr-modal">'
				+	'	<div class="wr-modal-dialog">'
				+	'		<div class="wr-modal-body">'
				+	'			<div class="wr-modal-panel" data-panel-id="confirm"><p></p></div>'
				+	'			<div class="wr-modal-panel active" data-panel-id="reasons"><h3><strong><?php echo __rcg_lang('choosePointsBeforeDeactivateThePlugin');?>:</strong></h3><ul id="reasons-list">' + reasonsHtml + '</ul></div>'
				+	'		</div>'
				+	'		<div class="wr-modal-footer">'
				+	'			<a href="#" class="button button-secondary wr-button-deactivate"><?php echo __rcg_lang('Deactivate');?></a>'
				+	'			<a href="#" class="button button-primary wr-button-close"><?php echo __rcg_lang('Cancel');?></a>'
				+	'		</div>'
				+	'	</div>'
				+	'</div>',
				$modal			= $( modalHtml ),
				$deactivateLink = $( '#the-list .deactivate > [data-slug='+wrForm.RCG_TD+'].wr-slug' ).prev();
		
			$modal.appendTo( $( 'body' ) );
			registerEventHandlers();
			function registerEventHandlers() {		
				$deactivateLink.click(function ( evt ) {
					evt.preventDefault();
					showModal();
				});
				$modal.on( 'click', '.wr-button-deactivate', function( evt ) {
					evt.preventDefault();
					
					if($('input[class="deactivate_points"]:checked').length> 0){
						var points = [];
						$('input[class="deactivate_points"]:checked').each(function() {  points.push($(this).val()); });
						var data = {'points':points};
						var jsonArr = JSON.parse(JSON.stringify(data));
						
						jQuery.ajax({
								 url : ajaxurl+'?action=deactivate_apply',
								 data: jsonArr,
								 type:'POST',
								 dataType:"JSON",
								 success: function(json){
									 if(json.success){
						                 window.location.href = $deactivateLink.attr('href');
										 closeModal();
									 }
								 },
							 });
					}else{
						closeModal();
						window.location.href = $deactivateLink.attr('href');
					}
				});
				// If the user has clicked outside the window, cancel it.
				$modal.on( 'click', function( evt ) {
					var $target = $( evt.target );
					// If the user has clicked anywhere in the modal dialog, just return.
					if ( $target.hasClass( 'wr-modal-body' ) || $target.hasClass( 'wr-modal-footer' ) ) {
						return;
					}
					// If the user has not clicked the close button and the clicked element is inside the modal dialog, just return.
					if ( ! $target.hasClass( 'wr-button-close' ) && ( $target.parents( '.wr-modal-body').length > 0 ||  $target.parents( '.wr-modal-footer').length > 0 ) ) {
						return;
					}		
					closeModal();
				});
			}
			function showModal() {
				// Display the dialog box.
				$modal.addClass( 'active' );	
				$( 'body' ).addClass( 'has-wr-modal' );
			}
			function closeModal() {
				$modal.removeClass( 'active' );	
				$( 'body' ).removeClass( 'has-wr-modal' );
			}	
		})( jQuery );
</script>
