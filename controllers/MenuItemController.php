<?php
require_once __DIR__ . '/../models/MenuItem.php';

class MenuItemController {
    private $model;

    public function __construct($pdo) {
        $this->model = new MenuItem($pdo);
    }


    public function showByCategory($category_id) {
        $categories = $this->model->getCategories();
        $menu_items = $this->model->getByCategory($category_id);
        $category_name = $this->model->getCategoryName($category_id);

        return [
            'categories' => $categories,
            'menu_items' => $menu_items,
            'category_name' => $category_name
        ];
    }
    public function showItemDetails($id) {
        $item = $this->model->getMenuItemById($id);
        if (!$item) {
            die("Item not found");
        }
        return $item;
    }
    
}
?>
