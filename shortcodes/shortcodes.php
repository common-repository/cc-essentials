<?php
/**
 * All shortcodes.
 *
 * @package    CC Essentials
 * @subpackage Shortcodes
 * @author     Jal Desai
 * @since	   1.0.0
 */

/* ===================================================================
/*	Content
/* =================================================================== */

/* Accordion toggle */

if ( ! function_exists( 'cce_toggle' ) ) :
function cce_toggle( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'title' => __( 'Title goes here', 'cc-essentials' ),
		'state' => 'open',
	), $atts, 'cce_toggle' );

	wp_enqueue_script( 'cce-shortcode-scripts' );

	return '<div data-id="' . esc_attr( $args['state'] ) . '" class="cce-section cce-toggle"><span class="cce-toggle-title">' . esc_html( $args['title'] ) . '</span><div class="cce-toggle-inner"><div class="cce-toggle-content">' . do_shortcode( $content ) . '</div></div></div>';
}
endif;
add_shortcode( 'cce_toggle', 'cce_toggle' );


/* Button */

if ( ! function_exists( 'cce_button' ) ) :
function cce_button( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'url'        => '#',
		'target'     => '_self',
		'color'      => 'blue',
		'size'       => 'small',
		'shape'      => 'rounded',
		'icon'       => '',
		'icon_order' => 'before',
		'alignment'	 => 'none'
	), $atts, 'cce_button' );

	$button_icon = '';
	$class       = " cce-button--{$args['size']}";
	$class       .= " cce-button--{$args['color']}";
	$class       .= " cce-button--{$args['shape']}";
	$class       .= " cce-button--{$args['alignment']}";

	if ( ! empty( $args['icon'] ) ) {
		if ( $args['icon_order'] == 'before' ) {
			$button_content = cce_icon( array( 'icon' => $args['icon'] ) );
			$button_content .= do_shortcode( $content );
		} else {
			$button_content = do_shortcode( $content );
			$button_content .= cce_icon( array( 'icon' => $args['icon'] ) );
		}
		$class .= " cce-button--icon-{$args['icon_order']}";
	} else {
		$button_content = do_shortcode( $content );
	}

	return '<a target="'. esc_attr( $args['target'] ) .'" href="'. esc_url( $args['url'] ) .'" class="cce-section cce-button'. esc_attr( $class ) .'">'. $button_content .'</a>';
}
endif;
add_shortcode( 'cce_button', 'cce_button' );


/* Icon box */

if ( ! function_exists( 'cce_iconbox' ) ) :
function cce_iconbox( $atts, $content = null ) {
    $args = shortcode_atts( array(
    	'icon' => '',
    	'icon_size' => '48px',
    	'icon_position' => 'top',
    	'title'	=> '',
    ), $atts, 'cce_iconbox' );
    
    $icon = cce_icon( array( 'icon' => $args['icon'], 'size' => $args['icon_size'] ) );
    $title = $args['title'] ? "<h5>{$args['title']}</h5>" : '';

	return '<div class="cce-section cce-iconbox cce-iconbox--'. esc_attr( $args['icon_position'] ) . '">' . $icon . '<div class="cce-iconbox--content">' . $title . do_shortcode( $content ) . '</div></div>';
}
endif;
add_shortcode( 'cce_iconbox', 'cce_iconbox' );


/* Notification */

if ( ! function_exists( 'cce_notification' ) ) :
function cce_notification( $atts, $content = null ) {
    $args = shortcode_atts( array(
    	'type' => 'info',
    	'title' => '',
    	'dismissible' => 'no'
    ), $atts, 'cce_notification' );
    
    $class = " cce-notification--{$args['type']}";
    $class .= " cce-notification--dismiss-{$args['dismissible']}";
    if ( $args['type'] == 'info' ) { $icon = '<i class="fa fa-info-circle"></i>'; }
    if ( $args['type'] == 'success' ) { $icon = '<i class="fa fa-check-circle"></i>'; }
    if ( $args['type'] == 'warning' ) { $icon = '<i class="fa fa-warning"></i>'; }
    if ( $args['type'] == 'danger' ) { $icon = '<i class="fa fa-times-circle"></i>'; }
    $title = $args['title'] ? "<strong>{$args['title']}</strong>" : '';
    $dismiss = ( $args['dismissible'] == 'yes' ) ? "<button type='button' class='dismiss-notification'>&times;</button>" : '';
    
    wp_enqueue_script( 'cce-shortcode-scripts' );

	return '<div class="cce-section cce-notification' . esc_attr( $class ) . '">' . $icon . $title . do_shortcode( $content ) . $dismiss . '</div>';
}
endif;
add_shortcode( 'cce_notification', 'cce_notification' );


