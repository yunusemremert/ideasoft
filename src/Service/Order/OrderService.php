<?php

namespace App\Service\Order;

final class OrderService
{
    private $file = __DIR__ . '/../../../data/orders.json';
    
    public function getAllOrders(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $file = file_get_contents($this->file);

        return json_decode($file, true);
    }

    public function addOrder(array $orders): void
    {
        $jFile = "";

        if (file_exists($this->file)) {
            $file    = file_get_contents($this->file);
            $jFile   = json_decode($file, true);

            $jFile[] = $orders;
        }

        $file = fopen($this->file, 'w');

        $data = json_encode($jFile ?: $orders, JSON_PRETTY_PRINT);

        fwrite($file, !empty($jFile) ? $data : '[' . $data . ']');
    }

    public function findOrder(int $id): array
    {
        $orders = $this->getAllOrders();

        foreach ($orders as $order) {
            if ($order["id"] === $id) {
                return $order;
            }
        }

        return [];
    }
    
    public function deleteOrder(int $id): void
    {
        $orders = $this->getAllOrders();

        $findId = array_search($id, array_column($orders, 'id'));

        unset($orders[$findId]);

        $file = fopen($this->file, 'w');

        fwrite($file, json_encode($orders, JSON_PRETTY_PRINT));
    }
}