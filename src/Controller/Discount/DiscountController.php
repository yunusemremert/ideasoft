<?php

namespace App\Controller\Discount;

use App\Controller\BaseController;
use App\Service\Discount\CategoryCountDiscount;
use App\Service\Discount\CategoryMinDiscount;
use App\Service\Discount\DiscountService;
use App\Service\Discount\TotalDiscount;
use App\Service\Order\OrderService;
use App\Service\Product\ProductService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DiscountController extends BaseController
{

    public function __construct(
        private readonly ProductService        $productService,
        private readonly TotalDiscount         $totalDiscountService,
        private readonly CategoryCountDiscount $categoryCountDiscountService,
        private readonly CategoryMinDiscount   $categoryMinDiscountService,
        private readonly OrderService          $orderService
    )
    {
    }

    public function calculate(Request $request, Response $response, array $args): Response
    {
        $orderId = $args["id"];

        if (empty($orderId)) {
            return $this->jsonResponse($response, 'false', "Invalid data!", 406);
        }

        $orderCheck = $this->orderService->findOrder($orderId);

        if (empty($orderCheck)) {
            return $this->jsonResponse($response, 'false', "Order not exist!", 404);
        }

        $discountService = new DiscountService(
            $this->productService,
            $this->totalDiscountService,
            $this->categoryCountDiscountService,
            $this->categoryMinDiscountService
        );

        $discountService->setOrderItems($orderCheck);

        $calculate       = $discountService->calculateDiscount();

        return $this->jsonResponse($response, 'success', $calculate, 200);
    }
}