<?php
if (!defined('ABSPATH')) {
    exit;
}
class AffiliateManager_CampaignManager
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'aff_mgr_affiliate_campaigns';
    }

  

    /**
     * Add a new affiliate campaign to the database.
     *
     * @param string campaign_name
     * @param string $description
     * @return bool True on success, false on failure.
     */
    public function add_campaign($campaign_name, $description)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_campaigns';

        return $wpdb->insert(
            $table_name,
            [
                'campaign_name' => sanitize_text_field(campaign_name),
                'description' => sanitize_textarea_field($description),
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ],
            [
                '%s',
                '%s'
            ]
        ) !== false;
    }

    /**
     * Update an existing campaign.
     *
     * @param int $id
     * @param array $data
     * @return bool True on success, false on failure.
     */
    public function update_campaign($id, $data)
    {
        global $wpdb;
        $result = $wpdb->update(
            $this->table_name,
            [
                'campaign_name' => sanitize_text_field($data['campaign_name']),
                'description' => sanitize_textarea_field($data['description']),
                'updated_at' => current_time('mysql')
            ],
            [ 'id' => (int) $id ],
            [ '%s', '%s', '%s' ],
            [ '%d' ]
        );

        return ($result !== false);
    }

    /**
     * Delete a campaign by ID.
     *
     * @param int $id
     * @return bool True on success, false on failure.
     */
    public function delete_campaign($id)
    {
        global $wpdb;
        $result = $wpdb->delete($this->table_name, [ 'id' => (int) $id ], [ '%d' ]);
        return ($result !== false);
    }

    /**
     * Get campaigns based on filters.
     *
     * @param array $filters
     * @return array List of campaigns.
     */
    public function get_campaigns($filters = [])
    {
        global $wpdb;
        $query = "SELECT * FROM {$this->table_name} WHERE 1=1";

        if (isset($filters['campaign_name'])) {
            $query .= $wpdb->prepare(" AND campaign_name LIKE %s", '%' . sanitize_text_field($filters['campaign_name']) . '%');
        }

        return $wpdb->get_results($query);
    }

    /**
 * Retrieve all affiliate campaigns.
 *
 * @return array|object|null The result set or null on failure.
 */
public function get_all_campaigns()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'aff_mgr_affiliate_campaigns';

    $results = $wpdb->get_results("SELECT * FROM $table_name");
    if ($results) {
        return $results;
    } else {
        return [];
    }
}

}
