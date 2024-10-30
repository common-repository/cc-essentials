<?php
/**
 * CCE Settings Page.
 *
 * @package CCEssentials
 * @since 1.0.0
 * @access private
 * @return void
 */

/**
 * Get all CCE settings.
 *
 * @return array Returns an array containing all CCE settings.
 */
function cce_get_settings() {
	$settings = get_option( 'cce_options' );

	if ( empty( $settings ) ) {
		$loveit_settings = is_array( get_option( 'cce_settings_loveit' ) ) ? get_option( 'cce_settings_loveit' ) : array();
		$social_settings = is_array( get_option( 'cce_settings_social' ) ) ? get_option( 'cce_settings_social' ) : array();
		$maps_settings = is_array( get_option( 'cce_settings_maps' ) ) ? get_option( 'cce_settings_maps' ) : array();

		$settings = array_merge( $loveit_settings, $social_settings, $maps_settings );

		update_option( 'cce_options', $settings );
	}

	return apply_filters( 'cce_get_settings', $settings );
}

function cce_options_page() {
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'loveit';

	ob_start(); ?>

	<div class="wrap">
		<h2><?php _e('CC Essentials – Settings', 'cc-essentials'); ?></h2>
		<h2 class="nav-tab-wrapper">
			<?php
			foreach ( cce_get_settings_tabs() as $tab_id => $tab_name ) {

				$tab_url = add_query_arg( array(
					'settings-updated' => false,
					'tab'              => $tab_id,
				) );

				$active = $active_tab == $tab_id ? ' nav-tab-active' : '';

				echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
					echo esc_html( $tab_name );
				echo '</a>';

			}
			?>
		</h2>

		<div id="tab_container">
			<form method="post" action="options.php">
				<table class="form-table">
				<?php

				if ( $_GET['page'] == 'cce' && isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
					flush_rewrite_rules();
				}

				settings_fields( 'cce_options' );
				do_settings_fields( 'cce_settings_' . $active_tab, 'cce_settings_' . $active_tab );
				?>
				</table>
				<?php submit_button(); ?>
			</form>
		</div><!-- #tab_container -->
	</div><!-- .wrap -->

	<?php
	echo ob_get_clean();
}

/**
 * Get settings tabs.
 *
 * @since 1.0.0
 * @return array An array containing tab names.
 */
function cce_get_settings_tabs() {
	$tabs              = array();
	$tabs['loveit']    = __( 'Love It! button', 'cc-essentials' );
	$tabs['social']    = __( 'Social buttons', 'cc-essentials' );
	$tabs['maps']    = __( 'Map settings', 'cc-essentials' );

	return apply_filters( 'cce_get_settings_tabs', $tabs );
}

/**
 * Validate user inputs upon save.
 *
 * @since 1.0.0
 * @param  array  $input
 * @return array
 */
function cce_settings_sanitize( $input = array() ) {
	global $cce_options;

	parse_str( $_POST['_wp_http_referer'], $referrer );

	$output    = array();
	$settings  = cce_get_registered_settings();
	$tab       = isset( $referrer['tab'] ) ? $referrer['tab'] : 'loveit';
	$post_data = isset( $_POST[ 'cce_settings_' . $tab ] ) ? $_POST[ 'cce_settings_' . $tab ] : array();

	$input = apply_filters( 'cce_settings_' . $tab . '_sanitize', $post_data );

	// Loop through each setting being saved and pass it through a sanitization filter
	foreach ( $input as $key => $value ) {
		// Get the setting type (checkbox, select, etc)
		$type = isset( $settings[ $key ][ 'type' ] ) ? $settings[ $key ][ 'type' ] : false;

		if ( $type ) {
			// Field type specific filter
			$output[ $key ] = apply_filters( 'cce_settings_sanitize_' . $type, $value, $key );
		}

		// General filter
		$output[ $key ] = apply_filters( 'cce_settings_sanitize', $value, $key );
	}

	// Loop through the whitelist and unset any that are empty for the tab being saved
	if ( ! empty( $settings[ $tab ] ) ) {
		foreach ( $settings[ $tab ] as $key => $value ) {

			// settings used to have numeric keys, now they have keys that match the option ID. This ensures both methods work
			if ( is_numeric( $key ) ) {
				$key = $value['id'];
			}

			if ( empty( $_POST[ 'cce_settings_' . $tab ][ $key ] ) ) {
				unset( $cce_options[ $key ] );
			}
		}
	}

	// Merge our new settings with the existing
	$output = array_merge( $cce_options, $output );

	add_settings_error( 'cce-notices', '', __( 'Settings Updated', 'cc-essentials' ), 'updated' );

	return $output;
}

