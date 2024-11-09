<?php
function create_affiliate_mgr_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $custom_prefix = $wpdb->prefix . 'aff_mgr_';

    // Define table names
    $links_table = $custom_prefix . 'affiliate_links';
    $campaigns_table = $custom_prefix . 'affiliate_campaigns';
    $metrics_table = $custom_prefix . 'affiliate_metrics';
    $categories_table = $custom_prefix . 'affiliate_categories';

    // SQL to create affiliate_campaigns table
    $sql_campaigns_table = "CREATE TABLE $campaigns_table (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        offer_name VARCHAR(255) NOT NULL,
        traffic_source VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // SQL to create affiliate_links table
    $sql_links_table = "CREATE TABLE $links_table (
        id INT(11) NOT NULL AUTO_INCREMENT,
        short_code VARCHAR(255) NOT NULL,
        redirect_url VARCHAR(255) NOT NULL,
        campaign_id INT(11),
        category_id INT(11),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (campaign_id) REFERENCES $campaigns_table(id) ON DELETE SET NULL ON UPDATE CASCADE,
        FOREIGN KEY (category_id) REFERENCES $categories_table(id) ON DELETE SET NULL ON UPDATE CASCADE
    ) $charset_collate;";

    // SQL to create affiliate_metrics table
    $sql_metrics_table = "CREATE TABLE $metrics_table (
        id INT(11) NOT NULL AUTO_INCREMENT,
        link_id INT(11),
        clicks INT(11) DEFAULT 0,
        conversions INT(11) DEFAULT 0,
        revenue DECIMAL(10, 2) DEFAULT 0.00,
        cost DECIMAL(10, 2) DEFAULT 0.00,
        profit_loss DECIMAL(10, 2) DEFAULT 0.00,
        epc FLOAT DEFAULT 0.00,
        roi FLOAT DEFAULT 0.00,
        ctr FLOAT DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (link_id) REFERENCES $links_table(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) $charset_collate;";

    // SQL to create affiliate_categories table
    $sql_categories_table = "CREATE TABLE $categories_table (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Create tables using dbDelta()
    dbDelta($sql_campaigns_table);
    dbDelta($sql_links_table);
    dbDelta($sql_metrics_table);
    dbDelta($sql_categories_table);

    // Update the database version
    update_option('affiliate_mgr_db_version', '1.2');
}

// Function to check and update the database schema if necessary
function affiliate_mgr_update_db_check() {
    $installed_version = get_option('affiliate_mgr_db_version');
    if ($installed_version != '1.2') {
        create_affiliate_mgr_tables();
    }
}
add_action('plugins_loaded', 'affiliate_mgr_update_db_check');
