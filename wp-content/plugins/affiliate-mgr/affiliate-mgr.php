<?php

/**
 * Plugin Name: Affiliate Manager
 * Description: A plugin to manage affiliate links, campaigns, and more.
 * Version: 1.0.0
 * Author: Jonathan Borthwick
 * License: GPL2
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

define('AFFILIATE_MGR_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Autoloader for classes.
function affiliate_manager_autoload($class_name) {
    if (strpos($class_name, 'AffiliateManager_') === 0) {
        $file = AFFILIATE_MGR_PLUGIN_DIR . 'includes/classes/' . $class_name . '.php';
        if (file_exists($file)) {
            include $file;
        } else {
            error_log("Autoloader error: Class file not found for {$class_name} at {$file}");
        }
    }
}
spl_autoload_register('affiliate_manager_autoload');

// Load textdomain for translations.
add_action('init', 'affiliate_mgr_load_textdomain');
function affiliate_mgr_load_textdomain() {
    load_plugin_textdomain('affiliate-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// Initialize the plugin.
function affiliate_manager_init() {
    if (is_admin()) {
        $admin_menu_manager = new AffiliateManager_AdminMenuManager();
        add_action('admin_menu', [$admin_menu_manager, 'register_admin_pages']);
    }

    // Rewrite rules.
    affiliate_mgr_add_rewrite_rules();
}
add_action('init', 'affiliate_manager_init');

// Add rewrite rules. Just manually customize the word 'offer' 
function affiliate_mgr_add_rewrite_rules() {
    add_rewrite_rule('^offer/([^/]*)/?$', 'index.php?affiliate_shortcode=$matches[1]', 'top');
}
add_filter('query_vars', 'affiliate_mgr_query_vars');
function affiliate_mgr_query_vars($vars) {
    $vars[] = 'affiliate_shortcode';
    return $vars;
}

// Handle shortcode redirects.
add_action('template_redirect', function() {
    $redirect_handler = new AffiliateManager_RedirectHandler();
    $redirect_handler->handle_redirects();
});

// Activation hook - setup database.
register_activation_hook(__FILE__, 'affiliate_manager_activate');
function affiliate_manager_activate() {
    require_once plugin_dir_path(__FILE__) . 'includes/classes/AffiliateManager_DatabaseManager.php';
    $db_manager = new AffiliateManager_DatabaseManager();
    $db_manager->create_tables();
}

// Enqueue admin assets.
// Enqueue admin assets.
function affiliate_manager_enqueue_admin_assets() {
    $screen = get_current_screen();
    //error_log('Current Screen ID: ' . $screen->id);

    // Enqueue styles and scripts based on the screen ID

    // For the settings page
    if ( $screen->id === 'toplevel_page_affiliate_manager_settings' ) {
        wp_enqueue_style(
            'affiliate-manager-admin-style', // Handle (unique identifier)
            plugin_dir_url(__FILE__) . 'assets/css/admin-style.css'
        );
        wp_enqueue_script(
            'affiliate-manager-validation', // Handle for validation script
            plugin_dir_url(__FILE__) . 'assets/js/am.validation.js',
            [],
            '1.0.0',
            true
        );
    }

    // For the dashboard page
    if ( $screen->id === 'toplevel_page_affiliate_manager_dashboard' ) {
        wp_enqueue_style(
            'affiliate-manager-dashboard-style', // Unique handle for dashboard style
            plugin_dir_url(__FILE__) . 'assets/css/admin-dashboard.css'
        );
        wp_enqueue_script(
            'affiliate-manager-dashboard-script', // Unique handle for dashboard script
            plugin_dir_url(__FILE__) . 'assets/js/am.dashboard.js',
            [],
            '1.0.0',
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'affiliate_manager_enqueue_admin_assets');

