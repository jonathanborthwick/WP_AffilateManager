<?php
// Check if accessed directly, exit if true for security
if (!defined('ABSPATH')) {
    exit;
}

// Include Networks Manager Class
$networks_manager = new AffiliateManager_NetworksManager();

// Check if we're editing an existing network
$network = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $network_id = intval($_GET['id']);
    $network = $networks_manager->get_network_by_id($network_id);
}

// Fetch all networks to display
$networks = $networks_manager->get_all_networks();
?>

<h2>Manage Affiliate Networks</h2>
<p>Here you can add, edit, or remove affiliate networks to group your campaigns.</p>

<form method="post" action="">
    <?php if ($network): ?>
        <input type="hidden" name="network_id" value="<?php echo esc_attr($network->id); ?>">
    <?php endif; ?>

    <table class="form-table">
        <tr>
            <th scope="row"><label for="network_name">Network Name</label></th>
            <td><input type="text" name="network_name" id="network_name" value="<?php echo $network ? esc_attr($network->network_name) : ''; ?>" required></td>
        </tr>
        <tr>
            <th scope="row"><label for="description">Description</label></th>
            <td><textarea name="description" id="description"><?php echo $network ? esc_textarea($network->description) : ''; ?></textarea></td>
        </tr>
        <tr>
            <th scope="row"><label for="api_key">API Key</label></th>
            <td><input type="text" name="api_key" id="api_key" value="<?php echo $network ? esc_attr($network->api_key) : ''; ?>"></td>
        </tr>
        <tr>
            <th scope="row"><label for="client_id">OAuth Client ID</label></th>
            <td><input type="text" name="client_id" id="client_id" value="<?php echo $network ? esc_attr($network->client_id) : ''; ?>"></td>
        </tr>
        <tr>
            <th scope="row"><label for="client_secret">OAuth Client Secret</label></th>
            <td><input type="text" name="client_secret" id="client_secret" value="<?php echo $network ? esc_attr($network->client_secret) : ''; ?>"></td>
        </tr>
        <tr>
            <th scope="row"><label for="access_token">Access Token</label></th>
            <td><input type="text" name="access_token" id="access_token" value="<?php echo $network ? esc_attr($network->access_token) : ''; ?>"></td>
        </tr>
        <tr>
            <th scope="row"><label for="url">URL</label></th>
            <td><input type="text" name="url" id="url" value="<?php echo $network ? esc_url($network->url) : ''; ?>"></td>
        </tr>
    </table>

    <p class="submit">
        <input type="submit" name="add_network" class="button button-primary" value="<?php echo $network ? 'Save Network' : 'Add Network'; ?>">
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
