(function($) {

	// Create a copy of the WP inline edit post function
	var $wp_inline_edit = inlineEditPost.edit;
	
	// Overwrite the function
	inlineEditPost.edit = function( id ) {
	
		// "call" the original WP edit function
		$wp_inline_edit.apply( this, arguments );
		
		// get the post ID
		var $post_id = '';
		if ( typeof( id ) == 'object' ) {
			$post_id = parseInt( this.getId( id ) );
		}

		if ( $post_id > 0 ) {
			
			// define the edit & post rows
			var $edit_row = $( '#edit-' + $post_id );
			var $post_row = $( '#post-' + $post_id );
			
			// get the current menu
			var $selected_menu = $( '#current_menu', $post_row ).val();
			
			// set the current menu
			$edit_row.find( 'select[name="selected_menu"]' ).val( $selected_menu ).prop( 'selected', true );
		}
		
	};
	
	$( '#bulk_edit' ).live( 'click', function() {
	
		// define the bulk edit row
		var $bulk_row = $( '#bulk-edit' );
		
		// get the selected post ids that are being edited
		var $post_ids = new Array();
		$bulk_row.find( '#bulk-titles' ).children().each( function() {
			$post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
		});
		
		// get the custom fields
		var $selected_menu = $bulk_row.find( 'select[name="selected_menu"]' ).val();

		// save the data
		$.ajax({
			url: ajaxurl, // this is a variable that WordPress has already defined for us
			type: 'POST',
			async: false,
			cache: false,
			data: {
				action: 'custom_menu_save_bulk_edit', // this is the name of our WP AJAX function that we'll set up next
				post_ids: $post_ids, // and these are the 2 parameters we're passing to our function
				selected_menu: $selected_menu
			}
		});
		
	});
	
})(jQuery);