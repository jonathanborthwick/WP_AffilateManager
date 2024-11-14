<?php
if (!defined('ABSPATH')) {
    exit;
}
class AffiliateManager_Helpers
{
    /**
     * Retrieve a settings value by its key.
     *
     * @param string $key The setting key.
     * @return mixed|null The value of the setting, or null if it doesn't exist.
     */
    public static function getSettingsValueByKey($key)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aff_mgr_settings';

        $result = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $table_name WHERE setting_key = %s", $key));

        return $result ? $result : null;
    }

    /**
     * General helper functions for the Affiliate Manager plugin.
     */

    /**
     * Sanitize and validate a URL.
     *
     * @param string $url The URL to sanitize.
     * @return string The sanitized URL.
     */
    public static function affiliate_manager_sanitize_url($url)
    {
        return esc_url_raw(trim($url));
    }

    /**
     * Format a datetime string for display.
     *
     * @param string $datetime The datetime string to format.
     * @return string The formatted datetime.
     */
    public static function affiliate_manager_format_datetime($datetime)
    {
        return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($datetime));
    }

    /**
     * Generate a safe slug from a given string.
     *
     * @param string $string The string to convert to a slug.
     * @return string The generated slug.
     */
    public static function affiliate_manager_generate_slug($string)
    {
        return sanitize_title_with_dashes($string);
    }

    /**
     * Display an admin notice.
     *
     * @param string $message The message to display.
     * @param string $type The type of notice (e.g., 'success', 'error', 'warning', 'info').
     */
    public static function affiliate_manager_display_admin_notice($message, $type = 'info')
    {
        $class = "notice notice-{$type} is-dismissible";
        echo "<div class='$class'><p>$message</p></div>";
    }
}
