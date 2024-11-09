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
function affiliate_manager_autoload($class_name)
{
    if (false !== strpos($class_name, 'AffiliateManager')) {
        $file = AFFILIATE_MGR_PLUGIN_DIR . 'includes/classes/' . $class_name . '.php';
        if (file_exists($file)) {
            include $file;
        }
    }
}
spl_autoload_register('affiliate_manager_autoload');

// Activation hook - setup database.
register_activation_hook(__FILE__, 'affiliate_manager_activate');
function affiliate_manager_activate()
{
    // Make sure the filename matches the class name.
    require_once plugin_dir_path(__FILE__) . 'includes/classes/AffiliateManager_DatabaseManager.php';
    $db_manager = new AffiliateManager_DatabaseManager();
    $db_manager->create_tables();
}

// Initialize the plugin.
function affiliate_manager_init()
{
    // Initialize admin menu.
    if (is_admin()) {
        $admin_menu_manager = new AffiliateManager_AdminMenuManager();
        add_action('admin_menu', [$admin_menu_manager, 'register_admin_pages']);
    }

    // Initialize link redirection.
    $redirect_handler = new AffiliateManager_RedirectHandler();
    add_action('init', [$redirect_handler, 'handle_redirects']);
}
add_action('plugins_loaded', 'affiliate_manager_init');

// Enqueue admin styles.
function affiliate_manager_enqueue_admin_styles()
{
    wp_enqueue_style('affiliate-manager-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'affiliate_manager_enqueue_admin_styles');
