<?php

namespace App\Controllers;

use App\Service\CategoryService;
use App\Service\ProductService;
use App\Core\Response\Response;
use Exception;

class IndexController extends Controller
{
    public function __construct(protected ProductService $productService, protected CategoryService $categoryService)
    {
    }
    public function showMainPage(): Response
    {
        try {
            $page = request()->getParameter("page", 1);
            $field = request()->getParameter("field", 'id');
            $order = request()->getParameter("order", 'asc');
            $filters = request()->getFilters([ 'category', 'made', 'price-from', 'price-to' ]);

            $data = $this->productService->getProducts($page, $field, $order, $filters);
            $data['categories'] = $this->categoryService->getAllCategories();
            $data['countries'] = $this->productService->getMadeInCountries();

            return response()->view('MainPage/MainPage', $data);

        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->view('Errors/Error404', ['error' => $error]);
        }

    }
}
