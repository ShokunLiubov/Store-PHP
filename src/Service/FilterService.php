<?php

namespace App\Service;

class FilterService  extends Service
{
    public function productFilters(&$query, $filters): void
    {
        if (!empty($filters['category'])) {
            $query->whereIn('category.id', 'category', $filters['category']);
        }

        if (!empty($filters['made'])) {
            $query->where('product.made', '=', 'made', $filters['made']);
        }

        if (!empty($filters['price-from'])) {
            $query->where('product.price', '>=', 'price_from', $filters['price-from']);
        }

        if (!empty($filters['price-to'])) {
            $query->where('product.price', '<=', 'price_to', $filters['price-to']);
        }
    }
}
