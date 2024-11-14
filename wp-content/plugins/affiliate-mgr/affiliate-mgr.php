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

// Include helpers.php
require_once AFFILIATE_MGR_PLUGIN_DIR . 'includes/helpers.php';

// Autoloader for classes.
function affiliate_manager_autoload($class_name)
{
    if (strpos($class_name, 'AffiliateManager_') === 0) {
        $file = AFFILIATE_MGR_PLUGIN_DIR . 'includes/classes/' . $class_name . '.php';
        if (file_exists($file)) {
            include $file;
        } else {
            // Optional: Log an error if the file doesn't exist
            error_log("Autoloader error: Class file not found for {$class_name} at {$file}");
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
    //error_log("Affiliate Manager Plugin Initialized.");
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

add_action('init', 'affiliate_mgr_add_rewrite_rules');
function affiliate_mgr_add_rewrite_rules()
{

    add_rewrite_rule(
        //'^([^/]*)/?$', // Match a single segment URL
        '^offer/([^/]*)/?$', // Match URLs starting with "offer/"
        'index.php?affiliate_shortcode=$matches[1]', // Pass matched part to query var
        'top'
    );
}

add_filter('query_vars', 'affiliate_mgr_query_vars');
function affiliate_mgr_query_vars($vars)
{
    $vars[] = 'affiliate_shortcode';
    return $vars;
}

add_action('template_redirect', 'affiliate_mgr_handle_shortcode_redirect');
function affiliate_mgr_handle_shortcode_redirect()
{

    // error_log("Redirect handler called."); // Check if the function is called

    $shortcode = get_query_var('affiliate_shortcode');
    //error_log("Shortcode value: " . $shortcode); // Log the shortcode value

    if (!empty($shortcode)) {
        // Fetch your URL from the database based on $shortcode and perform redirect
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_links';
        $link = $wpdb->get_row($wpdb->prepare("SELECT url FROM $table_name WHERE short_code = %s", $shortcode));

        if ($link && $link->url) {
            //error_log(" URL: " . $link->url); // Can log the URL to which we are redirecting

            // Perform the redirection
            wp_redirect($link->url);
            exit();
        } else {
            error_log("No URL found for shortcode: " . $shortcode); // Log if no URL is found
            // Redirect to some "Offer Not Found" page
            $localOrRemote = AffiliateManager_Helpers::getSettingsValueByKey('not-found-page-local-or-remote'); //either 'local' or 'remote'
            
            if ($localOrRemote == 'local') {
                $offerNotFoundSlug = AffiliateManager_Helpers::getSettingsValueByKey('offer-not-found');
                $not_found_page = get_page_by_path($offerNotFoundSlug); //locally hosted page. Will be a 404 if we don't have a page with this slug in the local wordpress
                wp_redirect(get_permalink($not_found_page->ID)); //locally hosted page
            } else {
                $not_found_page = AffiliateManager_Helpers::getSettingsValueByKey('offer-not-found'); //will be a full http or a single page slug
                wp_redirect($not_found_page);
            }
            exit();
        }
    } else {
        error_log("shortcode variable is empty");
    }
}
