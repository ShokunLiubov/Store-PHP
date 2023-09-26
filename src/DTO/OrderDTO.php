<?php

namespace App\DTO;

class OrderDTO
{
    public function getFirstName(): string
    {
        return $_REQUEST['first_name'];
    }

    public function getLastName(): string
    {
        return $_REQUEST['last_name'];
    }

    public function getAddress(): string
    {
        return $_REQUEST['address'];
    }

    public function getDelivery(): string
    {
        return $_REQUEST['deliverySelect'];
    }
}