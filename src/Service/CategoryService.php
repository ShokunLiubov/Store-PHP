<?php

namespace App\Service;

use App\Model\CategoryModel;
use App\Model\ProductModel;
use Exception;

class CategoryService  extends Service
{
    public function __construct(protected CategoryModel $categoryModel, protected ProductModel $productModel)
    {
    }

    /**
     * @throws Exception
     */
    public function getCategoryBySlug(string $slugCategory): array
    {
        $category = $this->categoryModel->getBySlug($slugCategory);

        if (!$category) {
            throw new Exception('Category not found!');
        }

        return $category;
    }

    public function getAllCategories(): array
    {
        $categories = $this->categoryModel->getAll();
        if (!$categories) {
            throw new Exception('Category not found!');
        }

        return $categories;
    }

    public function getProductsByCategory(int $page, string $field, string $order, array $category, array $filters = []): array
    {
        $filters['category'][] = $category['id'];
        $data =  $this->productModel->getAllWithPaginate($page, $field, $order, $filters);
        $data['path'] = $category['slug'];
        $data['category'] = $category['name'];

        return $data;
    }

}