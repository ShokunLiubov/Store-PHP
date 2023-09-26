<?php

namespace App\Service;


use App\DTO\OrderDTO;
use App\Model\DeliveryModel;
use App\Model\OrderItemsModel;
use App\Model\OrderModel;
use App\Model\ProductModel;
use App\Validator\OrderValidate;
use Exception;

class OrderService extends Service
{
    public function __construct(
        protected OrderModel      $orderModel,
        protected OrderDTO        $orderDTO,
        protected CartService     $cartService,
        protected DeliveryModel   $deliveryModel,
        protected OrderItemsModel $orderItemsModel,
        protected ProductModel    $productModel
    )
    {
    }

    public function getLastDeliveryInfo(): array|bool|null
    {
        $userId = $_SESSION['auth-user'];

        return $this->orderModel->query()
            ->select('first_name, last_name, address')
            ->where('user_id', '=', 'id', $userId)
            ->orderBy('id', 'desc')
            ->getOne();
    }

    /**
     * @throws Exception
     */
    public function createOrder(): void
    {
        $userId = $_SESSION['auth-user'];

        (new OrderValidate())->validate();

        $cartSum = $this->cartService->calcCartSum();
        $deliveryId = $this->orderDTO->getDelivery();
        $firstName = $this->orderDTO->getFirstName();
        $lastName = $this->orderDTO->getLastName();
        $address = $this->orderDTO->getAddress();

        $delivery = $this->deliveryModel->getById($deliveryId);

        if (!$delivery) {
            throw new Exception('Delivery not choose!');
        }

        $this->checkQuantityOrderItems();

        $total = $cartSum + $delivery['price'];

        $orderId = $this->orderModel->query()
            ->insert([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'address' => $address,
                'delivery_id' => $deliveryId,
                'user_id' => $userId,
                'total' => $total
            ])
            ->insertGetId();

        if (!$orderId) {
            throw new Exception('Something error!');
        }

        $this->createOrderItems($orderId);
    }

    /**
     * @throws Exception
     */
    public function checkQuantityOrderItems(): void
    {
        $cart = $this->cartService->getCart();

        foreach ($cart as $product) {
            $currentQuantityProduct = $this->productModel->query()
                ->select('quantity')
                ->where('id', '=', 'id', $product['id'])
                ->get();

            if ($currentQuantityProduct < $product['count']) {
                throw new Exception('Available ' . $product['title'] . ' ' . $currentQuantityProduct . '!');
            }
        }
    }

    public function createOrderItems(int $orderId): void
    {
        $cart = $this->cartService->getCart();

        foreach ($cart as $product) {
            $this->orderItemsModel->query()->insert([
                'quantity' => $product['count'],
                'price' => $product['price'],
                'order_id' => $orderId,
                'product_id' => $product['id'],
            ])->get();
        }

    }

    public function getTypesOfDeliveries(): array
    {
        return $this->deliveryModel->query()
            ->select()
            ->get();

    }

}