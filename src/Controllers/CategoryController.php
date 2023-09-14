<?php


namespace App\Controllers;
use \App\Service\CategoryService;
use App\Service\ProductService;
use App\Core\Response\Response;
use Exception;
use App\Contracts\Controller;

class CategoryController implements Controller
{
    public function __construct(protected CategoryService $categoryService, protected ProductService $productService)
    {
    }

    public function getCategoryPage(string $slugCategory): Response
    {
        try {
            $page = request()->getParameter("page", 1);
            $field = request()->getParameter("field", 'id');
            $order = request()->getParameter("order", 'asc');
            $filters = request()->getFilters(['made', 'price-from', 'price-to' ]);

            $category = $this->categoryService->getCategoryBySlug($slugCategory);
            $data = $this->categoryService->getProductsByCategory($page, $field, $order, $category, $filters);
            $data['countries'] = $this->productService->getMadeInCountries();
            $data['applied'] = $filters;

            return response()->view('MainPage/MainPage', $data);

        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->view('Errors/Error404', ['error' => $error]);
        }

    }
}