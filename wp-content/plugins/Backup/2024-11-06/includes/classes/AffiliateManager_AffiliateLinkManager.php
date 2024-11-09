<?php

class AffiliateManager_AffiliateLinkManager
{
    /**
     * Get link by shortcode.
     *
     * @param string $shortcode
     * @return object|null The link object or null if not found.
     */
    public function get_link_by_shortcode($shortcode)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_links';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE shortcode = %s", $shortcode));
    }

    /**
     * Get link by ID.
     *
     * @param int $link_id
     * @return object|null The link object or null if not found.
     */
    public function get_link_by_id($link_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_links';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $link_id));
    }

    /**
     * Generate QR code URL for a given link.
     *
     * @param string $link_url
     * @return string The URL of the generated QR code.
     */
    public function generate_qr_code($link_url)
    {
        $encoded_url = urlencode($link_url);
        $qr_code_url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={$encoded_url}&choe=UTF-8";
        return $qr_code_url;
    }

    /**
     * Add a new affiliate link to the database.
     *
     * @param string $link_name
     * @param string $url
     * @param string $shortcode
     * @param int $network_id
     * @return bool True on success, false on failure.
     */
    public function add_link($link_name, $url, $shortcode, $network_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_links';

        return $wpdb->insert(
            $table_name,
            [
                'link_name' => sanitize_text_field($link_name),
                'url' => esc_url_raw($url),
                'shortcode' => sanitize_text_field($shortcode),
                'network_id' => $network_id
            ],
            [
                '%s',
                '%s',
                '%s',
                '%d'
            ]
        ) !== false;
    }

    /**
     * Get all affiliate links from the database.
     *
     * @return array List of links.
     */
    public function get_links()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_links';
        return $wpdb->get_results("SELECT * FROM $table_name");
    }

    /**
     * Update an existing affiliate link.
     *
     * @param int $link_id
     * @param string $link_name
     * @param string $url
     * @param string $shortcode
     * @param int $network_id
     * @return bool True on success, false on failure.
     */
    public function update_link($link_id, $link_name, $url, $shortcode, $network_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_links';

        return $wpdb->update(
            $table_name,
            [
                'link_name' => sanitize_text_field($link_name),
                'url' => esc_url_raw($url),
                'shortcode' => sanitize_text_field($shortcode),
                'network_id' => $network_id
            ],
            ['id' => $link_id],
            [
                '%s',
                '%s',
                '%s',
                '%d'
            ],
            ['%d']
        ) !== false;
    }

    /**
     * Delete an affiliate link from the database.
     *
     * @param int $link_id
     * @return bool True on success, false on failure.
     */
    public function delete_link($link_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'affiliate_manager_links';
        return $wpdb->delete($table_name, ['id' => $link_id], ['%d']) !== false;
    }
}