<?php
/*
 * Plugin Name: Quick Post Change
 * Plugin URI: http://tylersteinhaus.com
 * Description: Metabox that allows you to change posts quickly on the edit screen.
 * Version: 1.1
 * Author: Tyler Steinhaus
 * Author URI: http://tylersteinhaus.com
 */
 
class ts_quick_post_change {
	/**
	 * Instance of this class for our singleton
	 */
	private static $instance = null;
	
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		add_action( 'admin_init', function()  {
			// Add Post Type Quick Post Change
			add_action( 'add_meta_boxes', array( $this, 'addQuickPostChangeMetaBox' ) );	
		} );
	}
    
    /**
		Quickly change between posts on the edit screen
		
		@since 09-16-2015
		@author Tyler Steinhaus
	**/
	public function addQuickPostChangeMetaBox( $post ) {
		$meta_box = function() {
			global $post;
			$pages = get_posts( array( 'post_type' => get_post_type() ) );
			$output = '<select name="page_id" id="page_id" style="width: 100%;" onchange="window.location.href = \'post.php?post=\'+this.value+\'&action=edit\';">';
			$output .= '<option value="">--- Select a post ---</option>';
			$output .= walk_page_dropdown_tree( $pages, 0, array( 'selected' => $post->ID ) );
			$output .= "</select>\n";
			echo $output;
		};
		
		$unset_post_types = array( 'attachment', 'revision', 'nav_menu_item', 'nf_sub' );
		$unset_post_types = apply_filters( 'quick_post_type_unset', $unset_post_types );
	
		$post_types = get_post_types();
		foreach( $post_types as $post_type ) {
			if( in_array( $post_type, $unset_post_types ) ) continue;
			add_meta_box( 'ts_quick_post_change', "Quick Post Change", $meta_box, $post_type, 'side', 'high' );
		}
	}
}

ts_quick_post_change::get_instance();
