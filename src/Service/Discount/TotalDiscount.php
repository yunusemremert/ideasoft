<?php

namespace App\Service\Discount;

use App\Interface\Discount\DiscountInterface;

class TotalDiscount implements DiscountInterface
{
    private float $totalAmountLimit;
    private int $discountRate;
    public string $discountName;
    public float $discountAmount;
    public float $totalAmountDiscount;

    public function __construct(float $totalAmountLimit, int $discountRate)
    {
        $this->totalAmountLimit    = $totalAmountLimit;
        $this->discountRate        = $discountRate;
        $this->discountName        = '10_PERCENT_OVER_1000';
        $this->discountAmount      = 0;
        $this->totalAmountDiscount = 0;
    }

    public function runOn(DiscountService $discountService): void
    {
        if ($discountService->orderTotalAmount >= $this->totalAmountLimit) {
            $this->discountAmount              = $discountService->orderTotalAmount * $this->discountRate / 100;
            $this->totalAmountDiscount         = $discountService->orderTotalAmount - $this->discountAmount;

            $discountService->orderTotalAmount = $this->totalAmountDiscount;
        }
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