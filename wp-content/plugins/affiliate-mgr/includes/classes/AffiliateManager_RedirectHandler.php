<?php
if (!defined('ABSPATH')) {
    exit;
}
class AffiliateManager_RedirectHandler
{

    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'aff_mgr_affiliate_links';
    }

    /**
     * Handle redirects based on URL slugs.
     */
    public function handle_redirects()
    {
        // Ensure the redirect happens only once during the request lifecycle
        if (isset($GLOBALS['affiliate_mgr_redirected']) && $GLOBALS['affiliate_mgr_redirected'] === true) {
            return;
        }
        $GLOBALS['affiliate_mgr_redirected'] = true;

        // Get the affiliate shortcode from query vars
        $slug = get_query_var('affiliate_shortcode');

        if (empty($slug)) {
            return;
        }

        $slug = sanitize_text_field($slug);
        $link = $this->get_link_by_slug($slug);

        if ($link) {
            // Save the click to the database
            $metrics_manager = new AffiliateManager_MetricsManager();
            $metrics_manager->increment_click($link->id);

            // Track referral source
            $referral = isset($_SERVER['HTTP_REFERER']) ? sanitize_text_field($_SERVER['HTTP_REFERER']) : 'Direct';
            $this->track_or_increment_referral($link->id, $referral);

            // Perform the redirect
            wp_redirect(esc_url_raw($link->url), 301);
            exit;
        }
    }

    /**
     * Retrieve affiliate link by slug.
     * Each slug is unique to a campaign
     * @param string $slug
     * @return object|null The affiliate link object or null if not found.
     */
    private function get_link_by_slug($slug)
    {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM {$this->table_name} WHERE short_code = %s LIMIT 1", $slug);
        return $wpdb->get_row($query);
    }

     /**
     * Track or increment referral source.
     * @param int $link_id The ID of the link.
     * @param string $source The source of the referral.
     */
    private function track_or_increment_referral($link_id, $source)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_referrals';

        // Check if a record exists for the given link ID and source
        $query = $wpdb->prepare("SELECT id, hits FROM {$table_name} WHERE link_id = %d AND source = %s LIMIT 1", $link_id, $source);
        $existing_record = $wpdb->get_row($query);

        if ($existing_record) {
            // Increment the hit count for the existing record
            $wpdb->update(
                $table_name,
                ['hits' => $existing_record->hits + 1],
                ['id' => $existing_record->id],
                ['%d'],
                ['%d']
            );
        } else {
            // Insert a new record with an initial hit count of 1
            $wpdb->insert(
                $table_name,
                [
                    'link_id' => $link_id,
                    'source' => $source,
                    'hits' => 1,
                    'created_at' => current_time('mysql', 1),
                ],
                ['%d', '%s', '%d', '%s']
            );
        }
    }
}
