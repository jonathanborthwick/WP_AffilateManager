<?php
if (!defined('ABSPATH')) {
    exit;
}

class AffiliateManager_MetricsManager
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'aff_mgr_affiliate_metrics';
    }

    /**
     * Insert the link id and source to the referals table
     */
    public function track_referral($link_id, $source)
    {
        global $wpdb;
        $referral_table = $wpdb->prefix . 'aff_mgr_affiliate_referrals';

        $wpdb->insert
        (
            $referral_table, 
            [
              'link_id' => $link_id,
              'source' => $source,
              'created_at' => current_time('mysql')
            ],
            ['%d', '%s', '%s']
        );
    }

    public function increment_click($link_id)
    {
        global $wpdb;
        // Check if metrics record exists in the metrics table
        $query = $wpdb->prepare("SELECT id FROM {$this->table_name} WHERE link_id = %d", $link_id);
        $record = $wpdb->get_var($query);
        if ($record) {
            $result = $wpdb->query($wpdb->prepare("UPDATE {$this->table_name} SET clicks = clicks + 1 WHERE link_id = %d", $link_id));
            if ($result === false) {
                error_log('Failed to update click count for link ID: ' . $link_id);
            }
        } else {
            $result = $wpdb->insert($this->table_name, [
                'link_id' => $link_id,
                'clicks' => 1,
                'conversions' => 0,
                'earnings' => 0.00,
            ], ['%d', '%d', '%d', '%f']);
            if ($result === false) {
                error_log('Failed to insert new metrics record for link ID: ' . $link_id);
            }
        }
        
    }

    /**
     * total_links, total_campaigns,total_clicks
     */
    public function get_metrics_summary()
    {
        global $wpdb;
        $query = "SELECT 
             COUNT(l.id) AS total_links, 
             COUNT(DISTINCT c.id) AS total_campaigns, 
             COUNT(r.id) AS total_clicks
         FROM 
             " . $wpdb->prefix . "aff_mgr_affiliate_links l
         LEFT JOIN 
             " . $wpdb->prefix . "aff_mgr_affiliate_campaigns c 
         ON l.campaign_id = c.id
         LEFT JOIN 
             " . $wpdb->prefix . "aff_mgr_affiliate_referrals r
         ON l.id = r.link_id;";

        $results = $wpdb->get_row($query, ARRAY_A);

        return 
        [
            'total_links' => isset($results['total_links']) ? intval($results['total_links']) : 0, 
            'total_campaigns' => isset($results['total_campaigns']) ? intval($results['total_campaigns']) : 0, 
            'total_clicks'=> isset($results['total_clicks']) ? intval($results['total_clicks']) : 0,
            'total_conversions'=>0,
            'total_revenue' => 0,
            'total_cost' => 0,
            'total_roi' => 0
        ];

    }



    public function get_recent_activity($limit = 10)
    {
        global $wpdb;
        $referral_table = $wpdb->prefix . 'aff_mgr_affiliate_referrals';
    $links_table = $wpdb->prefix . 'aff_mgr_affiliate_links';

    $query = "
        SELECT r.created_at, l.link_name, r.source, l.short_code
        FROM {$referral_table} r
        INNER JOIN {$links_table} l ON r.link_id = l.id
        ORDER BY r.created_at DESC
        LIMIT %d
    ";

    $results = $wpdb->get_results($wpdb->prepare($query, $limit));

    return $results;
    }
}