/**
 * Register settings‘ input fields.
 *
 * @since 1.0.0
 * @return void
 */
function cce_register_settings() {
	if ( false == get_option( 'cce_options' ) ) {
		add_option( 'cce_options' );
	}

	foreach ( cce_get_registered_settings() as $tab => $settings ) {
		add_settings_section(
			'cce_settings_' . $tab,
			__return_null(),
			'__return_false',
			'cce_settings_' . $tab
		);

		foreach ( $settings as $option ) {

			add_settings_field(
				'cce_settings[' . $option['id'] . ']',
				'<label for="cce_settings_' . $tab . '[' . $option['id'] . ']">'. $option['name'] .'</label>',
				function_exists( 'cce_' . $option['type'] . '_callback' ) ? 'cce_' . $option['type'] . '_callback' : 'cce_missing_callback',
				'cce_settings_' . $tab,
				'cce_settings_' . $tab,
				array(
					'id'      		=> $option['id'],
					'desc'    		=> ! empty( $option['desc'] ) ? $option['desc'] : '',
					'placeholder'	=> ! empty( $option['placeholder'] ) ? $option['placeholder'] : '',
					'name'    		=> $option['name'],
					'section' 		=> $tab,
					'size'    		=> isset( $option['size'] ) ? $option['size'] : null,
					'options' 		=> isset( $option['options'] ) ? $option['options'] : '',
					'std'     		=> isset( $option['std'] ) ? $option['std'] : '',
				)
			);
		}
	}

	register_setting( 'cce_options', 'cce_options', 'cce_settings_sanitize' );
}
add_action( 'admin_init', 'cce_register_settings' );

/**
 * Register all settings.
 *
 * @since 1.0.0
 * @return array
 */
