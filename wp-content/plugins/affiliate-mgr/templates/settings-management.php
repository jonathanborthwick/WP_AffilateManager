<?php
if (!defined('ABSPATH')) {
    exit;
}

$settings_manager = new AffiliateManager_SettingsManager();
$offer_not_found_page_type = $settings_manager->get_setting('not-found-page-local-or-remote');
$offer_not_found_url = $settings_manager->get_setting('offer-not-found');

?>
<div class="wrap">
    <h1>Affiliate Manager Settings</h1>
    <p>Here you can manage all of the settings for the plugin</p>
    
    <form method="post" action="">
        <?php wp_nonce_field('affiliate_mgr_settings_save', 'affiliate_mgr_settings_nonce'); ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="offer_not_found_page_type">Offer Not Found Page Type</label>
                </th>
                <td>
                    <select id="offer_not_found_page_type" name="offer_not_found_page_type">
                        <option value="local" <?php selected($offer_not_found_page_type, 'local'); ?>>Local</option>
                        <option value="remote" <?php selected($offer_not_found_page_type, 'remote'); ?>>Remote</option>
                    </select>
                    <p class="description">Choose whether to use a local WordPress slug or a remote URL for the "Offer Not Found" page.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="offer_not_found_url">Offer Not Found URL</label>
                </th>
                <td>
                    <input type="text" id="offer_not_found_url" name="offer_not_found_url" value="<?php echo esc_attr($offer_not_found_url); ?>" class="regular-text">
                    <p class="description">URL to redirect to when an offer is not found.</p>
                </td>
            </tr>
        </table>

        <?php submit_button('Save Settings'); ?>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['affiliate_mgr_settings_nonce']) && wp_verify_nonce($_POST['affiliate_mgr_settings_nonce'], 'affiliate_mgr_settings_save')) {
    $new_offer_not_found_page_type = sanitize_text_field($_POST['offer_not_found_page_type']);
    $new_offer_not_found_url = esc_url_raw(trim($_POST['offer_not_found_url']));

    $settings_manager->update_setting('not-found-page-local-or-remote', $new_offer_not_found_page_type);
    $settings_manager->update_setting('offer-not-found', $new_offer_not_found_url);

    echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully.</p></div>';
}
?>

