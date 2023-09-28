<?php

namespace App\Service;


use App\DTO\OrderDTO;
use App\Model\DeliveryModel;
use App\Model\OrderItemsModel;
use App\Model\OrderModel;
use App\Model\ProductModel;
use Exception;

class OrderService extends Service
{
    public function __construct(
        protected OrderModel      $orderModel,
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

    public function getDeliveryInfo(OrderDTO $dto): array
    {
        return [
            'first_name' => $dto->getFirstName(),
            'last_name' => $dto->getLastName(),
            'address' => $dto->getAddress()
        ];
    }

    public function getCheckoutInfo(): array
    {
        return [
            'cartProducts' => $this->cartService->getCart(),
            'cartSum' => $this->cartService->calcCartSum(),
            'deliveries' => $this->getTypesOfDeliveries()
        ];
    }

    /**
     * @throws Exception
     */
    public function createOrder(OrderDTO $dto): void
    {
        $cartSum = $this->cartService->calcCartSum();
        $data = $this->getDeliveryInfo($dto);
        $data['user_id'] = $_SESSION['auth-user'];
        $data['delivery_id'] = $dto->getDelivery();

        $delivery = $this->deliveryModel->getById($data['delivery_id']);

        if (!$delivery) {
            throw new Exception('Delivery not choose!');
        }

        $this->checkQuantityOrderItems();

        $data['total'] = $cartSum + $delivery['price'];

        $orderId = $this->orderModel->query()
            ->insert($data)
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