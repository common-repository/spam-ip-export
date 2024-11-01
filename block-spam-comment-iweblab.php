<?php
/* 
Plugin Name: Block Spam Comment Iweblab
Version: 0.1.1
Description: Block Spam Comment Iweblab
Author: Iweblab
Author URI: https://iweblab.it
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Activation hook registration and include files
register_activation_hook(__FILE__, 'block_spam_comment_iweblab');
include( plugin_dir_path( __FILE__ ) . 'includes/settings.php');

 // Activation
function block_spam_comment_iweblab() {
    if (! wp_next_scheduled ( 'export_spam_comment_iweblab' )) {
		wp_schedule_event(strtotime('22:58:55'), 'daily', 'export_spam_comment_iweblab');
    }
	register_uninstall_hook( __FILE__, 'block_spam_uninstall_iweblab' );
}

// Uninstall
function block_spam_uninstall_iweblab() {
	if (wp_next_scheduled ( 'export_spam_comment_iweblab' )) {
		$timestamp = wp_next_scheduled( 'export_spam_comment_iweblab' );
		wp_unschedule_event($timestamp, 'export_spam_comment_iweblab');
	}
}

// Settings link from plugin list page
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'apd_settings_link' );
function apd_settings_link( array $links ) {
    $url = get_admin_url() . "tools.php?page=block-spam-comments";
    $settings_link = '<a href="' . $url . '">' . __('Settings', 'iwb') . '</a>';
    $links[] = $settings_link;
    return $links;
}

// The core of plugin
add_action('export_spam_comment_iweblab', 'do_export');
function do_export() {
	// Arguments for the query
	$args = array(
		'status' => 'spam'
	);

	// The comment query
	$comments_query = new WP_Comment_Query;
	$comments = $comments_query->query( $args );

	$ips  = [];
	$ipno = ['127.0.0.1'];

	// The comment loop
	if ( !empty( $comments ) ) {
		foreach ( $comments as $comment ) {
			if (filter_var($comment->comment_author_IP, FILTER_VALIDATE_IP) && !in_array($comment->comment_author_IP, $ipno, true)) {
				if (!in_array($comment->comment_author_IP, $ips, true)) {
					array_push($ips, $comment->comment_author_IP);
				}
			}
		}
	}

	// Always new line
	array_push($ips, '');
	
	// Make file
    $file = plugin_dir_path( __FILE__ ) . '/98dfv4v59.txt'; 
	file_put_contents($file, implode(PHP_EOL, $ips));

	// Delete spam comment
	if ( !empty( $comments ) ) {
		foreach ( $comments as $comment ) {
			if (filter_var($comment->comment_author_IP, FILTER_VALIDATE_IP)) {
				wp_delete_comment( $comment->comment_ID, true );
			}
		}
	}
}