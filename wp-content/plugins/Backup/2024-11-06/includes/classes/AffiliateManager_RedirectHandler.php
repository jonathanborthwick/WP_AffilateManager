<?php

class AffiliateManager_RedirectHandler
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'affiliate_links';
    }

    /**
     * Handle redirects based on URL slugs.
     */
    public function handle_redirects()
    {
        if (!isset($_GET['affiliate_slug'])) {
            return;
        }

        $slug = sanitize_text_field($_GET['affiliate_slug']);
        $link = $this->get_link_by_slug($slug);

        if ($link) {
            wp_redirect(esc_url_raw($link->url), 301);
            exit;
        }
    }

    /**
     * Retrieve affiliate link by slug.
     *
     * @param string $slug
     * @return object|null The affiliate link object or null if not found.
     */
    private function get_link_by_slug($slug)
    {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM {$this->table_name} WHERE link_name = %s LIMIT 1", $slug);
        return $wpdb->get_row($query);
    }
}