/* Tabbed content */

if ( ! function_exists( 'cce_tabs' ) ) :
function cce_tabs( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'position' => 'top',
	), $atts, 'cce_tabs' );

	wp_enqueue_script( 'cce-shortcode-scripts' );

	preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

	$tab_titles = array();
    if ( isset($matches[1]) ) {
    	$tab_titles = $matches[1];
    }

    $output = '';

    if ( count( $tab_titles ) ) {
    	$output .= '<section id="'. uniqid('cce-tabs-') .'" class="cce-section cce-tabs cce-tabs--'. esc_attr( $args['position'] ) .'">';
    	$output .= '<ul class="cce-nav cce-clearfix">';

    	foreach ( $tab_titles as $tab ) {
    		$output .= '<li><a href="#cce-tab-'. sanitize_title( $tab[0] ) .'">' . $tab[0] . '</a></li>';
    	}

    	$output .= '</ul>';
    	$output .= do_shortcode( $content );
    	$output .= '</section>';
    } else {
    	$output .= do_shortcode( $content );
    }
    return $output;
}
endif;

if ( ! function_exists( 'cce_tab' ) ) :
function cce_tab( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'title' => __( 'Tab', 'cc-essentials' )
	), $atts, 'cce_tab' );

	return '<div id="cce-tab-'. sanitize_title( $args['title'] ) .'" class="cce-tab">'. do_shortcode( $content ) .'</div>';
}
endif;
add_shortcode( 'cce_tab', 'cce_tab' );
add_shortcode( 'cce_tabs', 'cce_tabs' );


/* Testimonial / quote */

if ( ! function_exists( 'cce_testimonial' ) ) :
function cce_testimonial( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'name' => '',
		'subtitle' => '',
		'image' => '',
		'url' => ''
	), $atts, 'cce_testimonial' );
	
	$image = $args['image'] ? '<img src="' . esc_url($args['image']) . '" />' : FALSE;
	$url = $args['url'] ? ' - <a href="' . esc_url($args['url']) . '">' . esc_url($args['url']) . '</a>' : FALSE;

	return '<div class="cce-section cce-testimonial"><blockquote>' . wpautop( do_shortcode( $content ) )  . '<small><cite>' . $image . esc_attr($args['name']) . '</cite>' . esc_attr($args['subtitle']) . $url . '</small></blockquote></div>';
}
endif;
add_shortcode( 'cce_testimonial', 'cce_testimonial' );


/* ===================================================================
/*	Layout columns
/* =================================================================== */

/* Columns wrapper */

if ( ! function_exists( 'cce_columns' ) ) :
function cce_columns( $atts, $content = null ) {
    return '<section class="cce-section cce-columns">' . do_shortcode( $content ) . '</section>';
}
endif;
add_shortcode( 'cce_columns', 'cce_columns' );

/* Columns of various widths */

if ( ! function_exists( 'cce_one_third' ) ) :
function cce_one_third( $atts, $content = null ) {
	return '<div class="cce-column cce-one-third">' . do_shortcode( $content ) . '</div>';
}
endif;
add_shortcode( 'cce_one_third', 'cce_one_third' );

if ( ! function_exists( 'cce_one_third_last' ) ) :
function cce_one_third_last( $atts, $content = null ) {
	return '<div class="cce-column cce-one-third cce-column-last">' . do_shortcode( $content ) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'cce_one_third_last', 'cce_one_third_last' );

