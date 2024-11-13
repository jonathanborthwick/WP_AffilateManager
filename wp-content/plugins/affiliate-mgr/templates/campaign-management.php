<?php
if (!defined('ABSPATH')) {
    exit;
}

// Include the Campaign Manager Class
$campaign_manager = new AffiliateManager_CampaignManager();

// Handle form submissions and actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_campaign'])) {
    // Verify nonce for security
    if (!isset($_POST['add_campaign_nonce']) || !wp_verify_nonce($_POST['add_campaign_nonce'], 'add_campaign_action')) {
        wp_die('Security check failed.');
    }

    // Get and sanitize form inputs
    $campaign_name = sanitize_text_field($_POST['campaign_name']);
    $description = sanitize_textarea_field($_POST['description']);

    // Add the campaign
    $added = $campaign_manager->add_campaign($campaign_name, $description);

    if ($added) {
        echo '<div class="notice notice-success is-dismissible"><p>Campaign added successfully.</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>Failed to add the campaign. Please try again.</p></div>';
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

    // Refresh the campaigns list
    $campaigns = $campaign_manager->get_all_campaigns();
}



// Fetch all campaigns to display
$campaigns = $campaign_manager->get_all_campaigns();
?>

<div class="wrap">
    <h1>Manage Campaigns</h1>
    <p>Here you can add, edit, or remove campaigns to group your affiliate links.</p>

    <h2>Add New Campaign</h2>
    <form method="post" action="">
        <?php wp_nonce_field('add_campaign_action', 'add_campaign_nonce'); ?>
        <table class="form-table">
            <tr>
                <th><label for="campaign_name">Campaign Name</label></th>
                <td><input type="text" id="campaign_name" name="campaign_name" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="description">Description</label></th>
                <td><textarea id="description" name="description" class="large-text" rows="3"></textarea></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="add_campaign" id="add_campaign" class="button button-primary" value="Add Campaign">
        </p>
    </form>

    <h2>Existing Campaigns</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Campaign Name</th>
                <th scope="col">Description</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($campaigns)) : ?>
                <?php foreach ($campaigns as $campaign) : ?>
                    <tr>
                        <td><?php echo esc_html($campaign->id); ?></td>
                        <td><?php echo esc_html($campaign->campaign_name); ?></td>
                        <td><?php echo esc_html($campaign->description); ?></td>
                        <td>
                            <a href="admin.php?page=affiliate_manager_campaigns&action=edit&id=<?php echo esc_attr($campaign->id); ?>" class="button">Edit</a>
                            <a href="admin.php?page=affiliate_manager_campaigns&action=delete&id=<?php echo esc_attr($campaign->id); ?>" class="button button-danger" onclick="return confirm('Are you sure you want to delete this campaign?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">No campaigns found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
