<?php


namespace App\Controllers;


use App\Core\Response\Response;
use App\DTO\OrderDTO;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\UserService;
use App\Validator\OrderValidate;
use Exception;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CartService  $cartService,
    )
    {
    }

    public function checkout(): Response
    {
        $data = $this->orderService->getCheckoutInfo();
        $data['deliveryInfo'] = $this->orderService->getLastDeliveryInfo();

        return response()->view('Pages/Checkout/Checkout', $data);
    }

    public function createOrder(OrderValidate $validator, OrderDTO $dto): Response
    {
        try {
            $validator->validate($dto);
            $this->orderService->createOrder($dto);
            $this->cartService->clearCart();

            return response()->view('Pages/CompleteOrder/CompleteOrder');
        } catch (Exception $e) {
            $data = $this->orderService->getCheckoutInfo();
            $data['deliveryInfo'] = $this->orderService->getDeliveryInfo($dto);
            $data['error'] = $e->getMessage();

            return response()->view('Pages/Checkout/Checkout', $data);
        }
    }

}
