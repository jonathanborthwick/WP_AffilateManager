<div class="wrap">
    <h1>Manage Affiliate Links</h1>
    <p>Here you can manage all affiliate links, edit them, or view their shortcodes and QR codes.</p>

    <h2>Add New Link</h2>
    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th><label for="link_name">Link Name</label></th>
                <td><input type="text" id="link_name" name="link_name" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="url">URL</label></th>
                <td><input type="url" id="url" name="url" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="shortcode">Shortcode</label></th>
                <td><input type="text" id="shortcode" name="shortcode" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="network_id">Affiliate Network</label></th>
                <td>
                    <select id="network_id" name="network_id" class="regular-text">
                        <option value="">Select a Network</option>
                        <?php foreach ($networks as $network) : ?>
                            <option value="<?php echo esc_attr($network->id); ?>"><?php echo esc_html($network->network_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="add_link" id="add_link" class="button button-primary" value="Add Link">
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
                        <td><?php echo esc_html(home_url('/' . $link->shortcode)); ?></td>
                        <td>
                            <?php 
                            $network = $networksManager->get_network_by_id($link->network_id);
                            echo $network ? esc_html($network->network_name) : esc_html__('No network assigned', 'affiliate-manager');
                            ?>
                        </td>
                        <td>
                            <?php $qr_code_url = $linkManager->generate_qr_code(home_url('/' . $link->shortcode)); ?>
                            <img src="<?php echo esc_url($qr_code_url); ?>" alt="QR Code" width="50" height="50">
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
