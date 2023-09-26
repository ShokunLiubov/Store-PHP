<?php

namespace App\Service;

use App\Model\CategoryModel;
use Exception;

class CategoryService  extends Service
{
    public function __construct(protected CategoryModel $categoryModel, protected ProductService $productService)
    {
    }

    /**
     * @throws Exception
     */
    public function getCategoryBySlug(string $slug): array
    {

        $category = $this->categoryModel->query()
            ->select()
            ->where('slug', '=', 'slug', $slug)
            ->getOne();

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

    public function getProductsByCategory(array $category, array $filters): array
    {
        $filters['category'][] = $category['id'];
        $data =  $this->productService->getProducts($filters);
        $data['path'] = $category['slug'];
        $data['category'] = $category['name'];

        return $data;
    }

}