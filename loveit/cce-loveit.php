<?php

if ( ! class_exists( 'CCELoveIt' ) ) {

class CCELoveIt {

    function __construct() {
	    $cce_options = get_option('cce_options');
	    $show_on_posts = isset($cce_options['show_loveit_button_on']['post']) ? $cce_options['show_loveit_button_on']['post'] : FALSE;
	    $show_on_pages = isset($cce_options['show_loveit_button_on']['page']) ? $cce_options['show_loveit_button_on']['page'] : FALSE;

        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts') );
        add_filter('the_content', array(&$this, 'the_content'));
        add_filter('the_excerpt', array(&$this, 'the_content'));
        add_action('publish_post', array(&$this, 'setup_loveit'));
        if ( $show_on_posts ) {
        	add_filter('manage_posts_columns', array(&$this, 'cce_loveit_column_title'));
			add_action('manage_posts_custom_column', array(&$this, 'cce_loveit_column_content'), 10, 2);
		}
		if ( $show_on_pages ) {
			add_filter('manage_pages_columns', array(&$this, 'cce_loveit_column_title'));
			add_action('manage_pages_custom_column', array(&$this, 'cce_loveit_column_content'), 10, 2);
		}
		add_action('wp_ajax_cce_loveit', array(&$this, 'ajax_callback'));
		add_action('wp_ajax_nopriv_cce_loveit', array(&$this, 'ajax_callback'));
        add_shortcode('cce_loveit', array(&$this, 'shortcode'));
        add_action('widgets_init', create_function('', 'register_widget("CCELoveIt_Widget");'));
	}

	function enqueue_scripts() {

		$cce_options = get_option('cce_options');

		if ( !empty($cce_options['show_loveit_button_on']) ) {

			global $cce;
			wp_enqueue_style( 'cce-loveit', $cce->plugin_url() . '/assets/css/cce-loveit.css' );
			wp_enqueue_script( 'cce-loveit', $cce->plugin_url() . '/assets/js/min/cce-loveit.min.js', array('jquery'), false, true );
			wp_localize_script( 'cce-loveit', 'cce_loveit', array('ajaxurl' => admin_url('admin-ajax.php'), 'loved_text' => __( 'You already loved this!', 'cc-essentials' )) );
		}
	}

	function admin_enqueue_scripts() {

		$cce_options = get_option('cce_options');

		if ( !empty($cce_options['show_loveit_button_on']) ) {

			global $cce;
			wp_enqueue_style( 'cce-admin-loveit-styles', $cce->plugin_url() . '/assets/css/cce-admin-styles-loveit.css' );
		}
	}

	function the_content( $content ) {
	   // Don't show on custom page templates
	    if(is_page_template()) return $content;
	    // Don't show on Stacked slides
	    if(get_post_type() == 'slide') return $content;

		global $wp_current_filter;
		if ( in_array( 'get_the_excerpt', (array) $wp_current_filter ) ) {
			return $content;
		}

		$cce_options = get_option('cce_options');
		$show_on_posts = isset($cce_options['show_loveit_button_on']['post']) ? $cce_options['show_loveit_button_on']['post'] : FALSE;
	    $show_on_pages = isset($cce_options['show_loveit_button_on']['page']) ? $cce_options['show_loveit_button_on']['page'] : FALSE;

		if( is_singular('post') && $show_on_posts ) $content .= $this->do_likes('loveit-after-content');
		if( is_page() && !is_front_page() && $show_on_pages ) $content .= $this->do_likes('loveit-after-content');

		//Under consideration: if(( is_front_page() || is_home() || is_category() || is_tag() || is_author() || is_date() || is_search()) && $options['add_to_other'] ) $content .= $this->do_likes();

		return $content;
	}

	function setup_loveit( $post_id ) {
		if(!is_numeric($post_id)) return;

		add_post_meta($post_id, '_cce_loves', '0', true);
	}

	function cce_loveit_column_title( $defaults ) {
		$defaults['cce-loveit-data'] = __( 'Loves received', 'cc-essentials' );
		return $defaults;
	}

	function cce_loveit_column_content( $column_name, $post_id ) {
		if ($column_name == 'cce-loveit-data') {
			$loves = get_post_meta($post_id, '_cce_loves', true);
			if ($loves) {
				echo $loves . ' ' . __( 'loves','cc-essentials' );
			} else {
				echo __( '0 loves','cc-essentials' );
			}
		}
	}

	function ajax_callback( $post_id ) {

		$cce_options = get_option('cce_options');

		if( isset($_POST['loves_id']) ) {
		    // Click event. Get and Update Count
			$post_id = str_replace('cce-loveit-', '', $_POST['loves_id']);
			echo $this->cce_love_this($post_id, $cce_options['suffix_text_zero'], $cce_options['hide_count_if_zero']['yes'], $cce_options['suffix_text_one'], $cce_options['suffix_text_more'], 'update');
		} else {
		    // AJAXing data in. Get Count
			$post_id = str_replace('cce-loveit-', '', $_POST['post_id']);
			echo $this->cce_love_this($post_id, $cce_options['suffix_text_zero'], $cce_options['hide_count_if_zero']['yes'], $cce_options['suffix_text_one'], $cce_options['suffix_text_more'], 'get');
		}

		exit;
	}

	function cce_love_this($post_id, $suffix_text_zero = false, $hide_count_if_zero = false, $suffix_text_one = false, $suffix_text_more = false, $action = 'get') {
		if(!is_numeric($post_id)) return;
		$suffix_text_zero = strip_tags($suffix_text_zero);
		$suffix_text_one = strip_tags($suffix_text_one);
		$suffix_text_more = strip_tags($suffix_text_more);

		switch($action) {

			case 'get':
				$loves = get_post_meta($post_id, '_cce_loves', true);
				$love_count_span = '';
				if( !$loves ){
					$loves = 0;
					add_post_meta($post_id, '_cce_loves', $loves, true);
				}

				if( $loves == 0 ) { $suffix = $suffix_text_zero; }
				elseif( $loves == 1 ) { $suffix = $suffix_text_one; }
				else { $suffix = $suffix_text_more; }
				
				if( !($loves == 0 && $hide_count_if_zero) ) { $love_count_span = '<span class="cce-loveit-count">'. $loves .'</span>'; }

				return $love_count_span.'<span class="cce-loveit-suffix">'. $suffix .'</span>';
				break;

			case 'update':
				$loves = get_post_meta($post_id, '_cce_loves', true);
				$love_count_span = '';
				if( isset($_COOKIE['cce_loves_'. $post_id]) ) return $loves;

				$loves++;
				update_post_meta($post_id, '_cce_loves', $loves);
				setcookie('cce_loves_'. $post_id, $post_id, time()*20, '/');

				if( $loves == 0 ) { $suffix = $suffix_text_zero; }
				elseif( $loves == 1 ) { $suffix = $suffix_text_one; }
				else { $suffix = $suffix_text_more; }
				
				if( !($loves == 0 && $hide_count_if_zero) ) { $love_count_span = '<span class="cce-loveit-count">'. $loves .'</span>'; }

				return $love_count_span.'<span class="cce-loveit-suffix">'. $suffix .'</span>';
				break;

		}
	}

	function shortcode( $atts ) {
		extract( shortcode_atts( array(
		), $atts ) );

		return $this->do_likes();
	}

	function do_likes($classes = null) {
		global $post;
		$classes = $classes ? ' ' . $classes : '';

        $cce_options = get_option('cce_options');

		$output = $this->cce_love_this($post->ID, $cce_options['suffix_text_zero'], $cce_options['suffix_text_one'], $cce_options['suffix_text_more']);

  		$class = 'cce-loveit';
  		$title = __('Love this', 'cc-essentials');
  		$prefix_text = $cce_options['prefix_text'];
		if( isset($_COOKIE['cce_loves_'. $post->ID]) ){
			$class = 'cce-loveit loved';
			$title = __('You already loved this!', 'cc-essentials');
		}

		$prefix_span = ( '' != $prefix_text ) ? '<span class="cce-loveit-prefix">' . $prefix_text . '</span>' : false;

		return '<span class="cce-loveit-wrapper' . $classes . '">' . $prefix_span . '<a href="#" class="'. $class .'" id="cce-loveit-'. $post->ID .'" title="'. $title .'">'. $output .'</a></span>';
	}

}

$GLOBALS['cce_loveit'] = new CCELoveIt();

/**
 * Template Tag
 */
function cce_loveit()
{
	global $cce_loveit;
    echo $cce_loveit->do_likes();
}

/**
 * Widget to display most loved posts.
 */

class CCELoveIt_Widget extends WP_Widget {

	function __construct() {
		parent::__construct( 'cce_loveit_widget', __('&#x95; [CC] Most Loved Posts', 'cc-essentials'), array( 'description' => __('Displays most loved posts in descending order. Installed by &#x2018;CC Essentials&#x2019; plugin.', 'cc-essentials') ) );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$desc = $instance['description'];
		$posts = empty( $instance['posts'] ) ? 1 : $instance['posts'];
		$display_count = $instance['display_count'];
		$display_suffix_prefix = $instance['display_suffix_prefix'];

		// Output our widget
		echo $before_widget;
		if( !empty( $title ) ) echo $before_title . $title . $after_title;

		if( $desc ) echo '<p>' . $desc . '</p>';

		$loved_posts_args = array(
			'numberposts' => $posts,
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'meta_key' => '_cce_loves',
			'post_type' => 'post',
			'post_status' => 'publish'
		);
		$loved_posts = get_posts($loved_posts_args);

		echo '<ul class="cce_most_loved_posts">';

		global $cce_options;
		foreach( $loved_posts as $loved_post ) {
			$count_output = '';
			if( $display_count ) {
				$count = get_post_meta( $loved_post->ID, '_cce_loves', true);
				$prefix = ( $display_suffix_prefix && $cce_options['prefix_text'] ) ? $cce_options['prefix_text'].' ' : false;
				$suffix = ( $display_suffix_prefix ) ? ( ('0' === $count) ? ' '.$cce_options['suffix_text_zero'] : (('1' === $count) ? ' '.$cce_options['suffix_text_one'] : ' '.$cce_options['suffix_text_more']) ) : false;
				$count_output = " <span class='cce-loveit-count'>($prefix$count$suffix)</span>";
			}
			echo '<li><a href="' . get_permalink($loved_post->ID) . '">' . get_the_title($loved_post->ID) . '</a>' . $count_output . '</li>';
		}
		echo '</ul>';

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['description'] = strip_tags($new_instance['description'], '<a><b><strong><i><em><span>');
		$instance['posts'] = strip_tags($new_instance['posts']);
		$instance['display_count'] = strip_tags($new_instance['display_count']);
		$instance['display_suffix_prefix'] = strip_tags($new_instance['display_suffix_prefix']);

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance
		);

		$defaults = array(
			'title' => __('Most loved posts', 'cc-essentials'),
			'description' => '',
			'posts' => 5,
			'display_count' => 1
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = $instance['title'];
		$description = $instance['description'];
		$posts = $instance['posts'];
		$display_count = $instance['display_count'];
		$display_suffix_prefix = isset( $instance['display_suffix_prefix'] ) ? $instance['display_suffix_prefix'] : false;
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'cc-essentials'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', 'cc-essentials'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo $description; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>"><?php _e('Posts:', 'cc-essentials'); ?></label>
			<input id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" type="text" value="<?php echo $posts; ?>" size="3" />
		</p>
		<p>
			<input id="<?php echo $this->get_field_id('display_count'); ?>" name="<?php echo $this->get_field_name('display_count'); ?>" type="checkbox" value="1" <?php checked( $display_count ); ?>>
			<label for="<?php echo $this->get_field_id('display_count'); ?>"><?php _e('Display counts', 'cc-essentials'); ?></label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('display_suffix_prefix'); ?>" name="<?php echo $this->get_field_name('display_suffix_prefix'); ?>" type="checkbox" value="1" <?php checked( $display_suffix_prefix ); ?>>
			<label for="<?php echo $this->get_field_id('display_suffix_prefix'); ?>"><?php echo sprintf(__('Display suffix & prefix %1$sEdit%2$s', 'cc-essentials'), '<small>(<a href="admin.php?page=cce">', '</a>)</small>' ); ?></label>
		</p>

		<?php
	}
}

}
?>
