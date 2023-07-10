<?php

namespace App\Interface\Discount;

interface DiscountInterfaceFactory {
    public function getDiscountName(): string;

    public function getDiscountAmount(): float;

    public function getTotalAmountDiscount(): float;
}