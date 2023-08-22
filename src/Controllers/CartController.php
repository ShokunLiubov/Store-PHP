<?php

namespace App\Controllers;

use App\Controllers\ProductController;
use App\Service\ProductService;
use App\Service\CartService;
use Exception;
use Twig\Environment;

class CartController
{
    public function showCart(Environment $twig): void
    {
        try {
            $cartProducts = (new CartService())->getCart();
            $cartSum = (new CartService())->calcCartSum();

            echo $twig->render('Cart/Cart.twig', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum
            ]);

        } catch (Exception $e) {
            $error[] = $e->getMessage();
            (new CartService())->handlerErrors($twig, $error);
        }
    }

    public function hideCart(Environment $twig): void
    {
        echo $twig->render('Cart/Cart.twig', [
            'cartModal' => false
        ]);
    }


    public function addToCart(Environment $twig, $id): void
    {
        try {
            $addProduct = (new ProductService())->getProduct($id);
            $sessionCart = (new CartService())->addToCart($addProduct);
            $cartSum = (new CartService())->calcCartSum();

            echo $twig->render('Cart/Cart.twig', [
                'cartModal' => true,
                'cartProducts' => $sessionCart,
                'cartSum' => $cartSum
            ]);

        } catch (Exception $e) {
            $error[] = $e->getMessage();
            (new CartService())->handlerErrors($twig, $error);
        }

    }

    public function removeFromCart(Environment $twig, $id): void
    {
        try {
            (new CartService())->remove($id);
            $cartProducts = (new CartService())->getCart();
            $cartSum = (new CartService())->calcCartSum();

            echo $twig->render('Cart/Cart.twig', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum,
            ]);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            (new CartService())->handlerErrors($twig, $error);
        }
    }

    public function incrementCount(Environment $twig, $id): void
    {
        try {
            (new CartService())->increment($id);
            $cartProducts = (new CartService())->getCart();
            $cartSum = (new CartService())->calcCartSum();

            echo $twig->render('Cart/Cart.twig', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum,
            ]);

        } catch (Exception $e) {
            $error[] = $e->getMessage();
            (new CartService())->handlerErrors($twig, $error);
        }

    }

    public function decrementCount(Environment $twig, $id): void
    {
        try {
            (new CartService())->decrement($id);
            $cartProducts = (new CartService())->getCart();
            $cartSum = (new CartService())->calcCartSum();

            echo $twig->render('Cart/Cart.twig', [
                'cartModal' => true,
                'cartProducts' => $cartProducts,
                'cartSum' => $cartSum,
            ]);

        } catch (Exception $e) {
            $error[] = $e->getMessage();
            (new CartService())->handlerErrors($twig, $error);
        }
    }
}
