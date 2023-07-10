<?php

namespace App\Service\Customer;

final class CustomerService
{
    private $file = __DIR__ . '/../../../data/customers.json';
    
    public function getAllCustomers(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $file = file_get_contents($this->file);

        return json_decode($file, true);
    }
}