if ( ! function_exists( 'cce_two_third' ) ) :
function cce_two_third( $atts, $content = null ) {
	return '<div class="cce-column cce-two-third">' . do_shortcode( $content ) . '</div>';
}
endif;
add_shortcode( 'cce_two_third', 'cce_two_third' );

if ( ! function_exists( 'cce_two_third_last' ) ) :
function cce_two_third_last( $atts, $content = null ) {
	return '<div class="cce-column cce-two-third cce-column-last">' . do_shortcode( $content ) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'cce_two_third_last', 'cce_two_third_last' );

if ( ! function_exists( 'cce_one_half' ) ) :
function cce_one_half( $atts, $content = null ) {
	return '<div class="cce-column cce-one-half">' . do_shortcode( $content ) . '</div>';
}
endif;
add_shortcode( 'cce_one_half', 'cce_one_half' );

if ( ! function_exists( 'cce_one_half_last' ) ) :
function cce_one_half_last( $atts, $content = null ) {
	return '<div class="cce-column cce-one-half cce-column-last">' . do_shortcode( $content ) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'cce_one_half_last', 'cce_one_half_last' );

if ( ! function_exists( 'cce_one_fourth' ) ) :
function cce_one_fourth( $atts, $content = null ) {
	return '<div class="cce-column cce-one-fourth">' . do_shortcode( $content ) . '</div>';
}
endif;
add_shortcode( 'cce_one_fourth', 'cce_one_fourth' );

if ( ! function_exists( 'cce_one_fourth_last' ) ) :
function cce_one_fourth_last( $atts, $content = null ) {
	return '<div class="cce-column cce-one-fourth cce-column-last">' . do_shortcode( $content ) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'cce_one_fourth_last', 'cce_one_fourth_last' );

if ( ! function_exists( 'cce_three_fourth' ) ) :
function cce_three_fourth( $atts, $content = null ) {
	return '<div class="cce-column cce-three-fourth">' . do_shortcode( $content ) . '</div>';
}
endif;
add_shortcode( 'cce_three_fourth', 'cce_three_fourth' );

if ( ! function_exists( 'cce_three_fourth_last' ) ) :
function cce_three_fourth_last( $atts, $content = null ) {
	return '<div class="cce-column cce-three-fourth cce-column-last">' . do_shortcode( $content ) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'cce_three_fourth_last', 'cce_three_fourth_last' );


/* ===================================================================
/*	Typography related
/* =================================================================== */

/* Dropcap */

if ( ! function_exists( 'cce_dropcap' ) ) :
function cce_dropcap( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'bgcolor'   => '',
		'color' 	=> '',
		'shape' 	=> 'round',
		'size' 		=> '50px'
	), $atts, 'cce_dropcap' );
	
	$style = $args['bgcolor'] ? "background:{$args['bgcolor']}; " : "";
	$style .= $args['color'] ? "color:{$args['color']}; " : "";
	$style .= $args['size'] ? "font-size:{$args['size']}; width:{$args['size']}; height:{$args['size']}" : "";

	return '<span class="cce-section cce-dropcap cce-dropcap--' . esc_attr( $args['shape'] ) . '" style="' . esc_attr($style) . '">' . do_shortcode( $content ) . '</span>';
}
endif;
add_shortcode( 'cce_dropcap', 'cce_dropcap' );


/* Lead paragraph */

if ( ! function_exists( 'cce_leadparagraph' ) ):
function cce_leadparagraph( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'size' => '160%',
		'color'=> ''
	), $atts, 'cce_leadparagraph' );
	
	$style = "font-size:{$args['size']}; color:{$args['color']}";
	return '<p class="cce-section cce-leadparagraph" style="' . esc_attr($style) . '">' . do_shortcode( $content ) . '</p>';
}
endif;
add_shortcode( 'cce_leadparagraph', 'cce_leadparagraph' );


/* Label */

if ( ! function_exists( 'cce_label' ) ):
function cce_label( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'color' => 'blue',
		'shape' => 'rounded'
	), $atts, 'cce_label' );

	return '<span class="cce-section cce-label cce-label--' . esc_attr( $args['color'] ) . ' cce-label--' . esc_attr( $args['shape'] ) . '">' . do_shortcode( $content ) . '</span>';
}
endif;
add_shortcode( 'cce_label', 'cce_label' );


