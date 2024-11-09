<?php
// Check if accessed directly, exit if true for security
if (!defined('ABSPATH')) {
    exit;
}

// Include Categories Manager Class
$categories_manager = new AffiliateManager_CategoriesManager();
$categories = $categories_manager->get_all_categories();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = sanitize_text_field($_POST['category_name']);
        $notes = sanitize_textarea_field($_POST['category_notes']);
        $categories_manager->add_category($name, $notes);
    } elseif (isset($_POST['delete_category'])) {
        $category_id = intval($_POST['category_id']);
        $categories_manager->delete_category($category_id);
    }
}

// Display page title
?>
<div class="wrap">
    <h1><?php esc_html_e('Manage Categories', 'affiliate-manager'); ?></h1>

    <!-- Add New Category Form -->
    <h2><?php esc_html_e('Add New Category', 'affiliate-manager'); ?></h2>
    <form method="POST" action="">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="category_name"><?php esc_html_e('Category Name', 'affiliate-manager'); ?></label>
                </th>
                <td>
                    <input type="text" name="category_name" id="category_name" required />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="category_notes"><?php esc_html_e('Notes', 'affiliate-manager'); ?></label>
                </th>
                <td>
                    <textarea name="category_notes" id="category_notes"></textarea>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="add_category" id="add_category" class="button button-primary" value="<?php esc_attr_e('Add Category', 'affiliate-manager'); ?>">
        </p>
    </form>

    <!-- Existing Categories Table -->
    <h2><?php esc_html_e('Existing Categories', 'affiliate-manager'); ?></h2>
    <table class="widefat fixed">
        <thead>
            <tr>
                <th><?php esc_html_e('ID', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('Name', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('Notes', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('Actions', 'affiliate-manager'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)) : ?>
                <?php foreach ($categories as $category) : ?>
                    <tr>
                        <td><?php echo esc_html($category->id); ?></td>
                        <td><?php echo esc_html($category->name); ?></td>
                        <td><?php echo esc_html($category->notes); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="category_id" value="<?php echo esc_attr($category->id); ?>" />
                                <input type="submit" name="delete_category" class="button button-secondary" value="<?php esc_attr_e('Delete', 'affiliate-manager'); ?>" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4"><?php esc_html_e('No categories found.', 'affiliate-manager'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
