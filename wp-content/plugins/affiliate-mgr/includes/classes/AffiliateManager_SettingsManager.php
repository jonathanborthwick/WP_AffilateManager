<?php
if (!defined('ABSPATH')) {
    exit;
}

class AffiliateManager_SettingsManager
{
    /**
     * Update a setting.
     *
     * @param string $key   The setting key.
     * @param string $value The setting value.
     * @return void
     */
    public function update_setting($key, $value)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_settings';

        // Check if the setting already exists
        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE setting_key = %s", $key));

        if ($exists > 0) {
            // Update existing setting
            $wpdb->update(
                $table_name,
                ['setting_value' => $value],
                ['setting_key' => $key],
                ['%s'],
                ['%s']
            );
        } else {
            // Insert new setting
            $wpdb->insert(
                $table_name,
                [
                    'setting_key' => $key,
                    'setting_value' => $value
                ],
                ['%s', '%s']
            );
        }
    }

    /**
     * Get a setting value by key.
     *
     * @param string $key The setting key.
     * @return string|null The setting value or null if not found.
     */
    public function get_setting($key)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_settings';

        return $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $table_name WHERE setting_key = %s", $key));
    }
}
