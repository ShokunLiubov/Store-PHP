<?php

namespace App\Controllers;

use App\Service\ProductService;
use App\Service\CartService;
use Exception;
use App\Core\Response\Response;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function showCart(): Response
    {
        try {
            $cartProducts = $this->cartService->getCart();
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Cart/Cart', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum
            ]);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return $this->cartService->handlerErrors($error);
        }
    }

    public function hideCart(Environment $twig): Response
    {
        return response()->view('Cart/Cart', [
            'cartModal' => false
        ]);
    }


    public function addToCart($id): Response
    {
        try {
            $addProduct = (new ProductService())->getProduct($id);
            $sessionCart = $this->cartService->addToCart($addProduct);
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Cart/Cart', [
                'cartModal' => true,
                'cartProducts' => $sessionCart,
                'cartSum' => $cartSum
            ]);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return $this->cartService->handlerErrors($error);
        }

    }

    public function removeFromCart($id): Response
    {
        try {
            $this->cartService->remove($id);
            $cartProducts = $this->cartService->getCart();
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Cart/Cart', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum,
            ]);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return $this->cartService->handlerErrors($error);
        }
    }

    public function incrementCount($id): Response
    {
        try {
            $this->cartService->increment($id);
            $cartProducts = $this->cartService->getCart();
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Cart/Cart', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum,
            ]);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return $this->cartService->handlerErrors($error);
        }

    }

    public function decrementCount($id): Response
    {
        try {
            $this->cartService->decrement($id);
            $cartProducts = $this->cartService->getCart();
            $cartSum = $this->cartService->calcCartSum();

            return response()->view('Cart/Cart', [
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
