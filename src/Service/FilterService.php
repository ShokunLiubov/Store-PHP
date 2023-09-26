<?php

namespace App\Service;

class FilterService  extends Service
{
    public function productFilters(&$query, &$filters)
    {
        if (!empty($filters['category'])) {
            $query->whereIn('category.id', 'category', $filters['category']);
        }

        if (!empty($filters['made'])) {
            $query->whereIn('product.made', 'made', $filters['made']);
        }

        if (!empty($filters['price-from']) && !empty($filters['price-to'])) {
            if ($filters['price-from'] > $filters['price-to']) {
                $filters['price-to'] = $filters['price-from'];
            }
        }

        if (!empty($filters['price-from'])) {
            $query->where('product.price', '>=', 'price_from', $filters['price-from']);

            if (empty($filters['price-to'])) {
                $filters['price-to'] = $filters['price-from'];
            }
        }

        if (!empty($filters['price-to'])) {
            $query->where('product.price', '<=', 'price_to', $filters['price-to']);

            if (empty($filters['price-from'])) {
                $filters['price-from'] = $filters['price-to'];
                $query->where('product.price', '>=', 'price_from', $filters['price-from']);
            }
        }
        return $query;

    }
}
