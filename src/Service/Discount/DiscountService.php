<?php

namespace App\Service\Discount;

use App\Service\Product\ProductService;

final class DiscountService
{
    public array $discounts = [];
    public array $products = [];
    public int|float $orderTotalAmount = 0;
    private int $orderId;

    public function __construct(array $order)
    {
        $this->products         = $this->getOrderProductCategories($order["items"]);
        $this->orderTotalAmount = $order["total"];
        $this->discounts        = [];
        $this->orderId          = $order["id"];
    }

    private function getOrderProductCategories(array $orderProducts): array
    {
        $productService = new ProductService();

        $products = [];

        foreach ($orderProducts as $orderProduct) {
            $productCheck = $productService->findProduct($orderProduct["productId"]);

            $orderProduct["categoryId"]     = "";

            if (!empty($productCheck)) {
                $orderProduct["categoryId"] = $productCheck["category"];
            }

            $products[] = $orderProduct;
        }

        return $products;
    }

    public function calculateDiscount(): array
    {
        // Total Discount
        $this->totalDiscount();

        // Category Count Discount
        $this->categoryCountDiscount();

        // Category Min Discount
        $this->categoryMinDiscount();

        if (!empty($this->discounts)) {
            return $this->getDiscounts();
        }

        return [];
    }

    private function getDiscounts(): array
    {
        // Discount Calculate
        $totalDiscountAmount = 0;

        $discounts = [];
        foreach ($this->discounts as $discount) {
            $discounts[] = array(
                'discountReason' => $discount->getDiscountName(),
                'discountAmount' => $discount->getDiscountAmount(),
                'subtotal'       => $discount->getTotalAmountDiscount()
            );

            $totalDiscountAmount += $discount->getDiscountAmount();
        }

        return [
            'orderId'         => $this->orderId,
            'discounts'       => $discounts,
            'totalDiscount'   => $totalDiscountAmount,
            'discountedTotal' => $this->orderTotalAmount
        ];
    }

    private function totalDiscount(): void
    {
        $totalDiscount     = new TotalDiscount(1000, 10);
        $totalDiscount->runOn($this);

        $this->discounts[] = $totalDiscount;
    }

    private function categoryCountDiscount(): void
    {
        $categoryDiscount  = new CategoryCountDiscount(2, 6, 100);
        $categoryDiscount->runOn($this);

        $this->discounts[] = $categoryDiscount;
    }

    private function categoryMinDiscount(): void
    {
        $categoryDiscount  = new CategoryMinDiscount(1, 2, 20);
        $categoryDiscount->runOn($this);

        $this->discounts[] = $categoryDiscount;
    }
}