<?php

namespace App\Service\Discount;

use App\Interface\Discount\DiscountInterface;

class CategoryMinDiscount implements DiscountInterface
{
    private int $categoryId;
    private int $productCountLimit;
    private int $discountRate;
    public string $discountName;
    public float $discountAmount;
    public float $totalAmountDiscount;

    public function __construct(int $categoryId, int $productCountLimit, int $discountRate)
    {
        $this->categoryId          = $categoryId;
        $this->productCountLimit   = $productCountLimit;
        $this->discountRate        = $discountRate;
        $this->discountName        = 'BUY_' . $productCountLimit . '_GET_2';
        $this->discountAmount      = 0;
        $this->totalAmountDiscount = 0;
    }

    public function runOn(DiscountService $discountService): void
    {
        $categoryProducts = $this->getCategoryProducts($discountService->products);

        if (!empty($categoryProducts)) {
            usort($categoryProducts, function ($a, $b) {
                return $a["unitPrice"] - $b["unitPrice"];
            });

            $this->discountAmount              = $categoryProducts[0]['total'] * $this->discountRate / 100;
            $this->totalAmountDiscount         = $discountService->orderTotalAmount - $this->discountAmount;

            $discountService->orderTotalAmount = $this->totalAmountDiscount;
        }
    }

    private function getCategoryProducts(array $products): array
    {
        return array_filter($products, function ($product) {
            return $this->categoryId === $product['categoryId'] && $product["quantity"] >= $this->productCountLimit;
        });
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