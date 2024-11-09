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
