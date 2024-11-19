<?php
if (!defined('ABSPATH')) {
    exit;
}

// Include the Campaign Manager Class
$campaign_manager = new AffiliateManager_CampaignManager();
$campaigns = $campaign_manager->get_all_campaigns();

// Check if an edit is requested
$edit_campaign = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $campaign_id = intval($_GET['id']);
    $edit_campaign = $campaign_manager->get_campaign($campaign_id);
}

// Handle form submissions and actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify nonce for security
    if (!isset($_POST['campaign_nonce']) || !wp_verify_nonce($_POST['campaign_nonce'], 'campaign_action')) {
        wp_die('Security check failed.');
    }

    // Get and sanitize form inputs
    $campaign_name = sanitize_text_field($_POST['campaign_name']);
    $description = sanitize_textarea_field($_POST['description']);

    if (isset($_POST['campaign_id']) && $_POST['campaign_id']) {
        // Update existing campaign
        $campaign_id = intval($_POST['campaign_id']);
        $updated = $campaign_manager->update_campaign($campaign_id, [
            'campaign_name' => $campaign_name,
            'description' => $description,
        ]);

        if ($updated) {
            echo '<div class="notice notice-success is-dismissible"><p>Campaign updated successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Failed to update the campaign. Please try again.</p></div>';
        }
    } else {
        // Add new campaign
        $added = $campaign_manager->add_campaign($campaign_name, $description);

        if ($added) {
            echo '<div class="notice notice-success is-dismissible"><p>Campaign added successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Failed to add the campaign. Please try again.</p></div>';
        }
    }
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $campaign_id = intval($_GET['id']);
    $deleted = $campaign_manager->delete_campaign($campaign_id);

    if ($deleted) {
        echo '<div class="notice notice-success is-dismissible"><p>Campaign deleted successfully.</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>Failed to delete the campaign. Please try again.</p></div>';
    }
}

// Fetch all campaigns to display
$campaigns = $campaign_manager->get_all_campaigns();
?>

<div class="wrap">
    <h1>Manage Campaigns</h1>
    <p>Here you can add, edit, or remove campaigns to group your affiliate links.</p>

    <h2><?php echo $edit_campaign ? 'Edit Campaign' : 'Add New Campaign'; ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field('campaign_action', 'campaign_nonce'); ?>
        <input type="hidden" name="campaign_id" value="<?php echo $edit_campaign ? esc_attr($edit_campaign->id) : ''; ?>">
        
        <table class="form-table">
            <tr>
                <th><label for="campaign_name">Campaign Name</label></th>
                <td><input name="campaign_name" type="text" id="campaign_name" value="<?php echo $edit_campaign ? esc_attr($edit_campaign->campaign_name) : ''; ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="description">Description</label></th>
                <td><textarea name="description" id="description" class="large-text"><?php echo $edit_campaign ? esc_textarea($edit_campaign->description) : ''; ?></textarea></td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $edit_campaign ? 'Save Changes' : 'Add Campaign'; ?>">
        </p>
    </form>

    <h2>Existing Campaigns</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Campaign Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($campaigns as $campaign): ?>
                <tr>
                    <td><?php echo esc_html($campaign->id); ?></td>
                    <td><?php echo esc_html($campaign->campaign_name); ?></td>
                    <td><?php echo esc_html($campaign->description); ?></td>
                    <td>
                        <a href="?page=affiliate_manager_campaigns&action=edit&id=<?php echo esc_attr($campaign->id); ?>" class="button">Edit</a>
                        <a href="?page=affiliate_manager_campaigns&action=delete&id=<?php echo esc_attr($campaign->id); ?>" class="button" onclick="return confirm('Are you sure you want to delete this campaign?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