function cce_get_registered_settings() {
	$cce_settings = array(
		'loveit' => apply_filters('cce_loveit_settings', 
			array(
				'show_loveit_button_on' => array(
					'id'   			=> 'show_loveit_button_on',
					'name' 			=> __('Show ‘Love It!’ button on', 'verve'),
					'desc' 			=> __('Specify where the ‘Love It!’ button should be displayed.', 'verve'),
					'type' 			=> 'checkbox',
					'options'		=> array (
						'post'	=> array( 'name' => __( 'Blog posts', 'cc-essentials' ) ),
						'page'  => array( 'name' => __( 'Pages', 'cc-essentials' ) ),
						
					)
				),
				'shortcode_template_tag' => array(
					'id'   			=> 'shortcode_template_tag',
					'name' 			=> __('Shortcode & Template tag usage', 'verve'),
					'desc' 			=> '<p>'.__('To use ‘Love It!’ anywhere within your posts and pages you can use the shortcode:', 'verve').'</p><p><code>[cce_loveit]</code></p><p>' . __('To use ‘Love It!’ button manually in your theme template, use the following PHP code:', 'verve') . '</p><p><code>&lt;?php if( function_exists(\'cce_loveit\') ) cce_loveit(); ?&gt;</code></p><p>' . __('Note: All the settings below are applicable to LoveIt button inserted using the shortcode or the template tag.', 'verve') . '</p>',
					'type' 			=> 'textblock'
				),
				'prefix_text' => array(
					'id'   			=> 'prefix_text',
					'name' 			=> __('Prefix text', 'verve'),
					'placeholder' 	=> 'Loved this?',
					'desc' 			=> __('The text before the icon. Leave blank for no text before the icon.', 'verve'),
					'type' 			=> 'text'
				),
				'suffix_text_zero' => array(
					'id'   			=> 'suffix_text_zero',
					'name' 			=> __('Suffix text when no loves received', 'verve'),
					'placeholder' 	=> __('No loves yet!', 'verve'),
					'desc' 			=> __('The text after the count when no one has loved a post/page. Leave blank for no text after the count.', 'verve'),
					'type' 			=> 'text'
				),
				'hide_count_if_zero' => array(
					'id'   			=> 'hide_count_if_zero',
					'name' 			=> __('Hide count when no loves received', 'verve'),
					'desc' 			=> __('Check this to hide the ‘0’ count when no loves are received.', 'verve'),
					'type' 			=> 'checkbox',
					'options'		=> array (
						'yes'	=> array( 'name' => __( 'Yes, hide it', 'cc-essentials' ) )					
					)
				),
				'suffix_text_one' => array(
					'id'   			=> 'suffix_text_one',
					'name' 			=> __('Suffix text when 1 love received', 'verve'),
					'placeholder' 	=> __('person loved this!', 'verve'),
					'desc' 			=> __('The text after the count when one person has loved a post/page. Leave blank for no text after the count.', 'verve'),
					'type' 			=> 'text'
				),
				'suffix_text_more' => array(
					'id'   			=> 'suffix_text_more',
					'name' 			=> __('Suffix text when more than 1 loves received', 'verve'),
					'placeholder' 	=> __('people loved this!', 'verve'),
					'desc' 			=> __('The text after the count when more than one person has loved a post/page. Leave blank for no text after the count.', 'verve'),
					'type' 			=> 'text'
				)
			)
		),
		'social' => apply_filters( 'cce_social_settings',
			array(
				'facebook' => array(
					'id'   			=> 'facebook',
					'name' 			=> 'Facebook',
					'placeholder' 	=> 'https://www.facebook.com/username',
					'desc' 			=> 'Enter the URL of your Facebook profile or page.',
					'type' 			=> 'url'
				),
				'twitter' => array(
					'id'   			=> 'twitter',
					'name' 			=> 'Twitter',
					'placeholder' 	=> 'http://twitter.com/username',
					'desc' 			=> 'Enter the URL of your Twitter profile.',
					'type' 			=> 'url'
				),
				'instagram' => array(
					'id'   			=> 'instagram',
					'name' 			=> 'Instagram',
					'placeholder' 	=> 'http://instagram.com/username',
					'desc' 			=> 'Enter the URL of your Instagram profile.',
					'type' 			=> 'url'
				),
				'youtube' => array(
					'id'   			=> 'youtube',
					'name' 			=> 'YouTube',
					'placeholder' 	=> 'http://www.youtube.com/user/username',
					'desc' 			=> 'Enter the URL of your Youtube profile.',
					'type' 			=> 'url'
				),
				'flickr' => array(
					'id'   			=> 'flickr',
					'name' 			=> 'Flickr',
					'placeholder' 	=> 'http://www.flickr.com/photos/username',
					'desc' 			=> 'Enter the URL of your Flickr profile.',
					'type' 			=> 'url'
				),
				'google-plus' => array(
					'id'   			=> 'google-plus',
					'name' 			=> 'Google+',
					'placeholder' 	=> 'https://plus.google.com/username',
					'desc' 			=> 'Enter the URL of your Google+ profile if you use it :P',
					'type' 			=> 'url'
				),
				'linkedin' => array(
					'id'   			=> 'linkedin',
					'name' 			=> 'LinkedIn',
					'placeholder' 	=> 'http://www.linkedin.com/in/username',
					'desc' 			=> 'Enter the URL of your LinkedIn profile.',
					'type' 			=> 'url'
				),
				'mail' => array(
					'id'   			=> 'mail',
					'name' 			=> 'Email',
					'placeholder' 	=> 'user@name.com',
					'desc' 			=> 'Enter your email.',
					'type' 			=> 'text'
				),
				'pinterest' => array(
					'id'   			=> 'pinterest',
					'name' 			=> 'Pinterest',
					'placeholder' 	=> 'http://pinterest.com/username',
					'desc' 			=> 'Enter the URL of your Pinterest profile.',
					'type' 			=> 'url'
				),
				'dribbble' => array(
					'id'   			=> 'dribbble',
					'name' 			=> 'Dribbble',
					'placeholder' 	=> 'http://dribbble.com/username',
					'desc' 			=> 'Enter the URL of your Dribbble profile.',
					'type' 			=> 'url'
				),
				'behance' => array(
					'id'   			=> 'behance',
					'name' 			=> 'Behance',
					'placeholder'	=> 'https://behance.com/username',
					'desc' 			=> 'Enter the URL of your Behance profile.',
					'type' 			=> 'url'
				),
				'deviantart' => array(
					'id'   			=> 'deviantart',
					'name' 			=> 'Deviant Art',
					'placeholder' 	=> 'http://username.deviantart.com',
					'desc' 			=> 'Enter the URL of your DeviantArt profile.',
					'type' 			=> 'url'
				),
				'foursquare' => array(
					'id'   			=> 'foursquare',
					'name' 			=> 'Foursquare',
					'placeholder' 	=> 'https://foursquare.com/username',
					'desc' 			=> 'Enter the URL of your Foursquare profile.',
					'type' 			=> 'url'
				),
				'github' => array(
					'id'   			=> 'github',
					'name' 			=> 'GitHub',
					'placeholder' 	=> 'https://github.com/username',
					'desc' 			=> 'Enter the URL of your Github page.',
					'type' 			=> 'url'
				),
				'rss' => array(
					'id'   			=> 'rss',
					'name' 			=> 'RSS',
					'placeholder' 	=> get_bloginfo( 'rss2_url' ),
					'desc' 			=> 'Enter the URL of your RSS feed.',
					'type' 			=> 'url'
				),
				'skype' => array(
					'id'   			=> 'skype',
					'name' 			=> 'Skype',
					'placeholder' 	=> 'skype: +1 (234) 5678 &nbsp;&nbsp;or&nbsp;&nbsp; skype: john_doe',
					'desc' 			=> 'Enter your Skype call URI. For e.g. &lsquo;skype: [phone_number]&rsquo; or &lsquo;skype: [username]&rsquo;',
					'type' 			=> 'text'
				),
				'tumblr' => array(
					'id'   			=> 'tumblr',
					'name' 			=> 'Tumblr',
					'placeholder' 	=> 'http://username.tumblr.com',
					'desc' 			=> 'Enter the URL of your Tumblr blog.',
					'type' 			=> 'url'
				),
				'vimeo' => array(
					'id'   			=> 'vimeo',
					'name' 			=> 'Vimeo',
					'placeholder' 	=> 'https://vimeo.com/username',
					'desc' 			=> 'Enter the URL of your Vimeo profile.',
					'type' 			=> 'url'
				),
				'vine' => array(
					'id'   			=> 'vine',
					'name' 			=> 'Vine',
					'placeholder' 	=> 'https://vine.co/username',
					'desc' 			=> 'Enter the URL of your Vine profile.',
					'type' 			=> 'url'
				),
				'wordpress' => array(
					'id'   			=> 'wordpress',
					'name' 			=> 'WordPress',
					'placeholder' 	=> 'https://profiles.wordpress.org/username',
					'desc' 			=> 'Enter the URL of your WordPress profile.',
					'type' 			=> 'url'
				)
			)
		),
		'maps' => apply_filters( 'cce_social_settings',
			array(
				'api_key' => array(
					'id'   			=> 'api_key',
					'name' 			=> 'Google Maps API Key',
					'placeholder' 	=> '',
					'desc' 			=> sprintf( __('An API key is required for proper functioning of the &lsquo;Google Map&rsquo; shortcode. %sGet your free Maps API Key%s.', 'cc-essentials'), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">', '</a>'),
					'type' 			=> 'text'
				)
			)
		)
	);

	return $cce_settings;
}


function cce_missing_callback( $args ) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'cc-essentials' ), $args['id'] );
}

