<?php
require_once __DIR__ . '/../models/menuCategory.php';

class MenuCategoryController {
    private $model;

    public function __construct($pdo) {
        $this->model = new MenuCategory($pdo);
    }

    public function index() {
        return $this->model->getAllCategories();
    }
}
?>