<?php


namespace App\Controllers;


use App\Core\Response\Response;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\UserService;
use Exception;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CartService $cartService,
    )
    {
    }

    public function checkout(): Response
    {
        try{
            $data['cartProducts'] = $this->cartService->getCart();
            $data['cartSum'] = $this->cartService->calcCartSum();
            $data['deliveries'] = $this->orderService->getTypesOfDeliveries();
            $data['deliveryInfo'] = $this->orderService->getLastDeliveryInfo();

            return response()->view('Pages/Checkout/Checkout', $data);

        } catch(Exception $e) {
            $error = $e->getMessage();
            return response()->view('Errors/Error404', ['error' => $error]);
        }

    }

    public function createOrder(): Response
    {
        try{
            $this->orderService->createOrder();
            $this->cartService->clearCart();

            return response()->view('Pages/CompleteOrder/CompleteOrder');

        } catch(Exception $e) {
            $data['cartProducts'] = $this->cartService->getCart();
            $data['cartSum'] = $this->cartService->calcCartSum();
            $data['deliveries'] = $this->orderService->getTypesOfDeliveries();
            $data['deliveryInfo'] = $this->orderService->getLastDeliveryInfo();

            $error = $e->getMessage();
            $data['error'] = $error;

            return response()->view('Pages/Checkout/Checkout', $data);
        }
    }

}
