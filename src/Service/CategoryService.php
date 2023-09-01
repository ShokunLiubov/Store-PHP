<?php

namespace App\Service;

use App\Model\CategoryModel;
use Exception;

class CategoryService
{
    public function __construct(protected CategoryModel $categoryModel)
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

    public function getProductsByCategory($page, $category): array
    {
        $limit = 10;
        $filters['category'] = $category['id'];
        $products = $this->categoryModel->getAllWithPaginate($page, $limit, $filters);
        $totalPages = $this->categoryModel->countAll($filters);
        $totalPages = $totalPages / $limit;
        $path = $category['slug'];

        return ['products' => $products, 'totalPages' => $totalPages,
                'currentPage' => (int)$page, 'path' => $path,
                'category' => $category['name']
                ];
    }

}