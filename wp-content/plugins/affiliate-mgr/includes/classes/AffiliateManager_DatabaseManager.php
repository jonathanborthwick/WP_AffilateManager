<?php
if (!defined('ABSPATH')) {
    exit;
}
class AffiliateManager_DatabaseManager
{
    /**
     * Create all necessary database tables for the plugin.
     */
    public function create_tables()
    {
        $this->create_settings_table();
        $this->insert_default_settings(); // Insert default settings
        $this->create_affiliate_links_table();
        $this->create_traffic_sources_table();
        $this->create_campaigns_table();
        $this->create_categories_table();
        $this->create_metrics_table();
        $this->create_networks_table(); // Add networks table creation
    }

 /**
     * Create the settings table.
     */
    private function create_settings_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_settings';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            setting_key varchar(255) NOT NULL,
            setting_value text NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY setting_key (setting_key)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Insert default settings into the settings table.
     */
    private function insert_default_settings()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_settings';

        // Check if the default settings exist before inserting them
        $not_found_page_type = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $table_name WHERE setting_key = %s", 'not-found-page-local-or-remote'));
        if ($not_found_page_type === null) {
            // Insert default for 'not-found-page-local-or-remote'
            $wpdb->insert(
                $table_name,
                [
                    'setting_key' => 'not-found-page-local-or-remote',
                    'setting_value' => 'local'
                ],
                [
                    '%s',
                    '%s'
                ]
            );
        }

        $offer_not_found = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $table_name WHERE setting_key = %s", 'offer-not-found'));
        if ($offer_not_found === null) {
            // Insert default for 'offer-not-found'
            $wpdb->insert(
                $table_name,
                [
                    'setting_key' => 'offer-not-found',
                    'setting_value' => 'offer-not-found' // This is the local slug name
                ],
                [
                    '%s',
                    '%s'
                ]
            );
        }
    }

    /**
     * Create the affiliate links table.
     */
    private function create_affiliate_links_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_links';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            link_name varchar(255) NOT NULL,-- A human readable name for the link eg. Joe Blogs's Organic Healthy Pills
            url text NOT NULL, -- This is where the redirect goes; i.e. the full affiliate link
            shortcode varchar(255) NOT NULL,--eg. the part after the domain name before the campaign id. Eg healthypills in healthypills1
            network_id mediumint(9) DEFAULT NULL,--*eg. Clickbank's id from the  aff_mgr_affiliate_networks table
            category_id mediumint(9) NULL,--foreign key to the aff_mgr_affiliate_categories table. May be null
            campaign_id mediumint(9) NULL,-- foreign key to the aff_mgr_affiliate_campaigns. May be null
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

/**
 * Creates traffic sources table for recording where things came from and what affiliate link it was.
 */
private function create_traffic_sources_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'aff_mgr_affiliate_traffic_sources';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        link_id mediumint(9) NOT NULL, -- Foreign key to the affiliate links table
        campaign_id mediumint(9) NULL,-- Foreign key to the aff_mgr_affiliate_campaigns table. Eg. If the link was spinningmonkey.com/healthypills1, then the campaign_id would be 1. Also, spinningmonkey.com/healthypills means no particular campaign and is ok 
        referrer_url VARCHAR(2048) NULL, -- may not be available. Retrieved via <dollar sign>_SERVER['HTTP_REFERER']
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

 /**
 * Create the campaigns table
 */
private function create_campaigns_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'aff_mgr_affiliate_campaigns';
    $traffic_sources_table = $wpdb->prefix . 'aff_mgr_affiliate_traffic_sources';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `campaign_name` varchar(255) NOT NULL,
        `description` text, /* explain strategy used here */
        `traffic_source_id` mediumint(9) NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`traffic_source_id`) REFERENCES `$traffic_sources_table`(`id`)
    ) ENGINE=InnoDB $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


    /**
     * Create the categories table.
     */
    private function create_categories_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_categories';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            category_name varchar(255) NOT NULL,
            description text,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Create the metrics table.
     */
    private function create_metrics_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_metrics';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            link_id mediumint(9) NOT NULL,
            clicks int NOT NULL,
            conversions int NOT NULL,
            earnings decimal(10, 2) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Create the affiliate networks table.
     */
    public function create_networks_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_networks';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            network_name varchar(255) NOT NULL,
            description text,
            api_key varchar(255) NULL,
            client_id varchar(255) NULL,
            client_secret varchar(255) NULL,
            access_token text NULL,
            url VARCHAR(2048) NULL,--eg clickbank.com
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
