<div class="wrap">
    <h1>Manage Affiliate Links</h1>
    <p>Here you can manage all affiliate links, edit them, or view their shortcodes and QR codes.</p>

    <?php
    // Handle link editing if the action is 'edit'
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
        $link_id = intval($_GET['id']);
        $link = $linkManager->get_link_by_id($link_id);
    }

    // Handle form submissions (adding or updating links)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_link'])) {
            $link_name = sanitize_text_field($_POST['link_name']);
            $url = esc_url_raw($_POST['url']);
            $shortcode = sanitize_text_field($_POST['shortcode']);
            $network_id = intval($_POST['network_id']);
            
            $linkManager->add_link($link_name, $url, $shortcode, $network_id);
            echo '<div class="updated"><p>Link added successfully.</p></div>';
        } elseif (isset($_POST['update_link'])) {
            $link_id = intval($_POST['link_id']);
            $link_name = sanitize_text_field($_POST['link_name']);
            $url = esc_url_raw($_POST['url']);
            $shortcode = sanitize_text_field($_POST['shortcode']);
            $network_id = intval($_POST['network_id']);
            
            $linkManager->update_link($link_id, $link_name, $url, $shortcode, $network_id);
            echo '<div class="updated"><p>Link updated successfully.</p></div>';
        }
    }

    // Handle deletion if the action is 'delete'
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $link_id = intval($_GET['id']);
        $linkManager->delete_link($link_id);
        echo '<div class="updated"><p>Link deleted successfully.</p></div>';
    }

    // Fetch all affiliate links
    $links = $linkManager->get_links();

// Include Networks Manager Class
$networks_manager = new AffiliateManager_NetworksManager();
    ?>

    <h2><?php echo isset($link) ? 'Edit Link' : 'Add New Link'; ?></h2>
    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=affiliate_manager_links')); ?>">
        <input type="hidden" name="link_id" value="<?php echo isset($link) ? esc_attr($link->id) : ''; ?>">
        <table class="form-table">
            <tr>
                <th><label for="link_name">Link Name</label></th>
                <td><input type="text" id="link_name" name="link_name" class="regular-text" required value="<?php echo isset($link) ? esc_attr($link->link_name) : ''; ?>"></td>
            </tr>
            <tr>
                <th><label for="url">URL</label></th>
                <td><input type="url" id="url" name="url" class="regular-text" required value="<?php echo isset($link) ? esc_attr($link->url) : ''; ?>"></td>
            </tr>
            <tr>
                <th><label for="shortcode">Shortcode</label></th>
                <td><input type="text" id="shortcode" name="shortcode" class="regular-text" required value="<?php echo isset($link) ? esc_attr($link->short_code) : ''; ?>"></td>
            </tr>
            <tr>
                <th><label for="network_id">Affiliate Network</label></th>
                <td>
                    <select id="network_id" name="network_id" class="regular-text">
                        <option value="">Select a Network</option>
                        <?php foreach ($networks as $network) : ?>
                            <option value="<?php echo esc_attr($network->id); ?>" <?php echo (isset($link) && $link->network_id == $network->id) ? 'selected' : ''; ?>>
                                <?php echo esc_html($network->network_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="<?php echo isset($link) ? 'update_link' : 'add_link'; ?>" id="add_link" class="button button-primary" value="<?php echo isset($link) ? 'Save Link' : 'Add Link'; ?>">
        </p>
    </form>

    <h2>Existing Links</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Link Name</th>
                <th scope="col">URL</th>
                <th scope="col">Shortcode</th>
                <th scope="col">Affiliate Network</th>
                <th scope="col">QR Code</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($links)) : ?>
                <?php foreach ($links as $link) : ?>
                    <tr>
                        <td><?php echo esc_html($link->id); ?></td>
                        <td><?php echo esc_html($link->link_name); ?></td>
                        <td><a href="<?php echo esc_url($link->url); ?>" target="_blank"><?php echo esc_html($link->url); ?></a></td>
                        <td><?php echo esc_html($link->short_code); ?></td>
                        <td>
                            <?php 
                            $network = $networks_manager->get_network_by_id($link->network_id);
                            echo $network ? esc_html($network->network_name) : esc_html__('No network assigned', 'affiliate-manager');
                            ?>
                        </td>
                        <td>
                            <!-- Placeholder for QR code if needed in future -->
                        </td>
                        <td>
                            <a href="admin.php?page=affiliate_manager_links&action=edit&id=<?php echo esc_attr($link->id); ?>" class="button">Edit</a>
                            <a href="admin.php?page=affiliate_manager_links&action=delete&id=<?php echo esc_attr($link->id); ?>" class="button button-danger" onclick="return confirm('Are you sure you want to delete this link?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">No affiliate links found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
