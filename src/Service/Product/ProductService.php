<?php

namespace App\Service\Product;

final class ProductService
{
    private $file = __DIR__ . '/../../../data/products.json';
    
    public function getAllProducts(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $file = file_get_contents($this->file);

        return json_decode($file, true);
    }

    public function findProduct(int $id): array
    {
        $products = $this->getAllProducts();

        foreach ($products as $product) {
            if ($product["id"] === $id) {
                return $product;
            }
        }

        return [];
    }
}