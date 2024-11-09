<?php
/**
 * Plugin Name: Affiliate Manager
 * Plugin URI: https://spinningmonkey.com
 * Description: Manage affiliate campaigns and links directly from WordPress.
 * Version: 1.0.0
 * Author: Jonathan Borthwick
 * Author URI: https://spinningmonkey.com
 * License: GPL2
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Include files for different functionalities
require_once plugin_dir_path(__FILE__) . 'includes/db-setup.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/frontend.php';

$custom_prefix = $wpdb->prefix . 'aff_mgr_';

// Run on plugin activation
function affiliate_mgr_activate() {
    create_affiliate_mgr_tables();
}

function affiliate_mgr_deactivate() {
    // No action on deactivation to preserve data
}

// Run on plugin uninstall
function affiliate_mgr_uninstall() {
    global $wpdb;
    $custom_prefix = $wpdb->prefix . 'aff_mgr_';

    $wpdb->query("DROP TABLE IF EXISTS {$custom_prefix}affiliate_campaigns");
    $wpdb->query("DROP TABLE IF EXISTS {$custom_prefix}affiliate_links");
    $wpdb->query("DROP TABLE IF EXISTS {$custom_prefix}affiliate_metrics");
}


function affiliate_mgr_cleanup() {
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS {$custom_prefix}affiliate_campaigns");
    $wpdb->query("DROP TABLE IF EXISTS {$custom_prefix}affiliate_links");
    $wpdb->query("DROP TABLE IF EXISTS {$custom_prefix}affiliate_metrics");
}

register_activation_hook(__FILE__, 'affiliate_mgr_activate');
register_deactivation_hook(__FILE__, 'affiliate_mgr_deactivate');
register_uninstall_hook(__FILE__, 'affiliate_mgr_uninstall');