/* ===================================================================
/*	Misc. elements
/* =================================================================== */

/* Image */

if ( ! function_exists( 'cce_image' ) ) :
function cce_image( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'effect'    => 'grayscale',
		'alignment' => 'none',
		'src'       => '',
		'url'       => '',
	), $atts, 'cce_image' );

	$output = '<figure class="cce-section cce-image cce-image--' . esc_attr( $args['effect'] ) . ' cce-image--' . esc_attr( $args['alignment'] ) . '">';

	if ( $args['url'] != '' ) {
		$output .= '<a href="' . esc_url( $args['url'] ) . '"><img src="' . esc_url( $args['src'] ) . '" alt=""></a>';
	} else {
		$output .= '<img src="' . esc_url( $args['src'] ) . '" alt="">';
	}

	$output .= '</figure>';

	return $output;
}
endif;
add_shortcode( 'cce_image', 'cce_image' );


/* Video */

if ( ! function_exists( 'cce_video' ) ) :
function cce_video( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'src' => '',
	), $atts, 'cce_video' );

	return '<div class="cce-section cce-video">' . $GLOBALS['wp_embed']->run_shortcode( '[embed]'. esc_url( $args['src'] ) .'[/embed]' ) . '</div>';
}
endif;
add_shortcode( 'cce_video', 'cce_video' );


/* Horizontal divider */

if ( ! function_exists( 'cce_divider' ) ) :
function cce_hdivider( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'type' => 'single',
		'length' => 'long',
	), $atts, 'cce_hdivider' );

	return '<hr class="cce-section cce-divider cce-divider--' . esc_attr( $args['type'] ) . ' cce-divider--' . esc_attr( $args['length'] ) . '">';
}
endif;
add_shortcode( 'cce_hdivider', 'cce_hdivider' );


/* Google map shortcode */

if ( ! function_exists( 'cce_map' ) ) :
function cce_map( $atts ) {
	$args = shortcode_atts( array(
		'location'    => '40.778462,-73.968201',
		'width'  => '100%',
		'height' => '350px',
		'zoom'   => 15,
		'style'  => 'none',
	), $atts, 'cce_map' );
	
	$location = explode(',', esc_js( $args['location'] ));

	$map_styles = array(
		'none'             => '[]',
		'mixed'            => '[{"featureType":"landscape","stylers":[{"hue":"#00dd00"}]},{"featureType":"road","stylers":[{"hue":"#dd0000"}]},{"featureType":"water","stylers":[{"hue":"#000040"}]},{"featureType":"poi.park","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","stylers":[{"hue":"#ffff00"}]},{"featureType":"road.local","stylers":[{"visibility":"off"}]}]',
		'pale_dawn'        => '[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]',
		'greyscale'        => '[{"featureType":"all","stylers":[{"saturation":-100},{"gamma":0.5}]}]',
		'bright_bubbly'    => '[{"featureType":"water","stylers":[{"color":"#19a0d8"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"weight":6}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#e85113"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-40}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-20}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"road.highway","elementType":"labels.icon"},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","stylers":[{"lightness":20},{"color":"#efe9e4"}]},{"featureType":"landscape.man_made","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"hue":"#11ff00"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"hue":"#4cff00"},{"saturation":58}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#f0e4d3"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-10}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"simplified"}]}]',
		'subtle_grayscale' => '[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]',
	);

	$map_id = uniqid('cce-map-');
	
	$cce_options = get_option('cce_options');
	$api_key = isset( $cce_options['api_key'] ) ? 'key='.$cce_options['api_key'] : false;

	wp_enqueue_script( 'google-maps', ( is_ssl() ? 'https' : 'http' ) . '://maps.googleapis.com/maps/api/js?'.$api_key.'&v=3.exp' );

	?>

	<script type="text/javascript">
	    jQuery(window).load(function(){
	    	var cce_sc_map = {};

	    	cce_sc_map.Map = ( function($) {
	    		function setupMap(options) {
	    			var mapOptions, mapElement, map, marker;

	    			if ( typeof google === 'undefined' ) return;

	    			mapOptions = {
	    				zoom: parseFloat(options.zoom),
	    				center: new google.maps.LatLng(options.center.lat, options.center.long),
	    				scrollwheel: false,
	    				styles: options.styles
	    			};

	    			mapElement = document.getElementById(options.id);
	    		 	map = new google.maps.Map(mapElement, mapOptions);

	    			marker = new google.maps.Marker({
	    				position: new google.maps.LatLng(options.center.lat, options.center.long),
	    				map: map
	    			});
	    		}
	    		return {
	    			init: function(options) {
	    				setupMap(options);
	    			}
	    		}
	    	} )(jQuery);

    	    var options = {
    	    	id: "<?php echo esc_js( $map_id ); ?>",
    	    	styles: <?php echo $map_styles[$args['style']]; ?>,
    	    	zoom: <?php echo esc_js( $args['zoom'] ); ?>,
    	    	center: {lat: <?php echo $location[0]; ?>, long: <?php echo $location[1]; ?>}
    	    };

    	    cce_sc_map.Map.init(options);
	    });
	</script>

	<?php

	return '<section id="'. esc_attr( $map_id ) .'" class="cce-section google-map" style="width:'. esc_attr( $args['width'] ) .';height:'. esc_attr( $args['height'] ) .'"></section>';
}
endif;
add_shortcode( 'cce_map', 'cce_map' );


