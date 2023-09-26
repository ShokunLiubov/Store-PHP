<?php

namespace App\Controllers;

use App\Service\ProductService;
use App\Service\CartService;
use Exception;
use App\Core\Response\Response;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService, protected ProductService $productService)
    {
    }

    public function showCart(): Response
    {
        try {
            $cartProducts = $this->cartService->getCart();
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Components/Cart/Cart', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum
            ]);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return $this->cartService->handlerErrors($error);
        }
    }

    public function hideCart(): Response
    {
        return response()->view('Components/Cart/Cart', [
            'cartModal' => false
        ]);
    }


    public function addToCart(int $id): Response
    {
        try {
            $addProduct = $this->productService->getProduct($id);
            $sessionCart = $this->cartService->addToCart($addProduct);
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Components/Cart/Cart', [
                'cartModal' => true,
                'cartProducts' => $sessionCart,
                'cartSum' => $cartSum
            ]);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return $this->cartService->handlerErrors($error);
        }

    }

    public function removeFromCart(int $id): Response
    {
        try {
            $this->cartService->remove($id);
            $cartProducts = $this->cartService->getCart();
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Components/Cart/Cart', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum,
            ]);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return $this->cartService->handlerErrors($error);
        }
    }

    public function incrementCount(int $id): Response
    {
        try {
            $this->cartService->increment($id);
            $cartProducts = $this->cartService->getCart();
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Components/Cart/Cart', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum,
            ]);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return $this->cartService->handlerErrors($error);
        }

    }

    public function decrementCount(int $id): Response
    {
        try {
            $this->cartService->decrement($id);
            $cartProducts = $this->cartService->getCart();
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Components/Cart/Cart', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum,
            ]);

        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return $this->cartService->handlerErrors($error);
        }
    }
}
