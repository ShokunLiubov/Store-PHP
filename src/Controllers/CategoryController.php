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

    /**
     * @throws Exception
     */
    public function getCategoryPage(string $slugCategory): Response
    {
            $filters = request()->getFilters(['made', 'price-from', 'price-to', 'search']);

            $category = $this->categoryService->getCategoryBySlug($slugCategory);
            $data = $this->categoryService->getProductsByCategory($category, $filters);
            $data['countries'] = $this->productService->getMadeInCountries();

            return response()->view('Pages/MainPage/MainPage', $data);

    }
}