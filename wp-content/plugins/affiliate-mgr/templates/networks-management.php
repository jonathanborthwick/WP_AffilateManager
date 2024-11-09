<?php
if (isset($_POST['add_network'])) {
    // Get the submitted form data
    $network_name = isset($_POST['network_name']) ? sanitize_text_field($_POST['network_name']) : '';
    $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';

    // Check if data is valid
    if (!empty($network_name)) {
        // Create an instance of the Networks Manager class
        $networksManager = new AffiliateManager_NetworksManager();
        $success = $networksManager->add_network($network_name, $description);

        if ($success) {
            echo '<div class="notice notice-success is-dismissible"><p>Network added successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Failed to add the network. Please try again.</p></div>';
        }
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>Network name is required.</p></div>';
    }
}
?>

<div class="wrap">
    <h1>Manage Affiliate Networks</h1>
    <p>Here you can add, edit, or remove affiliate networks to group your campaigns.</p>

    <h2>Add New Network</h2>
    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=affiliate_manager_networks')); ?>">
        <table class="form-table">
            <tr>
                <th><label for="network_name">Network Name</label></th>
                <td><input type="text" id="network_name" name="network_name" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="description">Description</label></th>
                <td><textarea id="description" name="description" class="large-text" rows="3"></textarea></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="add_network" id="add_network" class="button button-primary" value="Add Network">
        </p>
    </form>

    <h2>Existing Campaigns</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Network Name</th>
                <th scope="col">Description</th>
                <th scope="col">Campaign</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($networks)) : ?>
                <?php foreach ($networks as $network) : ?>
                    <tr>
                        <td><?php echo esc_html($network->id); ?></td>
                        <td><?php echo esc_html($network->network_name); ?></td>
                        <td><?php echo esc_html($network->description); ?></td>
                        <td><?php echo isset($network->campaign) ? esc_html($network->campaign) : 'No campaign linked'; ?></td>
                        <td>
                            <a href="admin.php?page=affiliate_manager_networks&action=edit&id=<?php echo esc_attr($network->id); ?>" class="button">Edit</a>
                            <a href="admin.php?page=affiliate_manager_networks&action=delete&id=<?php echo esc_attr($network->id); ?>" class="button button-danger" onclick="return confirm('Are you sure you want to delete this network?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">No affiliate networks found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
