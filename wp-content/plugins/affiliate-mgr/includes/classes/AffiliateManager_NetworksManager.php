<?php
if (!defined('ABSPATH')) {
    exit;
}
class AffiliateManager_NetworksManager
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'aff_mgr_affiliate_networks';
    }

    // Method to get all networks
    public function get_all_networks()
    {
        global $wpdb;

        // Fetch all networks from the database
        $query = "SELECT * FROM {$this->table_name}";
        return $wpdb->get_results($query);
    }

    // Method to get a network by ID
    public function get_network_by_id($network_id)
    {
        global $wpdb;
        // Prepare and execute the query to get the network by ID
        $query = $wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $network_id);
        $result = $wpdb->get_row($query);
        return $result ? $result : null;
    }

    // Method to add a new network
    public function add_network($network_name, $description, $api_key = null, $client_id = null, $client_secret = null, $access_token = null, $url = null)
    {
        global $wpdb;

        return $wpdb->insert(
            $this->table_name,
            [
                'network_name' => sanitize_text_field($network_name),
                'description' => sanitize_textarea_field($description),
                'api_key' => sanitize_text_field($api_key),
                'client_id' => sanitize_text_field($client_id),
                'client_secret' => sanitize_text_field($client_secret),
                'access_token' => sanitize_textarea_field($access_token),
                'url' => esc_url_raw($url)
            ],
            [
                '%s', '%s', '%s', '%s', '%s', '%s', '%s'
            ]
        );
    }

    // Method to update an existing network
    public function edit_network($network_id, $network_name, $description, $api_key = null, $client_id = null, $client_secret = null, $access_token = null, $url = null)
    {
        global $wpdb;

        return $wpdb->update(
            $this->table_name,
            [
                'network_name' => sanitize_text_field($network_name),
                'description' => sanitize_textarea_field($description),
                'api_key' => sanitize_text_field($api_key),
                'client_id' => sanitize_text_field($client_id),
                'client_secret' => sanitize_text_field($client_secret),
                'access_token' => sanitize_textarea_field($access_token),
                'url' => esc_url_raw($url)
            ],
            ['id' => $network_id],
            [
                '%s', '%s', '%s', '%s', '%s', '%s', '%s'
            ],
            ['%d']
        );
    }

    public function handle_form_submission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_network'])) {
                // Add or update network
                $network_name = sanitize_text_field($_POST['network_name']);
                $description = sanitize_textarea_field($_POST['description']);
                $api_key = sanitize_text_field($_POST['api_key']);
                $client_id = sanitize_text_field($_POST['client_id']);
                $client_secret = sanitize_text_field($_POST['client_secret']);
                $access_token = sanitize_textarea_field($_POST['access_token']);
                $url = esc_url_raw($_POST['url']);
    
                if (isset($_POST['network_id']) && !empty($_POST['network_id'])) {
                    // Update existing network
                    $network_id = intval($_POST['network_id']);
                    $this->edit_network($network_id, $network_name, $description, $api_key, $client_id, $client_secret, $access_token, $url);
                } else {
                    // Add new network
                    $this->add_network($network_name, $description, $api_key, $client_id, $client_secret, $access_token, $url);
                }
            }
        }
    
        // Handle delete action
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $network_id = intval($_GET['id']);
            $this->delete_network($network_id);
            // Redirect to prevent resubmission
            wp_redirect(admin_url('admin.php?page=affiliate_manager_networks'));
            exit;
        }
    }
    
    // delete_network 
    public function delete_network($network_id) {
        global $wpdb;
        $wpdb->delete(
            $this->table_name,
            ['id' => $network_id],
            ['%d']
        );
    }
    
}
