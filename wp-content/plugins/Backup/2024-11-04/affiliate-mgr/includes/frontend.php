<?php
require_once plugin_dir_path(__FILE__) . 'redirect-handler.php';
function affiliate_mgr_handler() {
    affiliate_mgr_redirect();
}
add_action('template_redirect', 'affiliate_mgr_handler');
