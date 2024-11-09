<?php
if (!defined('ABSPATH')) {
    exit;
}
class AffiliateManager_CategoriesManager
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'aff_mgr_affiliate_categories';
    }

    /**
     * Get all categories from the database.
     *
     * @return array|null
     */
    public function get_all_categories()
    {
        global $wpdb;
        $query = "SELECT * FROM {$this->table_name}";
        return $wpdb->get_results($query);
    }

    /**
     * Add a new category to the database.
     *
     * @param string $name
     * @param string|null $notes
     * @return bool|int
     */
    public function add_category($name, $notes = null)
    {
        global $wpdb;
        $result = $wpdb->insert(
            $this->table_name,
            [
                'name' => $name,
                'notes' => $notes,
                'created_at' => current_time('mysql')
            ],
            [
                '%s', '%s', '%s'
            ]
        );

        return $result;
    }

    /**
     * Delete a category from the database.
     *
     * @param int $id
     * @return bool|int
     */
    public function delete_category($id)
    {
        global $wpdb;
        return $wpdb->delete($this->table_name, ['id' => $id], ['%d']);
    }
}
