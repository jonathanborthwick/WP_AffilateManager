<?php

class AffiliateManager_StatsManager
{
    /**
     * Get the total number of affiliates.
     *
     * @return int
     */
    public function get_total_affiliates()
    {
        // Assuming there's a table for affiliates.
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_affiliates';
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }

    /**
     * Get the total number of affiliate links.
     *
     * @return int
     */
    public function get_total_links()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_links';
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }

    /**
     * Get the total number of campaigns.
     *
     * @return int
     */
    public function get_total_campaigns()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_campaigns';
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }

    /**
     * Get the total earnings from affiliate activities.
     *
     * @return float
     */
    public function get_total_earnings()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_earnings';
        return (float) $wpdb->get_var("SELECT SUM(amount) FROM $table_name");
    }

    /**
     * Get recent affiliate activities.
     *
     * @return array
     */
    public function get_recent_affiliate_activities()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_activities';
        return $wpdb->get_results("SELECT * FROM $table_name ORDER BY date DESC LIMIT 10");
    }
}
