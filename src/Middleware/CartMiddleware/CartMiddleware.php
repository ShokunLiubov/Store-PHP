<?php

namespace App\Middleware\CartMiddleware;

use App\Core\Response\Response;

class CartMiddleware
{
    public function emptyCart() : Response | null
    {
        if (isset($_SESSION['cart']) && empty($_SESSION['cart'])) {
            return response()->redirect('main');
        }

        return null;
    }
}