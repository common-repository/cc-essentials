<?php

if ( ! class_exists( 'CCShortcodes' ) ) {

class CCShortcodes {

	public function __construct() {
		add_action( 'init', array( &$this, 'shortcodes_init' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_menu_styles' ) );
		add_filter( 'mce_external_languages', array( &$this, 'add_tinymce_lang' ), 10, 1 );
		add_action( 'wp_ajax_popup', array( &$this, 'shortcode_popup_callback' ) );
	}

	public function admin_menu_styles( $hook ) {
		global $cce;
		
		$cce_options = get_option('cce_options');
		$api_key = isset( $cce_options['api_key'] ) ? 'key='.$cce_options['api_key'] : false;

		wp_enqueue_style( 'cce-admin-shortcodes-styles', $cce->plugin_url() . '/assets/css/cce-admin-styles-shortcodes.css' );

		wp_enqueue_style( 'font-awesome', $cce->plugin_url() . '/assets/css/font-awesome.min.css', '', '4.7' );

		wp_register_script( 'font-awesome-icons-list', $cce->plugin_url() . '/assets/js/min/icons.min.js', array(), '4.7', true );
		wp_enqueue_script( 'font-awesome-icons-list' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?'.$api_key.'&libraries=places', null, $cce->version, true );
		wp_enqueue_script( 'cce-shortcode-plugins', $cce->plugin_url() . '/assets/js/min/shortcodes_plugins.min.js', array( 'font-awesome-icons-list', 'google-maps' ), $cce->version, true );
		
		wp_enqueue_style( 'wp-color-picker' );        
		wp_enqueue_script( 'wp-color-picker' );

		wp_localize_script( 'jquery', 'CCShortcodes', array(
			'plugin_folder'           => WP_PLUGIN_URL .'/shortcodes',
			'media_frame_video_title' => __( 'Upload or select a video', 'cc-essentials' ),
			'media_frame_image_title' => __( 'Upload or select an image', 'cc-essentials' )
		) );
	}

	public function shortcodes_init() {
		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( &$this, 'add_rich_plugins' ) );
			add_filter( 'mce_buttons', array( &$this, 'register_rich_buttons' ) );
		}
	}

	public function add_tinymce_lang( $arr ) {
		global $cce;
		$arr['cceShortcodes'] = $cce->plugin_path() . '/assets/js/plugin-lang.php';
		return $arr;
	}

	public function add_rich_plugins( $plugin_array ) {
		global $cce;

		$plugin_array['cceShortcodes'] = $cce->plugin_url() . '/assets/js/min/plugin.min.js'; // For older version of TinyMCE, use plugin.legacy.js

		return $plugin_array;
	}

	public function register_rich_buttons( $buttons ) {
		array_push( $buttons, 'cceShortcodes' );
		return $buttons;
	}

	public function shortcode_popup_callback(){
		require_once( 'shortcode-class.php' );
		$shortcode = new CCE_Shortcodes( $_REQUEST['popup'] );

		?>
		<!DOCTYPE html>
		<html>
		<head></head>
		<body>

		<div id="cce-popup">
			
			<span id="_cce_popup_title" class="hidden"><?php echo $shortcode->popup_title; ?></span>
			
			<div id="cce-sc-form-body">
				
				<form method="post" id="cce-sc-form">
	
					<ul>
	
						<?php echo $shortcode->output; ?>
	
						<!--
						<li class="cce-sc-form-row">
							<?php if ( ! $shortcode->has_child ) : ?><?php endif; ?>
						</li>
						-->
	
					</ul>
	
				</form>
				
			</div>
			
			<div id="cce-sc-form-footer">
				<a href="#" class="button button-primary button-large cce-insert"><?php _e( 'Insert Shortcode', 'cc-essentials' ); ?></a>
			</div>

			<div class="clear"></div>

		</div>
		<script type="text/javascript">
			
			(function($){

				"use strict";
			
				$(document).ready(function(){
					
					$(".cce-form-color").wpColorPicker();					
					
				});
			
			})(jQuery);
			
		</script>
		</body>
		</html>
		<?php

		die();
	}

}

$GLOBALS['cceShortcodes'] = new CCShortcodes();

}
