<?php

namespace App\Interface\Discount;

use App\Service\Discount\DiscountService;

interface DiscountInterface extends DiscountInterfaceFactory
{
    public function runOn(DiscountService $discountService);
}