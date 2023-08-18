<?php

namespace App\Controllers;

use App\Controllers\ProductController;

class CartController
{
    public function showCart($twig)
    {
        $cartProducts = $this->getCartProducts();

        echo $twig->render('Cart/Cart.twig', [
            'cartModal' => true,
            'cartProducts' => $cartProducts
        ]);
    }

    public function hideCart($twig): void
    {
        echo $twig->render('Cart/Cart.twig', [
            'cartModal' => false
        ]);
    }

    public function getCartProducts(): array
    {
        return [];
    }

    public function addToCart($twig, $id)
    {
        $cartModal = true;

        $cartProducts = $this->getCartProducts();
        d($id);
        echo $twig->render('Cart/Cart.twig', [
            'cartModal' => $cartModal,
            'cartProducts' => $cartProducts
        ]);
    }

    public function removeFromCart($twig)
    {
        $cart = true;
    }

    public function incrementCount($twig)
    {
        $cart = true;
    }

    public function decrementCount($twig)
    {
        $cart = true;
    }
}
