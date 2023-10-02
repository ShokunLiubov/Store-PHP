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
            $filters = request()->getFilters(['category', 'made', 'price-from', 'price-to', 'search']);

            $data = $this->productService->getProducts($filters);
            $data['categories'] = $this->categoryService->getAllCategories();
            $data['countries'] = $this->productService->getMadeInCountries();

            return response()->view('Pages/MainPage/MainPage', $data);
    }
}
