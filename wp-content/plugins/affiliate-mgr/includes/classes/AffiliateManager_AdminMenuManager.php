<?php
if (!defined('ABSPATH')) {
    exit;
}
class AffiliateManager_AdminMenuManager
{
    /**
     * Register the admin menu pages for the plugin.
     */
    public function register_admin_pages()
    {
        add_menu_page(
            'Affiliate Manager',      // Page title
            'Affiliate Manager',      // Menu title
            'manage_options',         // Capability
            'affiliate_manager_dashboard',  // Menu slug
            [$this, 'render_dashboard_page'],  // Callback function
            'dashicons-admin-links',  // Icon
            6                         // Position
        );

        // Add submenu pages
        add_submenu_page(
            'affiliate_manager_dashboard', // Parent slug
            'Manage Links',                // Page title
            'Manage Links',                // Menu title
            'manage_options',              // Capability
            'affiliate_manager_links',     // Menu slug
            [$this, 'render_links_page']   // Callback function
        );

        add_submenu_page(
            'affiliate_manager_dashboard', // Parent slug
            'Manage Campaigns',            // Page title
            'Manage Campaigns',            // Menu title
            'manage_options',              // Capability
            'affiliate_manager_campaigns', // Menu slug
            [$this, 'render_campaigns_page'] // Callback function
        );

        add_submenu_page(
            'affiliate_manager_dashboard', // Parent slug
            'Manage Categories',           // Page title
            'Manage Categories',           // Menu title
            'manage_options',              // Capability
            'affiliate_manager_categories', // Menu slug
            [$this, 'render_categories_page'] // Callback function
        );

        add_submenu_page(
            'affiliate_manager_dashboard', // Parent slug
            'Affiliate Networks',          // Page title
            'Affiliate Networks',          // Menu title
            'manage_options',              // Capability
            'affiliate_manager_networks',  // Menu slug
            [$this, 'render_networks_page'] // Callback function
        );
    }

    /**
     * Render the dashboard page.
     */
    public function render_dashboard_page()
    {
        $template = plugin_dir_path(__FILE__) . '../../templates/admin-dashboard.php';
        if (file_exists($template)) {
            include $template;
        } else {
            echo '<div class="error"><p>' . esc_html__('Error: Template file not found for the dashboard. Path: ', 'affiliate-manager') . esc_html($template) . '</p></div>';
        }
    }

    /**
     * Render the links page.
     */
    public function render_links_page()
    {
        $template = plugin_dir_path(__FILE__) . '../../templates/links-management.php';
        if (file_exists($template)) {
            include $template;
        } else {
            echo '<div class="error"><p>' . esc_html__('Error: Template file not found for managing links. Path: ', 'affiliate-manager') . esc_html($template) . '</p></div>';
        }
    }

    public function render_campaigns_page()
{
    $campaign_manager = new AffiliateManager_CampaignManager();

    // Handle form submission for adding a new campaign.
    if (isset($_POST['add_campaign'])) {
        // Check nonce for security.
        if (!isset($_POST['add_campaign_nonce']) || !wp_verify_nonce($_POST['add_campaign_nonce'], 'add_campaign_action')) {
            echo '<div class="notice notice-error"><p>Security check failed.</p></div>';
        } else {
            $campaign_name = sanitize_text_field($_POST['campaign_name']);
            $description = sanitize_textarea_field($_POST['description']);
            $added = $campaign_manager->add_campaign($campaign_name, $description);

            if ($added) {
                echo '<div class="notice notice-success"><p>Campaign added successfully.</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>Failed to add the campaign. Please try again.</p></div>';
            }
        }
    }

    // Get all campaigns to display them.
    $campaigns = $campaign_manager->get_all_campaigns();

    // Include the campaign management template.
    $template = plugin_dir_path(__FILE__) . '../../templates/campaign-management.php';
    if (file_exists($template)) {
        include $template;
    } else {
        echo '<div class="notice notice-error"><p>Error: Template file not found for managing campaigns.</p></div>';
    }
}


    /**
     * Render the categories page.
     */
    public function render_categories_page()
    {
        $template = plugin_dir_path(__FILE__) . '../../templates/categories-management.php';
        if (file_exists($template)) {
            include $template;
        } else {
            echo '<div class="error"><p>' . esc_html__('Error: Template file not found for managing categories. Path: ', 'affiliate-manager') . esc_html($template) . '</p></div>';
        }
    }

    /**
     * Render the networks page.
     */
    public function render_networks_page()
    {
        $networks_manager = new AffiliateManager_NetworksManager();
        $networks_manager->handle_form_submission(); // Handle form submission

        $networks = $networks_manager->get_all_networks(); // Fetch all networks

        // Load the appropriate template
        $template = plugin_dir_path(__FILE__) . '../../templates/networks-management.php';
        if (file_exists($template)) {
            include $template;
        } else {
            echo "Error: Template file not found for managing networks.";
        }
    }
}
