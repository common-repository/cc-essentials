<?php
/**
 * Uninstall CCEssentials.
 *
 * @package CCEssentials
 * @since  1.0.3
 * @author Ram Ratan Maurya
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Delete Plugin Options
delete_option( 'cce_options' );
delete_option( 'cce_twitter_widget_tweets' );
delete_option( 'cce_twitter_widget_last_cache' );
