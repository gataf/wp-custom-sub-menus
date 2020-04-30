<?php
	/*
	* Plugin Name: Custom Menu Plugin
	* Plugin URI: http://www.seansdesign.net
	* Description: Add a custom menu to individual pages.
	* Version: 1.3.3
	* Author: Sean Sullivan
	* Author URI: http://www.seansdesign.net
	* Author Email: capt.yar@gmail.com
	* License: A "Slug" license name e.g. GPL2
		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as
		published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/

	$siteurl = get_option('siteurl');

	define('SRS_FOLDER', dirname(plugin_basename(__FILE__)));
	define('SRS_URL', $siteurl.'/wp-content/plugins/' . SRS_FOLDER);
	define('SRS_FILE_PATH', dirname(__FILE__));
	define('SRS_DIR_NAME', basename(SRS_FILE_PATH));


	// ==================== Adding Metabox to Page/Post ====================
	function meta_box_menu_per_page() {
	    add_meta_box( 'custom_menu', 'Custom Menu', 'meta_selected_callback','page', 'side', '' );
	}

	// ==================== The HTML function for the metabox ====================
	function meta_selected_callback( $post ) {
		if($post->post_type=='page' || $post->post_type=='post') :
		   	$selected_menu_id = get_post_meta( $post->ID ,'selected_menu');
			$menus = wp_get_nav_menus();
?>

		<div class="wp_custom_metaboxes">
			<h4>Select Menu for this <?php echo  $post->post_type?></h4>
			<p>
				<select name="selected_menu" id="selected_menu">
					<option value="default">Default Menu</option>
			    	<?php foreach($menus as $menu) { ?>
			    	<option value="<?php echo $menu->term_id; ?>" <?php echo ($selected_menu_id[0] == $menu->term_id) ? 'selected="selected"' : ''; ?>><?php echo $menu->name; ?></option>
			    	<?php } ?>
		  		</select>
		 	</p>
		</div>

<?php
		endif;
	}

	// ==================== Save the menu ====================
	function meta_selected_menu_save( $post_id ) {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		  	if( isset( $_POST['selected_menu'] ) )
	        	update_post_meta( $post_id, 'selected_menu', $_POST['selected_menu'] );
	}


	// ==================== Add column to page list ====================
	function menu_columns( $columns ) {
        $columns['selected_menu'] = 'Custom Menu';
        return $columns;
    }

    // ==================== Display the actual column results ====================
	function menu_show_columns( $name ) {
		if ( $name == 'selected_menu' ) :
	        global $post;

	        $menu_id = get_post_meta($post->ID, 'selected_menu', true);
	        $menu_name = wp_get_nav_menu_object($menu_id);

	        echo $menu_id !== 'default' && $menu_id ? $menu_name->name . '<input type="hidden" id="current_menu" value="' . $menu_id . '">' : 'Default Menu';

		endif;
    }

    // ==================== Show dropdown in Quick Edit ====================
    function custom_menu_qe_bulk( $column_name, $post_id ) {

    	if ( ! in_array( $column_name, array( 'selected_menu' ) ) )
			return;

    	$menus = wp_get_nav_menus();

    	switch ( $column_name ) {
			case 'selected_menu':
?>
			<fieldset class="inline-edit-col-left">
				<div class="inline-edit-col">
					<label>
						<span class="title">Menu</span>
						<span class="input-text-wrap">
							<select name="selected_menu">
								<option value="default">Default Menu</option>
								<?php foreach($menus as $menu) { ?>
						    	<option value="<?php echo $menu->term_id; ?>"><?php echo $menu->name; ?></option>
						    	<?php } ?>
							</select>
						</span>
					</label>
				</div>
			</fieldset>
<?php
			break;
		}


	}

	// ==================== Pre-populate Quick Edit field ====================
	function custom_menu_enque() {
		wp_enqueue_script( 'manage-wp-posts-using-bulk-quick-edit', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'bulk-quick-edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
	}


	// ==================== Save when bulk editing ====================
	function custom_menu_save_bulk_edit() {
		// get our variables
		$post_ids = ( isset( $_POST[ 'post_ids' ] ) && !empty( $_POST[ 'post_ids' ] ) ) ? $_POST[ 'post_ids' ] : array();
		$selected_menu = ( isset( $_POST[ 'selected_menu' ] ) && !empty( $_POST[ 'selected_menu' ] ) ) ? $_POST[ 'selected_menu' ] : NULL;

		echo $_POST[ 'selected_menu' ];

		// if everything is in order
		if ( !empty( $post_ids ) && is_array( $post_ids ) && !empty( $selected_menu ) ) :
		  	foreach( $post_ids as $post_id ) :
		    	update_post_meta( $post_id, 'selected_menu', $selected_menu );
		  	endforeach;
		endif;
	}

	// ==================== Create shortcode to display menu in text widget ====================
	function custom_menu_shortcode( $args = '' ) {
		global $post;

		if ( is_home() || is_single() || is_archive() )
			$postid = get_option( 'page_for_posts' );
		else
			$postid = $post->ID;

		$selected_menu_id = get_post_meta( $postid ,'selected_menu');
	 	$new_menu_id = $selected_menu_id ? $selected_menu_id[0] : '';

		// If menu is not default, show custom menu. If is default, show child pages
		if($new_menu_id !== 'default' || $new_menu_id == '') :

			$args = array(
				'theme_location'  => '',
				'menu'            => $new_menu_id
				);

		    $nav_menu = wp_nav_menu( $args );
			echo strip_tags($nav_menu,'<a>');

		else :

			if ( is_page() && $post->post_parent )
				$child_of = $post->post_parent;
			else
				$child_of =  $post->ID;

			$childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $child_of . '&echo=0&depth=1' );
			if ( $childpages )
				$string = '<ul class="menu">' . $childpages . '</ul>';

			return $string;

		endif;

	}

	register_activation_hook(__FILE__,'srs_start_stop');
	register_deactivation_hook(__FILE__ , 'srs_start_stop' );

	// ==================== Filters and actions ====================
	add_action( 'add_meta_boxes', 'meta_box_menu_per_page' ); // Adding the meta box to edit page
	add_action( 'save_post', 'meta_selected_menu_save' ); // Saving the menu
	add_filter( 'manage_pages_columns', 'menu_columns'); // Registering the new column
	add_action( 'manage_pages_custom_column',  'menu_show_columns'); // Actually displaying the custom menu in the column
	add_action( 'bulk_edit_custom_box', 'custom_menu_qe_bulk', 10, 2 ); // Adding dropdown menu to bulk edit
	add_action( 'quick_edit_custom_box', 'custom_menu_qe_bulk', 10, 2 ); // Adding dropdown menu to quick edit
	add_action( 'admin_print_scripts-edit.php', 'custom_menu_enque' ); // Pre-populating the bulk and quick edit dropdowns
	add_action( 'wp_enqueue_scripts', 'custom_menu_enque' ); // Enque the bulk-quick-edit.js
	add_action( 'wp_ajax_custom_menu_save_bulk_edit', 'custom_menu_save_bulk_edit' ); // Actually saving the ajax AND WORKING!
	add_action( 'wp_ajax_nopriv_custom_menu_save_bulk_edit', 'custom_menu_save_bulk_edit' ); // Actually saving the ajax AND WORKING!
	add_shortcode( 'the_custom_menu', 'custom_menu_shortcode' ); // Creating shortcode to be placed in widget to show actual menu

	// Install / Uninstall
	function srs_start_stop() {
	    global $wpdb;
	}
?>
