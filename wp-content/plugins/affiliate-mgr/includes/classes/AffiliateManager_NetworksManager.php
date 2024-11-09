<?php
if (!defined('ABSPATH')) {
    exit;
}
class AffiliateManager_NetworksManager
{

// Method to get all networks.
public function get_all_networks()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'aff_mgr_affiliate_networks';

    // Fetch all networks from the database.
    $query = "SELECT * FROM $table_name";
    return $wpdb->get_results($query);
}

    public function handle_form_submission()
    {
        if (isset($_POST['add_network'])) {
            // Validate and sanitize form inputs
            $network_name = isset($_POST['network_name']) ? sanitize_text_field($_POST['network_name']) : '';
            $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';
    
            if (!empty($network_name)) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'aff_mgr_affiliate_networks';
    
                // Insert into the database
                $inserted = $wpdb->insert(
                    $table_name,
                    [
                        'network_name' => $network_name,
                        'description' => $description
                    ],
                    [
                        '%s',
                        '%s'
                    ]
                );
    
                if ($inserted) {
                    // Redirect to avoid form resubmission
                    wp_redirect(admin_url('admin.php?page=affiliate_manager_networks&success=1'));
                    exit;
                } else {
                    // Add an error message if the insert failed
                    echo '<div class="notice notice-error"><p>Failed to add the network. Please try again.</p></div>';
                }
            } else {
                echo '<div class="notice notice-error"><p>Network name is required.</p></div>';
            }
        }
    }
    

    /**
     * Add a new affiliate network to the database.
     *
     * @param string $network_name
     * @param string $description
     * @return bool True on success, false on failure.
     */
    public function add_network($network_name, $description)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_networks';

        return $wpdb->insert(
            $table_name,
            [
                'network_name' => sanitize_text_field($network_name),
                'description' => sanitize_textarea_field($description)
            ],
            [
                '%s',
                '%s'
            ]
        ) !== false;
    }

    /**
     * Get all affiliate networks from the database.
     *
     * @return array List of networks.
     */
    public function get_networks()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_networks';
        return $wpdb->get_results("SELECT * FROM $table_name");
    }

    /**
     * Get network by ID.
     *
     * @param int $network_id
     * @return object|null The network object or null if not found.
     */
    public function get_network_by_id($network_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_networks';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $network_id));
    }

    /**
     * Update an existing affiliate network.
     *
     * @param int $network_id
     * @param string $network_name
     * @param string $description
     * @return bool True on success, false on failure.
     */
    public function update_network($network_id, $network_name, $description)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_networks';

        return $wpdb->update(
            $table_name,
            [
                'network_name' => sanitize_text_field($network_name),
                'description' => sanitize_textarea_field($description)
            ],
            ['id' => $network_id],
            [
                '%s',
                '%s'
            ],
            ['%d']
        ) !== false;
    }

    /**
     * Delete an affiliate network from the database.
     *
     * @param int $network_id
     * @return bool True on success, false on failure.
     */
    public function delete_network($network_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_affiliate_networks';
        return $wpdb->delete($table_name, ['id' => $network_id], ['%d']) !== false;
    }
}

