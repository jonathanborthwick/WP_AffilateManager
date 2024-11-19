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

    public function increment_click($link_id)
{
    global $wpdb;

    // Check if metrics record exists
    $query = $wpdb->prepare("SELECT id FROM {$this->table_name} WHERE link_id = %d", $link_id);
    $record = $wpdb->get_var($query);

    if ($record) {
        // Increment click count
        $wpdb->query($wpdb->prepare("UPDATE {$this->table_name} SET clicks = clicks + 1 WHERE link_id = %d", $link_id));
    } else {
        // Insert new record
        $wpdb->insert($this->table_name, [
            'link_id' => $link_id,
            'clicks' => 1,
            'conversions' => 0,
            'earnings' => 0.00,
        ], ['%d', '%d', '%d', '%f']);
    }
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


public function track_referral($link_id, $source)
{
    global $wpdb;
    $referral_table = $wpdb->prefix . 'aff_mgr_affiliate_referrals';

    $wpdb->insert($referral_table, [
        'link_id' => $link_id,
        'source' => $source,
        'created_at' => current_time('mysql')
    ], ['%d', '%s', '%s']);
}


    /**
     * Get a summary of all metrics.
     *
     * @return array
     */
    public function get_metrics_summary()
    {
        global $wpdb;

        $query = "SELECT 
                    SUM(clicks) AS total_clicks, 
                    SUM(conversions) AS total_conversions, 
                    SUM(revenue) AS total_revenue, 
                    SUM(cost) AS total_cost, 
                    CASE WHEN SUM(cost) > 0 THEN ((SUM(revenue) - SUM(cost)) / SUM(cost)) * 100 ELSE 0 END AS total_roi
                  FROM {$this->table_name}";

        $results = $wpdb->get_row($query, ARRAY_A);

        return [
            'total_clicks' => isset($results['total_clicks']) ? intval($results['total_clicks']) : 0,
            'total_conversions' => isset($results['total_conversions']) ? intval($results['total_conversions']) : 0,
            'total_revenue' => isset($results['total_revenue']) ? floatval($results['total_revenue']) : 0.0,
            'total_cost' => isset($results['total_cost']) ? floatval($results['total_cost']) : 0.0,
            'total_roi' => isset($results['total_roi']) ? floatval($results['total_roi']) : 0.0
        ];
    }

    /**
     * Get detailed metrics for a specific campaign.
     *
     * @param int $campaign_id
     * @return array
     */
    public function get_campaign_metrics($campaign_id)
    {
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE campaign_id = %d",
            $campaign_id
        );

        return $wpdb->get_results($query, ARRAY_A);
    }
}