/**
 * Text callback.
 *
 * Renders text fields.
 *
 * @since 1.0.0
 * @param  array $args Arguments passed by the setting
 * @global $cce_options Array of all CCEssentials options
 * @return void
 */
function cce_text_callback( $args ) {
	global $cce_options;

	if ( isset( $cce_options[ $args['id'] ] ) )
		$value = $cce_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

	$html = '<input type="text" class="' . $size . '-text" id="cce_settings_' . $args['section'] . '[' . $args['id'] . ']" name="cce_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" placeholder="' . $args['placeholder'] . '"/>';
	$html .= '<p class="description">'  . $args['desc'] . '</p>';

	echo $html;
}

/**
 * URL callback.
 *
 * Renders URL fields.
 *
 * @param  array $args Arguments passed by the setting
 * @global $cce_options Array of all CCEssentials options
 * @return void
 */
function cce_url_callback( $args ) {
	global $cce_options;

	if ( isset( $cce_options[ $args['id'] ] ) )
		$value = $cce_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

	$html = '<input type="text" class="' . $size . '-text" id="cce_settings_' . $args['section'] . '[' . $args['id'] . ']" name="cce_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_url( $value ) . '" placeholder="' . $args['placeholder'] . '"/>';
	$html .= '<p class="description">'  . $args['desc'] . '</p>';

	echo $html;
}

