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

    public function get_category($id)
    {
        global $wpdb;
    
        $query = $wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id);
        return $wpdb->get_row($query);
    }
    
    public function update_category($id, $data)
{
    global $wpdb;

    $result = $wpdb->update(
        $this->table_name,
        [
            'name' => sanitize_text_field($data['name']),
            'notes' => sanitize_textarea_field($data['notes']),
            'updated_at' => current_time('mysql')
        ],
        ['id' => (int) $id],
        ['%s', '%s', '%s'],
        ['%d']
    );

    return ($result !== false);
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
