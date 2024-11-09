<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['short_code']) && isset($_POST['redirect_url'])) {
    global $wpdb;
    $table = $wpdb->prefix . 'affiliate_links';

    $short_code = sanitize_text_field($_POST['short_code']);
    $redirect_url = esc_url_raw($_POST['redirect_url']);

    $wpdb->insert(
        $table,
        [
            'short_code' => $short_code,
            'redirect_url' => $redirect_url,
        ]
    );

    echo '<div class="updated"><p>Affiliate link added successfully!</p></div>';
}
?>

<div class="wrap">
    <h1>Affiliate Manager Dashboard</h1>
    
    <form method="POST" action="">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="short_code">Short Code</label></th>
                <td><input type="text" name="short_code" id="short_code" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="redirect_url">Redirect URL</label></th>
                <td><input type="url" name="redirect_url" id="redirect_url" class="regular-text" required></td>
            </tr>
        </table>
        <?php submit_button('Add Redirect'); ?>
    </form>
</div>

