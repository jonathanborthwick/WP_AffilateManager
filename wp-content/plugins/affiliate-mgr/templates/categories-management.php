<?php
// Check if accessed directly, exit if true for security
if (!defined('ABSPATH')) {
    exit;
}

// Include Categories Manager Class
$categories_manager = new AffiliateManager_CategoriesManager();

// Check if an edit is requested
$edit_category = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $category_id = intval($_GET['id']);
    $edit_category = $categories_manager->get_category($category_id);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['category_nonce']) && wp_verify_nonce($_POST['category_nonce'], 'category_action')) {
        // Sanitize input
        $name = sanitize_text_field($_POST['category_name']);
        $notes = sanitize_textarea_field($_POST['category_notes']);
        
        if (isset($_POST['category_id']) && $_POST['category_id']) {
            // Update existing category
            $category_id = intval($_POST['category_id']);
            $categories_manager->update_category($category_id, [
                'name' => $name,
                'notes' => $notes,
            ]);
        } else {
            // Add new category
            $categories_manager->add_category($name, $notes);
        }
    }
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $category_id = intval($_GET['id']);
    $categories_manager->delete_category($category_id);
}

// Fetch all categories to display
$categories = $categories_manager->get_all_categories();
?>

<div class="wrap">
    <h1><?php esc_html_e('Manage Categories', 'affiliate-manager'); ?></h1>

    <!-- Add/Edit Category Form -->
    <h2><?php echo $edit_category ? esc_html__('Edit Category', 'affiliate-manager') : esc_html__('Add New Category', 'affiliate-manager'); ?></h2>
    <form method="POST" action="">
        <?php wp_nonce_field('category_action', 'category_nonce'); ?>
        <input type="hidden" name="category_id" value="<?php echo $edit_category ? esc_attr($edit_category->id) : ''; ?>">
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="category_name"><?php esc_html_e('Category Name', 'affiliate-manager'); ?></label>
                </th>
                <td>
                    <input type="text" name="category_name" id="category_name" value="<?php echo $edit_category ? esc_attr($edit_category->name) : ''; ?>" required />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="category_notes"><?php esc_html_e('Notes', 'affiliate-manager'); ?></label>
                </th>
                <td>
                    <textarea name="category_notes" id="category_notes"><?php echo $edit_category ? esc_textarea($edit_category->notes) : ''; ?></textarea>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $edit_category ? esc_attr__('Save Changes', 'affiliate-manager') : esc_attr__('Add Category', 'affiliate-manager'); ?>">
        </p>
    </form>

    <!-- Existing Categories Table -->
    <h2><?php esc_html_e('Existing Categories', 'affiliate-manager'); ?></h2>
    <table class="wp-list-table widefat fixed striped">
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
                            <a href="?page=affiliate_manager_categories&action=edit&id=<?php echo esc_attr($category->id); ?>" class="button"><?php esc_html_e('Edit', 'affiliate-manager'); ?></a>
                            <a href="?page=affiliate_manager_categories&action=delete&id=<?php echo esc_attr($category->id); ?>" class="button" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this category?', 'affiliate-manager'); ?>');"><?php esc_html_e('Delete', 'affiliate-manager'); ?></a>
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
