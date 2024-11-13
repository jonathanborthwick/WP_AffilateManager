<?php
if (!defined('ABSPATH')) {
    exit;
}
class AffiliateManager_StatsManager
{
    
    /**
     * Get the total number of affiliate links.
     *
     * @return int
     */
    public function get_total_links()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_links';
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
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_campaigns';
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }

 
    /**
     * Get recent affiliate activities.
     *
     * @return array
     */
    public function get_recent_affiliate_activities()
    {
        global $wpdb;
        $sql = "SELECT a.short_code, a.created_at, c.campaign_name, n.network_name, s.referrer_url AS source ";
        $sql.="FROM wpzw_aff_mgr_affiliate_links a ";
        $sql.="JOIN wpzw_aff_mgr_affiliate_campaigns c ";
        $sql.="ON a.campaign_id = c.id ";
        $sql.="JOIN wpzw_aff_mgr_affiliate_networks n ";
        $sql.="ON a.network_id = n.id ";
        $sql.="JOIN wpzw_aff_mgr_affiliate_traffic_sources s ";
        $sql.="ON s.campaign_id = c.id ";
        $sql.="ORDER BY a.created_at desc LIMIT 10;";
        return $wpdb->get_results($sql);
    }
}
