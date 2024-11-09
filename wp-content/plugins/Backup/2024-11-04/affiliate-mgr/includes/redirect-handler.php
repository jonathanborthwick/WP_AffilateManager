<?php
function affiliate_mgr_redirect() {
    global $wpdb;
    
    // Get the request path
    $request = trim($_SERVER['REQUEST_URI'], '/');
    $links_table = $wpdb->prefix . 'affiliate_links';

    // Check if the requested short code exists in the database
    $result = $wpdb->get_row(
        $wpdb->prepare("SELECT redirect_url FROM $links_table WHERE short_code = %s", $request)
    );

    // If the short code exists, redirect to the corresponding URL
    if ($result) {
        // Increment click count for tracking if needed
        $metrics_table = $wpdb->prefix . 'affiliate_metrics';
        $wpdb->query($wpdb->prepare("UPDATE $metrics_table SET clicks = clicks + 1 WHERE link_id = %d", $result->id));

        // Perform redirection
        wp_redirect($result->redirect_url);
        exit;
    }
}
