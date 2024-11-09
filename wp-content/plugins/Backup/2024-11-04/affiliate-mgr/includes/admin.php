<?php
function affiliate_mgr_menu() {
    add_menu_page(
        'Affiliate Manager',
        'Affiliate Manager',
        'manage_options',
        'affiliate-mgr',
        'affiliate_mgr_dashboard',
        'dashicons-admin-links',
        100
    );

    add_submenu_page(
        'affiliate-mgr',
        'Campaigns',
        'Campaigns',
        'manage_options',
        'affiliate-mgr-campaigns',
        'affiliate_mgr_campaigns_page'
    );

    add_submenu_page(
        'affiliate-mgr',
        'Links',
        'Links',
        'manage_options',
        'affiliate-mgr-links',
        'affiliate_mgr_links_page'
    );

    add_submenu_page(
        'affiliate-mgr',
        'Categories',
        'Categories',
        'manage_options',
        'affiliate-mgr-categories',
        'affiliate_mgr_categories_page'
    );

    add_submenu_page(
        'affiliate-mgr',
        'View by Category',
        'View by Category',
        'manage_options',
        'affiliate-mgr-view-categories',
        'affiliate_mgr_view_categories_page'
    );
}
add_action('admin_menu', 'affiliate_mgr_menu');

// Main Dashboard Page
function affiliate_mgr_dashboard() {
    if (file_exists(plugin_dir_path(__FILE__) . '../templates/admin-dashboard.php')) {
        include plugin_dir_path(__FILE__) . '../templates/admin-dashboard.php';
    } else {
        echo '<div class="wrap"><h1>Affiliate Manager Dashboard!</h1><p>Template file not found.</p></div>';
    }
}

// Campaigns Page
function affiliate_mgr_campaigns_page() {
    echo '<div class="wrap"><h1>Manage Campaigns</h1>';
    // Add campaign management form/table here
    echo '</div>';
}

// Links Page
function affiliate_mgr_links_page() {
    global $wpdb;
    $custom_prefix = $wpdb->prefix . 'aff_mgr_';
    $links_table = $custom_prefix . 'affiliate_links';
    $categories_table = $custom_prefix . 'affiliate_categories';

    // Fetch all links and categories
    $links = $wpdb->get_results("SELECT * FROM $links_table");
    $categories = $wpdb->get_results("SELECT * FROM $categories_table");

    // Output wrapper div and draggable categories and links
    echo '<div class="wrap"><h1>Manage Links</h1>';

    if (!empty($categories)) {
        echo '<div id="categories-wrapper" style="display: flex;">';

        foreach ($categories as $category) {
            echo '<div class="category-box" id="category-' . esc_attr($category->id) . '" style="margin: 10px; padding: 10px; border: 1px solid #ccc; width: 200px;">
                    <h3>' . esc_html($category->name) . '</h3>
                    <ul class="link-list" data-category-id="' . esc_attr($category->id) . '">';
            
            // Display existing links in each category
            $category_links = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $links_table WHERE category_id = %d", $category->id)
            );
            foreach ($category_links as $link) {
                echo '<li class="link-item" id="link-' . esc_attr($link->id) . '" data-link-id="' . esc_attr($link->id) . '">' . esc_html($link->short_code) . '</li>';
            }
            
            echo '  </ul>
                  </div>';
        }
        echo '</div>';
    }

    // Display links not in any category
    echo '<div id="uncategorized-links" style="margin-top: 20px;">
            <h3>Uncategorized Links</h3>
            <ul class="link-list">';
    foreach ($links as $link) {
        if (!$link->category_id) {
            echo '<li class="link-item" id="link-' . esc_attr($link->id) . '" data-link-id="' . esc_attr($link->id) . '">' . esc_html($link->short_code) . '</li>';
        }
    }
    echo '</ul></div>';

    echo '</div>'; // Closing wrap div

    // Enqueue the script for drag-and-drop functionality
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
    echo '<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>';
    echo '<script>
            jQuery(document).ready(function($) {
                $(".link-list").sortable({
                    connectWith: ".link-list",
                    receive: function(event, ui) {
                        var linkId = ui.item.data("link-id");
                        var categoryId = $(this).data("category-id") || null;

                        // Make an AJAX request to update the link category
                        $.post(ajaxurl, {
                            action: "update_link_category",
                            link_id: linkId,
                            category_id: categoryId
                        }, function(response) {
                            if (response.success) {
                                console.log("Link category updated successfully");
                            } else {
                                alert("Failed to update link category");
                            }
                        });
                    }
                }).disableSelection();
            });
          </script>';
}


// Categories Page
function affiliate_mgr_categories_page() {
    global $wpdb;
    $custom_prefix = $wpdb->prefix . 'aff_mgr_';
    $categories_table = $custom_prefix . 'affiliate_categories';

    // Handle form submission for adding categories
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
        $category_name = sanitize_text_field($_POST['category_name']);
        $wpdb->insert(
            $categories_table,
            [
                'name' => $category_name
            ]
        );
        echo '<div class="notice notice-success is-dismissible"><p>Category added successfully!</p></div>';
    }

    echo '<div class="wrap">
            <h1>Manage Categories</h1>
            <form method="POST" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="category_name">Category Name</label></th>
                        <td><input type="text" name="category_name" id="category_name" class="regular-text" required></td>
                    </tr>
                </table>
                '; submit_button('Add Category'); echo '
            </form>
          </div>';
}




// View by Category Page
function affiliate_mgr_view_categories_page() {
    global $wpdb;
    $custom_prefix = $wpdb->prefix . 'aff_mgr_';
    $links_table = $custom_prefix . 'affiliate_links';
    $categories_table = $custom_prefix . 'affiliate_categories';

    $categories = $wpdb->get_results("SELECT * FROM $categories_table");

    echo '<div class="wrap">
            <h1>View Shortcodes by Category</h1>';

    if (!empty($categories)) {
        foreach ($categories as $category) {
            echo '<h2>' . esc_html($category->name) . '</h2>';

            $links = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $links_table WHERE category_id = %d", $category->id)
            );

            if (!empty($links)) {
                echo '<ul>';
                foreach ($links as $link) {
                    echo '<li>' . esc_html($link->short_code) . ' - ' . esc_url($link->redirect_url) . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No shortcodes found for this category.</p>';
            }
        }
    } else {
        echo '<p>No categories found.</p>';
    }

    echo '</div>';
}

// AJAX handler for updating link categories
function affiliate_mgr_update_link_category() {
    if (isset($_POST['link_id']) && isset($_POST['category_id'])) {
        global $wpdb;
        $custom_prefix = $wpdb->prefix . 'aff_mgr_';
        $links_table = $custom_prefix . 'affiliate_links';

        $link_id = intval($_POST['link_id']);
        $category_id = $_POST['category_id'] !== "null" ? intval($_POST['category_id']) : null;

        // Update link category
        $wpdb->update(
            $links_table,
            ['category_id' => $category_id],
            ['id' => $link_id]
        );

        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_update_link_category', 'affiliate_mgr_update_link_category');



function affiliate_mgr_enqueue_admin_styles() {
    wp_enqueue_style(
        'affiliate-mgr-admin-style', // Unique handle for the stylesheet
        plugin_dir_url(__FILE__) . '../assets/css/admin-style.css', // Path to the CSS file
        array(), // Dependencies
        '1.0.0' // Version number
    );
}
add_action('admin_enqueue_scripts', 'affiliate_mgr_enqueue_admin_styles');

