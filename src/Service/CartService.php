<?php

namespace App\Service;

use Exception;

class CartService
{
    public function getCart()
    {
        return $_SESSION['cart'] ?? ($_SESSION['cart'] = []);
    }

    /**
     * @throws Exception
     */
    public function addToCart($addProduct)
    {
        $cart = $this->getCart();

        if ($addProduct['quantity'] > 0) {
            $addProduct['count'] = 1;
        }

        $exist = $this->existProductInCart($cart, $addProduct);
        if ($exist) {
            return $_SESSION['cart'];
        }
        $cart[] = $addProduct;
        $_SESSION['cart'] = $cart;
        return $_SESSION['cart'];
    }

    /**
     * @throws Exception
     */
    public function existProductInCart($cart, $addProduct): bool
    {
        foreach ($cart as &$product) {
            if ($product['id'] === $addProduct['id']) {
                if ($product['quantity'] > $product['count']) {
                    $product['count'] += 1;
                    $_SESSION['cart'] = $cart;
                    return true;
                }
                throw new Exception(message: 'All in stock ' . $product['count']);
            }
        }
        return false;
    }

    public function calcCartSum(): int
    {
        $sum = 0;
        $cart = $this->getCart();

        foreach ($cart as &$product) {
            $sum += $product['count'] * $product['price'];
        }

        return $sum;
    }

    /**
     * @throws Exception
     */
    public function increment($id): void
    {
        $cart = $this->getCart();

        foreach ($cart as &$product) {
            if ($product['id'] == $id) {
                if ($product['quantity'] > $product['count']) {
                    $product['count'] += 1;
                    $_SESSION['cart'] = $cart;
                } else {
                    throw new Exception(message: 'All in stock ' . $product['count']);
                }
            }
        }

    }

    /**
     * @throws Exception
     */
    public function decrement($id): void
    {
        $cart = $this->getCart();

        foreach ($cart as &$product) {
            if ($product['id'] == $id) {
                if ($product['count'] != 1) {
                    $product['count'] -= 1;
                    $_SESSION['cart'] = $cart;
                    return;
                }
                $this->remove($id);
            }
        }

    }

    public function remove($id): void
    {
        $cart = $this->getCart();

        foreach ($cart as $key => $value) {
            if ($value['id'] == $id) {
                array_splice($cart, $key, 1);
                $_SESSION['cart'] = $cart;
            }
        }

    }

    public function handlerErrors($twig, array $errors): void
    {
        $cartProducts = $this->getCart();
        $cartSum = $this->calcCartSum();

        echo $twig->render('Cart/Cart.twig', [
            'cartModal' => true,
            'cartProducts' => $cartProducts,
            'cartSum' => $cartSum,
            'errors' => $errors
        ]);


    }

}