/**
 * Select callback.
 *
 * Renders select fields.
 *
 * @since 1.0.0
 * @param  array $args Arguments passed by the setting
 * @global $cce_options Array of all CCEssentials options
 * @return void
 */
function cce_select_callback( $args ) {
	global $cce_options;

	if ( isset( $cce_options[ $args['id'] ] ) )
		$value = $cce_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$html = '<select id="cce_settings_' . $args['section'] . '[' . $args['id'] . ']" name="cce_settings_' . $args['section'] . '[' . $args['id'] . ']"/>';

	foreach ( $args['options'] as $option => $name ) :
		$selected = selected( $option, $value, false );
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	endforeach;

	$html .= '</select>';
	$html .= '<p class="description">'  . $args['desc'] . '</p>';

	echo $html;
}

/**
 * Checkbox callback.
 *
 * Renders Checkbox fields.
 *
 * @since 1.0.0
 * @param  array $args Arguments passed by the setting
 * @global $cce_options Array of all CCEssentials options
 * @return void
 */
function cce_checkbox_callback( $args ) {
	global $cce_options;
	$html = '';
	
	if ( isset( $cce_options[ $args['id'] ] ) ) {
		$value = $cce_options[ $args['id'] ];
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}
		
	foreach ( $args['options'] as $option => $optionparams ) :	
		if ( isset( $value[$option] ) ) { 
			$checked = 'checked="checked"';
		} else {
			$checked = '';
			$value[$option] = 0;
		}
		$html .= '<input type="checkbox" id="cce_settings_' . $args['section'] . '[' . $args['id'] . '][' . $option . ']" name="cce_settings_' . $args['section'] . '[' . $args['id'] . '][' . $option . ']"' . $checked . '/>' . $optionparams['name'] . '<br>';	
	endforeach;

	$html .= '<p class="description">'  . $args['desc'] . '</p>';

	echo $html;
}

/**
 * Textblock callback.
 *
 * Renders text-block.
 *
 * @since 1.0.0
 * @param  array $args Arguments passed by the setting
 * @global $cce_options Array of all CCEssentials options
 * @return void
 */
function cce_textblock_callback( $args ) {
	global $cce_options;
	
	$html = $args['desc'];
	
	echo $html;
}


/**
 * Header callback.
 *
 * Renders the header.
 *
 * @since 1.0.0
 * @param  array $args Arguments passed by the setting
 * @return void
 */
function cce_header_callback( $args ) {
	echo '';
}
