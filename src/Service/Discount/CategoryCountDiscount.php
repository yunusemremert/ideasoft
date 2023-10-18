<?php

namespace App\Service\Discount;

use App\Interface\Discount\DiscountInterface;

class CategoryCountDiscount implements DiscountInterface
{
    private int $categoryId;
    private int $productCountLimit;
    private int $discountRate;
    public string $discountName;
    public float $discountAmount;
    public float $totalAmountDiscount;

    public function __construct()
    {
        $this->discountAmount      = 0;
        $this->totalAmountDiscount = 0;
    }

    public function setDiscount(int $categoryId, int $productCountLimit, int $discountRate): void
    {
        $this->categoryId        = $categoryId;
        $this->productCountLimit = $productCountLimit;
        $this->discountRate      = $discountRate;
        $this->discountName      = 'BUY_' . $productCountLimit . '_GET_1';
    }

    public function runOn(DiscountService $discountService): void
    {
        $categoryProducts = $this->getCategoryProducts($discountService->products);

        if (!empty($categoryProducts)) {
            $this->discountAmount              = $categoryProducts["total"] * $this->discountRate / 100;
            $this->totalAmountDiscount         = $discountService->orderTotalAmount - $this->discountAmount;

            $discountService->orderTotalAmount = $this->totalAmountDiscount;
        }
    }

    private function getCategoryProducts(array $products): array
    {
        foreach ($products as $product) {
            if ($this->categoryId === $product['categoryId'] && $this->productCountLimit === $product["quantity"]) {
                return $product;
            }
        }

        return [];
    }

    public function getDiscountName(): string
    {
        return $this->discountName;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function getTotalAmountDiscount(): float
    {
        return $this->totalAmountDiscount;
    }
}