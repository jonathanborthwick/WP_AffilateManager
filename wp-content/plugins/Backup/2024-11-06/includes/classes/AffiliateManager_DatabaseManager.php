<?php

class AffiliateManager_DatabaseManager
{
    /**
     * Create all necessary database tables for the plugin.
     */
    public function create_tables()
    {
        $this->create_affiliate_links_table();
        $this->create_campaigns_table();
        $this->create_traffic_sources_table();
        $this->create_categories_table();
        $this->create_metrics_table();
        $this->create_networks_table(); // Add networks table creation
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
            link_name varchar(255) NOT NULL,
            url text NOT NULL,
            shortcode varchar(255) NOT NULL,
            network_id mediumint(9) DEFAULT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

/**
 * Create the traffic sources table.
 */
private function create_traffic_sources_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'aff_mgr_traffic_sources';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        source_name varchar(255) NOT NULL,
        description text,
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

 /**
 * Create the campaigns table with traffic source link.
 */
private function create_campaigns_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'aff_mgr_affiliate_campaigns';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        offer_name varchar(255) NOT NULL,
        description text,
        traffic_source_id mediumint(9), -- Foreign key to the traffic_sources table
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        updated_at timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        FOREIGN KEY (traffic_source_id) REFERENCES {$wpdb->prefix}aff_mgr_traffic_sources(id)
    ) $charset_collate;";

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
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