/* ===================================================================
/*	Helper shortcodes
/* =================================================================== */

/* Icon shortcode */

if ( ! function_exists( 'cce_icon' ) ) :
function cce_icon( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'icon'       => '',
		'url'        => '',
		'size'       => '',
		'new_window' => 'no',
	), $atts, 'cce_icon' );

	$new_window = ( $args['new_window'] == 'no' ) ? '_self' : '_blank';

	$size = esc_attr( $args['size'] );

	$output = '';
	$attrs  = '';

	if ( ! empty( $args['url'] ) ) {
		$a_attrs = ' href="'. esc_url( $args['url'] ) .'" target="'. esc_attr( $new_window ) .'"';
	}

	if ( ! empty( $size ) ) {
		$attrs .= ' style="font-size:'. $size .';line-height:'. $size .'"';
	}

	if ( $args['url'] != '' ){
		$output .= '<a class="cce-icon-link" '. $a_attrs .'><i class="fa fa-'. esc_attr( $args['icon'] ) .'" '. $attrs .'></i></a>';
	} else {
		$output .= '<i class="fa fa-'. esc_attr( $args['icon'] ) .'" '. $attrs .'></i>';
	}

	return $output;
}
endif;
add_shortcode( 'cce_icon', 'cce_icon' );


/* Social shortcode */

if ( ! function_exists( 'cce_social' ) ) :
function cce_social( $atts ) {
	$args = shortcode_atts( array(
		'id'    => 'all',
		'style' => 'normal',
	), $atts, 'cce_social' );

	$registered_settings = cce_get_registered_settings();
	$social_urls         = array_keys( $registered_settings['social'] );
	$settings            = get_option( 'cce_options' );
	$output              = '<div class="cce-social-icons '. esc_attr( $args['style'] ) .'">';

	if ( $args['id'] == '' || $args['id'] == 'all' ) {
		$social_ids = $social_urls;
	} else {
		$social_ids = explode( ',', $args['id'] );
	}

	foreach ( $social_ids as $slug ) {
		$slug = trim( $slug );
		if ( isset( $settings[$slug] ) && $settings[$slug] != '' ) {
			$class = $slug;

			if ( 'mail' == $slug ) $class = 'envelope';
			if ( 'vimeo' == $slug ) $class = 'vimeo-square';

			$output .= "<a href='". esc_url( $settings[$slug] ) ."' target='_blank'><i class='fa fa-". esc_attr( $class ) ."'></i></a>";
		}
	}
	$output .= '</div>';

	return $output;

}
endif;
add_shortcode( 'cce_social', 'cce_social' );