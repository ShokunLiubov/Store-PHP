<?php

namespace App\Service;

use App\Core\DataBase\QueryBuilder;

class FilterService extends Service
{
    public function productFilters(QueryBuilder &$query, array &$filters): QueryBuilder
    {
        $this->applySearchFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);
        $this->applyMadeFilter($query, $filters);
        $this->applyPriceFilter($query, $filters);

        return $query;
    }

    protected function applySearchFilter(QueryBuilder &$query, array &$filters): void
    {
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query
                ->whereGroup(function (QueryBuilder $query) use ($search) {
                    $query
                        ->where('product.title', 'LIKE', 'title', "%$search%")
                        ->whereOr('product.description', 'LIKE', 'description', "%$search%")
                        ->whereOr('category.name', 'LIKE', 'name', "%$search%");
                });
        }
    }

    protected function applyCategoryFilter(QueryBuilder &$query, array &$filters): void
    {
        if (!empty($filters['category'])) {
            $query->whereIn('category.id', 'category', $filters['category']);
        }
    }

    protected function applyMadeFilter(QueryBuilder &$query, array &$filters): void
    {
        if (!empty($filters['made'])) {
            $query->whereIn('product.made', 'made', $filters['made']);
        }
    }

    protected function applyPriceFilter(QueryBuilder &$query, array &$filters): void
    {
        $priceFrom = $filters['price-from'] ?? null;
        $priceTo = $filters['price-to'] ?? null;

        if ($priceFrom && $priceTo && $priceFrom > $priceTo) {
            $priceTo = $priceFrom;
            $filters['price-to'] = $priceTo;
        }

        if ($priceFrom) {
            $query->where('product.price', '>=', 'price_from', $priceFrom);
        }

        if ($priceTo) {
            $query->where('product.price', '<=', 'price_to', $priceTo);
        }

        if ($priceTo && !$priceFrom) {
            $query->where('product.price', '>=', 'price_from', $priceTo);
        }

        if ($priceFrom && !$priceTo) {
            $query->where('product.price', '<=', 'price_to', $priceFrom);
        }
    }
}
