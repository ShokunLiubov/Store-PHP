<?php


namespace App\Controllers;
use \App\Service\CategoryService;
use App\Service\ProductService;
use App\Core\Response\Response;
use Exception;

class CategoryController
{

    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function getCategoryPage($slugCategory): Response
    {
        try {
            $page = $_GET["page"] ?? 1;
            $field = $_GET["field"] ?? 'id';
            $order = $_GET["order"] ?? 'asc';
            $filters = $_GET["filters"] ?? [];
            $category = $this->categoryService->getCategoryBySlug($slugCategory);
            $productsData = $this->categoryService->getProductsByCategory($page,$field, $order, $category);

            return response()->view('MainPage/MainPage', $productsData);

        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->view('Errors/Error404', ['error' => $error]);
        }

    }
}